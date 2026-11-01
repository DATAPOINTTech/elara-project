<?php
$currentPage = 'reports';
$pageTitle = 'Reports & Financial Analytics - ' . $appConfig['parlor']['name'];
$currency = $appConfig['parlor']['currency_symbol'];
require __DIR__ . '/layouts/header.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-charcoal-900 border border-brand-300/40 text-brand-300 flex items-center justify-center font-bold text-sm shadow-xs">
                <i class="fa-solid fa-chart-line"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black text-charcoal-900 font-cinzel flex items-center">
                    Reports & Business Analytics
                </h1>
                <p class="text-xs text-charcoal-400">Revenue, stylist commissions, and sales intelligence</p>
            </div>
        </div>
        <a href="/reports/export" class="px-4 py-2.5 bg-charcoal-900 hover:bg-black text-brand-300 border border-brand-400/30 rounded-xl text-xs font-bold tracking-wider uppercase shadow-xs transition flex items-center self-start sm:self-auto">
            <i class="fa-solid fa-download mr-1.5 text-brand-400"></i> Export Complete Sales CSV
        </a>
    </div>

    <!-- Revenue Summary Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-brand-200 shadow-2xs">
            <p class="text-[11px] font-bold uppercase tracking-wider text-charcoal-400">Today's Sales</p>
            <h3 class="text-2xl font-extrabold text-charcoal-900 mt-1"><?= $currency ?><?= number_format((float)$metrics['today_revenue'], 2) ?></h3>
            <p class="text-[11px] text-emerald-700 font-semibold mt-1"><?= (int)$metrics['today_orders'] ?> orders today</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-brand-200 shadow-2xs">
            <p class="text-[11px] font-bold uppercase tracking-wider text-charcoal-400">Past 7 Days</p>
            <h3 class="text-2xl font-extrabold text-charcoal-900 mt-1"><?= $currency ?><?= number_format((float)$metrics['week_revenue'], 2) ?></h3>
            <p class="text-[11px] text-charcoal-500 font-medium mt-1">Rolling week revenue</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-brand-200 shadow-2xs">
            <p class="text-[11px] font-bold uppercase tracking-wider text-charcoal-400">This Month</p>
            <h3 class="text-2xl font-extrabold text-brand-700 mt-1"><?= $currency ?><?= number_format((float)$metrics['month_revenue'], 2) ?></h3>
            <p class="text-[11px] text-charcoal-500 font-medium mt-1">Month to date</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-brand-200 shadow-2xs">
            <p class="text-[11px] font-bold uppercase tracking-wider text-charcoal-400">Avg. Ticket Size</p>
            <h3 class="text-2xl font-extrabold text-charcoal-900 mt-1"><?= $currency ?><?= number_format((float)$metrics['avg_order_value'], 2) ?></h3>
            <p class="text-[11px] text-charcoal-500 font-medium mt-1">Per guest invoice</p>
        </div>
    </div>

    <!-- 7-Day Revenue Trend Visualizer -->
    <div class="bg-white rounded-3xl border border-brand-200 shadow-2xs p-6 space-y-4">
        <div class="flex items-center justify-between pb-2 border-b border-brand-100">
            <h3 class="text-sm font-bold text-charcoal-900 flex items-center font-cinzel">
                <i class="fa-solid fa-wave-square text-brand-600 mr-2"></i> Daily Sales Trend (Past 7 Days)
            </h3>
            <span class="text-xs font-semibold text-charcoal-400">Total: <?= $currency ?><?= number_format((float)$metrics['week_revenue'], 2) ?></span>
        </div>

        <div class="grid grid-cols-7 gap-2 pt-4 items-end h-48">
            <?php 
                $maxDayVal = max(array_values($metrics['daily_trend']));
                if ($maxDayVal <= 0) $maxDayVal = 1;
            ?>
            <?php foreach ($metrics['daily_trend'] as $day => $val): ?>
            <?php 
                $pct = min(100, max(8, round(($val / $maxDayVal) * 100)));
                $dayLabel = date('D (m/d)', strtotime($day));
            ?>
            <div class="flex flex-col items-center h-full justify-end group">
                <span class="text-[10px] font-bold text-brand-800 mb-1 opacity-90 group-hover:opacity-100 transition">
                    <?= $val > 0 ? ($currency . number_format($val, 0)) : '-' ?>
                </span>
                <div class="w-full bg-brand-50 rounded-t-xl overflow-hidden flex items-end justify-center p-1" style="height: 120px;">
                    <div class="w-full bg-gradient-to-t from-charcoal-900 to-brand-600 rounded-t-lg transition-all duration-300 group-hover:from-black group-hover:to-brand-500" style="height: <?= $pct ?>%;"></div>
                </div>
                <span class="text-[10px] text-charcoal-500 font-semibold mt-2 text-center whitespace-nowrap">
                    <?= $dayLabel ?>
                </span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Two Columns: Stylist Leaderboard & Payment Channels -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Stylist Performance & Commission Tracking (8 cols) -->
        <div class="lg:col-span-8 bg-white rounded-3xl border border-brand-200 shadow-2xs p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-brand-100">
                <div>
                    <h3 class="text-base font-extrabold text-charcoal-900 font-cinzel">Stylist Performance & Commissions</h3>
                    <p class="text-xs text-charcoal-400">Automated commission calculations based on client invoices</p>
                </div>
                <span class="text-xs font-bold text-brand-800 bg-brand-50 border border-brand-200 px-2.5 py-1 rounded-full">
                    Active Staff: <?= count($metrics['stylist_sales']) ?>
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-charcoal-600">
                    <thead class="bg-brand-50/70 uppercase text-[10px] text-charcoal-500 font-bold border-b border-brand-200">
                        <tr>
                            <th class="py-3 px-3">Artist / Stylist</th>
                            <th class="py-3 px-3">Specialty</th>
                            <th class="py-3 px-3 text-center">Invoices</th>
                            <th class="py-3 px-3 text-right">Gross Sales</th>
                            <th class="py-3 px-3 text-center">Comm. Rate</th>
                            <th class="py-3 px-3 text-right">Commission Earned</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brand-100">
                        <?php foreach ($metrics['stylist_sales'] as $st): ?>
                        <tr class="hover:bg-brand-50/40 transition">
                            <td class="py-3.5 px-3 font-bold text-charcoal-900 flex items-center space-x-2">
                                <span class="w-7 h-7 rounded-full bg-brand-100 text-brand-900 border border-brand-300 font-extrabold text-xs flex items-center justify-center">
                                    <?= substr($st['name'], 0, 1) ?>
                                </span>
                                <span><?= htmlspecialchars($st['name']) ?></span>
                            </td>
                            <td class="py-3.5 px-3 text-charcoal-500">
                                <?= htmlspecialchars($st['role']) ?>
                            </td>
                            <td class="py-3.5 px-3 text-center font-bold text-charcoal-800">
                                <?= (int)$st['order_count'] ?>
                            </td>
                            <td class="py-3.5 px-3 text-right font-black text-charcoal-900">
                                <?= $currency ?><?= number_format((float)$st['total_sales'], 2) ?>
                            </td>
                            <td class="py-3.5 px-3 text-center">
                                <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold bg-brand-50 text-brand-800 border border-brand-200">
                                    <?= (float)$st['rate'] ?>%
                                </span>
                            </td>
                            <td class="py-3.5 px-3 text-right font-extrabold text-emerald-700">
                                <?= $currency ?><?= number_format((float)$st['commission_earned'], 2) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payment Channels & Bestsellers (4 cols) -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Payment Method Card -->
            <div class="bg-white rounded-3xl border border-brand-200 shadow-2xs p-6 space-y-4">
                <h3 class="text-sm font-bold text-charcoal-900 flex items-center font-cinzel">
                    <i class="fa-solid fa-credit-card text-brand-600 mr-2"></i> Payment Channels
                </h3>

                <div class="space-y-3">
                    <?php 
                        $totalRev = $metrics['total_revenue'] > 0 ? $metrics['total_revenue'] : 1;
                        foreach ($metrics['payment_breakdown'] as $channel => $amt): 
                        $pct = round(($amt / $totalRev) * 100);
                    ?>
                    <div>
                        <div class="flex justify-between text-xs font-bold mb-1">
                            <span class="text-charcoal-700"><?= $channel ?></span>
                            <span class="text-charcoal-900"><?= $currency ?><?= number_format($amt, 2) ?> (<?= $pct ?>%)</span>
                        </div>
                        <div class="w-full bg-brand-50 rounded-full h-2 overflow-hidden border border-brand-100">
                            <div class="bg-charcoal-900 h-2 rounded-full" style="width: <?= $pct ?>%;"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Top Services Card -->
            <div class="bg-white rounded-3xl border border-brand-200 shadow-2xs p-6 space-y-4">
                <h3 class="text-sm font-bold text-charcoal-900 flex items-center font-cinzel">
                    <i class="fa-solid fa-trophy text-amber-600 mr-2"></i> Top Requested Items
                </h3>

                <div class="divide-y divide-brand-100 text-xs">
                    <?php foreach ($metrics['top_items'] as $item): ?>
                    <div class="py-2.5 flex items-center justify-between">
                        <div class="min-w-0 pr-2">
                            <div class="font-bold text-charcoal-900 truncate"><?= htmlspecialchars($item['name']) ?></div>
                            <span class="text-[10px] text-charcoal-400 capitalize"><?= $item['type'] ?></span>
                        </div>
                        <div class="text-right shrink-0">
                            <strong class="text-charcoal-800 font-extrabold"><?= (int)$item['qty'] ?> sold</strong>
                            <div class="text-[10px] text-brand-700 font-bold"><?= $currency ?><?= number_format($item['revenue'], 2) ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>

    </div>

</div>

<?php require __DIR__ . '/layouts/footer.php'; ?>
