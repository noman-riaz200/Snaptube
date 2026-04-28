<?php
/**
 * SnapTube APK Website - Report a Bug Page
 * Functional bug report form with CSRF protection.
 */

require_once __DIR__ . '/config.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCsrf()) {
        $errors[] = 'Invalid request. Please try again.';
    } else {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $issueType = trim($_POST['issue_type'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if (strlen($name) < 2) {
            $errors[] = 'Please enter your name.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        }
        if (empty($issueType)) {
            $errors[] = 'Please select an issue type.';
        }
        if (strlen($description) < 10) {
            $errors[] = 'Please describe the issue in at least 10 characters.';
        }

        if (empty($errors)) {
            // In a production environment, you would save to database or send email here.
            // For now, we simulate a successful submission.
            $success = true;
        }
    }
}

require_once __DIR__ . '/header.php';
?>

<main>
  <!-- ========== HERO SECTION ========== -->
  <section class="hero" style="padding: 4rem 0 2rem;">
    <div class="container hero-inner">
      <div class="hero-content">
        <h1>Report a <span>Bug</span></h1>
        <p class="hero-desc">Found an issue with our website or the download process? Let us know and we will fix it as soon as possible.</p>
      </div>
    </div>
  </section>

  <!-- ========== FORM SECTION ========== -->
  <section class="features" style="padding-top: 2rem; padding-bottom: 4rem;">
    <div class="container">
      <div class="feature-card reveal" style="max-width: 600px; margin: 0 auto;">
        <h2 style="margin-bottom: 1.5rem;">Bug Report Form</h2>

        <?php if ($success): ?>
          <div class="alert alert-success" style="margin-bottom: 1.5rem;">
            Thank you! Your bug report has been received. We will investigate and get back to you if needed.
          </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
          <div class="alert alert-error" style="margin-bottom: 1.5rem;">
            <ul style="margin: 0; padding-left: 1.25rem;">
              <?php foreach ($errors as $error): ?>
                <li><?php echo e($error); ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <?php if (!$success): ?>
          <form method="POST" action="report-a-bug.php" novalidate>
            <input type="hidden" name="csrf_token" value="<?php echo csrfToken(); ?>">

            <div class="form-group">
              <label for="name">Your Name</label>
              <input type="text" id="name" name="name" value="<?php echo e($_POST['name'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
              <label for="email">Email Address</label>
              <input type="email" id="email" name="email" value="<?php echo e($_POST['email'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
              <label for="issue_type">Issue Type</label>
              <select id="issue_type" name="issue_type" required style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border); border-radius: var(--radius); font-size: 0.9375rem; font-family: inherit; background: var(--white);">
                <option value="">Select an issue type</option>
                <option value="download" <?php echo (isset($_POST['issue_type']) && $_POST['issue_type'] === 'download') ? 'selected' : ''; ?>>Download Problem</option>
                <option value="installation" <?php echo (isset($_POST['issue_type']) && $_POST['issue_type'] === 'installation') ? 'selected' : ''; ?>>Installation Issue</option>
                <option value="website" <?php echo (isset($_POST['issue_type']) && $_POST['issue_type'] === 'website') ? 'selected' : ''; ?>>Website Bug</option>
                <option value="other" <?php echo (isset($_POST['issue_type']) && $_POST['issue_type'] === 'other') ? 'selected' : ''; ?>>Other</option>
              </select>
            </div>

            <div class="form-group">
              <label for="description">Issue Description</label>
              <textarea id="description" name="description" rows="5" required placeholder="Please describe the bug in detail, including steps to reproduce."><?php echo e($_POST['description'] ?? ''); ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Submit Bug Report</button>
          </form>
        <?php else: ?>
          <a href="report-a-bug.php" class="btn btn-secondary" style="margin-top: 1rem;">Submit Another Report</a>
        <?php endif; ?>
      </div>
    </div>
  </section>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>

