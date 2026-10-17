<?php
declare(strict_types=1);

/*
 * Rdzeń strony: ścieżki, dane (pliki JSON w /data), helpery widoków.
 * Strona nie używa bazy danych – wszystkie ustawienia z panelu lądują w /data/*.json.
 */

date_default_timezone_set('Europe/Warsaw');
mb_internal_encoding('UTF-8');

define('ROOT', dirname(__DIR__));
define('APP', ROOT . '/app');
define('DATA', ROOT . '/data');
define('UPLOADS', ROOT . '/uploads');

require APP . '/lib/markdown.php';

/* ---------- dane ---------- */

function data_defaults(string $name): array
{
    static $defaults = null;
    if ($defaults === null) {
        $defaults = require APP . '/defaults.php';
    }
    return $defaults[$name] ?? [];
}

function load_data(string $name): array
{
    static $cache = [];
    if (isset($cache[$name])) {
        return $cache[$name];
    }
    $file = DATA . "/$name.json";
    $data = [];
    if (is_file($file)) {
        $decoded = json_decode((string) file_get_contents($file), true);
        if (is_array($decoded)) {
            $data = $decoded;
        }
    }
    $defaults = data_defaults($name);
    // listy (np. wpisy, wiadomości) nie są scalane z domyślnymi
    if ($defaults && !array_is_list($defaults)) {
        $data = array_replace($defaults, $data);
    } elseif (!is_file($file)) {
        $data = $defaults;
    }
    return $cache[$name] = $data;
}

function save_data(string $name, array $data): bool
{
    if (!is_dir(DATA)) {
        mkdir(DATA, 0755, true);
    }
    $file = DATA . "/$name.json";
    $tmp = $file . '.' . bin2hex(random_bytes(4)) . '.tmp';
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($json === false || file_put_contents($tmp, $json, LOCK_EX) === false) {
        return false;
    }
    return rename($tmp, $file);
}

function settings(): array { return load_data('settings'); }
function prices(): array { return load_data('prices'); }
function promo(): array { return load_data('promo'); }

/* ---------- promocja ---------- */

function promo_active(?array $p = null): bool
{
    $p ??= promo();
    if (empty($p['enabled'])) {
        return false;
    }
    $today = date('Y-m-d');
    if (!empty($p['start']) && $today < $p['start']) {
        return false;
    }
    if (!empty($p['end']) && $today > $p['end']) {
        return false;
    }
    return true;
}

/* ---------- kursy (kategorie prawa jazdy) ---------- */

/** Lista kursów posortowana wg kolejności z panelu. */
function courses(bool $onlyActive = true): array
{
    $list = load_data('courses');
    if ($onlyActive) {
        $list = array_values(array_filter($list, fn($c) => !empty($c['active'])));
    }
    usort($list, fn($a, $b) => ((int) ($a['order'] ?? 99)) <=> ((int) ($b['order'] ?? 99)));
    return array_map('course_normalize', $list);
}

function course_normalize(array $c): array
{
    return $c + [
        'slug' => '', 'category' => '', 'name' => '', 'short' => '', 'lead' => '', 'price' => 0,
        'theory_hours' => 0, 'practice_hours' => 0, 'bonus_hours' => 0, 'vehicle' => '',
        'includes' => '', 'content' => '', 'meta_title' => '', 'meta_description' => '',
        'active' => true, 'featured' => false, 'order' => 99,
    ];
}

function find_course(string $slug, bool $onlyActive = true): ?array
{
    foreach (courses($onlyActive) as $c) {
        if ($c['slug'] === $slug) {
            return $c;
        }
    }
    return null;
}

/** Kurs „główny” – wyróżniony w panelu albo pierwszy aktywny. */
function main_course(): array
{
    $all = courses();
    foreach ($all as $c) {
        if (!empty($c['featured'])) {
            return $c;
        }
    }
    return $all[0] ?? course_normalize(['name' => 'Kurs prawa jazdy', 'slug' => 'kat-b', 'category' => 'B']);
}

function course_url(array $c): string
{
    return '/kurs-prawa-jazdy-' . $c['slug'];
}

/** Elementy „w cenie” (jeden na linię w panelu). */
function course_includes(array $c): array
{
    return array_values(array_filter(array_map('trim', explode("\n", (string) $c['includes']))));
}

function promo_course_slug(): string
{
    return (string) (promo()['course'] ?? '');
}

/** Cena, jaką klient faktycznie zapłaci dziś (z promocją, jeśli dotyczy tego kursu). */
function course_price(?array $c = null): int
{
    $c ??= main_course();
    $p = promo();
    if (promo_active($p) && (int) ($p['price'] ?? 0) > 0 && promo_course_slug() === $c['slug']) {
        return (int) $p['price'];
    }
    return (int) $c['price'];
}

