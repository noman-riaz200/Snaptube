<?php
/**
 * SnapTube APK Website - Dynamic Sitemap Generator
 *
 * Outputs a valid XML sitemap including static and dynamic URLs.
 * Connects securely via PDO (shared config.php).
 */

require_once __DIR__ . '/config.php';

// ------------------------------------------------------------------
// 1. Set XML header BEFORE any output
// ------------------------------------------------------------------
header('Content-Type: text/xml; charset=UTF-8');
header('X-Robots-Tag: noindex, follow');

// ------------------------------------------------------------------
// 2. Helpers
// ------------------------------------------------------------------

/**
 * Return an ISO 8601 / W3C datetime string for a given timestamp.
 *
 * @param int $timestamp Unix timestamp
 * @return string
 */
function sitemapDateTime(int $timestamp): string
{
    return gmdate('c', $timestamp);
}

/**
 * Build an absolute URL for a given path.
 *
 * @param string $path e.g. 'index.php' or 'page/my-slug'
 * @return string
 */
function sitemapUrl(string $path): string
{
    $base = rtrim(baseUrl(), '/');
    $path = ltrim($path, '/');
    return $base . '/' . $path;
}

// ------------------------------------------------------------------
// 3. Static page definitions
//    key => [ 'path', priority, 'file_for_mtime' ]
// ------------------------------------------------------------------
$staticPages = [
    ['index.php',      '1.0', __DIR__ . '/index.php'],
    ['download.php',   '0.9', __DIR__ . '/download.php'],
    ['about.php',      '0.8', __DIR__ . '/about.php'],
    ['contact.php',    '0.8', __DIR__ . '/contact.php'],
];

// ------------------------------------------------------------------
// 4. Fetch dynamic pages from the database
// ------------------------------------------------------------------
$dynamicPages = [];
try {
    $stmt = $pdo->query(
        "SELECT slug, priority, updated_at
         FROM pages
         WHERE is_active = 1
         ORDER BY id ASC"
    );
    $dynamicPages = $stmt->fetchAll();
} catch (PDOException $e) {
    // Graceful degradation: log error, continue with static pages only
    error_log('Sitemap DB error: ' . $e->getMessage());
}

// ------------------------------------------------------------------
// 5. Output XML
// ------------------------------------------------------------------
echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($staticPages as $page): ?>
  <url>
    <loc><?php echo htmlspecialchars(sitemapUrl($page[0]), ENT_XML1, 'UTF-8'); ?></loc>
    <lastmod><?php echo sitemapDateTime(filemtime($page[2])); ?></lastmod>
    <priority><?php echo $page[1]; ?></priority>
  </url>
<?php endforeach; ?>

<?php foreach ($dynamicPages as $row): ?>
  <url>
    <loc><?php echo htmlspecialchars(sitemapUrl('page/' . $row['slug']), ENT_XML1, 'UTF-8'); ?></loc>
    <lastmod><?php echo sitemapDateTime(strtotime($row['updated_at'])); ?></lastmod>
    <priority><?php echo number_format((float) $row['priority'], 1); ?></priority>
  </url>
<?php endforeach; ?>
</urlset>

