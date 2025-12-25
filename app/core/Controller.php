<?php

/**
 * Class Controller - Base controller cho tất cả controllers
 */
class Controller
{
    /**
     * Load model
     */
    protected function model($model)
    {
        require_once APP_PATH . '/models/' . $model . '.php';
        return new $model();
    }

    /**
     * Load view
     */
    protected function view($view, $data = [])
    {
        extract($data);
        require_once APP_PATH . '/views/' . $view . '.php';
    }

    /**
     * Redirect đến URL
     */
    protected function redirect($url)
    {
        header('Location: ' . BASE_URL . '/' . $url);
        exit;
    }

    /**
     * Kiểm tra user đã đăng nhập chưa
     */
    protected function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Kiểm tra user có phải admin không
     */
    protected function isAdmin()
    {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    /**
     * Require đăng nhập
     */
    protected function requireLogin()
    {
        if (!$this->isLoggedIn()) {
            $_SESSION['error'] = 'Bạn cần đăng nhập để truy cập trang này';
            $this->redirect('auth/login');
        }
    }

    /**
     * Require admin
     */
    protected function requireAdmin()
    {
        $this->requireLogin();
        if (!$this->isAdmin()) {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            $this->redirect('');
        }
    }

    /**
     * Set flash message
     */
    protected function setFlash($type, $message)
    {
        $_SESSION[$type] = $message;
    }

    /**
     * Get flash message và xóa
     */
    protected function getFlash($type)
    {
        if (isset($_SESSION[$type])) {
            $message = $_SESSION[$type];
            unset($_SESSION[$type]);
            return $message;
        }
        return null;
    }
}
