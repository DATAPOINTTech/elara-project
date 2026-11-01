<?php
$currentPage = 'invoices';
$pageTitle = 'Invoice History - ' . $appConfig['parlor']['name'];
$currency = $appConfig['parlor']['currency_symbol'];
require __DIR__ . '/layouts/header.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-charcoal-900 border border-brand-300/40 text-brand-300 flex items-center justify-center font-bold text-sm shadow-xs">
                <i class="fa-solid fa-receipt"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black text-charcoal-900 font-cinzel flex items-center">
                    Guest Invoices & Receipts
                </h1>
                <p class="text-xs text-charcoal-400">History of all transactions recorded in Google Cloud Firestore</p>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <a href="/reports/export" class="px-4 py-2.5 bg-brand-50 hover:bg-brand-100 text-charcoal-800 border border-brand-200 rounded-xl text-xs font-bold tracking-wider uppercase transition flex items-center">
                <i class="fa-solid fa-file-csv mr-1.5 text-brand-600"></i> Export CSV
            </a>
            <a href="/pos" class="px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-xs font-bold tracking-wider uppercase shadow-xs transition flex items-center">
                <i class="fa-solid fa-plus mr-1.5"></i> New POS Bill
            </a>
        </div>
    </div>

    <!-- Invoices Table Container -->
    <div class="bg-white rounded-3xl border border-brand-200 shadow-2xs overflow-hidden">
        
        <div class="p-5 border-b border-brand-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="text-xs font-bold text-charcoal-700">
                Total Invoices: <span class="text-brand-800 font-extrabold"><?= count($invoices) ?></span>
            </div>
            <div class="relative w-full sm:w-64">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-charcoal-400 text-xs"></i>
                <input 
                    type="text" 
                    id="invoiceSearch" 
                    placeholder="Search invoice or guest..." 
                    oninput="filterInvoicesTable(this.value)"
                    class="w-full pl-8 pr-3 py-1.5 bg-brand-50/40 border border-brand-200 rounded-xl text-xs focus:ring-1 focus:ring-brand-500 placeholder-charcoal-300"
                >
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-charcoal-600" id="invoicesTable">
                <thead class="bg-brand-50/70 uppercase text-[10px] text-charcoal-500 font-bold border-b border-brand-200">
                    <tr>
                        <th class="py-3.5 px-4">Invoice #</th>
                        <th class="py-3.5 px-4">Date & Time</th>
                        <th class="py-3.5 px-4">Guest Details</th>
                        <th class="py-3.5 px-4">Artist</th>
                        <th class="py-3.5 px-4">Billed By</th>
                        <th class="py-3.5 px-4 text-center">Items</th>
                        <th class="py-3.5 px-4 text-right">Grand Total</th>
                        <th class="py-3.5 px-4 text-center">Payment</th>
                        <th class="py-3.5 px-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-brand-100">
                    <?php if (empty($invoices)): ?>
                    <tr>
                        <td colspan="9" class="py-12 text-center text-charcoal-400">
                            <i class="fa-solid fa-receipt text-3xl mb-2 text-brand-300 block"></i>
                            No invoices issued yet.
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($invoices as $inv): ?>
                    <tr class="hover:bg-brand-50/40 transition invoice-row" data-search="<?= strtolower(htmlspecialchars(($inv['invoice_number'] ?? '') . ' ' . ($inv['customer_name'] ?? '') . ' ' . ($inv['customer_phone'] ?? '') . ' ' . ($inv['cashier_name'] ?? ''))) ?>">
                        <td class="py-3.5 px-4 font-mono font-bold text-charcoal-900">
                            #<?= htmlspecialchars($inv['invoice_number'] ?? 'INV-0000') ?>
                        </td>
                        <td class="py-3.5 px-4 text-charcoal-500">
                            <?= date('M d, Y - H:i', strtotime($inv['created_at'] ?? 'now')) ?>
                        </td>
                        <td class="py-3.5 px-4">
                            <strong class="text-charcoal-900 block"><?= htmlspecialchars($inv['customer_name'] ?? 'Walk-in') ?></strong>
                            <?php if (!empty($inv['customer_phone'])): ?>
                            <span class="text-[11px] text-charcoal-400"><?= htmlspecialchars($inv['customer_phone']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3.5 px-4 text-charcoal-700 font-medium">
                            <?= htmlspecialchars($inv['stylist_name'] ?? 'Artist') ?>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="inline-flex items-center space-x-1.5 px-2 py-0.5 rounded-md bg-brand-50 border border-brand-200 text-charcoal-800 text-[11px] font-semibold">
                                <i class="fa-solid fa-user-tag text-[10px] text-brand-600"></i>
                                <span><?= htmlspecialchars($inv['cashier_name'] ?? 'Front Desk') ?></span>
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-brand-50 text-brand-900 border border-brand-200">
                                <?= count($inv['items'] ?? []) ?> items
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-right font-black text-charcoal-900 text-sm">
                            <?= $currency ?><?= number_format((float)($inv['total'] ?? 0), 2) ?>
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                <?= htmlspecialchars($inv['payment_method'] ?? 'Cash') ?>
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <div class="inline-flex items-center space-x-1.5">
                                <!-- Thermal Receipt -->
                                <a href="/receipt?id=<?= urlencode($inv['invoice_number']) ?>" target="_blank" class="p-1.5 bg-brand-50 hover:bg-brand-100 text-charcoal-700 rounded-lg transition" title="Print 80mm Thermal Receipt">
                                    <i class="fa-solid fa-print"></i>
                                </a>
                                <!-- Download PDF -->
                                <a href="/invoice/download?id=<?= urlencode($inv['invoice_number']) ?>&download=1" class="p-1.5 bg-brand-50 hover:bg-brand-100 text-brand-800 rounded-lg transition" title="Download PDF">
                                    <i class="fa-solid fa-file-pdf"></i>
                                </a>
                                <!-- WhatsApp Share Link -->
                                <button onclick="triggerWhatsAppShare('<?= htmlspecialchars($inv['invoice_number']) ?>', '<?= htmlspecialchars($inv['customer_phone'] ?? '') ?>')" class="p-1.5 bg-brand-50 hover:bg-emerald-50 text-emerald-700 rounded-lg transition" title="WhatsApp Share Link">
                                    <i class="fa-brands fa-whatsapp"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

</div>

<script>
function filterInvoicesTable(query) {
    const q = query.toLowerCase().trim();
    const rows = document.querySelectorAll('.invoice-row');
    rows.forEach(r => {
        const text = r.dataset.search || '';
        if (!q || text.includes(q)) {
            r.style.display = '';
        } else {
            r.style.display = 'none';
        }
    });
}

async function triggerWhatsAppShare(invoiceId, defaultPhone) {
    let phone = defaultPhone;
    if (!phone) {
        phone = prompt('Enter Guest WhatsApp Phone Number (with country code):');
        if (!phone) return;
    }

    try {
        const res = await fetch(`/api/whatsapp/share?id=${encodeURIComponent(invoiceId)}&phone=${encodeURIComponent(phone)}`);
        const data = await res.json();
        if (data.success && data.whatsapp_link) {
            window.open(data.whatsapp_link, '_blank');
        } else {
            alert(data.error || 'Could not generate WhatsApp link.');
        }
    } catch (err) {
        alert('Server communication error.');
    }
}
</script>

<?php require __DIR__ . '/layouts/footer.php'; ?>
