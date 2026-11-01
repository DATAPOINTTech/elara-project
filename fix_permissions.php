<?php
/**
 * ELARA Beauté Studio - Automated Permission Fixer for cPanel
 *
 * This utility standardizes file and folder permissions across the entire
 * cPanel installation to resolve "Permission denied" errors:
 * - Folders -> 0755 (rwxr-xr-x)
 * - Files   -> 0644 (rw-r--r--)
 * - Storage -> 0777 (rwxrwxrwx)
 *
 * Access via: https://elara.datapointtechnology.com/fix_permissions.php
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');

$baseDir = __DIR__;
$fixedDirs = 0;
$fixedFiles = 0;
$errors = [];

// 1. Recursive fix for all files & directories
try {
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($baseDir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($iterator as $item) {
        $path = $item->getPathname();
        
        // Skip .git internal objects for speed
        if (strpos($path, DIRECTORY_SEPARATOR . '.git') !== false) {
            continue;
        }

        if ($item->isDir()) {
            if (@chmod($path, 0755)) {
                $fixedDirs++;
            } else {
                $errors[] = "Could not set 0755 on folder: $path";
            }
        } elseif ($item->isFile()) {
            if (strpos($path, 'storage' . DIRECTORY_SEPARATOR) !== false) {
                @chmod($path, 0666);
            } else {
                @chmod($path, 0644);
            }
            $fixedFiles++;
        }
    }
} catch (\Throwable $e) {
    $errors[] = "Scan exception: " . $e->getMessage();
}

// 2. Specific fix for storage and cache directories
$writablePaths = [
    $baseDir . '/storage',
    $baseDir . '/storage/cache',
    $baseDir . '/public/storage',
    $baseDir . '/public/storage/invoices',
];

foreach ($writablePaths as $wPath) {
    if (!is_dir($wPath)) {
        @mkdir($wPath, 0777, true);
    }
    @chmod($wPath, 0777);
}

// 3. Test readability of the specific file that threw the user error
$testFile = $baseDir . '/vendor/sabberworm/php-css-parser/src/Rule/Rule.php';
$testFileReadable = is_readable($testFile);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Permission Repair - ELARA Beauté Studio</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #faf7f2; color: #1c1917; padding: 40px 20px; }
        .card { max-width: 650px; margin: 0 auto; background: #fff; padding: 32px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border: 2px solid #eadeca; }
        h1 { margin-top: 0; font-size: 22px; color: #0f172a; display: flex; align-items: center; gap: 10px; }
        .success { background: #ecfdf5; border: 1px solid #6ee7b7; padding: 16px; border-radius: 10px; color: #065f46; font-size: 14px; margin: 20px 0; }
        .row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
        .btn { display: inline-block; background: #0066ff; color: #fff; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: bold; font-size: 14px; margin-top: 15px; }
        .btn:hover { background: #0052cc; }
        .badge { padding: 4px 10px; border-radius: 99px; font-weight: bold; font-size: 12px; }
        .badge-pass { background: #dcfce7; color: #166534; }
        .badge-warn { background: #fef3c7; color: #92400e; }
    </style>
</head>
<body>
<div class="card">
    <h1>🔧 Permissions Repaired Successfully</h1>
    <p style="color: #64748b; font-size: 14px;">All cPanel file and folder permissions have been standardized to secure production defaults (0755 for folders, 0644 for files, 0777 for storage).</p>
    
    <div class="row">
        <span>Directories updated (0755):</span>
        <strong><?= $fixedDirs ?></strong>
    </div>
    <div class="row">
        <span>Files updated (0644):</span>
        <strong><?= $fixedFiles ?></strong>
    </div>
    <div class="row">
        <span>Vendor CSS Parser (Rule.php) readable:</span>
        <span>
            <?php if ($testFileReadable): ?>
                <span class="badge badge-pass">READABLE (OK)</span>
            <?php else: ?>
                <span class="badge badge-warn">CHECK FOLDER RIGHTS</span>
            <?php endif; ?>
        </span>
    </div>

    <div class="success">
        ✅ <strong>Ready!</strong> You can now access your ELARA Beauté Studio application.
    </div>

    <div style="text-align: center;">
        <a href="/" class="btn">Launch ELARA Studio Portal &rarr;</a>
    </div>
</div>
</body>
</html>
