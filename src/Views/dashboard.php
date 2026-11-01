<?php
$currentPage = 'dashboard';
$pageTitle = 'Dashboard - ' . $appConfig['parlor']['name'];
$currency = $appConfig['parlor']['currency_symbol'];
require __DIR__ . '/layouts/header.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Top Luxury Hero Banner Styled as per Logo Theme -->
    <div class="bg-gradient-to-r from-charcoal-900 via-charcoal-800 to-black rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden border border-brand-300/20">
        <!-- Background Ambient Watermark Pattern -->
        <div class="absolute -right-12 -bottom-12 opacity-5 pointer-events-none select-none">
            <img src="/assets/images/elara_logo.jpg" alt="Watermark" class="w-96 h-auto invert">
        </div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center space-x-5">
                <div class="bg-white p-2 rounded-2xl border border-brand-300/50 shadow-md shrink-0 hidden sm:block">
                    <img src="/assets/images/elara_logo.jpg" alt="ELARA Emblem" class="h-16 w-auto object-contain rounded-xl">
                </div>
                <div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-brand-500/20 text-brand-300 border border-brand-400/30 backdrop-blur-sm mb-2">
                        <i class="fa-solid fa-crown mr-1.5 text-brand-400"></i> Haute Beauté & Day Spa
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-wide font-cinzel text-white">
                        <?= htmlspecialchars($appConfig['parlor']['name']) ?>
                    </h1>
                    <p class="text-brand-200/90 text-xs sm:text-sm mt-1 max-w-xl font-medium tracking-wide">
                        <?= htmlspecialchars($appConfig['parlor']['tagline']) ?> &bull; <?= htmlspecialchars($appConfig['parlor']['subtitle']) ?>
                    </p>
                </div>
            </div>

            <!-- Quick Action Shortcuts -->
            <div class="flex flex-wrap items-center gap-3">
                <a href="/pos" class="px-5 py-3 bg-brand-500 hover:bg-brand-600 text-white rounded-xl font-bold text-xs tracking-wider uppercase shadow-md transition flex items-center group">
                    <i class="fa-solid fa-cash-register mr-2 group-hover:scale-110 transition"></i>
                    Launch POS Terminal
                </a>
                <button onclick="toggleElaraChat()" class="px-4 py-3 bg-white/10 hover:bg-white/15 border border-brand-300/40 text-brand-200 rounded-xl font-bold text-xs tracking-wider uppercase backdrop-blur-sm transition flex items-center">
                    <i class="fa-solid fa-wand-magic-sparkles mr-2 text-brand-400"></i> AI Studio Copilot
                </button>
            </div>
        </div>
    </div>

    <!-- Stat Cards Grid (Styled per Logo Warm Champagne & Charcoal Palette) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Today's Revenue -->
        <div class="bg-white p-5 rounded-2xl border border-brand-200 shadow-2xs flex items-center justify-between hover:shadow-sm hover:border-brand-300 transition">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-charcoal-400">Today's Revenue</p>
                <h3 class="text-2xl font-extrabold text-charcoal-900 mt-1"><?= $currency ?><?= number_format((float)$stats['today_revenue'], 2) ?></h3>
                <p class="text-[11px] text-emerald-700 font-semibold mt-1 flex items-center">
                    <i class="fa-solid fa-arrow-trend-up mr-1 text-emerald-600"></i> <?= (int)$stats['today_orders'] ?> guest visits completed
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-brand-50 border border-brand-200 text-brand-700 flex items-center justify-center text-xl shadow-2xs">
                <i class="fa-solid fa-receipt"></i>
            </div>
        </div>

        <!-- Invoices Issued -->
        <div class="bg-white p-5 rounded-2xl border border-brand-200 shadow-2xs flex items-center justify-between hover:shadow-sm hover:border-brand-300 transition">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-charcoal-400">Total Invoices</p>
                <h3 class="text-2xl font-extrabold text-charcoal-900 mt-1"><?= (int)$stats['total_orders'] ?></h3>
                <p class="text-[11px] text-charcoal-500 font-medium mt-1">
                    Avg. ticket <?= $currency ?><?= number_format((float)$stats['avg_order_value'], 2) ?>
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-brand-50 border border-brand-200 text-brand-700 flex items-center justify-center text-xl shadow-2xs">
                <i class="fa-solid fa-file-invoice-dollar"></i>
            </div>
        </div>

        <!-- Inventory Alerts -->
        <div class="bg-white p-5 rounded-2xl border border-brand-200 shadow-2xs flex items-center justify-between hover:shadow-sm hover:border-brand-300 transition">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-charcoal-400">Inventory Status</p>
                <h3 class="text-2xl font-extrabold <?= $stats['low_stock_count'] > 0 ? 'text-amber-700' : 'text-charcoal-900' ?> mt-1">
                    <?= (int)$stats['low_stock_count'] ?> Low Stock
                </h3>
                <p class="text-[11px] text-charcoal-500 font-medium mt-1">
                    <?= $stats['low_stock_count'] > 0 ? 'Requires replenishment' : 'Adequate supply buffer' ?>
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl <?= $stats['low_stock_count'] > 0 ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-brand-50 text-brand-700 border-brand-200' ?> border flex items-center justify-center text-xl shadow-2xs">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
        </div>

        <!-- Stylists on Roster -->
        <div class="bg-white p-5 rounded-2xl border border-brand-200 shadow-2xs flex items-center justify-between hover:shadow-sm hover:border-brand-300 transition">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-charcoal-400">Master Artists</p>
                <h3 class="text-2xl font-extrabold text-charcoal-900 mt-1"><?= count($staff) ?> Stylists</h3>
                <p class="text-[11px] text-emerald-700 font-semibold mt-1">
                    <i class="fa-solid fa-circle text-[8px] mr-1 text-emerald-500"></i> Active stations
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-brand-50 border border-brand-200 text-brand-700 flex items-center justify-center text-xl shadow-2xs">
                <i class="fa-solid fa-spa"></i>
            </div>
        </div>
    </div>

    <!-- Main Content Split: Recent Invoices & Sidebar -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Recent Invoices Table (2 cols) -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-brand-200 shadow-2xs p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-brand-100">
                <div>
                    <h2 class="text-base font-extrabold text-charcoal-900 tracking-wide">Recent Guest Transactions</h2>
                    <p class="text-xs text-charcoal-400">Live feed recorded in Google Cloud Firestore</p>
                </div>
                <a href="/invoices" class="text-xs font-bold text-brand-700 hover:text-brand-900 flex items-center">
                    View Invoices <i class="fa-solid fa-arrow-right ml-1"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-charcoal-600">
                    <thead class="bg-brand-50/70 uppercase text-[10px] text-charcoal-500 font-bold border-b border-brand-200">
                        <tr>
                            <th class="py-3 px-3">Invoice #</th>
                            <th class="py-3 px-3">Guest Details</th>
                            <th class="py-3 px-3">Artist</th>
                            <th class="py-3 px-3 text-right">Total</th>
                            <th class="py-3 px-3 text-center">Payment</th>
                            <th class="py-3 px-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brand-100">
                        <?php if (empty($recentInvoices)): ?>
                        <tr>
                            <td colspan="6" class="py-8 text-center text-charcoal-400">No invoices recorded yet. Click Launch POS Terminal to create one!</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach (array_slice($recentInvoices, 0, 6) as $inv): ?>
                        <tr class="hover:bg-brand-50/40 transition">
                            <td class="py-3.5 px-3 font-mono font-bold text-charcoal-900">
                                #<?= htmlspecialchars($inv['invoice_number'] ?? 'INV-0000') ?>
                                <span class="block text-[10px] text-charcoal-400 font-sans font-normal">
                                    <?= date('M d, H:i', strtotime($inv['created_at'] ?? 'now')) ?>
                                </span>
                            </td>
                            <td class="py-3.5 px-3 font-semibold text-charcoal-800">
                                <?= htmlspecialchars($inv['customer_name'] ?? 'Walk-in Guest') ?>
                            </td>
                            <td class="py-3.5 px-3 text-charcoal-500">
                                <?= htmlspecialchars($inv['stylist_name'] ?? 'Artist') ?>
                            </td>
                            <td class="py-3.5 px-3 text-right font-extrabold text-charcoal-900 text-sm">
                                <?= $currency ?><?= number_format((float)($inv['total'] ?? 0), 2) ?>
                            </td>
                            <td class="py-3.5 px-3 text-center">
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-brand-100 text-brand-900 border border-brand-200">
                                    <?= htmlspecialchars($inv['payment_method'] ?? 'Cash') ?>
                                </span>
                            </td>
                            <td class="py-3.5 px-3 text-center">
                                <div class="inline-flex items-center space-x-1.5">
                                    <!-- Print Thermal -->
                                    <a href="/receipt?id=<?= urlencode($inv['invoice_number']) ?>" target="_blank" class="p-1.5 bg-brand-50 hover:bg-brand-100 text-charcoal-700 rounded-lg transition" title="Print 80mm Receipt">
                                        <i class="fa-solid fa-print"></i>
                                    </a>
                                    <!-- Download PDF -->
                                    <a href="/invoice/download?id=<?= urlencode($inv['invoice_number']) ?>&download=1" class="p-1.5 bg-brand-50 hover:bg-brand-100 text-charcoal-700 rounded-lg transition" title="Download Branded PDF">
                                        <i class="fa-solid fa-file-pdf text-brand-700"></i>
                                    </a>
                                    <!-- WhatsApp Share -->
                                    <?php if (!empty($inv['customer_phone'])): ?>
                                    <a href="/api/whatsapp/share?id=<?= urlencode($inv['invoice_number']) ?>" target="_blank" class="p-1.5 bg-brand-50 hover:bg-emerald-50 text-emerald-700 rounded-lg transition" title="Send WhatsApp Receipt">
                                        <i class="fa-brands fa-whatsapp"></i>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right: Low Stock Warnings & Stylist Roster (1 col) -->
        <div class="space-y-6">
            
            <!-- Low Stock Card -->
            <div class="bg-white rounded-3xl border border-brand-200 shadow-2xs p-5 space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-bold text-charcoal-900 flex items-center">
                        <i class="fa-solid fa-triangle-exclamation text-amber-600 mr-2"></i> Stock Restock Alerts
                    </h3>
                    <a href="/inventory" class="text-xs text-brand-700 font-semibold hover:underline">Manage</a>
                </div>

                <?php if (empty($stats['low_stock_items'])): ?>
                <div class="text-center py-4 text-xs text-charcoal-500 bg-brand-50/50 rounded-xl border border-brand-100">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base mb-1 block"></i>
                    All retail products are well-stocked.
                </div>
                <?php else: ?>
                <div class="space-y-2">
                    <?php foreach ($stats['low_stock_items'] as $item): ?>
                    <div class="p-3 rounded-xl bg-amber-50/70 border border-amber-200/80 flex items-center justify-between text-xs">
                        <div>
                            <div class="font-bold text-charcoal-900"><?= htmlspecialchars($item['name']) ?></div>
                            <div class="text-[11px] text-amber-800">Safety threshold: <?= (int)$item['min_stock'] ?> units</div>
                        </div>
                        <span class="px-2.5 py-1 rounded-lg bg-amber-700 text-white font-extrabold text-[11px]">
                            <?= (int)$item['stock'] ?> left
                        </span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Stylist Roster Card with Logo Brand Motif -->
            <div class="bg-white rounded-3xl border border-brand-200 shadow-2xs p-5 space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-brand-100">
                    <h3 class="text-sm font-bold text-charcoal-900 flex items-center">
                        <i class="fa-solid fa-crown text-brand-600 mr-2"></i> Salon Specialists
                    </h3>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-brand-700">Roster</span>
                </div>
                <div class="divide-y divide-brand-100">
                    <?php foreach ($staff as $st): ?>
                    <div class="py-2.5 flex items-center justify-between text-xs">
                        <div class="flex items-center space-x-2.5">
                            <div class="w-8 h-8 rounded-full bg-brand-100 text-brand-900 border border-brand-300/60 font-bold flex items-center justify-center text-xs">
                                <?= substr($st['name'], 0, 1) ?>
                            </div>
                            <div>
                                <div class="font-bold text-charcoal-900"><?= htmlspecialchars($st['name']) ?></div>
                                <div class="text-[10px] text-charcoal-400"><?= htmlspecialchars($st['role']) ?></div>
                            </div>
                        </div>
                        <span class="text-[10px] font-semibold text-brand-800 bg-brand-100/70 px-2.5 py-0.5 rounded-full border border-brand-200">
                            <?= (float)$st['commission_rate'] ?>% Comm.
                        </span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>

    </div>

</div>

<?php require __DIR__ . '/layouts/footer.php'; ?>
