<?php
/**
 * SnapTube APK Website - Dynamic Page Handler
 * Displays content from the `pages` table based on URL slug.
 */

require_once __DIR__ . '/config.php';

$slug = $_GET['slug'] ?? '';

$pageTitle = '';
$pageMeta  = '';
$pageContent = '';

if (empty($slug) || !preg_match('/^[a-z0-9-]+$/', $slug)) {
    http_response_code(404);
    $pageTitle = 'Page Not Found';
    $pageContent = '<p>Sorry, the page you are looking for does not exist.</p>';
} else {
    $stmt = $pdo->prepare("SELECT title, meta_description, content, updated_at FROM pages WHERE slug = :slug AND is_active = 1 LIMIT 1");
    $stmt->execute([':slug' => $slug]);
    $page = $stmt->fetch();

    if ($page) {
        $pageTitle = e($page['title']);
        $pageMeta  = e($page['meta_description']);
        $pageContent = '<p>' . nl2br(e($page['content'])) . '</p>';
    } else {
        http_response_code(404);
        $pageTitle = 'Page Not Found';
        $pageContent = '<p>Sorry, the page you are looking for does not exist.</p>';
    }
}

require_once __DIR__ . '/header.php';
?>

<main>
  <section class="hero" style="padding: 4rem 0 2rem;">
    <div class="container hero-inner">
      <div class="hero-content">
        <h1><?php echo $pageTitle; ?></h1>
        <?php if (!empty($pageMeta)): ?>
        <p class="hero-desc"><?php echo $pageMeta; ?></p>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <section class="features" style="padding-top: 2rem;">
    <div class="container">
      <article class="feature-card reveal" style="max-width: 800px; margin: 0 auto 2rem;">
        <?php echo $pageContent; ?>
      </article>
    </div>
  </section>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>

