<?php

/**
 * Class App - Core MVC Router
 * Xử lý routing và điều hướng request đến controller tương ứng
 */
class App
{
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();

        // Kiểm tra controller tồn tại
        if (isset($url[0])) {
            $controllerName = ucfirst($url[0]) . 'Controller';
            if (file_exists(APP_PATH . '/controllers/' . $controllerName . '.php')) {
                $this->controller = $controllerName;
                unset($url[0]);
            }
        }

        // Require controller
        require_once APP_PATH . '/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // Kiểm tra method tồn tại
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // Lấy params
        $this->params = $url ? array_values($url) : [];

        // Gọi controller->method với params
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    /**
     * Parse URL từ request
     */
    private function parseUrl()
    {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}
