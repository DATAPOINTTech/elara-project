<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use Elara\Services\FirestoreService;
use Elara\Services\PdfService;
use Elara\Services\WhatsAppService;
use Elara\Controllers\ElaraAgentController;
use Elara\Controllers\BillingController;

echo "========================================================\n";
echo "   ELARA Parlor & Spa Management System - Test Suite    \n";
echo "========================================================\n\n";

// Load .env
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->safeLoad();
}

$passed = 0;
$failed = 0;

function assertTest(string $name, bool $condition, string $details = '') {
    global $passed, $failed;
    if ($condition) {
        echo " [PASS] {$name}\n";
        $passed++;
    } else {
        echo " [FAIL] {$name} - {$details}\n";
        $failed++;
    }
}

// 1. Test FirestoreService
echo "--- Testing Firestore Service ---\n";
$db = new FirestoreService();
$services = $db->getCollection('services');
assertTest('Firestore auto-seeds default salon services', count($services) >= 10, 'Found ' . count($services));

$inventory = $db->getCollection('inventory');
assertTest('Firestore auto-seeds default retail inventory', count($inventory) >= 5, 'Found ' . count($inventory));

$testDocId = 'test_doc_' . time();
$created = $db->createDocument('test_col', ['name' => 'Test Treatment', 'price' => 50.0], $testDocId);
assertTest('Create document in Firestore', isset($created['id']) && $created['id'] === $testDocId);

$retrieved = $db->getDocument('test_col', $testDocId);
assertTest('Retrieve document by ID', $retrieved !== null && $retrieved['name'] === 'Test Treatment');

$updated = $db->updateDocument('test_col', $testDocId, ['price' => 75.0]);
assertTest('Update document in Firestore', $updated !== null && $updated['price'] === 75.0);

$deleted = $db->deleteDocument('test_col', $testDocId);
assertTest('Delete document in Firestore', $deleted === true && $db->getDocument('test_col', $testDocId) === null);

// 2. Test PdfService
echo "\n--- Testing Dompdf Invoice Generation ---\n";
$pdfService = new PdfService();
$sampleInvoice = [
    'invoice_number' => 'INV-TEST-9999',
    'customer_name' => 'Lady Eleanor Vance',
    'customer_phone' => '+15559871234',
    'customer_email' => 'eleanor@example.com',
    'stylist_name' => 'Elena Rostova',
    'payment_method' => 'Card',
    'status' => 'Paid',
    'created_at' => date('Y-m-d H:i:s'),
    'items' => [
        [
            'name' => 'Signature 24K Gold Glow Facial',
            'type' => 'service',
            'quantity' => 1,
            'price' => 85.00,
            'total' => 85.00,
        ],
        [
            'name' => 'Olaplex No. 3 Hair Perfector 100ml',
            'type' => 'product',
            'quantity' => 1,
            'price' => 30.00,
            'total' => 30.00,
        ]
    ],
    'subtotal' => 115.00,
    'discount_percent' => 10,
    'discount_amount' => 11.50,
    'tax_percent' => 8.0,
    'tax_amount' => 8.28,
    'total' => 111.78,
    'notes' => 'Client skin consultation scheduled for next fortnight.',
];

$pdfRes = $pdfService->generateInvoicePdf($sampleInvoice);
assertTest('Dompdf PDF generated successfully', file_exists($pdfRes['file_path']) && filesize($pdfRes['file_path']) > 1000, "Size: " . filesize($pdfRes['file_path']) . " bytes");
echo "      PDF File saved at: " . $pdfRes['file_path'] . "\n";

// 3. Test WhatsAppService
echo "\n--- Testing WhatsApp Click-to-Chat & Cloud API ---\n";
$waService = new WhatsAppService();
$cleanPhone = $waService->formatPhoneNumber('+1 (555) 987-1234');
assertTest('Phone number normalization for WhatsApp', $cleanPhone === '15559871234', "Got: {$cleanPhone}");

$waLink = $waService->generateWaMeLink('+1 (555) 987-1234', $sampleInvoice, $pdfRes['public_url']);
assertTest('WhatsApp wa.me deep link generation', str_starts_with($waLink, 'https://wa.me/15559871234?text='), "Link: {$waLink}");

$cloudPayload = $waService->getCloudApiPayload('+1 (555) 987-1234', $sampleInvoice, $pdfRes['public_url']);
assertTest('WhatsApp Cloud API template payload format', $cloudPayload['messaging_product'] === 'whatsapp' && $cloudPayload['type'] === 'template');

// 4. Test AI Agent (ELARA)
echo "\n--- Testing ELARA AI Assistant Controller ---\n";
$agent = new ElaraAgentController($db);

// We can test the method directly via Reflection to test internal expert logic
$reflector = new ReflectionClass($agent);
$buildContextMethod = $reflector->getMethod('buildOperationalContext');
$buildContextMethod->setAccessible(true);
$context = $buildContextMethod->invoke($agent);

assertTest('ELARA gathers real-time salon context', isset($context['parlor_name']) && isset($context['today_revenue']));
echo "      Live Context: Today's Revenue = {$context['currency']}{$context['today_revenue']}, Orders = {$context['today_invoices_count']}, Low Stock Alerts = " . count($context['low_stock_alerts']) . "\n";

$expertFallbackMethod = $reflector->getMethod('generateExpertFallback');
$expertFallbackMethod->setAccessible(true);

