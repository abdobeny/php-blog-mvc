<?php
/**
 * Configuration template.
 * -----------------------------------------------------------------------------
 * Copy this file to `config.php` (which is git-ignored):
 *
 *     cp config.sample.php config.php
 *
 * Values are read from environment variables when present; otherwise the
 * defaults below are used:
 *   - on localhost        -> local development database
 *   - on the live domain  -> production database
 *
 * Because config.php auto-detects the environment, the same file works both
 * locally and on the server — replace the production values below with the
 * ones from your host's control panel.
 */

if (!function_exists('env_or')) {
    function env_or($key, $default) {
        $value = getenv($key);
        return ($value === false || $value === '') ? $default : $value;
    }
}

$host    = strtolower(preg_replace('/:\d+$/', '', $_SERVER['HTTP_HOST'] ?? ''));
$isLocal = $host === '' || in_array($host, ['localhost', '127.0.0.1', '[::1]'], true);

if ($isLocal) {
    // ---------- Local development (php -S localhost:8000 router.php) ----------
    define('DB_HOST', env_or('DB_HOST', '127.0.0.1'));
    define('DB_NAME', env_or('DB_NAME', 'blog'));
    define('DB_USER', env_or('DB_USER', 'bloguser'));
    define('DB_PASS', env_or('DB_PASS', 'blogpass'));
} else {
    // ---------- Production (change these to match your host) ----------
    define('DB_HOST', env_or('DB_HOST', 'sqlXXX.infinityfree.com'));
    define('DB_NAME', env_or('DB_NAME', 'if0_XXXXXXXX_blog'));
    define('DB_USER', env_or('DB_USER', 'if0_XXXXXXXX'));
    define('DB_PASS', env_or('DB_PASS', 'your-database-password'));
}

// Show PHP errors on screen? Keep false in production.
define('APP_DEBUG', filter_var(env_or('APP_DEBUG', 'false'), FILTER_VALIDATE_BOOLEAN));
