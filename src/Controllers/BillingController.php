<?php

declare(strict_types=1);

namespace Elara\Controllers;

use Elara\Services\FirestoreService;
use Elara\Services\PdfService;
use Elara\Services\WhatsAppService;
use Exception;

class BillingController
{
    private FirestoreService $db;
    private PdfService $pdf;
    private WhatsAppService $whatsapp;
    private array $appConfig;

    public function __construct(FirestoreService $db, PdfService $pdf, WhatsAppService $whatsapp)
    {
        $this->db = $db;
        $this->pdf = $pdf;
        $this->whatsapp = $whatsapp;
        $this->appConfig = require dirname(__DIR__, 2) . '/config/app.php';
    }

    /**
     * POS Terminal Interface.
     */
    public function pos(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $currentUser = $_SESSION['elara_user'] ?? [
            'id' => 'usr_cashier',
            'name' => 'Front Desk Cashier',
            'role' => 'cashier',
            'username' => 'cashier',
        ];

        $services = $this->db->getCollection('services', 'name');
        $inventory = $this->db->getCollection('inventory', 'name');
        $staff = $this->db->getCollection('staff', 'name');
        $appConfig = $this->appConfig;

        require dirname(__DIR__) . '/Views/pos.php';
    }

    /**
     * Invoices History & Management.
     */
    public function invoices(): void
    {
        $invoices = $this->db->getCollection('invoices', '-created_at');
        $appConfig = $this->appConfig;

        require dirname(__DIR__) . '/Views/invoices.php';
    }

    /**
     * Process POS Checkout, inventory decrement, PDF generation, and WhatsApp link creation.
     */
    public function checkout(): void
    {
        header('Content-Type: application/json');

        try {
            $rawInput = file_get_contents('php://input');
            $rawInput = preg_replace('/^\xEF\xBB\xBF/', '', (string)$rawInput);
            $input = json_decode($rawInput, true) ?? $_POST;
            $items = $input['items'] ?? [];

            if (empty($items)) {
                echo json_encode(['success' => false, 'error' => 'Cart is empty. Please add services or products.']);
                exit;
            }

            $customerName = trim($input['customer_name'] ?? 'Walk-in Guest');
            if (empty($customerName)) {
                $customerName = 'Walk-in Guest';
            }
            $customerPhone = trim($input['customer_phone'] ?? '');
            $customerEmail = trim($input['customer_email'] ?? '');
            $stylistId = $input['stylist_id'] ?? '';
            $paymentMethod = $input['payment_method'] ?? 'Cash';
            $discountPercent = max(0, min(100, (float)($input['discount_percent'] ?? 0)));
            $notes = trim($input['notes'] ?? 'Thank you for visiting ELARA Parlor & Spa!');

            // Find stylist name
            $stylistName = 'Senior Stylist';
            if ($stylistId) {
                $stylistDoc = $this->db->getDocument('staff', $stylistId);
                if ($stylistDoc && !empty($stylistDoc['name'])) {
                    $stylistName = $stylistDoc['name'];
                }
            }

            // Calculate totals and decrement product inventory
            $subtotal = 0.0;
            $processedItems = [];

            foreach ($items as $item) {
                $qty = max(1, (int)($item['quantity'] ?? 1));
                $price = (float)($item['price'] ?? 0);
                $type = $item['type'] ?? 'service';
                $itemId = $item['id'] ?? '';
                $name = $item['name'] ?? 'Custom Service';

                $lineTotal = round($price * $qty, 2);
                $subtotal += $lineTotal;

                $processedItems[] = [
                    'id' => $itemId,
                    'type' => $type,
                    'name' => $name,
                    'price' => $price,
                    'quantity' => $qty,
                    'total' => $lineTotal,
                ];

                // If retail product, deduct from inventory
                if ($type === 'product' && !empty($itemId)) {
                    $product = $this->db->getDocument('inventory', $itemId);
                    if ($product) {
                        $currentStock = (int)($product['stock'] ?? 0);
                        $newStock = max(0, $currentStock - $qty);
                        $this->db->updateDocument('inventory', $itemId, ['stock' => $newStock]);
                    }
                }
            }

            $discountAmount = round($subtotal * ($discountPercent / 100), 2);
            $taxRate = (float)($this->appConfig['parlor']['tax_rate'] ?? 8.0);
            $taxable = max(0, $subtotal - $discountAmount);
            $taxAmount = round($taxable * ($taxRate / 100), 2);
            $total = round($taxable + $taxAmount, 2);

            // Generate unique invoice number: INV-YEAR-RANDOM
            $datePrefix = date('Ymd');
            $randSeq = strtoupper(substr(bin2hex(random_bytes(3)), 0, 4));
            $invoiceNumber = "INV-{$datePrefix}-{$randSeq}";

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Capture Active Cashier / User Name
            $cashierName = trim($input['cashier_name'] ?? '');
            if (empty($cashierName)) {
                $cashierName = $_SESSION['elara_user']['name'] ?? 'Front Desk Cashier';
            }
            $cashierId = $_SESSION['elara_user']['id'] ?? ($input['cashier_id'] ?? 'usr_cashier');

            $invoiceData = [
                'id' => $invoiceNumber,
                'invoice_number' => $invoiceNumber,
                'customer_name' => $customerName,
                'customer_phone' => $customerPhone,
                'customer_email' => $customerEmail,
                'stylist_id' => $stylistId,
                'stylist_name' => $stylistName,
                'cashier_id' => $cashierId,
                'cashier_name' => $cashierName,
                'items' => $processedItems,
                'subtotal' => $subtotal,
                'discount_percent' => $discountPercent,
                'discount_amount' => $discountAmount,
                'tax_percent' => $taxRate,
                'tax_amount' => $taxAmount,
                'total' => $total,
                'payment_method' => $paymentMethod,
                'status' => 'Paid',
                'notes' => $notes,
                'created_at' => date('Y-m-d H:i:s'),
            ];

            // 1. Generate Branded PDF Invoice
            $pdfResult = $this->pdf->generateInvoicePdf($invoiceData);
            $invoiceData['pdf_path'] = $pdfResult['relative_url'];
            $invoiceData['pdf_url'] = $pdfResult['public_url'];

            // 2. Save Invoice to Firestore
            $createdInvoice = $this->db->createDocument('invoices', $invoiceData, $invoiceNumber);

            // 3. Generate WhatsApp deep link
            $waLink = '';
            if (!empty($customerPhone)) {
                $waLink = $this->whatsapp->generateWaMeLink($customerPhone, $createdInvoice, $pdfResult['public_url']);
            }

            echo json_encode([
                'success' => true,
                'invoice' => $createdInvoice,
                'pdf_url' => $pdfResult['public_url'],
                'receipt_url' => '/receipt?id=' . $invoiceNumber,
                'whatsapp_link' => $waLink,
                'message' => "Sale completed successfully! Invoice #{$invoiceNumber} issued.",
            ]);
            exit;
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit;
        }
    }

