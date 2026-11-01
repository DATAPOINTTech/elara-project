<?php

declare(strict_types=1);

namespace Elara\Services;

use Dompdf\Dompdf;
use Dompdf\Options;
use Exception;

class PdfService
{
    private array $appConfig;
    private string $storageDir;

    public function __construct()
    {
        $this->appConfig = require dirname(__DIR__, 2) . '/config/app.php';
        $this->storageDir = dirname(__DIR__, 2) . '/public/storage/invoices';

        if (!is_dir($this->storageDir)) {
            @mkdir($this->storageDir, 0777, true);
        }
    }

    /**
     * Generate branded PDF invoice and return relative and absolute paths.
     */
    public function generateInvoicePdf(array $invoice): array
    {
        $invoiceNumber = $invoice['invoice_number'] ?? ('INV-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8)));
        $filename = "{$invoiceNumber}.pdf";
        $filePath = $this->storageDir . DIRECTORY_SEPARATOR . $filename;
        $relativeUrl = "storage/invoices/{$filename}";

        $html = $this->renderInvoiceHtml($invoice);

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $pdfOutput = $dompdf->output();
        file_put_contents($filePath, $pdfOutput);

        return [
            'file_name' => $filename,
            'file_path' => $filePath,
            'relative_url' => $relativeUrl,
            'public_url' => rtrim($this->appConfig['url'], '/') . '/' . $relativeUrl,
        ];
    }

    /**
     * Stream or download PDF file to browser.
     */
    public function streamPdf(string $invoiceNumber, bool $download = false): void
    {
        $filename = "{$invoiceNumber}.pdf";
        $filePath = $this->storageDir . DIRECTORY_SEPARATOR . $filename;

        if (!file_exists($filePath)) {
            throw new Exception("Invoice PDF {$filename} not found.");
        }

        header('Content-Type: application/pdf');
        header('Content-Length: ' . filesize($filePath));
        $disposition = $download ? 'attachment' : 'inline';
        header("Content-Disposition: {$disposition}; filename=\"{$filename}\"");
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');
        readfile($filePath);
        exit;
    }

    /**
     * Render luxury styled invoice HTML for Dompdf matching Logo palette.
     */
    private function renderInvoiceHtml(array $invoice): string
    {
        $parlor = $this->appConfig['parlor'];
        $currency = $parlor['currency_symbol'];
        $invoiceNum = htmlspecialchars($invoice['invoice_number'] ?? 'INV-0000');
        $date = htmlspecialchars($invoice['created_at'] ?? date('Y-m-d H:i:s'));
        $customerName = htmlspecialchars($invoice['customer_name'] ?? 'Walk-in Guest');
        $customerPhone = htmlspecialchars($invoice['customer_phone'] ?? 'N/A');
        $customerEmail = htmlspecialchars($invoice['customer_email'] ?? 'N/A');
        $stylist = htmlspecialchars($invoice['stylist_name'] ?? 'Senior Stylist');
        $cashier = htmlspecialchars($invoice['cashier_name'] ?? 'Front Desk Cashier');
        $paymentMethod = htmlspecialchars($invoice['payment_method'] ?? 'Cash');
        $status = htmlspecialchars($invoice['status'] ?? 'Paid');
        $notes = htmlspecialchars($invoice['notes'] ?? 'Thank you for visiting ELARA Beauté Studio. We look forward to pampering you again soon.');

        $subtotal = number_format((float)($invoice['subtotal'] ?? 0), 2);
        $discountAmount = number_format((float)($invoice['discount_amount'] ?? 0), 2);
        $discountPercent = (float)($invoice['discount_percent'] ?? 0);
        $taxAmount = number_format((float)($invoice['tax_amount'] ?? 0), 2);
        $taxPercent = (float)($invoice['tax_percent'] ?? $parlor['tax_rate']);
        $total = number_format((float)($invoice['total'] ?? 0), 2);

        // Load logo as base64 for bulletproof Dompdf rendering
        $logoFile = dirname(__DIR__, 2) . '/public/assets/images/elara_logo.jpg';
        $logoBase64 = file_exists($logoFile) ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($logoFile)) : '';

        $logoHtml = $logoBase64 ? "<img src=\"{$logoBase64}\" style=\"height: 52px; width: auto; margin-bottom: 6px; display: block;\">" : '';

        $itemsHtml = '';
        foreach ($invoice['items'] ?? [] as $index => $item) {
            $num = $index + 1;
            $name = htmlspecialchars($item['name'] ?? 'Item');
            $type = ucfirst(htmlspecialchars($item['type'] ?? 'service'));
            $badgeColor = ($type === 'Service') ? '#583e28' : '#795a34';
            $badgeBg = ($type === 'Service') ? '#f5efe4' : '#faf6ee';
            $qty = (int)($item['quantity'] ?? 1);
            $price = number_format((float)($item['price'] ?? 0), 2);
            $lineTotal = number_format((float)($item['total'] ?? ($qty * (float)$item['price'])), 2);

            $itemsHtml .= "
                <tr>
                    <td style=\"padding: 10px 12px; border-bottom: 1px solid #f3e9d7; text-align: center; color: #78716c;\">{$num}</td>
                    <td style=\"padding: 10px 12px; border-bottom: 1px solid #f3e9d7;\">
                        <strong style=\"color: #1c1917; font-size: 13px;\">{$name}</strong>
                    </td>
                    <td style=\"padding: 10px 12px; border-bottom: 1px solid #f3e9d7; text-align: center;\">
                        <span style=\"display: inline-block; padding: 2px 8px; border-radius: 9999px; font-size: 10px; font-weight: bold; background-color: {$badgeBg}; color: {$badgeColor}; border: 1px solid #eadeca;\">{$type}</span>
                    </td>
                    <td style=\"padding: 10px 12px; border-bottom: 1px solid #f3e9d7; text-align: center; color: #44403c;\">{$qty}</td>
                    <td style=\"padding: 10px 12px; border-bottom: 1px solid #f3e9d7; text-align: right; color: #44403c;\">{$currency}{$price}</td>
                    <td style=\"padding: 10px 12px; border-bottom: 1px solid #f3e9d7; text-align: right; font-weight: bold; color: #1c1917;\">{$currency}{$lineTotal}</td>
                </tr>
            ";
        }

        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {$invoiceNum}</title>
    <style>
        @page {
            margin: 28px 32px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1c1917;
            font-size: 12px;
            line-height: 1.45;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #b88c58;
            padding-bottom: 16px;
            margin-bottom: 20px;
        }
        .logo-title {
            font-size: 22px;
            font-weight: 800;
            color: #1c1917;
            letter-spacing: 1px;
            margin: 0;
            text-transform: uppercase;
        }
        .tagline {
            font-size: 10px;
            color: #b88c58;
            margin-top: 3px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            font-weight: bold;
        }
        .invoice-title {
            font-size: 22px;
            font-weight: 800;
            color: #1c1917;
            text-align: right;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .invoice-badge {
            display: inline-block;
            background: #1c1917;
            color: #f5efe4;
            font-size: 11px;
            font-weight: bold;
            padding: 4px 12px;
            border-radius: 4px;
            margin-top: 5px;
            border: 1px solid #b88c58;
        }
        .info-table {
            width: 100%;
            margin-bottom: 24px;
        }
        .info-card {
            background-color: #fbf9f5;
            border: 1px solid #eadeca;
            border-radius: 8px;
            padding: 12px 14px;
        }
        .info-card-header {
            font-size: 10px;
            font-weight: 800;
            color: #855d37;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
            border-bottom: 1px dashed #dcc6a8;
            padding-bottom: 4px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th {
            background: #1c1917;
            color: #f5efe4;
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
            padding: 10px 12px;
            letter-spacing: 0.5px;
        }
        .totals-table {
            width: 100%;
        }
        .total-row td {
            padding: 6px 12px;
            font-size: 12px;
        }
        .grand-total {
            background: #1c1917;
            color: #f5efe4 !important;
            font-size: 15px !important;
            font-weight: 800;
            border-radius: 6px;
        }
        .grand-total td {
            color: #f5efe4 !important;
            padding: 10px 12px !important;
        }
        .footer-note {
            margin-top: 35px;
            padding-top: 15px;
            border-top: 1px solid #e7e5e4;
            text-align: center;
            font-size: 10px;
            color: #78716c;
        }
        .stamp-box {
            border: 2px dashed #b88c58;
            color: #855d37;
            font-weight: 800;
            font-size: 12px;
            letter-spacing: 1.5px;
            padding: 8px 16px;
            display: inline-block;
            border-radius: 6px;
            transform: rotate(-2deg);
            text-transform: uppercase;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <table class="header-table">
        <tr>
            <td style="width: 55%; vertical-align: top;">
                {$logoHtml}
                <h1 class="logo-title">{$parlor['name']}</h1>
                <div class="tagline">{$parlor['tagline']}</div>
                <div style="margin-top: 8px; color: #57534e; font-size: 11px; line-height: 1.4;">
                    {$parlor['address']}<br>
                    Phone: {$parlor['phone']} | WhatsApp: {$parlor['whatsapp']}<br>
                    Email: {$parlor['email']}
                </div>
            </td>
            <td style="width: 45%; text-align: right; vertical-align: top;">
                <h2 class="invoice-title">Guest Receipt</h2>
                <div class="invoice-badge"># {$invoiceNum}</div>
                <div style="margin-top: 8px; font-size: 11px; color: #57534e;">
                    <strong>Date & Time:</strong> {$date}<br>
                    <strong>Payment Mode:</strong> {$paymentMethod}<br>
                    <strong>Billed By (Cashier):</strong> <strong style="color: #1c1917;">{$cashier}</strong><br>
                    <strong>Status:</strong> <span style="color: #15803d; font-weight: bold;">{$status}</span>
                </div>
            </td>
        </tr>
    </table>

    <!-- Client & Stylist Info -->
    <table class="info-table" cellspacing="10" cellpadding="0">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <div class="info-card">
                    <div class="info-card-header">Billed To (Guest Details)</div>
                    <strong style="font-size: 14px; color: #1c1917;">{$customerName}</strong><br>
                    <span style="color: #57534e; font-size: 11px;">Phone: {$customerPhone}</span><br>
                    <span style="color: #57534e; font-size: 11px;">Email: {$customerEmail}</span>
                </div>
            </td>
            <td style="width: 50%; vertical-align: top;">
                <div class="info-card">
                    <div class="info-card-header">Salon Service Artist</div>
                    <strong style="font-size: 14px; color: #1c1917;">{$stylist}</strong><br>
                    <span style="color: #57534e; font-size: 11px;">Station: ELARA Beauté Studio Main</span><br>
                    <span style="color: #57534e; font-size: 11px;">Service Guarantee: 100% Satisfaction</span>
                </div>
            </td>
        </tr>
    </table>

    <!-- Line Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">#</th>
                <th style="width: 45%; text-align: left;">Treatment / Product</th>
                <th style="width: 15%; text-align: center;">Category</th>
                <th style="width: 10%; text-align: center;">Qty</th>
                <th style="width: 12%; text-align: right;">Unit Price</th>
                <th style="width: 13%; text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            {$itemsHtml}
        </tbody>
    </table>

    <!-- Totals & Notes -->
    <table class="totals-table">
        <tr>
            <td style="width: 55%; vertical-align: top; padding-right: 25px;">
                <div style="background: #fbf9f5; border: 1px solid #eadeca; border-radius: 6px; padding: 12px; margin-bottom: 12px;">
                    <div style="font-size: 10px; font-weight: bold; color: #855d37; text-transform: uppercase; margin-bottom: 4px;">Artist Note & Aftercare</div>
                    <div style="color: #44403c; font-size: 11px; font-style: italic;">
                        "{$notes}"
                    </div>
                </div>

                <div style="margin-top: 15px;">
                    <span class="stamp-box">PAID & VERIFIED</span>
                </div>
            </td>
            <td style="width: 45%; vertical-align: top;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr class="total-row">
                        <td style="color: #78716c; text-align: left;">Subtotal:</td>
                        <td style="text-align: right; font-weight: bold; color: #1c1917;">{$currency}{$subtotal}</td>
                    </tr>
                    <tr class="total-row">
                        <td style="color: #78716c; text-align: left;">Discount ({$discountPercent}%):</td>
                        <td style="text-align: right; color: #855d37; font-weight: bold;">- {$currency}{$discountAmount}</td>
                    </tr>
                    <tr class="total-row">
                        <td style="color: #78716c; text-align: left;">{$parlor['tax_name']} ({$taxPercent}%):</td>
                        <td style="text-align: right; color: #44403c;">+ {$currency}{$taxAmount}</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding-top: 6px;"></td>
                    </tr>
                    <tr class="grand-total">
                        <td style="text-align: left; text-transform: uppercase; color: #f5efe4;">Grand Total:</td>
                        <td style="text-align: right; color: #f5efe4;">{$currency}{$total}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Footer -->
    <div class="footer-note">
        <strong>{$parlor['name']}</strong> &bull; {$parlor['tagline']} &bull; All treatments performed by certified artists.<br>
        Invoices are electronically signed and verified. Instagram: @elarabeautestudio &bull; Phone: {$parlor['phone']} &bull; WhatsApp: {$parlor['whatsapp']}<br>
        <span style="display: inline-block; margin-top: 5px; font-size: 8.5px; color: #a8a29e;">Designed, Developed and Hosted By : <strong style="color: #44403c;">DATAPOINT Technologies</strong></span>
    </div>

</body>
</html>
HTML;
    }
}
