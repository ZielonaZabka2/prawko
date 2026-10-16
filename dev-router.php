<?php
// Lokalny podgląd: php -S localhost:8000 -t public dev-router.php
// Udaje reguły z public/.htaccess (na serwerze nie jest potrzebny).
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (preg_match('~^/(app|data)(/|$)|/\.~', $path)) { http_response_code(403); exit('Forbidden'); }
$file = __DIR__ . '/public' . $path;
if ($path !== '/' && is_file($file)) { return false; }
if (is_dir($file) && is_file(rtrim($file, '/') . '/index.php')) {
    if (!str_ends_with($path, '/')) { header('Location: ' . $path . '/'); exit; }
    chdir(rtrim($file, '/'));
    require rtrim($file, '/') . '/index.php';
    return true;
}
require __DIR__ . '/public/index.php';
