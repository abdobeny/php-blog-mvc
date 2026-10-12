<?php
/**
 * Configuration template.
 * -----------------------------------------------------------------------------
 * Copy this file to `config.php` (which is git-ignored) and adjust the values:
 *
 *     cp config.sample.php config.php
 *
 * Every value can also be provided through an environment variable, which is
 * the recommended approach on a real host (nothing secret in the codebase).
 */

if (!function_exists('env_or')) {
    function env_or($key, $default) {
        $value = getenv($key);
        return ($value === false || $value === '') ? $default : $value;
    }
}

define('DB_HOST', env_or('DB_HOST', '127.0.0.1'));
define('DB_NAME', env_or('DB_NAME', 'blog'));
define('DB_USER', env_or('DB_USER', 'bloguser'));
define('DB_PASS', env_or('DB_PASS', 'blogpass'));

// Show PHP errors on screen? Keep false in production.
define('APP_DEBUG', filter_var(env_or('APP_DEBUG', 'false'), FILTER_VALIDATE_BOOLEAN));
