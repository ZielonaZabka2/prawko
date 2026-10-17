<?php
declare(strict_types=1);

/*
 * Panel administracyjny strony OSK.
 * Wszystkie zmiany zapisują się w /data/*.json – bez bazy danych.
 */

require dirname(__DIR__) . '/app/bootstrap.php';
require APP . '/admin/helpers.php';

header('X-Robots-Tag: noindex, nofollow');
header('Cache-Control: no-store');
start_session();

$page = (string) ($_GET['p'] ?? 'start');
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

/* ---------- logowanie ---------- */
if ($page === 'wyloguj') {
    $_SESSION = [];
    session_regenerate_id(true);
    redirect('?p=logowanie');
}

if (!is_admin()) {
    $error = '';
    if ($method === 'POST' && $page === 'logowanie') {
        if (!csrf_check()) {
            $error = 'Sesja wygasła. Spróbuj jeszcze raz.';
        } elseif (login_blocked()) {
            $error = 'Zbyt wiele nieudanych prób. Odczekaj 15 minut.';
        } elseif (password_verify((string) ($_POST['password'] ?? ''), (string) load_data('auth')['hash'])) {
            login_reset();
            session_regenerate_id(true);
            $_SESSION['admin'] = true;
            $_SESSION['admin_seen'] = time();
            redirect('?p=start');
        } else {
            login_fail();
            usleep(400000);
            $error = 'Nieprawidłowe hasło.';
        }
    }
    admin_render('login', ['error' => $error], false);
    exit;
}

/* ---------- akcje (POST) ---------- */
if ($method === 'POST') {
    if (!csrf_check()) {
        flash('Sesja wygasła – zmiany nie zostały zapisane. Spróbuj ponownie.', 'error');
        redirect('?p=' . urlencode($page));
    }
    require APP . '/admin/actions.php';
    exit;
}

/* ---------- widoki ---------- */
$views = [
    'start' => 'Pulpit',
    'kursy' => 'Kursy',
    'kurs' => 'Edycja kursu',
    'cennik' => 'Cennik',
    'promocja' => 'Promocja i popup',
    'blog' => 'Blog',
    'wpis' => 'Edycja wpisu',
    'wiadomosci' => 'Wiadomości',
    'firma' => 'Dane firmy',
    'haslo' => 'Hasło',
];
if (!isset($views[$page])) {
    $page = 'start';
}
admin_render($page, ['title' => $views[$page]]);
