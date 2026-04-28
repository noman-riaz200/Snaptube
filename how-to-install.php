<?php
/**
 * SnapTube APK Website - How to Install Page
 * Step-by-step installation guide.
 */

require_once __DIR__ . '/header.php';
?>

<main>
  <!-- ========== HERO SECTION ========== -->
  <section class="hero" style="padding: 4rem 0 2rem;">
    <div class="container hero-inner">
      <div class="hero-content">
        <h1>How to <span>Install</span> SnapTube</h1>
        <p class="hero-desc">Get started with your safe APK install in just a few simple steps. Works on all Android devices running Android 4.1 or higher.</p>
      </div>
    </div>
  </section>

  <!-- ========== INSTALLATION STEPS ========== -->
  <section class="install" aria-label="Installation Guide">
    <div class="container">
      <div class="steps">
        <div class="step reveal">
          <div class="step-num" aria-hidden="true">1</div>
          <div class="step-content">
            <h3>Download the APK</h3>
            <p>Click the download button on our site to get the latest SnapTube APK file directly to your Android device. The file size is only around 20MB, so it downloads quickly even on slower connections. Always download from the official SnapTube web source to ensure a safe APK install.</p>
          </div>
        </div>
        <div class="step reveal">
          <div class="step-num" aria-hidden="true">2</div>
          <div class="step-content">
            <h3>Allow Unknown Sources</h3>
            <p>Go to <strong>Settings > Security</strong> (or <strong>Privacy</strong> on newer Android versions) and enable <strong>Unknown Sources</strong> or <strong>Install unknown apps</strong>. This allows your device to install applications from outside the Google Play Store. This is a standard and safe step for any free video downloader for Android.</p>
          </div>
        </div>
        <div class="step reveal">
          <div class="step-num" aria-hidden="true">3</div>
          <div class="step-content">
            <h3>Install & Enjoy</h3>
            <p>Open the downloaded file from your notifications or file manager, tap <strong>Install</strong>, and wait a few seconds. Once installed, open SnapTube and start downloading your favorite videos and music immediately. Your safe APK install is now complete!</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ========== TROUBLESHOOTING ========== -->
  <section class="features" aria-label="Troubleshooting">
    <div class="container">
      <h2 class="section-title reveal">Troubleshooting Tips</h2>
      <p class="section-subtitle reveal">Common issues and how to fix them quickly</p>
      <div class="features-grid">
        <article class="feature-card reveal">
          <div class="feature-icon" aria-hidden="true">🛡️</div>
          <h3>"Install Blocked" Warning</h3>
          <p>If you see an "Install blocked" message, tap <strong>Settings</strong> in the popup and toggle the permission to allow installation from this source. This is a normal Android security feature.</p>
        </article>
        <article class="feature-card reveal">
          <div class="feature-icon" aria-hidden="true">🔄</div>
          <h3>Corrupted APK Error</h3>
          <p>If the APK appears corrupted, delete the file and re-download it. Ensure your internet connection is stable during the download to prevent incomplete files.</p>
        </article>
        <article class="feature-card reveal">
          <div class="feature-icon" aria-hidden="true">💾</div>
          <h3>Not Enough Storage</h3>
          <p>Free up at least 50MB of storage before installing. You can clear cache or uninstall unused apps to make room for SnapTube.</p>
        </article>
      </div>
    </div>
  </section>

  <!-- ========== CTA SECTION ========== -->
  <section class="hero" style="padding: 3rem 0;">
    <div class="container hero-inner">
      <div class="hero-content">
        <h2>Start Your Download Now</h2>
        <p class="hero-desc">Get the latest SnapTube APK and enjoy unlimited video and music downloads.</p>
        <div class="download-btn-wrap">
          <a href="<?php echo $downloadUrl; ?>" class="download-btn" rel="nofollow noopener" download="snaptube.apk">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" width="24" height="24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
            Download SnapTube
          </a>
        </div>
      </div>
    </div>
  </section>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>

