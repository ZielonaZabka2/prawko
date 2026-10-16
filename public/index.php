<?php
declare(strict_types=1);

require __DIR__ . '/app/bootstrap.php';
require APP . '/view.php';
require APP . '/schema.php';

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$path = rawurldecode($path);

// /adres/ → /adres (jeden kanoniczny adres)
if ($path !== '/' && str_ends_with($path, '/')) {
    header('Location: ' . rtrim($path, '/'), true, 301);
    exit;
}
$route = trim($path, '/');

$pages = [
    '' => 'home',
    'kurs-prawa-jazdy-kat-b' => 'kurs',
    'jazdy-doszkalajace' => 'doszkalajace',
    'noclegi-dla-kursantow' => 'noclegi',
    'cennik' => 'cennik',
    'kontakt' => 'kontakt',
    'blog' => 'blog',
    'regulamin' => 'regulamin',
    'polityka-prywatnosci' => 'polityka',
];

switch (true) {
    case $route === 'sitemap.xml':
        require APP . '/seo/sitemap.php';
        exit;
    case $route === 'robots.txt':
        require APP . '/seo/robots.php';
        exit;
    case $route === 'llms.txt':
        require APP . '/seo/llms.php';
        exit;
    case isset($pages[$route]):
        render($pages[$route]);
        exit;
    case preg_match('~^blog/([a-z0-9-]+)$~', $route, $m) === 1:
        $post = find_post($m[1], !is_admin()); // administrator widzi też szkice
        if ($post) {
            render('post', ['post' => $post]);
            exit;
        }
        // brak wpisu → 404
}

http_response_code(404);
render('404');
