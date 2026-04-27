<?php
/**
 * SnapTube Admin - Dashboard to Manage Site Settings
 */

require_once __DIR__ . '/../config.php';

// Auth check
if (empty($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCsrf()) {
        $error = 'Invalid security token. Please refresh and try again.';
    } else {
        $downloadUrl = trim($_POST['download_url'] ?? '');
        $hasUrl      = $downloadUrl !== '';
        $hasFile     = isset($_FILES['apk_file']) && $_FILES['apk_file']['error'] === UPLOAD_ERR_OK;

        if ($hasUrl && $hasFile) {
            $error = 'Please provide either a Download URL or upload an APK file, not both.';
        } elseif (!$hasUrl && !$hasFile) {
            $error = 'Please provide either a Download URL or upload an APK file.';
        } else {
            $data = [
                'app_version'        => $_POST['app_version'] ?? '',
                'meta_title'         => $_POST['meta_title'] ?? '',
                'meta_description'   => $_POST['meta_description'] ?? '',
                'seo_keywords'       => $_POST['seo_keywords'] ?? '',
                'site_name'          => $_POST['site_name'] ?? '',
            ];

            if ($hasFile) {
                $file     = $_FILES['apk_file'];
                $fileName = basename($file['name']);
                $ext      = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                if ($ext !== 'apk') {
                    $error = 'Only APK files are allowed.';
                } else {
                    $uploadDir = __DIR__ . '/../uploads';

                    // 1) Ensure uploads/ directory exists and is writable
                    if (!is_dir($uploadDir)) {
                        if (!mkdir($uploadDir, 0755, true)) {
                            $error = 'Uploads directory does not exist and could not be created.';
                            debugLog('Upload error: mkdir failed for ' . $uploadDir);
                        }
                    } elseif (!is_writable($uploadDir)) {
                        $error = 'Uploads directory is not writable. Check folder permissions.';
                        debugLog('Upload error: directory not writable: ' . $uploadDir);
                    }

                    if (empty($error)) {
                        $safeName = 'snaptube_' . time() . '.apk';
                        $destPath = $uploadDir . '/' . $safeName;

                        // 2) Move the uploaded file
                        if (move_uploaded_file($file['tmp_name'], $destPath)) {
                            // 3) Verify the file actually exists and is readable
                            if (file_exists($destPath) && is_readable($destPath)) {
                                // 4) Delete previous APK to avoid clutter (optional but recommended)
                                $oldPath = getSetting('apk_file_path');
                                if ($oldPath) {
                                    $oldFull = __DIR__ . '/../' . ltrim($oldPath, '/\\');
                                    if (file_exists($oldFull) && $oldFull !== $destPath) {
                                        @unlink($oldFull);
                                    }
                                }

                                $data['download_url']  = '';
                                $data['apk_file_path'] = 'uploads/' . $safeName;
                                debugLog('Upload success: saved to ' . $destPath . ' (' . filesize($destPath) . ' bytes)');
                            } else {
                                $error = 'File was moved but is not readable on the server.';
                                debugLog('Upload error: file missing after move. Destination: ' . $destPath);
                            }
                        } else {
                            $error = 'Failed to move uploaded APK file. Check directory permissions.';
                            debugLog('Upload error: move_uploaded_file returned false. Tmp: ' . $file['tmp_name'] . ' Dest: ' . $destPath);
                        }
                    }
                }
            } else {
                $data['download_url']  = $downloadUrl;
                $data['apk_file_path'] = '';
            }

            if (empty($error)) {
                if (updateSettings($data)) {
                    $success = 'Settings updated successfully!';
                } else {
                    $error = 'Failed to update settings. Please try again.';
                }
            }
        }
    }
}