function course_regular_price(?array $c = null): int
{
    $c ??= main_course();
    return (int) $c['price'];
}

function course_discounted(?array $c = null): bool
{
    $c ??= main_course();
    return course_price($c) < course_regular_price($c);
}

/** Podstawia {cena}, {teoria} itd. w opisie kursu z panelu. */
function course_tokens(string $text, array $c): string
{
    return strtr($text, [
        '{cena}' => zl(course_price($c)),
        '{cena_regularna}' => zl(course_regular_price($c)),
        '{teoria}' => (string) (int) $c['theory_hours'],
        '{praktyka}' => (string) (int) $c['practice_hours'],
        '{gratis}' => (string) (int) $c['bonus_hours'],
        '{auto}' => (string) $c['vehicle'],
        '{kategoria}' => (string) $c['category'],
    ]);
}

/* ---------- blog ---------- */

function posts(bool $onlyPublished = true): array
{
    $posts = load_data('posts');
    if ($onlyPublished) {
        $posts = array_values(array_filter($posts, fn($p) => !empty($p['published'])));
    }
    usort($posts, fn($a, $b) => strcmp($b['date'] ?? '', $a['date'] ?? ''));
    return $posts;
}

function find_post(string $slug, bool $onlyPublished = true): ?array
{
    foreach (posts($onlyPublished) as $p) {
        if (($p['slug'] ?? '') === $slug) {
            return $p;
        }
    }
    return null;
}

function slugify(string $text): string
{
    $map = ['ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z', 'ż' => 'z'];
    $text = mb_strtolower(trim($text));
    $text = strtr($text, $map);
    $text = preg_replace('~[^a-z0-9]+~', '-', $text) ?? '';
    return trim($text, '-') ?: 'wpis';
}

/* ---------- helpery widoków ---------- */