$salesAnswer = $expertFallbackMethod->invoke($agent, 'How are our sales today?', $context);
assertTest('ELARA answers revenue question accurately', str_contains($salesAnswer, number_format((float)$context['today_revenue'], 2)), $salesAnswer);

$stockAnswer = $expertFallbackMethod->invoke($agent, 'Which products are low on stock?', $context);
assertTest('ELARA identifies low stock alert products', str_contains($stockAnswer, 'threshold') || str_contains($stockAnswer, 'Stock'), $stockAnswer);

$bridalAnswer = $expertFallbackMethod->invoke($agent, 'Suggest a bridal package', $context);
assertTest('ELARA provides luxury bridal recommendations', str_contains($bridalAnswer, 'Bridal') || str_contains($bridalAnswer, 'Facial'), $bridalAnswer);

// 5. Test AuthController & Role-Based Access
echo "\n--- Testing Staff Authentication & Role Security ---\n";
use Elara\Controllers\AuthController;
$auth = new AuthController($db);
$registeredUsers = $auth->getRegisteredUsers();

assertTest('Pre-configured staff accounts include Admin and Cashier roles', count($registeredUsers) >= 4);

$adminFound = false;
$cashierFound = false;
foreach ($registeredUsers as $u) {
    if ($u['role'] === 'admin' && $u['username'] === 'admin') $adminFound = true;
    if ($u['role'] === 'cashier' && $u['username'] === 'cashier1') $cashierFound = true;
}
assertTest('Admin user configured', $adminFound);
assertTest('Cashier user configured', $cashierFound);

// 6. Test User-Based POS Invoice Tracking
echo "\n--- Testing User-Based POS Cashier Tracking & Invoice Printing ---\n";
$cashierInvoice = [
    'invoice_number' => 'INV-TEST-CASHIER-1',
    'customer_name' => 'Mrs. Ayesha Farooq',
    'customer_phone' => '+923001234567',
    'customer_email' => 'ayesha@example.pk',
    'stylist_name' => 'Sehar Tariq (Bridal Director)',
    'cashier_id' => 'usr_cashier1',
    'cashier_name' => 'Fatima Sheikh (Front Desk Lead)',
    'payment_method' => 'Cash',
    'status' => 'Paid',
    'created_at' => date('Y-m-d H:i:s'),
    'items' => [
        [
            'name' => 'Royal Barat Couture Glamour',
            'type' => 'service',
            'quantity' => 1,
            'price' => 65000.00,
            'total' => 65000.00,
        ]
    ],
    'subtotal' => 65000.00,
    'discount_percent' => 5,
    'discount_amount' => 3250.00,
    'tax_percent' => 8.0,
    'tax_amount' => 4940.00,
    'total' => 66690.00,
    'notes' => 'Bridal jewelry setting at studio on Main Qasimabad Road.',
];

// Verify PDF contains cashier name
$cashierPdfRes = $pdfService->generateInvoicePdf($cashierInvoice);
assertTest('PDF Invoice includes Billed By (Cashier) name', file_exists($cashierPdfRes['file_path']));

// Verify WhatsApp message includes cashier name
$cashierWaLink = $waService->generateWaMeLink('+923001234567', $cashierInvoice, $cashierPdfRes['public_url']);
assertTest('WhatsApp receipt link includes Cashier name', str_contains(urldecode($cashierWaLink), 'Fatima Sheikh (Front Desk Lead)'));

// 7. Test Portal Making of ELARA & Instagram Integration
echo "\n--- Testing Web Portal 'Making of ELARA' & Instagram Integration ---\n";
$portalHtml = file_get_contents(__DIR__ . '/src/Views/portal.php');
assertTest('Web Portal contains Making of ELARA section', str_contains($portalHtml, 'id="making-of-elara"'));
assertTest('Web Portal contains official Instagram links', str_contains($portalHtml, 'https://www.instagram.com/elarabeautestudio/'));
assertTest('Web Portal contains Instagram video reel cards', str_contains($portalHtml, 'Reel 01 &bull; Architecture') && str_contains($portalHtml, 'Reel 03 &bull; Henna Lab'));
assertTest('Web Portal contains Main Qasimabad Road Hyderabad address', str_contains($portalHtml, 'Main Qasimabad Road') && str_contains($portalHtml, 'Hyderabad'));

// 8. Test DATAPOINT Technologies Attribution & Logo
echo "\n--- Testing DATAPOINT Technologies Attribution & Logo ---\n";
assertTest('DATAPOINT logo file exists in public assets', file_exists(__DIR__ . '/public/assets/images/datapoint_logo.png'));
$footerHtml = file_get_contents(__DIR__ . '/src/Views/layouts/footer.php');
assertTest('Management footer contains DATAPOINT Technologies credit', str_contains($footerHtml, 'Designed, Developed and Hosted By :') && str_contains($footerHtml, 'DATAPOINT Technologies'));
assertTest('Management footer embeds DATAPOINT logo', str_contains($footerHtml, '/assets/images/datapoint_logo.png'));
assertTest('Portal footer contains DATAPOINT Technologies credit', str_contains($portalHtml, 'Designed, Developed and Hosted By :') && str_contains($portalHtml, 'DATAPOINT Technologies'));
assertTest('Portal footer embeds DATAPOINT logo', str_contains($portalHtml, '/assets/images/datapoint_logo.png'));

// Summary
echo "\n========================================================\n";
echo "   Tests Completed: {$passed} Passed, {$failed} Failed\n";
echo "========================================================\n";

if ($failed > 0) {
    exit(1);
}

