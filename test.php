<?php
/**
 * ELARA Beauté Studio - Standalone cPanel Environment Diagnostic
 * Access this via: https://elara.datapointtechnology.com/test.php
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');

$phpVersion = PHP_VERSION;
$phpVersionId = PHP_VERSION_ID;
$phpSapi = PHP_SAPI;
$isPhp82OrHigher = $phpVersionId >= 80200;
$isPhp80OrHigher = $phpVersionId >= 80000;

$extensions = ['curl', 'mbstring', 'gd', 'fileinfo', 'openssl', 'dom', 'json'];
$extResults = [];
foreach ($extensions as $ext) {
    $extResults[$ext] = extension_loaded($ext);
}

$storageDir = __DIR__ . '/storage';
$storageWritable = is_dir($storageDir) && is_writable($storageDir);

$publicStorageDir = __DIR__ . '/public/storage';
$publicStorageWritable = is_dir($publicStorageDir) && is_writable($publicStorageDir);

$autoloadExists = file_exists(__DIR__ . '/vendor/autoload.php');
$envExists = file_exists(__DIR__ . '/.env');

$autoloadBoot = false;
$autoloadError = '';
if ($autoloadExists) {
    try {
        require_once __DIR__ . '/vendor/autoload.php';
        $autoloadBoot = true;
    } catch (\Throwable $e) {
        $autoloadError = $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
    }
}

$servicesBoot = false;
$servicesError = '';
if ($autoloadBoot) {
    try {
        if (file_exists(__DIR__ . '/.env')) {
            $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
            $dotenv->safeLoad();
        }
        $db = new \Elara\Services\FirestoreService();
        $pdf = new \Elara\Services\PdfService();
        $whatsapp = new \Elara\Services\WhatsAppService();
        $servicesBoot = true;
    } catch (\Throwable $e) {
        $servicesError = $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
    }
}

$allGood = $isPhp80OrHigher && !in_array(false, $extResults, true) && $autoloadBoot && $servicesBoot;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>cPanel Environment Health Check - ELARA Beauté Studio</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #faf7f2; color: #1c1917; padding: 30px; }
        .card { max-width: 650px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border: 2px solid #eadeca; }
        h1 { margin-top: 0; font-size: 22px; color: #0f172a; display: flex; align-items: center; gap: 10px; }
        .badge-pass { background: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 99px; font-weight: bold; font-size: 12px; }
        .badge-fail { background: #fee2e2; color: #991b1b; padding: 4px 10px; border-radius: 99px; font-weight: bold; font-size: 12px; }
        .row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
        .row:last-child { border-bottom: none; }
        .btn { display: inline-block; background: #0066ff; color: #fff; text-decoration: none; padding: 10px 20px; border-radius: 8px; font-weight: bold; margin-top: 20px; font-size: 13px; }
        .fix-box { background: #fefce8; border-left: 4px solid #eab308; padding: 12px; margin-top: 20px; font-size: 13px; color: #713f12; border-radius: 4px; }
    </style>
</head>
<body>
<div class="card">
    <h1>🩺 ELARA Studio cPanel Health Check</h1>
    <p style="font-size: 13px; color: #64748b;">This file tests your hosting environment directly without relying on any .htaccess rewrites.</p>
    
    <div class="row">
        <span><strong>PHP Version:</strong></span>
        <span>
            <?= htmlspecialchars($phpVersion) ?> (<?= htmlspecialchars($phpSapi) ?>)
            <?php if ($isPhp80OrHigher): ?>
                <span class="badge-pass">PASS (>= 8.0)</span>
            <?php else: ?>
                <span class="badge-fail">UPDATE REQUIRED (&lt; 8.0)</span>
            <?php endif; ?>
        </span>
    </div>

    <div class="row">
        <span><strong>Vendor Autoload:</strong></span>
        <span>
            <?php if ($autoloadBoot): ?>
                <span class="badge-pass">INITIALIZED OK</span>
            <?php elseif ($autoloadExists): ?>
                <span class="badge-fail">BOOT FAILED</span>
            <?php else: ?>
                <span class="badge-fail">MISSING</span>
            <?php endif; ?>
        </span>
    </div>

    <?php if ($autoloadError): ?>
    <div class="row" style="color: #dc2626; font-size: 12px; word-break: break-all;">
        <span><strong>Autoload Error:</strong></span>
        <span><?= htmlspecialchars($autoloadError) ?></span>
    </div>
    <?php endif; ?>

    <div class="row">
        <span><strong>Core Services (Firestore &amp; PDF):</strong></span>
        <span>
            <?php if ($servicesBoot): ?>
                <span class="badge-pass">READY</span>
            <?php else: ?>
                <span class="badge-fail">ERROR</span>
            <?php endif; ?>
        </span>
    </div>

    <?php if ($servicesError): ?>
    <div class="row" style="color: #dc2626; font-size: 12px; word-break: break-all;">
        <span><strong>Services Error:</strong></span>
        <span><?= htmlspecialchars($servicesError) ?></span>
    </div>
    <?php endif; ?>

    <div class="row">
        <span><strong>.env Configuration:</strong></span>
        <span><?= $envExists ? '<span class="badge-pass">FOUND</span>' : '<span class="badge-fail">MISSING</span>' ?></span>
    </div>

    <div class="row">
        <span><strong>storage/ Directory:</strong></span>
        <span><?= $storageWritable ? '<span class="badge-pass">WRITABLE</span>' : '<span class="badge-fail">NOT WRITABLE</span>' ?></span>
    </div>

    <div class="row">
        <span><strong>public/storage/ Directory:</strong></span>
        <span><?= $publicStorageWritable ? '<span class="badge-pass">WRITABLE</span>' : '<span class="badge-fail">NOT WRITABLE</span>' ?></span>
    </div>

    <h3 style="font-size: 14px; margin-top: 20px; color: #334155;">Required PHP Extensions:</h3>
    <?php foreach ($extResults as $ext => $loaded): ?>
        <div class="row" style="padding: 6px 0;">
            <span>Extension: <code><?= $ext ?></code></span>
            <span><?= $loaded ? '<span class="badge-pass">LOADED</span>' : '<span class="badge-fail">MISSING</span>' ?></span>
        </div>
    <?php endforeach; ?>

    <?php if ($allGood): ?>
        <div style="margin-top: 25px; padding: 15px; background: #ecfdf5; border: 1px solid #6ee7b7; border-radius: 8px; color: #065f46; font-size: 13px;">
            ✅ <strong>Server Environment 100% Ready!</strong>
        </div>
    <?php endif; ?>

    <div style="text-align: center;">
        <a href="/" class="btn">Return to Web Portal &rarr;</a>
    </div>
</div>
</body>
</html>
