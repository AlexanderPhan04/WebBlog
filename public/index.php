<?php

/**
 * File index.php - Entry point cho ứng dụng
 */

// Bắt đầu session
session_start();

// Load cấu hình
require_once '../config/config.php';
require_once '../config/Database.php';

// Load core classes
require_once APP_PATH . '/core/App.php';
require_once APP_PATH . '/core/Controller.php';
require_once APP_PATH . '/core/Model.php';

// Load models
require_once APP_PATH . '/models/User.php';
require_once APP_PATH . '/models/Post.php';
require_once APP_PATH . '/models/Comment.php';
require_once APP_PATH . '/models/Category.php';

// Khởi chạy ứng dụng
$app = new App();