// Fetch current settings
$settings = $pdo->query("SELECT * FROM site_settings WHERE id = 1 LIMIT 1")->fetch();
$token = csrfToken();
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - SnapTube</title>
  <meta name="robots" content="noindex, nofollow">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="background:#f3f4f6;">

  <header class="admin-header">
    <div class="container admin-header-inner">
      <div class="logo">
        <span class="logo-icon">▶</span>
        <span>SnapTube Admin</span>
      </div>
      <nav class="admin-nav">
        <a href="../index.php" target="_blank" class="btn btn-secondary" style="width:auto;padding:0.5rem 1rem;font-size:0.875rem;">View Site</a>
        <a href="logout.php" class="btn btn-secondary" style="width:auto;padding:0.5rem 1rem;font-size:0.875rem;">Logout</a>
      </nav>
    </div>
  </header>

  <main class="admin-main">
    <div class="admin-form">
      <h2>Site Settings</h2>

      <?php if ($success): ?>
        <div class="alert alert-success"><?php echo e($success); ?></div>
      <?php endif; ?>
      <?php if ($error): ?>
        <div class="alert alert-error"><?php echo e($error); ?></div>
      <?php endif; ?>

      <form method="POST" action="" id="settingsForm" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo e($token); ?>">

        <!-- General Settings -->
        <div style="margin-bottom:2rem;">
          <h3 style="font-size:1.1rem;font-weight:700;margin-bottom:1rem;color:var(--text);display:flex;align-items:center;gap:0.5rem;">
            <span style="background:var(--primary);color:var(--text);width:28px;height:28px;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:0.875rem;">⚙</span>
            General Settings
          </h3>
          <div class="form-row">
            <div class="form-group">
              <label for="site_name">Site Name</label>
              <input type="text" id="site_name" name="site_name" value="<?php echo e($settings['site_name'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
              <label for="app_version">App Version</label>
              <input type="text" id="app_version" name="app_version" value="<?php echo e($settings['app_version'] ?? ''); ?>" required placeholder="e.g. 7.29.0.729">
            </div>
          </div>

          <div style="background:#f9fafb;border-radius:var(--radius);padding:1.25rem;border:1px solid var(--border);margin-top:1rem;">
            <p style="font-size:0.875rem;font-weight:600;color:var(--text);margin-bottom:1rem;">Download Source <span style="color:var(--secondary);">*</span></p>

            <div class="form-group">
              <label for="download_url">Option A: Download URL (APK Link)</label>
              <input type="url" id="download_url" name="download_url" value="<?php echo e($settings['download_url'] ?? ''); ?>" placeholder="https://example.com/snaptube.apk" oninput="if(this.value) document.getElementById('apk_file').value='';">
            </div>

            <div style="text-align:center;font-size:0.875rem;color:var(--text-light);margin:0.75rem 0;font-weight:600;">— OR —</div>

            <div class="form-group">
              <label for="apk_file">Option B: Upload APK File</label>
              <input type="file" id="apk_file" name="apk_file" accept=".apk" onchange="if(this.files.length) document.getElementById('download_url').value='';">
              <?php if (!empty($settings['apk_file_path'])): ?>
                <small style="color:var(--text-light);font-size:0.75rem;display:block;margin-top:0.5rem;">
                  Current file: <strong><?php echo e(basename($settings['apk_file_path'])); ?></strong>
                </small>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <!-- SEO Settings -->
        <div style="margin-bottom:2rem;background:#f9fafb;border-radius:var(--radius);padding:1.5rem;border:1px solid var(--border);">
          <h3 style="font-size:1.1rem;font-weight:700;margin-bottom:1rem;color:var(--text);display:flex;align-items:center;gap:0.5rem;">
            <span style="background:var(--secondary);color:var(--white);width:28px;height:28px;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:0.875rem;">🔍</span>
            SEO Settings
          </h3>

          <div class="form-group">
            <label for="meta_title">SEO Title (Meta Title)</label>
            <input type="text" id="meta_title" name="meta_title" value="<?php echo e($settings['meta_title'] ?? ''); ?>" required maxlength="150" oninput="updateSeoPreview()">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-top:0.25rem;">
              <small style="color:var(--text-light);font-size:0.75rem;">Recommended: 50-60 characters for Google</small>
              <small id="titleCount" style="font-size:0.75rem;font-weight:600;">0 / 60</small>
            </div>
          </div>

          <div class="form-group">
            <label for="meta_description">SEO Description (Meta Description)</label>
            <textarea id="meta_description" name="meta_description" required maxlength="300" oninput="updateSeoPreview()" style="min-height:80px;resize:vertical;"><?php echo e($settings['meta_description'] ?? ''); ?></textarea>
            <div style="display:flex;justify-content:space-between;align-items:center;margin-top:0.25rem;">
              <small style="color:var(--text-light);font-size:0.75rem;">Recommended: 150-160 characters for Google</small>
              <small id="descCount" style="font-size:0.75rem;font-weight:600;">0 / 160</small>
            </div>
          </div>

          <div class="form-group">
            <label for="seo_keywords_input">SEO Keywords</label>
            <div class="tags-input-container" id="tagsContainer">
              <input type="text" id="seo_keywords_input" class="tags-input" placeholder="Type keyword and press Enter or comma..." maxlength="500">
            </div>
            <input type="hidden" id="seo_keywords" name="seo_keywords" value="<?php echo e($settings['seo_keywords'] ?? ''); ?>">
            <small style="color:var(--text-light);font-size:0.75rem;">Press <strong>Enter</strong> or <strong>,</strong> to add a tag. Click <strong>×</strong> to remove. Max 500 characters.</small>
          </div>

          <!-- Google SERP Preview -->
          <div style="margin-top:1.5rem;background:var(--white);border-radius:var(--radius);padding:1rem;border:1px solid #dfe1e5;">
            <p style="font-size:0.75rem;font-weight:700;color:var(--text-light);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.75rem;">Google Search Preview</p>
            <div style="font-family:arial,sans-serif;max-width:600px;">
              <div id="previewUrl" style="color:#202124;font-size:14px;line-height:1.3;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                <?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']; ?>/Snaptube/
              </div>
              <div id="previewTitle" style="color:#1a0dab;font-size:20px;line-height:1.3;text-decoration:none;cursor:pointer;padding:4px 0;">
                <?php echo e($settings['meta_title'] ?? 'SnapTube Download Official APK - Free Video Downloader for Android'); ?>
              </div>
              <div id="previewDesc" style="color:#4d5156;font-size:14px;line-height:1.58;">
                <?php echo e($settings['meta_description'] ?? 'Download SnapTube APK for free. Fast, safe, and easy video & music downloader for Android. 900M+ users trust SnapTube. Get the latest official version now!'); ?>
              </div>
            </div>
          </div>
        </div>

        <div class="form-row" style="margin-top:1.5rem;">
          <button type="submit" class="btn btn-primary">Save Changes</button>
          <a href="../index.php" target="_blank" class="btn btn-secondary" style="text-align:center;">Preview Site</a>
        </div>
      </form>

      <script>
        function updateSeoPreview() {
          const title = document.getElementById('meta_title').value;
          const desc = document.getElementById('meta_description').value;
          const titleCount = document.getElementById('titleCount');
          const descCount = document.getElementById('descCount');
          const previewTitle = document.getElementById('previewTitle');
          const previewDesc = document.getElementById('previewDesc');

          // Update counts
          titleCount.textContent = title.length + ' / 60';
          descCount.textContent = desc.length + ' / 160';

          // Color coding
          titleCount.style.color = (title.length >= 50 && title.length <= 60) ? '#16a34a' : (title.length > 60 ? '#dc2626' : '#6b7280');
          descCount.style.color = (desc.length >= 150 && desc.length <= 160) ? '#16a34a' : (desc.length > 160 ? '#dc2626' : '#6b7280');

          // Update preview
          previewTitle.textContent = title || 'SnapTube Download Official APK - Free Video Downloader for Android';
          previewDesc.textContent = desc || 'Download SnapTube APK for free. Fast, safe, and easy video & music downloader for Android. 900M+ users trust SnapTube. Get the latest official version now!';
        }
        updateSeoPreview();
      </script>
    </div>

    <div style="margin-top:2rem;background:var(--white);border-radius:var(--radius);padding:1.5rem;box-shadow:var(--shadow);">
      <h3 style="font-size:1rem;font-weight:700;margin-bottom:1rem;">Quick Info</h3>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;font-size:0.875rem;color:var(--text-light);">
        <div>
          <strong style="color:var(--text);display:block;margin-bottom:0.25rem;">Last Updated</strong>
          <?php echo e($settings['updated_at'] ?? 'N/A'); ?>
        </div>
        <div>
          <strong style="color:var(--text);display:block;margin-bottom:0.25rem;">Default Login</strong>
          admin / snaptube2024
        </div>
        <div>
          <strong style="color:var(--text);display:block;margin-bottom:0.25rem;">Database</strong>
          snaptube_apk
        </div>
      </div>
    </div>
  </main>

  <script src="../assets/js/main.js"></script>
</body>
</html>

