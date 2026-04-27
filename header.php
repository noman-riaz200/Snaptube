<?php
/**
 * SnapTube APK Website - SEO-Optimized Header
 * Includes dynamic meta, JSON-LD schemas, critical CSS inline, resource hints
 */

require_once __DIR__ . '/config.php';

// Fetch all settings once
$version     = e(getSetting('app_version'));
$downloadUrl = e(getDownloadLink());
$metaTitle   = e(getSetting('meta_title'));
$metaDesc    = e(getSetting('meta_description'));
$seoKeywords = e(getSetting('seo_keywords'));
$siteName    = e(getSetting('site_name'));
$updatedAt   = e(getSetting('updated_at'));

// Canonical URL: primary domain only, no query strings, no fragments
$canonicalUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$canonicalUrl = rtrim($canonicalUrl, '/') . '/';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Core SEO -->
  <title><?php echo $metaTitle; ?></title>
  <meta name="description" content="<?php echo $metaDesc; ?>">
  <meta name="keywords" content="<?php echo $seoKeywords; ?>">
  <meta name="author" content="<?php echo $siteName; ?>">
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="<?php echo $canonicalUrl; ?>">

  <!-- Open Graph -->
  <meta property="og:title" content="<?php echo $metaTitle; ?>">
  <meta property="og:description" content="<?php echo $metaDesc; ?>">
  <meta property="og:type" content="website">
  <meta property="og:url" content="<?php echo $canonicalUrl; ?>">
  <meta property="og:site_name" content="<?php echo $siteName; ?>">
  <meta property="og:image" content="<?php echo $canonicalUrl; ?>assets/images/snaptube-hero.webp">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?php echo $metaTitle; ?>">
  <meta name="twitter:description" content="<?php echo $metaDesc; ?>">
  <meta name="twitter:image" content="<?php echo $canonicalUrl; ?>assets/images/snaptube-hero.webp">

  <!-- Favicon -->
  <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>📱</text></svg>">

  <!-- Resource Hints (Self-hosted - no external CDNs) -->
  <!-- <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin> -->

  <!-- CRITICAL CSS INLINE (Hero + Header + Base Reset - ~4KB) -->
  <style>
    /* CSS Variables */
    :root {
      --primary: #FFCD22;
      --primary-dark: #e6b800;
      --secondary: #FF4210;
      --secondary-dark: #e63a00;
      --bg: #F9FAFB;
      --white: #ffffff;
      --text: #1f2937;
      --text-light: #6b7280;
      --border: #e5e7eb;
      --shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
      --shadow-lg: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
      --radius: 12px;
      --radius-lg: 20px;
      --max-width: 1200px;
    }

    /* Reset & Base */
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
    html { scroll-behavior: smooth; -webkit-text-size-adjust: 100%; }
    body {
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
      background: var(--bg); color: var(--text); line-height: 1.6;
      overflow-x: hidden; -webkit-font-smoothing: antialiased;
    }
    img { max-width: 100%; height: auto; display: block; }
    a { color: inherit; text-decoration: none; }
    button { font-family: inherit; cursor: pointer; border: none; background: none; }

    /* Utility */
    .container { width: 100%; max-width: var(--max-width); margin: 0 auto; padding: 0 1.25rem; }

    /* Header (Glassmorphism) */
    .header {
      position: sticky; top: 0; z-index: 1000;
      background: rgba(255,255,255,0.85);
      backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
      border-bottom: 1px solid rgba(229,231,235,0.5);
    }
    .header-inner { display: flex; align-items: center; justify-content: space-between; height: 64px; }
    .logo { display: flex; align-items: center; gap: 0.5rem; font-weight: 900; font-size: 1.5rem; color: var(--text); }
    .logo-icon {
      width: 36px; height: 36px;
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      border-radius: 10px; display: flex; align-items: center; justify-content: center;
      color: var(--white); font-size: 1.1rem;
    }
    .header-actions { display: flex; align-items: center; gap: 1rem; }
    .icon-btn {
      width: 40px; height: 40px; border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      background: var(--bg); color: var(--text); transition: all 0.2s ease;
    }
    .icon-btn:hover { background: var(--primary); color: var(--text); transform: translateY(-2px); }

    /* Hero Section */
    .hero {
      padding: 3rem 0 4rem;
      background: linear-gradient(180deg, var(--white) 0%, var(--bg) 100%);
      position: relative; overflow: hidden;
    }
    .hero::before {
      content: ''; position: absolute; top: -50%; right: -20%;
      width: 600px; height: 600px;
      background: radial-gradient(circle, rgba(255,205,34,0.15) 0%, transparent 70%);
      pointer-events: none;
    }
    .hero-inner {
      display: flex; flex-direction: column; align-items: center;
      text-align: center; position: relative; z-index: 1;
    }
    .version-badge {
      display: inline-flex; align-items: center; gap: 0.5rem;
      background: rgba(255,205,34,0.15); color: #b45309;
      padding: 0.4rem 1rem; border-radius: 9999px;
      font-size: 0.875rem; font-weight: 600; margin-bottom: 1.5rem;
      border: 1px solid rgba(255,205,34,0.3);
    }
    .hero h1 {
      font-size: clamp(2rem, 5vw, 3.5rem); font-weight: 900;
      line-height: 1.1; margin-bottom: 1rem; max-width: 800px;
    }
    .hero h1 span {
      background: linear-gradient(135deg, var(--secondary), #ff6b35);
      -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    }
    .hero-desc {
      font-size: 1.125rem; color: var(--text-light);
      max-width: 600px; margin-bottom: 2rem;
    }
    .download-btn {
      display: inline-flex; align-items: center; gap: 0.75rem;
      background: linear-gradient(135deg, var(--secondary), var(--secondary-dark));
      color: var(--white); font-size: 1.125rem; font-weight: 700;
      padding: 1rem 2.5rem; border-radius: var(--radius-lg);
      box-shadow: 0 10px 30px -5px rgba(255,66,16,0.4);
      transition: all 0.3s ease; position: relative; overflow: hidden;
    }
    .download-btn::before {
      content: ''; position: absolute; inset: 0;
      background: linear-gradient(135deg, rgba(255,255,255,0.2), transparent);
      opacity: 0; transition: opacity 0.3s ease;
    }
    .download-btn:hover::before { opacity: 1; }
    .download-btn:hover { transform: translateY(-3px); box-shadow: 0 20px 40px -5px rgba(255,66,16,0.5); }
    .download-btn svg { width: 24px; height: 24px; }

    @keyframes pulse-ring {
      0% { transform: scale(0.8); opacity: 0.8; }
      100% { transform: scale(1.3); opacity: 0; }
    }
    .download-btn-wrap { position: relative; margin-bottom: 2.5rem; }
    .download-btn-wrap::before {
      content: ''; position: absolute; inset: -8px;
      border-radius: calc(var(--radius-lg) + 8px);
      background: rgba(255,66,16,0.3);
      animation: pulse-ring 2s ease-out infinite; z-index: -1;
    }

    /* Mockup */
    .mockup { margin-top: 1rem; max-width: 320px; width: 100%; position: relative; }
    .mockup-phone {
      background: linear-gradient(145deg, #1f2937, #111827);
      border-radius: 36px; padding: 12px;
      box-shadow: var(--shadow-lg); border: 4px solid #374151;
    }
    .mockup-screen {
      background: linear-gradient(180deg, var(--primary) 0%, var(--secondary) 100%);
      border-radius: 28px; aspect-ratio: 9/16;
      display: flex; align-items: center; justify-content: center;
      color: var(--white); font-size: 1.5rem; font-weight: 800;
      position: relative; overflow: hidden;
    }
    .mockup-screen::after {
      content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 40%;
      background: linear-gradient(to top, rgba(0,0,0,0.3), transparent);
    }
    .mockup-notch {
      position: absolute; top: 12px; left: 50%; transform: translateX(-50%);
      width: 40%; height: 20px; background: #1f2937;
      border-radius: 0 0 12px 12px; z-index: 2;
    }

    /* Trust Bar (minimal critical styles) */
    .trust-bar {
      background: var(--white);
      border-top: 1px solid var(--border); border-bottom: 1px solid var(--border);
      padding: 2rem 0;
    }
    .trust-grid {
      display: grid; grid-template-columns: repeat(3, 1fr);
      gap: 1.5rem; text-align: center;
    }
    .stat-number {
      font-size: clamp(1.25rem, 3vw, 1.75rem);
      font-weight: 800; color: var(--secondary); margin-bottom: 0.25rem;
    }
    .trust-item p { font-size: 0.875rem; color: var(--text-light); font-weight: 500; }

    /* Responsive (critical breakpoint only) */
    @media (min-width: 768px) {
      .hero-inner { flex-direction: row; text-align: left; gap: 3rem; }
      .hero-content { flex: 1; }
      .hero h1, .hero-desc { max-width: none; }
      .mockup { max-width: 280px; flex-shrink: 0; }
    }
    @media (min-width: 1024px) {
      .mockup { max-width: 320px; }
    }
  </style>

  <!-- Non-critical CSS loaded asynchronously to prevent render-blocking -->
  <link rel="preload" href="assets/css/style.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="assets/css/style.css"></noscript>

  <!-- JSON-LD Schema: SoftwareApplication -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "SoftwareApplication",
    "name": "SnapTube",
    "applicationCategory": "Downloader",
    "operatingSystem": "Android",
    "softwareVersion": "<?php echo $version; ?>",
    "offers": {
      "@type": "Offer",
      "price": "0",
      "priceCurrency": "USD",
      "description": "Free"
    },
    "aggregateRating": {
      "@type": "AggregateRating",
      "ratingValue": "4.8",
      "ratingCount": "9000000",
      "bestRating": "5",
      "worstRating": "1"
    },
    "downloadUrl": "<?php echo $downloadUrl; ?>",
    "description": "<?php echo $metaDesc; ?>",
    "url": "<?php echo $canonicalUrl; ?>",
    "image": "<?php echo $canonicalUrl; ?>assets/images/snaptube-hero.webp"
  }
  </script>

  <!-- JSON-LD Schema: FAQPage -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
      {
        "@type": "Question",
        "name": "Is SnapTube APK safe to download?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Yes, SnapTube APK is 100% safe when downloaded from the official source. It is verified by multiple antivirus engines and trusted by over 900 million users worldwide."
        }
      },
      {
        "@type": "Question",
        "name": "How do I install SnapTube on Android?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Download the APK file, open it from your notifications or file manager, tap 'Install', and enable 'Unknown Sources' if prompted. The app will be ready in seconds."
        }
      },
      {
        "@type": "Question",
        "name": "Can I download YouTube videos with SnapTube?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "SnapTube supports downloading videos from YouTube and 50+ other platforms including Facebook, Instagram, TikTok, Twitter, and more in HD quality."
        }
      },
      {
        "@type": "Question",
        "name": "Is SnapTube free to use?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Absolutely! SnapTube is completely free to download and use. There are no subscription fees, no hidden costs, and no premium tiers. All features are available to every user at no charge."
        }
      },
      {
        "@type": "Question",
        "name": "What Android version do I need?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "SnapTube supports Android 4.1 and above. It works smoothly on all modern Android smartphones and tablets, including Samsung, Xiaomi, OPPO, Vivo, Realme, and other popular brands."
        }
      },
      {
        "@type": "Question",
        "name": "Can I convert videos to MP3?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Yes, SnapTube includes a built-in MP3 converter. Simply paste a video link, select the MP3 option, and download high-quality audio files directly to your device. Perfect for music lovers and podcast enthusiasts."
        }
      }
    ]
  }
  </script>
</head>
<body>

<!-- ========== HEADER ========== -->
<header class="header">
  <div class="container header-inner">
    <a href="./" class="logo" aria-label="<?php echo $siteName; ?> Home">
      <span class="logo-icon" aria-hidden="true">▶</span>
      <span><?php echo $siteName; ?></span>
    </a>
    <div class="header-actions">
      <button class="icon-btn" aria-label="Search" title="Search">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
      </button>
      <button class="icon-btn" aria-label="Language" title="Language">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
      </button>
    </div>
  </div>
</header>

