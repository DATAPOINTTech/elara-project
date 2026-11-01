<?php

declare(strict_types=1);

namespace Elara\Controllers;

use Elara\Services\FirestoreService;
use Exception;

class ElaraAgentController
{
    private FirestoreService $db;
    private array $appConfig;

    public function __construct(FirestoreService $db)
    {
        $this->db = $db;
        $this->appConfig = require dirname(__DIR__, 2) . '/config/app.php';
    }

    /**
     * Handle incoming chat message from ELARA Assistant Widget.
     */
    public function chat(): void
    {
        header('Content-Type: application/json');

        try {
            $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
            $userMessage = trim($input['message'] ?? '');
            $chatHistory = $input['history'] ?? [];

            if (empty($userMessage)) {
                echo json_encode(['success' => false, 'error' => 'Message cannot be empty.']);
                exit;
            }

            // 1. Gather Real-Time Salon Operational Context
            $context = $this->buildOperationalContext();

            // 2. Select AI Provider
            $aiConfig = $this->appConfig['ai'];
            $provider = strtolower($aiConfig['provider'] ?? 'gemini');
            $geminiKey = trim($aiConfig['gemini']['api_key'] ?? '');
            $openAiKey = trim($aiConfig['openai']['api_key'] ?? '');

            $reply = '';

            if ($provider === 'gemini' && !empty($geminiKey) && $geminiKey !== 'your-gemini-api-key') {
                $reply = $this->callGeminiApi($userMessage, $context, $chatHistory, $geminiKey);
            } elseif ($provider === 'openai' && !empty($openAiKey) && $openAiKey !== 'your-openai-api-key') {
                $reply = $this->callOpenAiApi($userMessage, $context, $chatHistory, $openAiKey);
            } else {
                // Intelligent Salon Expert Fallback Engine
                $reply = $this->generateExpertFallback($userMessage, $context);
            }

            echo json_encode([
                'success' => true,
                'reply' => $reply,
                'provider' => (!empty($geminiKey) && $geminiKey !== 'your-gemini-api-key') ? 'Gemini 1.5' : ((!empty($openAiKey)) ? 'OpenAI GPT' : 'ELARA Salon Intelligence Engine'),
                'timestamp' => date('H:i'),
            ]);
            exit;
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit;
        }
    }

    /**
     * Build rich real-time parlor operational system snapshot.
     */
    private function buildOperationalContext(): array
    {
        $services = $this->db->getCollection('services');
        $inventory = $this->db->getCollection('inventory');
        $invoices = $this->db->getCollection('invoices', '-created_at');
        $staff = $this->db->getCollection('staff');

        $today = date('Y-m-d');
        $todayRevenue = 0.0;
        $todayOrders = 0;
        $recentCustomers = [];

        foreach ($invoices as $inv) {
            if (substr($inv['created_at'] ?? '', 0, 10) === $today) {
                $todayRevenue += (float)($inv['total'] ?? 0);
                $todayOrders++;
                if (!empty($inv['customer_name'])) {
                    $recentCustomers[] = $inv['customer_name'];
                }
            }
        }

        $lowStockProducts = [];
        foreach ($inventory as $item) {
            $stock = (int)($item['stock'] ?? 0);
            $min = (int)($item['min_stock'] ?? 5);
            if ($stock <= $min) {
                $lowStockProducts[] = [
                    'name' => $item['name'],
                    'stock' => $stock,
                    'min' => $min,
                    'supplier' => $item['supplier'] ?? 'N/A',
                ];
            }
        }

        $popularServices = array_filter($services, fn($s) => !empty($s['popular']));

        return [
            'parlor_name' => $this->appConfig['parlor']['name'],
            'parlor_tagline' => $this->appConfig['parlor']['tagline'],
            'currency' => $this->appConfig['parlor']['currency_symbol'],
            'today_date' => date('l, F j, Y'),
            'today_revenue' => $todayRevenue,
            'today_invoices_count' => $todayOrders,
            'low_stock_alerts' => $lowStockProducts,
            'staff_members' => array_map(fn($s) => "{$s['name']} ({$s['role']})", $staff),
            'popular_services' => array_map(fn($s) => "{$s['name']} ({$this->appConfig['parlor']['currency_symbol']}{$s['price']})", array_slice($popularServices, 0, 5)),
            'total_services_count' => count($services),
            'total_products_count' => count($inventory),
        ];
    }