function e(?string $s): string
{
    return htmlspecialchars((string) $s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function zl(int|float $amount): string
{
    return number_format((float) $amount, 0, ',', "\u{00A0}") . "\u{00A0}zł";
}

function site_url(string $path = ''): string
{
    return rtrim((string) settings()['site_url'], '/') . '/' . ltrim($path, '/');
}

function phone_href(): string
{
    return 'tel:+48' . preg_replace('~\D~', '', (string) settings()['phone']);
}

function date_pl(string $ymd): string
{
    $months = ['stycznia', 'lutego', 'marca', 'kwietnia', 'maja', 'czerwca', 'lipca', 'sierpnia', 'września', 'października', 'listopada', 'grudnia'];
    $t = strtotime($ymd);
    if (!$t) {
        return '';
    }
    return (int) date('j', $t) . ' ' . $months[(int) date('n', $t) - 1] . ' ' . date('Y', $t);
}

function date_short(string $ymd): string
{
    $t = strtotime($ymd);
    return $t ? date('d.m.Y', $t) : '';
}

function full_address(): string
{
    $s = settings();
    return trim($s['street'] . ', ' . $s['postal_code'] . ' ' . $s['city']);
}

/**
 * Responsywny <img> ze zdjęć w wielu rozmiarach (nazwa-480.webp, nazwa-960.webp …).
 * $dir: 'assets/img' albo 'uploads'.
 */
function img(string $name, string $alt, string $sizes = '100vw', array $opt = []): string
{
    $dir = $opt['dir'] ?? 'assets/img';
    $variants = image_variants($name, $dir);
    if (!$variants) {
        return '';
    }
    $largest = end($variants);
    $default = $variants[0];
    foreach ($variants as $v) {
        if ($v['w'] >= ($opt['default_w'] ?? 960)) { $default = $v; break; }
        $default = $v;
    }
    $srcset = implode(', ', array_map(fn($v) => "/$dir/{$v['file']} {$v['w']}w", $variants));
    $attrs = [
        'src' => "/$dir/{$default['file']}",
        'srcset' => $srcset,
        'sizes' => $sizes,
        'width' => (string) $largest['w'],
        'height' => (string) $largest['h'],
        'alt' => $alt,
        'decoding' => 'async',
    ];
    if (!empty($opt['eager'])) {
        $attrs['fetchpriority'] = 'high';
    } else {
        $attrs['loading'] = 'lazy';
    }
    if (!empty($opt['class'])) {
        $attrs['class'] = $opt['class'];
    }
    $html = '<img';
    foreach ($attrs as $k => $v) {
        $html .= ' ' . $k . '="' . e($v) . '"';
    }
    return $html . '>';
}

function image_variants(string $name, string $dir = 'assets/img'): array
{
    static $cache = [];
    $key = "$dir/$name";
    if (isset($cache[$key])) {
        return $cache[$key];
    }
    $out = [];
    foreach (glob(ROOT . "/$dir/" . $name . '-*.webp') ?: [] as $file) {
        if (!preg_match('~^' . preg_quote($name, '~') . '-(\d+)\.webp$~', basename($file), $m)) {
            continue;
        }
        $size = @getimagesize($file);
        if ($size) {
            $out[] = ['file' => basename($file), 'w' => $size[0], 'h' => $size[1]];
        }
    }
    usort($out, fn($a, $b) => $a['w'] <=> $b['w']);
    return $cache[$key] = $out;
}

function image_url(string $name, string $dir = 'assets/img', int $minWidth = 1200): string
{
    $variants = image_variants($name, $dir);
    if (!$variants) {
        return site_url('assets/img/og-image.jpg');
    }
    $pick = end($variants);
    foreach ($variants as $v) {
        if ($v['w'] >= $minWidth) { $pick = $v; break; }
    }
    return site_url("$dir/{$pick['file']}");
}

function asset(string $path): string
{
    $file = ROOT . '/' . ltrim($path, '/');
    $v = is_file($file) ? substr((string) filemtime($file), -6) : '1';
    return '/' . ltrim($path, '/') . '?v=' . $v;
}

/** Ikony SVG (liniowe, 24×24, stroke = currentColor). */
function icon(string $name, string $class = 'icon'): string
{
    $paths = [
        'phone' => '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2"/>',
        'pin' => '<path d="M12 21s-7-6.1-7-11a7 7 0 0 1 14 0c0 4.9-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/>',
        'mail' => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/>',
        'clock' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'check' => '<path d="m5 12.5 4.5 4.5L19 7.5"/>',
        'arrow' => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'close' => '<path d="M6 6l12 12M18 6 6 18"/>',
        'menu' => '<path d="M4 7h16M4 12h16M4 17h16"/>',
        'book' => '<path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v15H6.5A2.5 2.5 0 0 0 4 20.5z"/><path d="M4 20.5A2.5 2.5 0 0 1 6.5 18H20v3H6.5"/>',
        'wheel' => '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="2"/><path d="M12 14v7M10.2 11 3.5 9M13.8 11l6.7-2"/>',
        'calendar' => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/>',
        'wallet' => '<path d="M3 7a2 2 0 0 1 2-2h12v4"/><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M16 13.5h2"/>',
        'home' => '<path d="m3 11 9-7 9 7"/><path d="M5 9.5V20h14V9.5"/><path d="M10 20v-6h4v6"/>',
        'user' => '<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>',
        'car' => '<path d="M5 16V11l2-5h10l2 5v5"/><path d="M3 16h18v3h-3M6 19H3"/><circle cx="7.5" cy="16" r="1.5"/><circle cx="16.5" cy="16" r="1.5"/><path d="M5 11h14"/>',
        'shield' => '<path d="M12 3 4 6v6c0 4.5 3.4 8.2 8 9 4.6-.8 8-4.5 8-9V6z"/><path d="m9 12 2 2 4-4"/>',
        'route' => '<circle cx="6" cy="18" r="2"/><circle cx="18" cy="6" r="2"/><path d="M8 18h7a3 3 0 0 0 0-6H9a3 3 0 0 1 0-6h7"/>',
        'facebook' => '<path d="M14 8h3V4h-3a4 4 0 0 0-4 4v3H7v4h3v6h4v-6h3l1-4h-4V8.5a.5.5 0 0 1 .5-.5z"/>',
        'zoom' => '<circle cx="11" cy="11" r="7"/><path d="m20 20-4-4M11 8v6M8 11h6"/>',
        'chevron' => '<path d="m9 6 6 6-6 6"/>',
        'chevron-left' => '<path d="m15 6-6 6 6 6"/>',
        'bed' => '<path d="M3 18V7M3 14h18v4M21 14v-2a3 3 0 0 0-3-3h-7v5"/><circle cx="7" cy="11" r="2"/>',
    ];
    $p = $paths[$name] ?? '';
    return '<svg class="' . e($class) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $p . '</svg>';
}

/* ---------- sesja, CSRF ---------- */

function start_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    session_name('osk_sid');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

function csrf_token(): string
{
    start_session();
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function csrf_check(): bool
{
    start_session();
    $sent = (string) ($_POST['csrf'] ?? '');
    return !empty($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $sent);
}

function client_ip(): string
{
    return (string) ($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
}

/* ---------- formularz kontaktowy ---------- */

/**
 * Zapisuje zgłoszenie i wysyła maila. Zwraca [ok, błędy, stare_wartości].
 */
function handle_contact_form(): array
{
    $v = [
        'name' => trim((string) ($_POST['name'] ?? '')),
        'phone' => trim((string) ($_POST['phone'] ?? '')),
        'email' => trim((string) ($_POST['email'] ?? '')),
        'topic' => trim((string) ($_POST['topic'] ?? '')),
        'message' => trim((string) ($_POST['message'] ?? '')),
        'consent' => !empty($_POST['consent']),
    ];
    $errors = [];

    // pułapki na boty: ukryte pole i zbyt szybkie wysłanie
    $tooFast = (time() - (int) ($_POST['ts'] ?? 0)) < 3;
    if (!empty($_POST['website']) || $tooFast) {
        return [true, [], []]; // udajemy sukces, nic nie zapisujemy
    }
    if (!csrf_check()) {
        $errors['form'] = 'Sesja wygasła. Odśwież stronę i spróbuj ponownie.';
    }
    if (mb_strlen($v['name']) < 2) {
        $errors['name'] = 'Podaj imię.';
    }
    $digits = preg_replace('~\D~', '', $v['phone']);
    if (strlen((string) $digits) < 9) {
        $errors['phone'] = 'Podaj numer telefonu (9 cyfr), żebyśmy mogli oddzwonić.';
    }
    if ($v['email'] !== '' && !filter_var($v['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Ten adres e-mail wygląda na niepoprawny.';
    }
    if (mb_strlen($v['message']) > 3000) {
        $errors['message'] = 'Wiadomość jest za długa (max 3000 znaków).';
    }
    if (!$v['consent']) {
        $errors['consent'] = 'Potrzebujemy zgody, żeby odpowiedzieć na zgłoszenie.';
    }

    // prosty limit: max 5 zgłoszeń z jednego IP na godzinę
    $messages = load_data('messages');
    $ipHash = hash('sha256', client_ip() . 'osk');
    $recent = array_filter($messages, fn($m) => ($m['ip'] ?? '') === $ipHash && strtotime($m['date'] ?? '') > time() - 3600);
    if (count($recent) >= 5) {
        $errors['form'] = 'Wysłano już kilka zgłoszeń. Zadzwoń do nas: ' . settings()['phone'] . '.';
    }

    if ($errors) {
        return [false, $errors, $v];
    }

    $topics = contact_topics();
    $entry = [
        'id' => bin2hex(random_bytes(6)),
        'date' => date('Y-m-d H:i:s'),
        'name' => mb_substr($v['name'], 0, 100),
        'phone' => mb_substr($v['phone'], 0, 30),
        'email' => mb_substr($v['email'], 0, 150),
        'topic' => $topics[$v['topic']] ?? $topics['kurs'],
        'message' => $v['message'],
        'read' => false,
        'ip' => $ipHash,
    ];
    array_unshift($messages, $entry);
    save_data('messages', array_slice($messages, 0, 1000));

    send_contact_mail($entry);
    return [true, [], []];
}

function contact_topics(): array
{
    return [
        'kurs' => 'Kurs prawa jazdy kat. B',
        'doszkalajace' => 'Jazdy doszkalające',
        'nocleg' => 'Nocleg / mieszkanie',
        'inne' => 'Inne pytanie',
    ];
}

function send_contact_mail(array $m): void
{
    $s = settings();
    $to = $s['form_email'] ?: $s['email'];
    if (!$to || !function_exists('mail')) {
        return;
    }
    $host = parse_url((string) $s['site_url'], PHP_URL_HOST) ?: 'localhost';
    $subject = '=?UTF-8?B?' . base64_encode('Nowe zgłoszenie ze strony: ' . $m['topic'] . ' – ' . $m['name']) . '?=';
    $body = "Nowe zgłoszenie z formularza na stronie\n\n"
        . "Temat: {$m['topic']}\n"
        . "Imię: {$m['name']}\n"
        . "Telefon: {$m['phone']}\n"
        . "E-mail: " . ($m['email'] ?: '–') . "\n"
        . "Data: {$m['date']}\n\n"
        . "Wiadomość:\n" . ($m['message'] ?: '–') . "\n\n"
        . "Wszystkie zgłoszenia: " . site_url('admin/?p=wiadomosci') . "\n";
    $headers = [
        'From: Strona OSK <no-reply@' . $host . '>',
        'Content-Type: text/plain; charset=UTF-8',
        'Content-Transfer-Encoding: 8bit',
    ];
    if ($m['email'] && filter_var($m['email'], FILTER_VALIDATE_EMAIL)) {
        $headers[] = 'Reply-To: ' . $m['email'];
    }
    @mail($to, $subject, $body, implode("\r\n", $headers));
}

/* ---------- panel administracyjny ---------- */

function is_admin(): bool
{
    // nie zakładamy sesji odwiedzającym, którzy nie mają ciasteczka sesji
    if (session_status() !== PHP_SESSION_ACTIVE && empty($_COOKIE['osk_sid'])) {
        return false;
    }
    start_session();
    if (empty($_SESSION['admin']) || (time() - (int) ($_SESSION['admin_seen'] ?? 0)) > 8 * 3600) {
        return false;
    }
    $_SESSION['admin_seen'] = time();
    return true;
}