    /**
     * Render 80mm/58mm Thermal Print Receipt.
     */
    public function receipt(): void
    {
        $id = $_GET['id'] ?? '';
        if (empty($id)) {
            die("Invoice ID is required.");
        }

        $invoice = $this->db->getDocument('invoices', $id);
        if (!$invoice) {
            die("Invoice #{$id} not found.");
        }

        $appConfig = $this->appConfig;
        $parlor = $appConfig['parlor'];

        require dirname(__DIR__) . '/Views/receipt_thermal.php';
    }

    /**
     * Download or view PDF invoice.
     */
    public function downloadPdf(): void
    {
        $id = $_GET['id'] ?? '';
        $download = isset($_GET['download']) && $_GET['download'] === '1';

        if (empty($id)) {
            die("Invoice ID missing.");
        }

        // Check if PDF file exists, or regenerate on the fly
        $filePath = dirname(__DIR__, 2) . "/public/storage/invoices/{$id}.pdf";
        if (!file_exists($filePath)) {
            $invoice = $this->db->getDocument('invoices', $id);
            if ($invoice) {
                $this->pdf->generateInvoicePdf($invoice);
            }
        }

        $this->pdf->streamPdf($id, $download);
    }

    /**
     * Return WhatsApp share link for an existing invoice.
     */
    public function whatsappShare(): void
    {
        header('Content-Type: application/json');
        $id = $_GET['id'] ?? '';
        $phone = $_GET['phone'] ?? '';

        if (empty($id)) {
            echo json_encode(['success' => false, 'error' => 'Invoice ID required.']);
            exit;
        }

        $invoice = $this->db->getDocument('invoices', $id);
        if (!$invoice) {
            echo json_encode(['success' => false, 'error' => 'Invoice not found.']);
            exit;
        }

        $targetPhone = !empty($phone) ? $phone : ($invoice['customer_phone'] ?? '');
        if (empty($targetPhone)) {
            echo json_encode(['success' => false, 'error' => 'Customer phone number is required for WhatsApp.']);
            exit;
        }

        $pdfUrl = rtrim($this->appConfig['url'], '/') . "/invoice/download?id=" . $id;
        $waLink = $this->whatsapp->generateWaMeLink($targetPhone, $invoice, $pdfUrl);
        $messageText = $this->whatsapp->buildReceiptMessage($invoice, $pdfUrl);

        echo json_encode([
            'success' => true,
            'whatsapp_link' => $waLink,
            'message_text' => $messageText,
            'phone' => $targetPhone,
        ]);
        exit;
    }
}

