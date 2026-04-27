<?php
/**
 * SnapTube APK Website - Configuration & Database Connection
 * Lightweight, secure PDO setup for Core PHP.
 */

// Start session globally (needed for admin and CSRF)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Error display (disable in production)
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

// Database credentials — update these to match your environment
$dbHost = 'localhost';
$dbName = 'snaptube_apk';
$dbUser = 'root';
$dbPass = '';
$dbCharset = 'utf8mb4';

// PDO connection
$dsn = "mysql:host={$dbHost};dbname={$dbName};charset={$dbCharset}";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, $options);
} catch (PDOException $e) {
    error_log('Database connection failed: ' . $e->getMessage());
    http_response_code(500);
    exit('Service temporarily unavailable.');
}

/**
 * Fetch a single setting value by column name from site_settings.
 *
 * @param string $key Column name in site_settings table
 * @return string
 */
function getSetting(string $key): string
{
    global $pdo;
    static $cache = [];

    if (array_key_exists($key, $cache)) {
        return $cache[$key];
    }

    $allowed = ['app_version','download_url','apk_file_path','meta_title','meta_description','seo_keywords','site_name','updated_at'];
    if (!in_array($key, $allowed, true)) {
        return '';
    }

    $stmt = $pdo->prepare("SELECT {$key} FROM site_settings WHERE id = 1 LIMIT 1");
    $stmt->execute();
    $row = $stmt->fetch();

    $value = $row[$key] ?? '';
    $cache[$key] = $value;
    return $value;
}

/**
 * Update site_settings row (admin use).
 *
 * @param array $data Associative array of columns to update
 * @return bool
 */
function updateSettings(array $data): bool
{
    global $pdo;
    $allowed = ['app_version','download_url','apk_file_path','meta_title','meta_description','seo_keywords','site_name'];

    $fields = [];
    $params = [];
    foreach ($data as $k => $v) {
        if (!in_array($k, $allowed, true)) {
            continue;
        }
        $fields[] = "{$k} = :{$k}";
        $params[":{$k}"] = trim($v);
    }

    if (empty($fields)) {
        return false;
    }

    $sql = "UPDATE site_settings SET " . implode(', ', $fields) . " WHERE id = 1";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute($params);
}

/**
 * Generate/return a CSRF token.
 *
 * @return string
 */
function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validate a CSRF token.
 *
 * @return bool
 */
function validateCsrf(): bool
{
    $token = $_POST['csrf_token'] ?? '';
    return hash_equals($_SESSION['csrf_token'] ?? '', $token);
}

/**
 * HTML escape helper.
 *
 * @param string $text
 * @return string
 */
function e(string $text): string
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

/**
 * Get absolute base URL of the site.
 *
 * @return string
 */
function baseUrl(): string
{
    static $url = null;
    if ($url !== null) return $url;

    $scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
    $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
    // Detect subfolder (e.g. /Snaptube/)
    $script = $_SERVER['SCRIPT_NAME'] ?? '/';
    $dir    = dirname($script);
    $dir    = ($dir === '/' || $dir === '\\') ? '/' : rtrim($dir, '/\\') . '/';

    $url = $scheme . '://' . $host . $dir;
    return $url;
}

/**
 * Determine the final download URL.
 * Prefers a local APK if it exists, otherwise falls back to an external URL.
 *
 * @return string Absolute download URL
 */
function getAbsoluteDownloadUrl(): string
{
    $apkPath = getSetting('apk_file_path');
    if ($apkPath) {
        $fullPath = __DIR__ . '/' . ltrim($apkPath, '/\\');
        if (file_exists($fullPath) && is_readable($fullPath)) {
            return baseUrl() . ltrim($apkPath, '/\\');
        }
    }
    // Fall back to external URL (ensure it is escaped at output time via e())
    return getSetting('download_url');
}

/**
 * Get the recommended download link for the frontend.
 * Returns the PHP download handler URL when a local APK exists,
 * otherwise falls back to an external URL.
 *
 * @return string Absolute download URL
 */
function getDownloadLink(): string
{
    $apkPath = getSetting('apk_file_path');
    if ($apkPath) {
        $fullPath = __DIR__ . '/' . ltrim($apkPath, '/\\');
        if (file_exists($fullPath) && is_readable($fullPath)) {
            // Use PHP handler for reliable headers & forced download
            return baseUrl() . 'download.php';
        }
    }
    // Fall back to external URL
    return getSetting('download_url');
}

/**
 * Append a debug message to a log file inside the project root.
 *
 * @param string $message
 * @return void
 */
function debugLog(string $message): void
{
    $logFile = __DIR__ . '/debug.log';
    $line    = '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL;
    @file_put_contents($logFile, $line, FILE_APPEND | LOCK_EX);
}

