<?php

declare(strict_types=1);

/**
 * ELARA Beauté Studio - Root cPanel Proxy & Platform Check
 */

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

require_once __DIR__ . '/public/index.php';
