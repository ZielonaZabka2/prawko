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

/* ---------- kopia zapasowa (ZIP z /data i /uploads) ---------- */
if ($page === 'kopia') {
    if (!class_exists('ZipArchive')) {
        flash('Serwer nie obsługuje plików ZIP – pobierz katalogi data/ i uploads/ przez FTP.', 'error');
        redirect('?p=start');
    }
    $tmp = tempnam(sys_get_temp_dir(), 'osk');
    $zip = new ZipArchive();
    $zip->open($tmp, ZipArchive::OVERWRITE);
    foreach (glob(DATA . '/*.json') ?: [] as $f) {
        if (basename($f) !== 'login_attempts.json') { $zip->addFile($f, 'data/' . basename($f)); }
    }
    foreach (glob(UPLOADS . '/*.webp') ?: [] as $f) {
        $zip->addFile($f, 'uploads/' . basename($f));
    }
    $zip->addFromString('JAK-PRZYWROCIC.txt', "Wgraj katalogi data/ i uploads/ przez FTP do katalogu strony, nadpisując istniejące pliki.\n");
    $zip->close();
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="kopia-strony-osk-' . date('Y-m-d') . '.zip"');
    header('Content-Length: ' . filesize($tmp));
    readfile($tmp);
    @unlink($tmp);
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
    'tresci' => 'Treści strony',
    'faq' => 'FAQ',
    'opinie' => 'Opinie',
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
