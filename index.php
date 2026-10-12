<?php

// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include configuration (also defines APP_DEBUG)
require_once __DIR__ . '/config.php';

// Error reporting: log everything, only display when debugging.
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('display_errors', (defined('APP_DEBUG') && APP_DEBUG) ? '1' : '0');
ini_set('display_startup_errors', (defined('APP_DEBUG') && APP_DEBUG) ? '1' : '0');

// Autoload classes
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/app/controllers/',
        __DIR__ . '/app/models/',
    ];

    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

class Router {
    public function __construct() {
        $url = $this->parseUrl();

        $controllerName = 'HomeController';
        $method = 'index';
        $notFound = false;

        // Resolve the controller from the first segment (default: HomeController).
        if (!empty($url[0]) && file_exists(__DIR__ . '/app/controllers/' . ucfirst($url[0]) . 'Controller.php')) {
            $controllerName = ucfirst($url[0]) . 'Controller';
            array_shift($url);
        } elseif (!empty($url[0])) {
            // Unknown controller -> 404
            $notFound = true;
        }

        if ($notFound) {
            $controller = new HomeController();
            $method = 'notFound';
        } else {
            $controller = new $controllerName();

            if (!empty($url[0])) {
                if (method_exists($controller, $url[0])) {
                    $method = $url[0];
                    array_shift($url);
                } else {
                    // Unknown action -> 404
                    $controller = new HomeController();
                    $method = 'notFound';
                    $url = [];
                }
            } elseif (!method_exists($controller, $method)) {
                // Controller exists but has no default index() action.
                $controller = new HomeController();
                $method = 'notFound';
            }
        }

        $params = array_values($url);
        call_user_func_array([$controller, $method], $params);
    }

    public function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}

$router = new Router();
