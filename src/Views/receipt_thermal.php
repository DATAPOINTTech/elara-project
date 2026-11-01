<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thermal Receipt - <?= htmlspecialchars($invoice['invoice_number'] ?? 'INV-0000') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/receipt.css">
</head>
<body class="bg-stone-100 min-h-screen py-6">

    <!-- Action Bar (Hidden on print) -->
    <div class="no-print max-w-md mx-auto mb-6 px-4">
        <div class="bg-white rounded-2xl shadow-md p-4 flex flex-wrap items-center justify-between gap-3 border border-stone-200">
            <a href="/pos" class="inline-flex items-center text-xs font-semibold text-stone-600 hover:text-stone-900 transition">
                <i class="fa-solid fa-arrow-left mr-1.5"></i> Back to POS
            </a>
            <div class="flex items-center space-x-2">
                <button onclick="window.print()" class="px-3.5 py-1.5 bg-stone-900 hover:bg-black text-amber-200 rounded-lg text-xs font-bold shadow-xs transition flex items-center">
                    <i class="fa-solid fa-print mr-1.5"></i> Print 80mm
                </button>
                <a href="/invoice/download?id=<?= urlencode($invoice['invoice_number']) ?>&download=1" class="px-3 py-1.5 bg-stone-800 hover:bg-stone-900 text-white rounded-lg text-xs font-bold transition flex items-center">
                    <i class="fa-solid fa-file-pdf mr-1.5 text-amber-400"></i> PDF
                </a>
                <?php if (!empty($invoice['customer_phone'])): ?>
                <a href="/api/whatsapp/share?id=<?= urlencode($invoice['invoice_number']) ?>" target="_blank" id="waDirectBtn" class="px-3 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg text-xs font-bold transition flex items-center">
                    <i class="fa-brands fa-whatsapp mr-1.5"></i> WhatsApp
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Thermal Receipt Body (Optimized for 80mm / 58mm ESC-POS) -->
    <div class="thermal-receipt-container">
        <!-- Logo & Studio Header -->
        <div class="receipt-header">
            <div style="margin-bottom: 8px;">
                <img src="/assets/images/elara_logo.jpg" alt="ELARA Logo" style="height: 48px; width: auto; margin: 0 auto; display: block;">
            </div>
            <div class="receipt-title"><?= htmlspecialchars($parlor['name']) ?></div>
            <div class="receipt-subtitle"><?= htmlspecialchars($parlor['tagline']) ?></div>
            <div style="font-size: 10px; color: #444;"><?= htmlspecialchars($parlor['address']) ?></div>
            <div style="font-size: 10px; color: #444;">Tel: <?= htmlspecialchars($parlor['phone']) ?> &bull; WA: <?= htmlspecialchars($parlor['whatsapp']) ?></div>
        </div>

        <div class="receipt-divider"></div>

        <!-- Receipt Meta -->
        <div class="receipt-row">
            <span>RCVR:</span>
            <strong>#<?= htmlspecialchars($invoice['invoice_number']) ?></strong>
        </div>
        <div class="receipt-row">
            <span>DATE:</span>
            <span><?= htmlspecialchars($invoice['created_at']) ?></span>
        </div>
        <div class="receipt-row">
            <span>CLIENT:</span>
            <span><?= htmlspecialchars($invoice['customer_name'] ?? 'Walk-in Guest') ?></span>
        </div>
        <?php if (!empty($invoice['customer_phone'])): ?>
        <div class="receipt-row">
            <span>PHONE:</span>
            <span><?= htmlspecialchars($invoice['customer_phone']) ?></span>
        </div>
        <?php endif; ?>
        <div class="receipt-row">
            <span>ARTIST:</span>
            <span><?= htmlspecialchars($invoice['stylist_name'] ?? 'Staff') ?></span>
        </div>
        <div class="receipt-row">
            <span>BILLED BY:</span>
            <strong><?= htmlspecialchars($invoice['cashier_name'] ?? 'Front Desk') ?></strong>
        </div>
        <div class="receipt-row">
            <span>PAYMENT:</span>
            <span><?= htmlspecialchars($invoice['payment_method']) ?> (PAID)</span>
        </div>

        <div class="receipt-double-divider"></div>

        <!-- Items Table -->
        <table class="receipt-table">
            <thead>
                <tr>
                    <th style="width: 55%;">TREATMENT / PRODUCT</th>
                    <th style="text-align: center; width: 15%;">QTY</th>
                    <th style="text-align: right; width: 30%;">AMT</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invoice['items'] ?? [] as $item): ?>
                <tr>
                    <td>
                        <?= htmlspecialchars($item['name']) ?><br>
                        <span style="font-size: 10px; color: #555;">[<?= ucfirst($item['type'] ?? 'service') ?>]</span>
                    </td>
                    <td style="text-align: center;"><?= (int)$item['quantity'] ?></td>
                    <td style="text-align: right;"><?= $parlor['currency_symbol'] ?><?= number_format((float)($item['total'] ?? ($item['price'] * $item['quantity'])), 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="receipt-divider"></div>

        <!-- Totals Calculation -->
        <div class="receipt-row">
            <span>SUBTOTAL:</span>
            <span><?= $parlor['currency_symbol'] ?><?= number_format((float)($invoice['subtotal'] ?? 0), 2) ?></span>
        </div>
        <?php if (!empty($invoice['discount_amount']) && (float)$invoice['discount_amount'] > 0): ?>
        <div class="receipt-row">
            <span>DISCOUNT (<?= (float)$invoice['discount_percent'] ?>%):</span>
            <span>-<?= $parlor['currency_symbol'] ?><?= number_format((float)$invoice['discount_amount'], 2) ?></span>
        </div>
        <?php endif; ?>
        <div class="receipt-row">
            <span><?= htmlspecialchars($parlor['tax_name']) ?> (<?= (float)($invoice['tax_percent'] ?? 0) ?>%):</span>
            <span>+<?= $parlor['currency_symbol'] ?><?= number_format((float)($invoice['tax_amount'] ?? 0), 2) ?></span>
        </div>

        <div class="receipt-double-divider"></div>

        <div class="receipt-row-bold">
            <span>TOTAL DUE:</span>
            <span><?= $parlor['currency_symbol'] ?><?= number_format((float)($invoice['total'] ?? 0), 2) ?></span>
        </div>

        <div class="receipt-divider"></div>

        <!-- Barcode / ESCPOS Footer -->
        <div class="barcode-box">
            <div class="barcode-lines">||| | ||||| || |||| ||||</div>
            <div style="font-size: 10px; font-weight: bold;"><?= htmlspecialchars($invoice['invoice_number']) ?></div>
        </div>

        <div class="receipt-footer">
            <div>*** THANK YOU FOR VISITING ***</div>
            <div style="margin-top: 3px; font-weight: bold; letter-spacing: 1px;">HAUTE BEAUTÉ & SPA</div>
            <div style="margin-top: 6px; font-size: 9px; line-height: 1.2;">
                Appointments & Queries: <?= htmlspecialchars($parlor['phone']) ?><br>
                Instagram: @elarabeautestudio &bull; <?= htmlspecialchars($parlor['email']) ?>
            </div>
            <div style="margin-top: 8px; padding-top: 6px; border-top: 1px dashed #bbb; font-size: 8px; color: #666; text-transform: uppercase; letter-spacing: 0.5px;">
                Designed, Developed and Hosted By :<br>
                <strong style="color: #222; font-size: 9px;">DATAPOINT Technologies</strong>
            </div>
        </div>
    </div>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('autoprint') === '1') {
            window.addEventListener('load', () => {
                setTimeout(() => window.print(), 300);
            });
        }
    </script>
</body>
</html>
