<?php

declare(strict_types=1);

namespace Elara\Services;

class WhatsAppService
{
    private array $appConfig;

    public function __construct()
    {
        $this->appConfig = require dirname(__DIR__, 2) . '/config/app.php';
    }

    /**
     * Normalize international phone numbers for WhatsApp (wa.me accepts digits only).
     */
    public function formatPhoneNumber(string $phone): string
    {
        // Strip everything except digits
        $digits = preg_replace('/\D+/', '', $phone);

        // If local US format 10 digits without country code, default to US +1
        if (strlen($digits) === 10) {
            $digits = '1' . $digits;
        }

        return $digits;
    }

    /**
     * Format a luxury parlor customer receipt text for WhatsApp.
     */
    public function buildReceiptMessage(array $invoice, ?string $pdfPublicUrl = null): string
    {
        $parlor = $this->appConfig['parlor'];
        $currency = $parlor['currency_symbol'];
        $customerName = $invoice['customer_name'] ?? 'Valued Guest';
        $invoiceNum = $invoice['invoice_number'] ?? 'INV-0000';
        $stylist = $invoice['stylist_name'] ?? 'Senior Stylist';
        $cashier = $invoice['cashier_name'] ?? 'Front Desk Cashier';
        $total = number_format((float)($invoice['total'] ?? 0), 2);
        $date = date('M d, Y', strtotime($invoice['created_at'] ?? 'now'));

        $itemsText = '';
        foreach ($invoice['items'] ?? [] as $item) {
            $name = $item['name'] ?? 'Item';
            $qty = $item['quantity'] ?? 1;
            $price = number_format((float)($item['total'] ?? $item['price']), 2);
            $itemsText .= "• {$name} (x{$qty}) - {$currency}{$price}\n";
        }

        $url = $pdfPublicUrl ?: ($this->appConfig['url'] . "/invoice/download?id=" . $invoiceNum);

        $msg = "✨ *{$parlor['name']}* ✨\n";
        $msg .= "_{$parlor['tagline']}_\n\n";
        $msg .= "Hello *{$customerName}*,\n";
        $msg .= "Thank you for visiting us today! Here is your official e-receipt:\n\n";
        $msg .= "🧾 *Invoice:* #{$invoiceNum}\n";
        $msg .= "📅 *Date:* {$date}\n";
        $msg .= "💇‍♀️ *Stylist / Specialist:* {$stylist}\n";
        $msg .= "👩‍💼 *Billed By (Cashier):* {$cashier}\n";
        $msg .= "💳 *Payment Mode:* {$invoice['payment_method']}\n\n";
        $msg .= "*Services & Products:*\n";
        $msg .= $itemsText . "\n";
        $msg .= "💰 *Total Amount:* *{$currency}{$total}* (Status: Paid ✅)\n\n";
        $msg .= "📄 *Download Your Branded PDF Invoice:*\n";
        $msg .= "{$url}\n\n";
        $msg .= "Need another appointment? Contact our concierge at {$parlor['phone']} or reply directly to this chat.\n";
        $msg .= "Have a glowing and beautiful day! 🌸";

        return $msg;
    }

    /**
     * Generate wa.me deep link with pre-filled message.
     */
    public function generateWaMeLink(string $phoneNumber, array $invoice, ?string $pdfPublicUrl = null): string
    {
        $cleanPhone = $this->formatPhoneNumber($phoneNumber);
        $message = $this->buildReceiptMessage($invoice, $pdfPublicUrl);
        $encodedText = rawurlencode($message);

        return "https://wa.me/{$cleanPhone}?text={$encodedText}";
    }

    /**
     * Structured template payload for Meta WhatsApp Cloud API (Graph API).
     */
    public function getCloudApiPayload(string $recipientPhone, array $invoice, string $pdfPublicUrl): array
    {
        $cleanPhone = $this->formatPhoneNumber($recipientPhone);
        $parlor = $this->appConfig['parlor'];
        $currency = $parlor['currency_symbol'];
        $customerName = $invoice['customer_name'] ?? 'Guest';
        $invoiceNum = $invoice['invoice_number'] ?? 'INV-0000';
        $total = number_format((float)($invoice['total'] ?? 0), 2);

        return [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $cleanPhone,
            'type' => 'template',
            'template' => [
                'name' => 'parlor_invoice_receipt',
                'language' => [
                    'code' => 'en_US',
                ],
                'components' => [
                    [
                        'type' => 'header',
                        'parameters' => [
                            [
                                'type' => 'document',
                                'document' => [
                                    'link' => $pdfPublicUrl,
                                    'filename' => "{$invoiceNum}.pdf",
                                ],
                            ],
                        ],
                    ],
                    [
                        'type' => 'body',
                        'parameters' => [
                            ['type' => 'text', 'text' => $customerName],
                            ['type' => 'text', 'text' => $parlor['name']],
                            ['type' => 'text', 'text' => $invoiceNum],
                            ['type' => 'text', 'text' => "{$currency}{$total}"],
                        ],
                    ],
                ],
            ],
        ];
    }
}

