-- Snaptube APK Website - Database Schema
-- Run this first to create the database and site_settings table

CREATE DATABASE IF NOT EXISTS snaptube_apk CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE snaptube_apk;

-- site_settings table: single-row configuration for the site
DROP TABLE IF EXISTS site_settings;
CREATE TABLE site_settings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    app_version VARCHAR(20) NOT NULL DEFAULT '7.29.0.729',
    download_url VARCHAR(255) NOT NULL DEFAULT 'https://www.snaptube.com/latest/snaptube-official.apk',
    apk_file_path VARCHAR(255) NOT NULL DEFAULT '',
    meta_title VARCHAR(150) NOT NULL DEFAULT 'SnapTube Download Official APK - Free Video Downloader for Android',
    meta_description VARCHAR(300) NOT NULL DEFAULT 'Download SnapTube APK for free. Fast, safe, and easy video & music downloader for Android. 900M+ users trust SnapTube. Get the latest official version now!',
    seo_keywords VARCHAR(500) NOT NULL DEFAULT 'SnapTube APK, video downloader, free video downloader, Android video downloader, SnapTube download, MP3 downloader, YouTube downloader, HD video downloader',
    site_name VARCHAR(50) NOT NULL DEFAULT 'SnapTube',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default row
INSERT INTO site_settings (id, app_version, download_url, apk_file_path, meta_title, meta_description, seo_keywords, site_name)
VALUES (1, '7.29.0.729', 'https://www.snaptube.com/latest/snaptube-official.apk', '', 'SnapTube Download Official APK - Free Video Downloader for Android', 'Download SnapTube APK for free. Fast, safe, and easy video & music downloader for Android. 900M+ users trust SnapTube. Get the latest official version now!', 'SnapTube APK, video downloader, free video downloader, Android video downloader, SnapTube download, MP3 downloader, YouTube downloader, HD video downloader', 'SnapTube');

-- ============================================================
-- Pages table for dynamic sitemap generation
-- ============================================================
DROP TABLE IF EXISTS pages;
CREATE TABLE pages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(100) NOT NULL UNIQUE,
    title VARCHAR(150) NOT NULL,
    meta_description VARCHAR(300) DEFAULT '',
    content TEXT,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    priority DECIMAL(2,1) NOT NULL DEFAULT 0.5,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample active pages (optional — remove if not needed)
INSERT INTO pages (slug, title, meta_description, content, is_active, priority)
VALUES
('privacy-policy', 'Privacy Policy', 'Read the privacy policy for SnapTube APK download.', 'This is the privacy policy page.', 1, 0.3),
('terms-of-service', 'Terms of Service', 'Read the terms of service for using SnapTube.', 'This is the terms of service page.', 1, 0.3);

-- Admin credentials table (lightweight, no full user system)
DROP TABLE IF EXISTS admin_users;
CREATE TABLE admin_users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default admin: username=admin, password=snaptube2024 (bcrypted for security)
-- Password hash generated for 'snaptube2024'
INSERT INTO admin_users (username, password_hash)
VALUES ('admin', '$2y$10$CSGMIaqNg6QkDrE18v893uH36JliOICR4teW0nPp5jhceaCZ7UUx2');

