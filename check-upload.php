<?php
/**
 * Upload & Download Diagnostic Script
 * Run this in your browser to verify file upload health.
 */

require_once __DIR__ . '/config.php';

header('Content-Type: text/plain; charset=utf-8');

echo "========================================\n";
echo "  SnapTube Upload Diagnostics\n";
echo "========================================\n\n";

// 1) Basic server info
echo "[1] Server Info\n";
echo "    PHP Version      : " . PHP_VERSION . "\n";
echo "    Server Software  : " . ($_SERVER['SERVER_SOFTWARE'] ?? 'unknown') . "\n";
echo "    Document Root    : " . ($_SERVER['DOCUMENT_ROOT'] ?? 'unknown') . "\n";
echo "    Script Name      : " . ($_SERVER['SCRIPT_NAME'] ?? 'unknown') . "\n";
echo "    Base URL         : " . baseUrl() . "\n\n";

// 2) Uploads directory checks
$uploadDir = __DIR__ . '/uploads';
echo "[2] Uploads Directory Check\n";
echo "    Path             : {$uploadDir}\n";
echo "    Exists           : " . (is_dir($uploadDir) ? 'YES' : 'NO') . "\n";
if (is_dir($uploadDir)) {
    echo "    Readable         : " . (is_readable($uploadDir) ? 'YES' : 'NO') . "\n";
    echo "    Writable         : " . (is_writable($uploadDir) ? 'YES' : 'NO') . "\n";
    echo "    Permissions      : " . substr(sprintf('%o', fileperms($uploadDir)), -4) . "\n";

    $files = array_diff(scandir($uploadDir), ['.', '..']);
    echo "    Files inside     : " . count($files) . "\n";
    foreach ($files as $f) {
        $full = $uploadDir . '/' . $f;
        echo "      - {$f} (" . (is_file($full) ? filesize($full) . ' bytes' : 'dir') . ")\n";
    }
} else {
    echo "    Attempting mkdir : ";
    if (@mkdir($uploadDir, 0755, true)) {
        echo "SUCCESS\n";
    } else {
        echo "FAILED (check parent directory permissions)\n";
    }
}
echo "\n";

// 3) Database settings
echo "[3] Database Settings (site_settings)\n";
$stmt = $pdo->query("SELECT * FROM site_settings WHERE id = 1 LIMIT 1");
$row  = $stmt->fetch();
if ($row) {
    echo "    app_version      : " . ($row['app_version'] ?? 'N/A') . "\n";
    echo "    download_url     : " . ($row['download_url'] ?? 'N/A') . "\n";
    echo "    apk_file_path    : " . ($row['apk_file_path'] ?? 'N/A') . "\n";
    echo "    updated_at       : " . ($row['updated_at'] ?? 'N/A') . "\n";
} else {
    echo "    ERROR: No settings row found!\n";
}
echo "\n";

// 4) File existence check
echo "[4] Local APK File Verification\n";
$apkPath = $row['apk_file_path'] ?? '';
if ($apkPath) {
    $fullPath = __DIR__ . '/' . ltrim($apkPath, '/\\');
    echo "    DB Path          : {$apkPath}\n";
    echo "    Full Path        : {$fullPath}\n";
    echo "    File Exists      : " . (file_exists($fullPath) ? 'YES' : 'NO') . "\n";
    if (file_exists($fullPath)) {
        echo "    File Readable    : " . (is_readable($fullPath) ? 'YES' : 'NO') . "\n";
        echo "    File Size        : " . filesize($fullPath) . " bytes\n";
        echo "    Expected URL     : " . baseUrl() . ltrim($apkPath, '/\\') . "\n";
    }
} else {
    echo "    apk_file_path is empty. Falling back to download_url.\n";
}
echo "\n";

// 5) Download URL that header.php would generate
echo "[5] Resolved Download URL (what users get)\n";
echo "    " . getAbsoluteDownloadUrl() . "\n\n";

// 6) PHP upload limits
echo "[6] PHP Upload Limits\n";
echo "    upload_max_filesize : " . ini_get('upload_max_filesize') . "\n";
echo "    post_max_size       : " . ini_get('post_max_size') . "\n";
echo "    max_file_uploads    : " . ini_get('max_file_uploads') . "\n";
echo "    upload_tmp_dir      : " . (ini_get('upload_tmp_dir') ?: sys_get_temp_dir()) . "\n\n";

// 7) Debug log tail
echo "[7] Debug Log Tail (if exists)\n";
$logFile = __DIR__ . '/debug.log';
if (file_exists($logFile)) {
    $lines = file($logFile);
    $tail  = array_slice($lines, -10);
    foreach ($tail as $l) {
        echo "    " . trim($l) . "\n";
    }
} else {
    echo "    No debug.log found yet.\n";
}

// 8) Download Link Test
echo "\n[8] Download Link Test\n";
$dlLink = getDownloadLink();
echo "    Generated Link   : " . $dlLink . "\n";
echo "    Link Type        : " . (strpos($dlLink, 'download.php') !== false ? 'PHP Handler (local APK)' : 'External URL') . "\n";
echo "    Is HTTPS         : " . (strpos($dlLink, 'https://') === 0 ? 'YES' : 'NO / HTTP') . "\n";

// Quick HEAD-like check for local handler
if (strpos($dlLink, 'download.php') !== false) {
    $handlerPath = __DIR__ . '/download.php';
    echo "    Handler Exists   : " . (file_exists($handlerPath) ? 'YES' : 'NO') . "\n";
    echo "    Handler Readable : " . (is_readable($handlerPath) ? 'YES' : 'NO') . "\n";
}
echo "\n    >>> Try this URL in your browser to verify:\n";
echo "    " . $dlLink . "\n";

echo "\n========================================\n";
echo "  End of diagnostics\n";
echo "========================================\n";

