<?php

class BaseController {
    protected function view($view, $data = []) {
        $file = __DIR__ . '/../views/' . $view . '.php';
        if (file_exists($file)) {
            extract($data);
            ob_start();
            require_once $file;
            echo ob_get_clean();
        } else {
            throw new Exception("View file not found: " . $file);
        }
    }

    protected function model($model) {
        // The autoloader should handle loading the model class
        return new $model();
    }

    protected function redirect($url) {
        header('Location: ' . $url);
        exit();
    }

    protected function json($data, $statusCode = 200) {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit();
    }

    /** Is a user currently logged in? */
    protected function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    /** Is the current user an administrator? */
    protected function isAdmin() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    /** Generate (once per session) and return the CSRF token. */
    protected function csrfToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /** A ready-to-use hidden CSRF input for a <form>. */
    protected function csrfField() {
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($this->csrfToken()) . '">';
    }

    /** Abort with 403 unless the submitted CSRF token matches the session. */
    protected function verifyCsrf() {
        $token = $_POST['csrf_token'] ?? '';
        if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            http_response_code(403);
            echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>403</title></head><body>'
               . '<h1>403 Forbidden</h1><p>Invalid or missing security token. '
               . 'Please go back, reload the page and try again.</p></body></html>';
            exit();
        }
    }
}
