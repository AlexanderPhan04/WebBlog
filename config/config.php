<?php

/**
 * File cấu hình chính cho ứng dụng WebBlog
 * Định nghĩa các hằng số và cấu hình toàn cục
 */

// Đường dẫn
define('APP_PATH', dirname(__DIR__) . '/app');
define('CONFIG_PATH', __DIR__);
define('PUBLIC_PATH', dirname(__DIR__) . '/public');

// URL - Tự động detect domain
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$baseUrl = $protocol . '://' . $host;

// Xử lý path cho localhost và production
if (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false) {
    $baseUrl .= '/WebBlog/public';
}

define('BASE_URL', $baseUrl);

// Phân trang
define('POSTS_PER_PAGE', 10);
define('COMMENTS_PER_PAGE', 20);
define('USERS_PER_PAGE', 20);

// Bảo mật
define('PASSWORD_MIN_LENGTH', 6);
define('SESSION_TIMEOUT', 3600); // 1 giờ

// Content
define('ALLOWED_HTML_TAGS', '<p><br><strong><em><u><h1><h2><h3><h4><h5><h6><ul><ol><li><a><img><blockquote><code><pre>');
define('EXCERPT_LENGTH', 200);

// Upload
define('UPLOAD_PATH', PUBLIC_PATH . '/uploads');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);

// Múi giờ
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Hiển thị lỗi (tắt trong production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
