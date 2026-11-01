<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

set_exception_handler(function (\Throwable $e) {
    if (!headers_sent()) {
        http_response_code(500);
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Startup Notice - ELARA Beauté Studio</title>
        <style>
            body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #faf7f2; color: #1c1917; padding: 40px 20px; }
            .box { max-width: 700px; margin: 0 auto; background: #fff; padding: 32px; border-radius: 20px; border: 2px solid #fecaca; box-shadow: 0 10px 25px rgba(0,0,0,0.06); }
            h1 { color: #dc2626; font-size: 20px; margin-top: 0; }
            .err { background: #fef2f2; border: 1px solid #fee2e2; padding: 12px 16px; border-radius: 8px; font-family: monospace; font-size: 13px; color: #991b1b; margin: 15px 0; word-break: break-all; }
            pre { background: #f8fafc; padding: 12px; border-radius: 8px; font-size: 11px; overflow-x: auto; color: #475569; }
        </style>
    </head>
    <body>
        <div class="box">
            <h1>âš ï¸ ELARA Studio Startup Notice</h1>
            <div class="err">
                <strong>Exception:</strong> <?= htmlspecialchars($e->getMessage()) ?><br>
                <strong>Location:</strong> <?= htmlspecialchars($e->getFile()) ?>:<?= $e->getLine() ?>
            </div>
            <pre><?= htmlspecialchars($e->getTraceAsString()) ?></pre>
        </div>
    </body>
    </html>
    <?php
    exit;
});

// Early PHP Version Compatibility Check
if (PHP_VERSION_ID < 80000) {
    http_response_code(500);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>PHP Version Update Required - ELARA Beauté Studio</title>
        <style>
            body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #faf7f2; color: #1c1917; padding: 40px 20px; }
            .box { max-width: 600px; margin: 0 auto; background: #fff; padding: 32px; border-radius: 20px; border: 2px solid #e2e8f0; box-shadow: 0 10px 25px rgba(0,0,0,0.06); }
            h1 { color: #b45309; font-size: 20px; margin-top: 0; }
            p { font-size: 14px; line-height: 1.6; color: #475569; }
            .step { background: #f8fafc; border-left: 4px solid #0284c7; padding: 12px 16px; margin: 16px 0; border-radius: 4px; font-size: 13px; }
            code { background: #e2e8f0; padding: 2px 6px; border-radius: 4px; font-weight: bold; }
        </style>
    </head>
    <body>
        <div class="box">
            <h1>⚠️ PHP Version Update Required</h1>
            <p>Your cPanel account is currently running <strong>PHP <?= htmlspecialchars(PHP_VERSION) ?></strong>. ELARA Beauté Studio requires <strong>PHP 8.2 or 8.3</strong>.</p>
            <div class="step">
                <strong>How to fix in 15 seconds:</strong><br>
                1. Log in to your cPanel dashboard.<br>
                2. Open <strong>MultiPHP Manager</strong> (or <strong>Select PHP Version</strong>).<br>
                3. Select your domain and switch the PHP version to <code>ea-php82</code> or <code>ea-php83</code>.<br>
                4. Click <strong>Apply</strong> and refresh this page.
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Static file handling for PHP built-in web server
if (php_sapi_name() === 'cli-server') {
    $file = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (is_file($file)) {
        return false;
    }
}

// Auto-heal vendor file & directory permissions on Linux/cPanel if needed
$cssParserTest = dirname(__DIR__) . '/vendor/sabberworm/php-css-parser/src/Rule/Rule.php';
if (file_exists($cssParserTest) && !is_readable($cssParserTest)) {
    try {
        $vendorDir = dirname(__DIR__) . '/vendor';
        @chmod($vendorDir, 0755);
        $it = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($vendorDir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($it as $item) {
            if ($item->isDir()) {
                @chmod($item->getPathname(), 0755);
            } else {
                @chmod($item->getPathname(), 0644);
            }
        }
    } catch (\Throwable $ignored) {}
}

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Dotenv\Dotenv;
use Elara\Services\FirestoreService;
use Elara\Services\PdfService;
use Elara\Services\WhatsAppService;
use Elara\Controllers\ServiceController;
use Elara\Controllers\InventoryController;
use Elara\Controllers\BillingController;
use Elara\Controllers\ReportController;
use Elara\Controllers\ElaraAgentController;
use Elara\Controllers\AuthController;

// Load environment variables
if (file_exists(dirname(__DIR__) . '/.env')) {
    $dotenv = Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->safeLoad();
}

$appConfig = require dirname(__DIR__) . '/config/app.php';

// Instantiate Core Services & Controllers with Graceful Diagnostic Catch
try {
    $db = new FirestoreService();
    $pdf = new PdfService();
    $whatsapp = new WhatsAppService();

    $serviceCtrl = new ServiceController($db);
    $inventoryCtrl = new InventoryController($db);
    $billingCtrl = new BillingController($db, $pdf, $whatsapp);
    $reportCtrl = new ReportController($db);
    $elaraCtrl = new ElaraAgentController($db);
    $authCtrl = new AuthController($db);
} catch (\Throwable $e) {
    http_response_code(500);
    error_log("ELARA Studio Startup Notice: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Setup Assistant - ELARA Beauté Studio</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-[#faf7f2] min-h-screen flex items-center justify-center p-6 text-slate-800 font-sans">
        <div class="max-w-xl w-full bg-white p-8 rounded-3xl shadow-xl border-2 border-amber-300 space-y-4">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-800 flex items-center justify-center text-xl font-bold">⚠️</div>
                <div>
                    <h2 class="text-lg font-black text-slate-900">ELARA Studio Setup Assistant</h2>
                    <p class="text-xs text-slate-500">cPanel Diagnostic Notice</p>
                </div>
            </div>
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 text-xs font-mono text-red-600 break-words">
                <?= htmlspecialchars($e->getMessage()) ?>
            </div>
            <div class="text-xs text-slate-600 space-y-2">
                <p><strong>Quick Checklist on cPanel:</strong></p>
                <ul class="list-disc pl-5 space-y-1">
                    <li>Ensure the <code class="bg-slate-100 px-1 py-0.5 rounded">storage/</code> directory has write permissions (chmod <strong>775</strong> or <strong>755</strong>).</li>
                    <li>Ensure PHP version is set to <strong>PHP 8.2</strong> or <strong>PHP 8.3</strong> in cPanel <strong>MultiPHP Manager</strong>.</li>
                    <li>Ensure extensions <code class="bg-slate-100 px-1 py-0.5 rounded">curl</code>, <code class="bg-slate-100 px-1 py-0.5 rounded">mbstring</code>, and <code class="bg-slate-100 px-1 py-0.5 rounded">gd</code> are enabled.</li>
                </ul>
            </div>
            <div class="pt-2 text-center">
                <a href="/" class="inline-block px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition">Retry Loading Web Portal</a>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Parse & Normalize Request Path for cPanel & Subdirectories
$rawPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
// Strip /public prefix or /index.php wrapper transparently
$uri = preg_replace('#^/public(/index\.php)?#i', '', $rawPath);
$uri = preg_replace('#^/index\.php#i', '', $uri);
$uri = rtrim($uri, '/');
if ($uri === '') {
    $uri = '/';
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Router Dispatch
switch ($uri) {
    // 0. Staff Authentication Gateway
    case '/login':
        if ($method === 'POST') {
            $authCtrl->authenticate();
        } else {
            $authCtrl->showLogin();
        }
        break;

    case '/logout':
        $authCtrl->logout();
        break;

    // 1. Public Customer Luxury Web Portal
    case '/':
    case '/portal':
    case '/home':
        $services = $db->getCollection('services', 'price');
        require dirname(__DIR__) . '/src/Views/portal.php';
        break;

    // 2. Staff & Management Dashboard (Admin Only & Password Protected)
    case '/dashboard':
    case '/admin':
        AuthController::requireAdmin();

        $invoices = $db->getCollection('invoices', '-created_at');
        $inventory = $db->getCollection('inventory');
        $services = $db->getCollection('services');
        $staff = $db->getCollection('staff');

        $today = date('Y-m-d');
        $todayRevenue = 0.0;
        $todayOrders = 0;
        $totalRevenue = 0.0;

        foreach ($invoices as $inv) {
            $amt = (float)($inv['total'] ?? 0);
            $totalRevenue += $amt;
            if (substr($inv['created_at'] ?? '', 0, 10) === $today) {
                $todayRevenue += $amt;
                $todayOrders++;
            }
        }

        $lowStockItems = array_filter($inventory, fn($p) => (int)($p['stock'] ?? 0) <= (int)($p['min_stock'] ?? 5));
        $avgTicket = count($invoices) > 0 ? ($totalRevenue / count($invoices)) : 0;

        $stats = [
            'today_revenue' => $todayRevenue,
            'today_orders' => $todayOrders,
            'total_orders' => count($invoices),
            'avg_order_value' => $avgTicket,
            'low_stock_count' => count($lowStockItems),
            'low_stock_items' => array_values($lowStockItems),
        ];
        $recentInvoices = $invoices;

        require dirname(__DIR__) . '/src/Views/dashboard.php';
        break;

    // User-Based POS Terminal Access (Captures Cashier Identity)
    case '/pos':
        AuthController::checkAuth();
        $billingCtrl->pos();
        break;

    // Admin-Only Studio Services Management
    case '/services':
        AuthController::requireAdmin();
        $serviceCtrl->index();
        break;

    // Admin-Only Inventory Management
    case '/inventory':
        AuthController::requireAdmin();
        $inventoryCtrl->index();
        break;

    // Invoices & Billing History (Authenticated Staff / Admin)
    case '/invoices':
        AuthController::checkAuth();
        $billingCtrl->invoices();
        break;

    // Admin-Only Analytics & Reports
    case '/reports':
        AuthController::requireAdmin();
        $reportCtrl->index();
        break;

    case '/reports/export':
        AuthController::requireAdmin();
        $reportCtrl->exportCsv();
        break;

    case '/receipt':
        $billingCtrl->receipt();
        break;

    case '/invoice/download':
        $billingCtrl->downloadPdf();
        break;

    // API Routes
    case '/api/checkout':
        if ($method === 'POST') {
            $billingCtrl->checkout();
        }
        break;

    case '/api/whatsapp/share':
        $billingCtrl->whatsappShare();
        break;

    case '/api/services':
        $serviceCtrl->apiList();
        break;

    case '/api/services/store':
        if ($method === 'POST') {
            $serviceCtrl->store();
        }
        break;

    case '/api/services/delete':
        if ($method === 'POST') {
            $serviceCtrl->delete();
        }
        break;

    case '/api/inventory':
        $inventoryCtrl->apiList();
        break;

    case '/api/inventory/store':
        if ($method === 'POST') {
            $inventoryCtrl->store();
        }
        break;

    case '/api/inventory/adjust':
        if ($method === 'POST') {
            $inventoryCtrl->adjustStock();
        }
        break;

    case '/api/inventory/delete':
        if ($method === 'POST') {
            $inventoryCtrl->delete();
        }
        break;

    case '/api/elara/chat':
        if ($method === 'POST') {
            $elaraCtrl->chat();
        }
        break;

    case '/api/reports/summary':
        $reportCtrl->apiSummary();
        break;

    default:
        http_response_code(404);
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Page Not Found - <?= htmlspecialchars($appConfig['name']) ?></title>
            <script src="https://cdn.tailwindcss.com"></script>
        </head>
        <body class="bg-[#faf7f2] min-h-screen flex items-center justify-center p-4">
            <div class="max-w-md w-full bg-white p-8 rounded-3xl shadow-lg border border-brand-200 text-center space-y-4">
                <div class="w-16 h-16 rounded-full bg-brand-50 border border-brand-200 text-brand-700 flex items-center justify-center text-2xl mx-auto font-black">404</div>
                <h2 class="text-xl font-bold text-charcoal-900 font-cinzel">Page Not Found</h2>
                <p class="text-xs text-charcoal-500">The requested studio endpoint does not exist.</p>
                <div class="pt-2 flex justify-center space-x-3">
                    <a href="/" class="px-4 py-2 bg-charcoal-900 text-brand-300 rounded-xl text-xs font-bold hover:bg-black transition">Web Portal</a>
                    <a href="/dashboard" class="px-4 py-2 bg-brand-600 text-white rounded-xl text-xs font-bold hover:bg-brand-700 transition">Staff Dashboard</a>
                </div>
            </div>
        </body>
        </html>
        <?php
        break;
}
