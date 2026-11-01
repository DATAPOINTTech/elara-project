<?php

declare(strict_types=1);

namespace Elara\Controllers;

use Elara\Services\FirestoreService;

class ReportController
{
    private FirestoreService $db;

    public function __construct(FirestoreService $db)
    {
        $this->db = $db;
    }

    public function index(): void
    {
        $invoices = $this->db->getCollection('invoices', '-created_at');
        $services = $this->db->getCollection('services');
        $inventory = $this->db->getCollection('inventory');
        $staff = $this->db->getCollection('staff');

        $metrics = $this->calculateMetrics($invoices, $inventory, $staff);
        $appConfig = require dirname(__DIR__, 2) . '/config/app.php';

        require dirname(__DIR__) . '/Views/reports.php';
    }

    public function apiSummary(): void
    {
        header('Content-Type: application/json');
        $invoices = $this->db->getCollection('invoices', '-created_at');
        $inventory = $this->db->getCollection('inventory');
        $staff = $this->db->getCollection('staff');

        $metrics = $this->calculateMetrics($invoices, $inventory, $staff);
        echo json_encode(['success' => true, 'data' => $metrics]);
        exit;
    }

    public function exportCsv(): void
    {
        $invoices = $this->db->getCollection('invoices', '-created_at');
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="elara_sales_report_' . date('Ymd_His') . '.csv"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Invoice Number', 'Date', 'Customer Name', 'Phone', 'Stylist', 'Payment Method', 'Subtotal', 'Discount', 'Tax', 'Total Amount', 'Status']);

        foreach ($invoices as $inv) {
            fputcsv($output, [
                $inv['invoice_number'] ?? '',
                $inv['created_at'] ?? '',
                $inv['customer_name'] ?? '',
                $inv['customer_phone'] ?? '',
                $inv['stylist_name'] ?? '',
                $inv['payment_method'] ?? '',
                $inv['subtotal'] ?? 0,
                $inv['discount_amount'] ?? 0,
                $inv['tax_amount'] ?? 0,
                $inv['total'] ?? 0,
                $inv['status'] ?? 'Paid',
            ]);
        }

        fclose($output);
        exit;
    }

    private function calculateMetrics(array $invoices, array $inventory, array $staff): array
    {
        $today = date('Y-m-d');
        $weekAgo = date('Y-m-d', strtotime('-7 days'));
        $monthStart = date('Y-m-01');

        $todayRevenue = 0.0;
        $todayOrders = 0;
        $weekRevenue = 0.0;
        $monthRevenue = 0.0;
        $totalRevenue = 0.0;
        $totalOrders = count($invoices);

        $paymentBreakdown = ['Cash' => 0.0, 'Card' => 0.0, 'Online' => 0.0];
        $stylistSales = [];
        $itemSalesCount = [];
        $dailyTrend = [];

        // Pre-fill last 7 days for trend
        for ($i = 6; $i >= 0; $i--) {
            $d = date('Y-m-d', strtotime("-{$i} days"));
            $dailyTrend[$d] = 0.0;
        }

        // Initialize stylist tracker
        foreach ($staff as $st) {
            $stylistSales[$st['name']] = [
                'name' => $st['name'],
                'role' => $st['role'] ?? 'Specialist',
                'rate' => (float)($st['commission_rate'] ?? 10),
                'total_sales' => 0.0,
                'order_count' => 0,
                'commission_earned' => 0.0,
            ];
        }

        foreach ($invoices as $inv) {
            $total = (float)($inv['total'] ?? 0);
            $totalRevenue += $total;
            $createdAt = $inv['created_at'] ?? '';
            $date = substr($createdAt, 0, 10);

            // Today
            if ($date === $today) {
                $todayRevenue += $total;
                $todayOrders++;
            }

            // Week
            if ($date >= $weekAgo) {
                $weekRevenue += $total;
            }

            // Month
            if ($date >= $monthStart) {
                $monthRevenue += $total;
            }

            // Daily trend
            if (isset($dailyTrend[$date])) {
                $dailyTrend[$date] += $total;
            }

            // Payment method
            $pm = $inv['payment_method'] ?? 'Cash';
            if (!isset($paymentBreakdown[$pm])) {
                $paymentBreakdown[$pm] = 0.0;
            }
            $paymentBreakdown[$pm] += $total;

            // Stylist sales & commissions
            $stName = $inv['stylist_name'] ?? 'Senior Stylist';
            if (!isset($stylistSales[$stName])) {
                $stylistSales[$stName] = [
                    'name' => $stName,
                    'role' => 'Stylist',
                    'rate' => 10,
                    'total_sales' => 0.0,
                    'order_count' => 0,
                    'commission_earned' => 0.0,
                ];
            }
            $stylistSales[$stName]['total_sales'] += $total;
            $stylistSales[$stName]['order_count'] += 1;
            $commRate = $stylistSales[$stName]['rate'] / 100;
            $stylistSales[$stName]['commission_earned'] += round($total * $commRate, 2);

            // Item counts
            foreach ($inv['items'] ?? [] as $item) {
                $itemName = $item['name'] ?? 'Item';
                $qty = (int)($item['quantity'] ?? 1);
                $type = $item['type'] ?? 'service';
                if (!isset($itemSalesCount[$itemName])) {
                    $itemSalesCount[$itemName] = ['name' => $itemName, 'qty' => 0, 'revenue' => 0.0, 'type' => $type];
                }
                $itemSalesCount[$itemName]['qty'] += $qty;
                $itemSalesCount[$itemName]['revenue'] += (float)($item['total'] ?? ($qty * $item['price']));
            }
        }

        // Sort top items
        usort($itemSalesCount, fn($a, $b) => $b['qty'] <=> $a['qty']);
        $topItems = array_slice($itemSalesCount, 0, 5);

        // Sort stylists by sales
        usort($stylistSales, fn($a, $b) => $b['total_sales'] <=> $a['total_sales']);

        // Inventory health
        $lowStockItems = [];
        $totalStockUnits = 0;
        foreach ($inventory as $item) {
            $stock = (int)($item['stock'] ?? 0);
            $min = (int)($item['min_stock'] ?? 5);
            $totalStockUnits += $stock;
            if ($stock <= $min) {
                $lowStockItems[] = $item;
            }
        }

        $avgOrderValue = $totalOrders > 0 ? round($totalRevenue / $totalOrders, 2) : 0.0;

        return [
            'today_revenue' => $todayRevenue,
            'today_orders' => $todayOrders,
            'week_revenue' => $weekRevenue,
            'month_revenue' => $monthRevenue,
            'total_revenue' => $totalRevenue,
            'total_orders' => $totalOrders,
            'avg_order_value' => $avgOrderValue,
            'payment_breakdown' => $paymentBreakdown,
            'stylist_sales' => array_values($stylistSales),
            'top_items' => $topItems,
            'daily_trend' => $dailyTrend,
            'low_stock_items' => $lowStockItems,
            'low_stock_count' => count($lowStockItems),
            'total_stock_units' => $totalStockUnits,
        ];
    }
}

