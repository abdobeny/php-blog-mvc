<?php
// Front-controller router for PHP's built-in web server.
//
// Usage (from the project root):
//     php -S localhost:8000 router.php
//
// The built-in server ignores .htaccess, so this file reproduces the
// mod_rewrite rule: real files are served as-is, everything else is
// forwarded to index.php with the path placed in $_GET['url'].

// Make relative includes (config.php, app/...) work no matter where
// the server was started from.
chdir(__DIR__);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = urldecode($path);

// Block direct access to sensitive files/directories (the built-in server
// ignores .htaccess, so enforce it here).
if (in_array($path, ['/config.php', '/database.sql', '/router.php'], true)
    || strpos($path, '/_unused') === 0) {
    http_response_code(404);
    echo 'Not found';
    return true;
}

// Serve existing static files (css/js/images) directly.
$file = realpath(__DIR__ . $path);
if ($path !== '/' && $file !== false && is_file($file) && strpos($file, __DIR__) === 0) {
    return false;
}

// Route through the front controller. Other query params are preserved.
$_GET['url'] = ltrim($path, '/');

require __DIR__ . '/index.php';
