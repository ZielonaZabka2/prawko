<?php
declare(strict_types=1);

function redirect(string $to): never
{
    header('Location: ' . $to, true, 303);
    exit;
}

function flash(string $msg, string $type = 'ok'): void
{
    $_SESSION['flash'][] = [$type, $msg];
}

function take_flashes(): array
{
    $f = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $f;
}

function admin_render(string $view, array $vars = [], bool $withLayout = true): void
{
    extract($vars, EXTR_SKIP);
    $current = $view;
    ob_start();
    require APP . "/admin/views/$view.php";
    $body = (string) ob_get_clean();
    if (!$withLayout) {
        echo $body;
        return;
    }
    require APP . '/admin/views/_layout.php';
}

/* ---------- limit prób logowania ---------- */

function login_key(): string
{
    return hash('sha256', client_ip() . '|osk-login');
}

function login_blocked(): bool
{
    $all = load_data('login_attempts');
    $a = $all[login_key()] ?? null;
    return $a && $a['count'] >= 5 && time() - $a['first'] < 900;
}

function login_fail(): void
{
    $all = load_data('login_attempts');
    $k = login_key();
    $a = $all[$k] ?? null;
    if (!$a || time() - $a['first'] >= 900) {
        $a = ['count' => 0, 'first' => time()];
    }
    $a['count']++;
    $all[$k] = $a;
    // sprzątanie starych wpisów
    $all = array_filter($all, fn($x) => time() - ($x['first'] ?? 0) < 86400);
    save_data('login_attempts', $all);
}

function login_reset(): void
{
    $all = load_data('login_attempts');
    unset($all[login_key()]);
    save_data('login_attempts', $all);
}

/* ---------- formularze ---------- */

function post_str(string $k, int $max = 500): string
{
    $v = trim((string) ($_POST[$k] ?? ''));
    $v = str_replace("\r\n", "\n", $v);
    return mb_substr($v, 0, $max);
}

function post_int(string $k, int $min = 0, int $max = 1000000): int
{
    $v = (int) preg_replace('~[^\d-]~', '', (string) ($_POST[$k] ?? '0'));
    return max($min, min($max, $v));
}

function post_bool(string $k): bool
{
    return !empty($_POST[$k]);
}

function post_date(string $k): string
{
    $v = (string) ($_POST[$k] ?? '');
    return preg_match('~^\d{4}-\d{2}-\d{2}$~', $v) ? $v : '';
}

function post_url(string $k): string
{
    $v = post_str($k, 300);
    if ($v === '') {
        return '';
    }
    return preg_match('~^(https?://|/|#|tel:|mailto:)~i', $v) ? $v : '';
}

function field(string $name, string $label, $value, array $o = []): string
{
    $type = $o['type'] ?? 'text';
    $id = 'f-' . $name;
    $help = isset($o['help']) ? '<p class="help">' . $o['help'] . '</p>' : '';
    $attrs = '';
    foreach (['min', 'max', 'step', 'maxlength', 'placeholder', 'required', 'inputmode', 'autocomplete'] as $a) {
        if (isset($o[$a])) {
            $attrs .= $o[$a] === true ? " $a" : ' ' . $a . '="' . e((string) $o[$a]) . '"';
        }
    }
    $suffix = isset($o['suffix']) ? '<span class="suffix">' . e($o['suffix']) . '</span>' : '';
    if ($type === 'textarea') {
        $input = '<textarea id="' . $id . '" name="' . e($name) . '" rows="' . ($o['rows'] ?? 4) . '"' . $attrs . '>' . e((string) $value) . '</textarea>';
    } elseif ($type === 'checkbox') {
        return '<div class="field field-check"><input type="checkbox" id="' . $id . '" name="' . e($name) . '" value="1"' . ($value ? ' checked' : '') . '><label for="' . $id . '">' . $label . '</label>' . $help . '</div>';
    } else {
        $input = '<input type="' . e($type) . '" id="' . $id . '" name="' . e($name) . '" value="' . e((string) $value) . '"' . $attrs . '>';
    }
    return '<div class="field' . ($suffix ? ' has-suffix' : '') . '"><label for="' . $id . '">' . $label . '</label><div class="input-wrap">' . $input . $suffix . '</div>' . $help . '</div>';
}

function promo_status(array $p): array
{
    $today = date('Y-m-d');
    if (empty($p['enabled'])) {
        return ['off', 'Wyłączona'];
    }
    if ($p['start'] && $today < $p['start']) {
        return ['plan', 'Zaplanowana – wystartuje ' . date_short($p['start'])];
    }
    if ($p['end'] && $today > $p['end']) {
        return ['end', 'Zakończona ' . date_short($p['end'])];
    }
    return ['on', 'Aktywna do ' . date_short($p['end'])];
}

function unread_count(): int
{
    return count(array_filter(load_data('messages'), fn($m) => empty($m['read'])));
}

/* ---------- zdjęcia do bloga ---------- */

/**
 * Przetwarza wgrane zdjęcie na WebP w 3 rozmiarach (uploads/<nazwa>-<szer>.webp).
 * Zwraca nazwę bazową albo komunikat błędu (string zaczynający się od "!").
 */
function process_upload(array $file, string $slug): string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return ($file['error'] ?? 0) === UPLOAD_ERR_INI_SIZE ? '!Plik jest za duży dla serwera.' : '!Nie udało się wgrać pliku.';
    }
    if ($file['size'] > 12 * 1024 * 1024) {
        return '!Plik jest za duży (max 12 MB).';
    }
    $info = @getimagesize($file['tmp_name']);
    $allowed = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP];
    if (!$info || !in_array($info[2], $allowed, true)) {
        return '!Dozwolone formaty: JPG, PNG, WEBP.';
    }
    if (!function_exists('imagewebp')) {
        return '!Serwer nie obsługuje formatu WebP (biblioteka GD).';
    }
    $src = @imagecreatefromstring((string) file_get_contents($file['tmp_name']));
    if (!$src) {
        return '!Nie udało się odczytać zdjęcia.';
    }
    // obrót wg EXIF (zdjęcia z telefonu)
    if ($info[2] === IMAGETYPE_JPEG && function_exists('exif_read_data')) {
        $exif = @exif_read_data($file['tmp_name']);
        $rot = [3 => 180, 6 => -90, 8 => 90][$exif['Orientation'] ?? 1] ?? 0;
        if ($rot) {
            $src = imagerotate($src, $rot, 0);
        }
    }
    if (!is_dir(UPLOADS)) {
        mkdir(UPLOADS, 0755, true);
    }
    $base = substr(slugify($slug), 0, 50) . '-' . bin2hex(random_bytes(3));
    $w = imagesx($src);
    $h = imagesy($src);
    $done = 0;
    foreach ([480, 960, 1600] as $target) {
        $tw = min($target, $w);
        $th = (int) round($h * $tw / $w);
        $dst = imagecreatetruecolor($tw, $th);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
        imagewebp($dst, UPLOADS . "/$base-$tw.webp", 80);
        imagedestroy($dst);
        $done++;
        if ($tw === $w) {
            break; // oryginał mniejszy niż kolejny rozmiar
        }
    }
    imagedestroy($src);
    return $done ? $base : '!Nie udało się zapisać zdjęcia.';
}

function delete_upload(string $base): void
{
    if ($base === '' || !preg_match('~^[a-z0-9-]+$~', $base)) {
        return;
    }
    foreach (glob(UPLOADS . '/' . $base . '-*.webp') ?: [] as $f) {
        @unlink($f);
    }
}
