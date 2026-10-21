<?php
// Lokalny podgląd: php -S localhost:8000 -t public dev-router.php
// Udaje reguły z public/.htaccess (na serwerze nie jest potrzebny), łącznie z kompresją gzip,
// żeby pomiary szybkości (Lighthouse) odpowiadały prawdziwemu hostingowi.
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (preg_match('~^/(app|data)(/|$)|/\.~', $path)) { http_response_code(403); exit('Forbidden'); }
$file = __DIR__ . '/public' . $path;
$gzipOk = str_contains($_SERVER['HTTP_ACCEPT_ENCODING'] ?? '', 'gzip');

if ($path !== '/' && is_file($file)) {
    $types = ['css' => 'text/css', 'js' => 'application/javascript', 'svg' => 'image/svg+xml', 'txt' => 'text/plain', 'json' => 'application/json'];
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    if (isset($types[$ext])) {
        header('Content-Type: ' . $types[$ext] . '; charset=UTF-8');
        header('Cache-Control: public, max-age=31536000, immutable');
        $body = (string) file_get_contents($file);
        if ($gzipOk) { header('Content-Encoding: gzip'); header('Vary: Accept-Encoding'); $body = gzencode($body, 6); }
        header('Content-Length: ' . strlen($body));
        echo $body;
        return true;
    }
    header('Cache-Control: public, max-age=31536000, immutable');
    return false;
}
if ($gzipOk) { ini_set('zlib.output_compression', '1'); }
if (is_dir($file) && is_file(rtrim($file, '/') . '/index.php')) {
    if (!str_ends_with($path, '/')) { header('Location: ' . $path . '/'); exit; }
    chdir(rtrim($file, '/'));
    require rtrim($file, '/') . '/index.php';
    return true;
}
require __DIR__ . '/public/index.php';
