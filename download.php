<?php
/**
 * SnapTube APK Secure Download Handler
 * Forces proper headers for APK downloads and supports resume/streaming.
 */

require_once __DIR__ . '/config.php';

// Gather debug info before any output
$apkPath    = getSetting('apk_file_path');
$externalUrl = getSetting('download_url');
$debugInfo  = [];

// If no local APK but we have an external URL, redirect there
if (empty($apkPath) && !empty($externalUrl)) {
    header('Location: ' . $externalUrl, true, 302);
    exit;
}

if (empty($apkPath)) {
    http_response_code(404);
    debugLog('Download error: apk_file_path is empty and no external URL configured.');
    exit('Download not available.');
}

$fullPath = __DIR__ . '/' . ltrim($apkPath, '/\\');
$debugInfo['db_path']     = $apkPath;
$debugInfo['resolved_path'] = $fullPath;
$debugInfo['file_exists'] = file_exists($fullPath) ? 'YES' : 'NO';

if (!file_exists($fullPath) || !is_readable($fullPath)) {
    http_response_code(404);
    debugLog('Download error: APK file missing or unreadable. Path: ' . $fullPath);
    exit('File not found on server.');
}

$fileSize = filesize($fullPath);
$fileName = basename($fullPath);
$debugInfo['file_size'] = $fileSize;
$debugInfo['file_name'] = $fileName;

// Clear any previous output buffers
while (ob_get_level()) {
    ob_end_clean();
}

// Set headers for APK download
header('Content-Type: application/vnd.android.package-archive');
header('Content-Disposition: attachment; filename="' . $fileName . '"');
header('Content-Length: ' . $fileSize);
header('Cache-Control: no-cache, must-revalidate');
header('Expires: 0');
header('Accept-Ranges: bytes');

// Support range requests (resume support)
$start = 0;
$end   = $fileSize - 1;

if (isset($_SERVER['HTTP_RANGE'])) {
    $range = $_SERVER['HTTP_RANGE'];
    if (preg_match('/bytes=(\d+)-(\d*)/', $range, $matches)) {
        $start = intval($matches[1]);
        $end   = !empty($matches[2]) ? intval($matches[2]) : $end;
        header('HTTP/1.1 206 Partial Content');
        header('Content-Range: bytes ' . $start . '-' . $end . '/' . $fileSize);
        header('Content-Length: ' . ($end - $start + 1));
    }
}

// Stream the file
$handle = fopen($fullPath, 'rb');
if ($handle === false) {
    http_response_code(500);
    debugLog('Download error: fopen failed for ' . $fullPath);
    exit('Unable to read file.');
}

fseek($handle, $start);
$bufferSize = 8192;
$bytesSent  = 0;

while (!feof($handle) && $bytesSent < ($end - $start + 1)) {
    $chunkSize = min($bufferSize, ($end - $start + 1) - $bytesSent);
    echo fread($handle, $chunkSize);
    $bytesSent += $chunkSize;
    flush();
}

fclose($handle);

debugLog('Download served: ' . $fileName . ' (' . $fileSize . ' bytes) to ' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
exit;

