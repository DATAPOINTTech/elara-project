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

$allGood = $isPhp80OrHigher && !in_array(false, $extResults, true) && $autoloadExists;
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
                <span class="badge-fail">FAIL (&lt; 8.0) &mdash; Switch to PHP 8.2 in cPanel!</span>
            <?php endif; ?>
        </span>
    </div>

    <div class="row">
        <span><strong>Vendor Autoload:</strong></span>
        <span><?= $autoloadExists ? '<span class="badge-pass">FOUND</span>' : '<span class="badge-fail">MISSING</span>' ?></span>
    </div>

    <div class="row">
        <span><strong>.env Configuration:</strong></span>
        <span><?= $envExists ? '<span class="badge-pass">FOUND</span>' : '<span class="badge-fail">MISSING</span>' ?></span>
    </div>

    <div class="row">
        <span><strong>storage/ Directory:</strong></span>
        <span><?= $storageWritable ? '<span class="badge-pass">WRITABLE</span>' : '<span class="badge-fail">NOT WRITABLE (chmod 775)</span>' ?></span>
    </div>

    <div class="row">
        <span><strong>public/storage/ Directory:</strong></span>
        <span><?= $publicStorageWritable ? '<span class="badge-pass">WRITABLE</span>' : '<span class="badge-fail">NOT WRITABLE (chmod 775)</span>' ?></span>
    </div>

    <h3 style="font-size: 14px; margin-top: 20px; color: #334155;">Required PHP Extensions:</h3>
    <?php foreach ($extResults as $ext => $loaded): ?>
        <div class="row" style="padding: 6px 0;">
            <span>Extension: <code><?= htmlspecialchars($ext) ?></code></span>
            <span><?= $loaded ? '<span class="badge-pass">LOADED</span>' : '<span class="badge-fail">MISSING</span>' ?></span>
        </div>
    <?php endforeach; ?>

    <?php if (!$isPhp80OrHigher): ?>
        <div class="fix-box">
            <strong>Action Required:</strong> Your server is running PHP <?= htmlspecialchars($phpVersion) ?>. In your cPanel dashboard, go to <strong>MultiPHP Manager</strong> or <strong>Select PHP Version</strong> and choose <strong>PHP 8.2</strong> or <strong>PHP 8.3</strong>.
        </div>
    <?php endif; ?>

    <div style="text-align: center;">
        <a href="/" class="btn">Return to Web Portal &rarr;</a>
    </div>
</div>
</body>
</html>