    /**
     * Call Google Gemini API (gemini-1.5-flash).
     */
    private function callGeminiApi(string $message, array $context, array $history, string $apiKey): string
    {
        $systemPrompt = $this->getSystemPrompt($context);
        $model = $this->appConfig['ai']['gemini']['model'] ?? 'gemini-1.5-flash';
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

        $contents = [];
        
        // Add conversation turns
        foreach ($history as $msg) {
            $role = ($msg['sender'] === 'user') ? 'user' : 'model';
            $contents[] = [
                'role' => $role,
                'parts' => [['text' => $msg['text']]],
            ];
        }

        // Add current user prompt
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $message]],
        ];

        $payload = [
            'system_instruction' => [
                'parts' => [['text' => $systemPrompt]],
            ],
            'contents' => $contents,
            'generationConfig' => [
                'temperature' => 0.7,
                'maxOutputTokens' => 800,
            ],
        ];

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_TIMEOUT => 15,
        ]);

        $res = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status === 200) {
            $json = json_decode($res, true);
            $candidateText = $json['candidates'][0]['content']['parts'][0]['text'] ?? null;
            if ($candidateText) {
                return trim($candidateText);
            }
        }

        // If Gemini API call fails, fall back to intelligent expert generator
        return $this->generateExpertFallback($message, $context);
    }

    /**
     * Call OpenAI Chat Completions API.
     */
    private function callOpenAiApi(string $message, array $context, array $history, string $apiKey): string
    {
        $systemPrompt = $this->getSystemPrompt($context);
        $model = $this->appConfig['ai']['openai']['model'] ?? 'gpt-4o-mini';
        $url = "https://api.openai.com/v1/chat/completions";

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
        ];

        foreach ($history as $msg) {
            $role = ($msg['sender'] === 'user') ? 'user' : 'assistant';
            $messages[] = ['role' => $role, 'content' => $msg['text']];
        }

        $messages[] = ['role' => 'user', 'content' => $message];

        $payload = [
            'model' => $model,
            'messages' => $messages,
            'temperature' => 0.7,
            'max_tokens' => 800,
        ];

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $apiKey,
            ],
            CURLOPT_TIMEOUT => 15,
        ]);

        $res = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status === 200) {
            $json = json_decode($res, true);
            $reply = $json['choices'][0]['message']['content'] ?? null;
            if ($reply) {
                return trim($reply);
            }
        }

        return $this->generateExpertFallback($message, $context);
    }

    /**
     * System instructions injecting salon identity & real-time live data.
     */
    private function getSystemPrompt(array $c): string
    {
        $lowStockStr = empty($c['low_stock_alerts']) ? 'All inventory items are well-stocked.' :
            implode(', ', array_map(fn($item) => "{$item['name']} (only {$item['stock']} left, min threshold {$item['min']})", $c['low_stock_alerts']));

        $staffStr = implode(', ', $c['staff_members']);
        $popularStr = implode(', ', $c['popular_services']);

        return <<<TXT
You are ELARA, the executive AI parlor & spa concierge and operational intelligence copilot for "{$c['parlor_name']}".
Your personality is poised, luxurious, warm, highly organized, and business-savvy.
You assist the salon manager, front desk receptionists, and stylists with salon operations, sales insights, inventory restocking, treatment recommendations, customer retention, and promotional campaigns.

REAL-TIME PARLOR SYSTEM SNAPSHOT (TODAY: {$c['today_date']}):
- Currency: {$c['currency']}
- Today's Total Gross Revenue: {$c['currency']}{$c['today_revenue']}
- Today's Total Completed Orders: {$c['today_invoices_count']}
- Current Low Stock Alert Items: {$lowStockStr}
- Master Stylists On Roster: {$staffStr}
- Popular Treatments: {$popularStr}
- Total Catalog Services: {$c['total_services_count']} treatments
- Total Retail Inventory: {$c['total_products_count']} SKUs

Guidelines:
1. When asked about revenue, stock, or stylists, use the exact real-time snapshot figures above.
2. Provide actionable, upscale advice for hair care, facials, bridal packages, and spa treatments.
3. Suggest upselling retail products (e.g. Olaplex, Moroccanoil, Rosewater toner) after chemical treatments or facials.
4. Keep answers concise, elegantly structured with bullet points and bold highlights.
TXT;
    }

    /**
     * High-grade rule-based salon expert engine for immediate out-of-the-box answers.
     */
    private function generateExpertFallback(string $prompt, array $c): string
    {
        $p = strtolower($prompt);
        $curr = $c['currency'];

        if (str_contains($p, 'revenue') || str_contains($p, 'sales') || str_contains($p, 'today') || str_contains($p, 'earning')) {
            $rev = number_format((float)$c['today_revenue'], 2);
            $count = $c['today_invoices_count'];
            return "✨ **Today's Financial Overview ({$c['today_date']}):**\n\n" .
                   "• **Total Revenue:** `{$curr}{$rev}`\n" .
                   "• **Completed Invoices:** `{$count}` transactions\n" .
                   "• **Average Ticket Size:** `{$curr}" . ($count > 0 ? number_format($c['today_revenue'] / $count, 2) : '0.00') . "`\n\n" .
                   "Salon traffic is flowing smoothly! You can view detailed breakdowns under the **Reports & Analytics** tab.";
        }

        if (str_contains($p, 'stock') || str_contains($p, 'inventory') || str_contains($p, 'alert') || str_contains($p, 'low')) {
            if (empty($c['low_stock_alerts'])) {
                return "🌟 **Inventory Health Report:**\n\nAll retail products and salon supplies are currently above their safety thresholds. No urgent reorders required today!";
            }
            $out = "⚠️ **Urgent Stock Alert Notice:**\n\nWe have **" . count($c['low_stock_alerts']) . " item(s)** running below minimum safety threshold:\n\n";
            foreach ($c['low_stock_alerts'] as $item) {
                $out .= "• **{$item['name']}**: Only **{$item['stock']} units** remaining (Safety minimum: {$item['min']}). Supplier: _{$item['supplier']}_\n";
            }
            $out .= "\n💡 *Recommendation:* Issue a purchase replenishment order today to prevent treatment delays during peak weekend bookings.";
            return $out;
        }

        if (str_contains($p, 'bridal') || str_contains($p, 'wedding') || str_contains($p, 'package')) {
            return "👰 **ELARA Signature Bridal & Royalty Curation:**\n\n" .
                   "For our luxury bridal clients, I recommend combining:\n" .
                   "1. **Signature 24K Gold Glow Facial** ({$curr}85) - Conducted 3-4 days prior to event for maximum radiance.\n" .
                   "2. **Bridal Royalty Glam & Royal Updo** ({$curr}350) - HD airbrush makeup with lash veil and jewelry setting.\n" .
                   "3. **Deluxe Gel Velvet Manicure & Rose Pedicure** ({$curr}100) - Long-lasting bridal nails.\n" .
                   "4. **Henna / Mehendi Artisan Design** ({$curr}60) - Floral intricate motifs.\n\n" .
                   "💎 **Bundle Package Price:** `{$curr}520` (Save 12% on complete bridal suite). Would you like me to draft an attractive WhatsApp promotional copy?";
        }

        if (str_contains($p, 'stylist') || str_contains($p, 'staff') || str_contains($p, 'team')) {
            $staffList = implode("\n", array_map(fn($s) => "• **{$s}**", $c['staff_members']));
            return "💇‍♀️ **ELARA Professional Artists on Roster:**\n\n{$staffList}\n\nAll stylists are verified with active performance tracking and automated commission calculations.";
        }

        if (str_contains($p, 'facial') || str_contains($p, 'skin')) {
            return "🌸 **Facial & Skin Rejuvenation Recommendations:**\n\n" .
                   "• **For Dull / Tired Skin:** *Signature 24K Gold Glow Facial* ({$curr}85) — Infuses micro-gold particles for deep luminosity.\n" .
                   "• **For Congested / Textured Skin:** *Hydra-Oxygenating Skin Rebirth* ({$curr}95) with gentle *Dermaplaning* add-on ({$curr}70).\n" .
                   "• **Home Aftercare Retail Pairing:** Always pair with *Pure Bulgarian Rosewater Toner* ({$curr}24) for optimal hydration maintenance.";
        }

        if (str_contains($p, 'promo') || str_contains($p, 'marketing') || str_contains($p, 'whatsapp message') || str_contains($p, 'campaign')) {
            return "📱 **Draft WhatsApp Promotion for Weekend Pampering:**\n\n" .
                   "```text\n" .
                   "✨ Weekend Luxury Treat at ELARA Parlor & Spa! ✨\n\n" .
                   "Treat yourself to the pampering you deserve! Book our signature 24K Gold Facial this Saturday and receive a complimentary Aromatherapy Scalp Massage + 15% off any Olaplex or Moroccanoil home care product.\n\n" .
                   "🗓 Limited VIP slots available. Reply 'GLOW' or call us at {$this->appConfig['parlor']['phone']} to reserve.\n" .
                   "```\n\n" .
                   "You can copy and send this directly via WhatsApp Broadcast to your client list!";
        }

        return "✨ **Welcome to ELARA Spa Intelligence!**\n\n" .
               "I'm continuously monitoring our parlor's appointments, inventory, and finances. Here are quick things I can help you with:\n\n" .
               "• 📊 **Check Today's Sales:** \"How much revenue have we made today?\"\n" .
               "• 📦 **Inventory Warnings:** \"Which products are low on stock?\"\n" .
               "• 👰 **Treatment Bundles:** \"Suggest a bridal or glam package\"\n" .
               "• 📣 **Marketing Copy:** \"Draft a weekend WhatsApp promotion\"\n" .
               "• 💇‍♀️ **Stylists & Commissions:** \"Review our staff roster and performance\"\n\n" .
               "*(Tip: You can add your Gemini API Key in `.env` to unlock open-ended conversational reasoning!)*";
    }
}

