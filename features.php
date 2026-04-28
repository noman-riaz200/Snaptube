<?php
/**
 * SnapTube APK Website - Features Page
 * Dedicated page showcasing all SnapTube features.
 */

require_once __DIR__ . '/header.php';
?>

<main>
  <!-- ========== HERO SECTION ========== -->
  <section class="hero" style="padding: 4rem 0 2rem;">
    <div class="container hero-inner">
      <div class="hero-content">
        <h1>Snaptube <span>Features</span></h1>
        <p class="hero-desc">Discover why SnapTube is the best free video downloader for Android. Powerful features, zero cost, and complete safety.</p>
      </div>
    </div>
  </section>

  <!-- ========== FEATURES GRID ========== -->
  <section class="features" aria-label="SnapTube Features">
    <div class="container">
      <div class="features-grid">
        <article class="feature-card reveal">
          <div class="feature-icon" aria-hidden="true">📥</div>
          <h3>Multi-Site Support</h3>
          <p>Download videos and music from YouTube, Facebook, Instagram, TikTok, Twitter, Vimeo, Dailymotion, and over 50 popular platforms with a single tap. No need for multiple apps.</p>
        </article>
        <article class="feature-card reveal">
          <div class="feature-icon" aria-hidden="true">🎵</div>
          <h3>MP3 Converter</h3>
          <p>Extract high-quality audio from any video instantly. Save your favorite songs, podcasts, and soundtracks in MP3 format directly to your device.</p>
        </article>
        <article class="feature-card reveal">
          <div class="feature-icon" aria-hidden="true">🎬</div>
          <h3>HD Video Quality</h3>
          <p>Choose from multiple resolutions ranging from 144p to 4K. Download videos in the quality that fits your storage and viewing needs.</p>
        </article>
        <article class="feature-card reveal">
          <div class="feature-icon" aria-hidden="true">⚡</div>
          <h3>Lightning Fast</h3>
          <p>Advanced multi-threading technology ensures the fastest download speeds possible. Start downloading in seconds without long waits.</p>
        </article>
        <article class="feature-card reveal">
          <div class="feature-icon" aria-hidden="true">🔒</div>
          <h3>100% Secure</h3>
          <p>Verified by top antivirus engines. No malware, no spyware, and no hidden trackers. Your privacy and security are our top priorities.</p>
        </article>
        <article class="feature-card reveal">
          <div class="feature-icon" aria-hidden="true">📱</div>
          <h3>Easy to Use</h3>
          <p>Clean, intuitive interface designed for everyone. No technical knowledge required. Just search, tap, and download.</p>
        </article>
        <article class="feature-card reveal">
          <div class="feature-icon" aria-hidden="true">🌙</div>
          <h3>Dark Mode</h3>
          <p>Protect your eyes with a beautiful dark theme. Perfect for nighttime browsing and downloading without straining your vision.</p>
        </article>
        <article class="feature-card reveal">
          <div class="feature-icon" aria-hidden="true">📂</div>
          <h3>Smart Manager</h3>
          <p>Built-in download manager with pause, resume, and batch download capabilities. Organize your files effortlessly.</p>
        </article>
        <article class="feature-card reveal">
          <div class="feature-icon" aria-hidden="true">🆓</div>
          <h3>Completely Free</h3>
          <p>No subscription fees, no hidden costs, and no premium tiers. All features are available to every user at no charge, forever.</p>
        </article>
      </div>
    </div>
  </section>

  <!-- ========== CTA SECTION ========== -->
  <section class="hero" style="padding: 3rem 0;">
    <div class="container hero-inner">
      <div class="hero-content">
        <h2>Ready to Experience SnapTube?</h2>
        <p class="hero-desc">Join 900 million users who trust SnapTube for their video and music downloads.</p>
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

