<?php
declare(strict_types=1);

/*
 * Wysyłka e-maili: przez skrzynkę na serwerze (SMTP, np. biuro@domena na LH.pl)
 * albo – gdy SMTP nie jest ustawione – zwykłą funkcją mail() serwera.
 * Bez bibliotek. Zwraca [true, ''] albo [false, 'opis błędu'].
 */

function mail_from_address(): string
{
    $s = settings();
    if (!empty($s['mail_from'])) {
        return $s['mail_from'];
    }
    $host = parse_url((string) $s['site_url'], PHP_URL_HOST) ?: 'localhost';
    return 'biuro@' . preg_replace('~^www\.~', '', $host);
}

function mail_header_encode(string $text): string
{
    return preg_match('~[^\x20-\x7E]~', $text) ? '=?UTF-8?B?' . base64_encode($text) . '?=' : $text;
}

function send_mail(string $to, string $subject, string $body, ?string $replyTo = null): array
{
    $s = settings();
    $from = mail_from_address();
    $fromName = $s['mail_from_name'] ?: $s['name'];
    if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
        return [false, 'Nieprawidłowy adres odbiorcy.'];
    }
    $headers = [
        'Date: ' . date('r'),
        'From: ' . mail_header_encode($fromName) . ' <' . $from . '>',
        'Message-ID: <' . bin2hex(random_bytes(12)) . '@' . (explode('@', $from)[1] ?? 'localhost') . '>',
        'MIME-Version: 1.0',
        'Content-Type: text/plain; charset=UTF-8',
        'Content-Transfer-Encoding: base64',
    ];
    if ($replyTo && filter_var($replyTo, FILTER_VALIDATE_EMAIL)) {
        $headers[] = 'Reply-To: ' . $replyTo;
    }
    $encodedBody = rtrim(chunk_split(base64_encode(str_replace("\n", "\r\n", str_replace("\r\n", "\n", $body)))));

    if (($s['mail_mode'] ?? 'php') === 'smtp' && !empty($s['smtp_host'])) {
        return smtp_send($to, $from, mail_header_encode($subject), $headers, $encodedBody);
    }
    if (!function_exists('mail')) {
        return [false, 'Serwer nie ma funkcji mail(). Ustaw wysyłkę przez skrzynkę (SMTP).'];
    }
    $ok = @mail($to, mail_header_encode($subject), $encodedBody, implode("\r\n", array_slice($headers, 1)), '-f' . $from);
    return $ok ? [true, ''] : [false, 'Funkcja mail() serwera zwróciła błąd. Ustaw wysyłkę przez skrzynkę (SMTP).'];
}

function smtp_send(string $to, string $from, string $subject, array $headers, string $body): array
{
    $s = settings();
    $host = (string) $s['smtp_host'];
    $port = (int) ($s['smtp_port'] ?: 465);
    $secure = $s['smtp_secure'] ?? 'ssl';
    $ctx = stream_context_create(['ssl' => ['verify_peer' => true, 'verify_peer_name' => true, 'SNI_enabled' => true]]);
    $remote = ($secure === 'ssl' ? 'ssl://' : 'tcp://') . $host . ':' . $port;
    $fp = @stream_socket_client($remote, $errno, $errstr, 12, STREAM_CLIENT_CONNECT, $ctx);
    if (!$fp) {
        return [false, "Brak połączenia z serwerem poczty $host:$port ($errstr)."];
    }
    stream_set_timeout($fp, 12);
    $read = function () use ($fp): array {
        $data = '';
        while (($line = fgets($fp, 1024)) !== false) {
            $data .= $line;
            if (strlen($line) < 4 || $line[3] === ' ') {
                break;
            }
        }
        return [(int) substr($data, 0, 3), trim($data)];
    };
    $cmd = function (string $c, array $okCodes) use ($fp, $read): array {
        fwrite($fp, $c . "\r\n");
        [$code, $msg] = $read();
        return [in_array($code, $okCodes, true), $msg];
    };
    $fail = function (string $step, string $msg) use ($fp): array {
        @fwrite($fp, "QUIT\r\n");
        fclose($fp);
        return [false, "Serwer poczty odrzucił krok „$step”: $msg"];
    };

    [$code, $msg] = $read();
    if ($code !== 220) { return $fail('połączenie', $msg); }
    $ehloHost = explode('@', $from)[1] ?? 'localhost';
    [$ok, $msg] = $cmd('EHLO ' . $ehloHost, [250]);
    if (!$ok) { return $fail('EHLO', $msg); }
    if ($secure === 'tls') {
        [$ok, $msg] = $cmd('STARTTLS', [220]);
        if (!$ok) { return $fail('STARTTLS', $msg); }
        if (!stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) { return $fail('szyfrowanie TLS', 'nie udało się'); }
        [$ok, $msg] = $cmd('EHLO ' . $ehloHost, [250]);
        if (!$ok) { return $fail('EHLO', $msg); }
    }
    if (!empty($s['smtp_user'])) {
        [$ok, $msg] = $cmd('AUTH LOGIN', [334]);
        if (!$ok) { return $fail('logowanie', $msg); }
        [$ok, $msg] = $cmd(base64_encode((string) $s['smtp_user']), [334]);
        if (!$ok) { return $fail('login', $msg); }
        [$ok, $msg] = $cmd(base64_encode((string) $s['smtp_pass']), [235]);
        if (!$ok) { return $fail('hasło', 'nieprawidłowy login lub hasło skrzynki'); }
    }
    [$ok, $msg] = $cmd('MAIL FROM:<' . $from . '>', [250]);
    if (!$ok) { return $fail('nadawca', $msg); }
    [$ok, $msg] = $cmd('RCPT TO:<' . $to . '>', [250, 251]);
    if (!$ok) { return $fail('odbiorca', $msg); }
    [$ok, $msg] = $cmd('DATA', [354]);
    if (!$ok) { return $fail('DATA', $msg); }
    $message = implode("\r\n", array_merge(['To: <' . $to . '>', 'Subject: ' . $subject], $headers)) . "\r\n\r\n" . $body;
    $message = preg_replace('~^\.~m', '..', $message); // kropka na początku linii
    [$ok, $msg] = $cmd($message . "\r\n.", [250]);
    if (!$ok) { return $fail('wysyłka', $msg); }
    $cmd('QUIT', [221]);
    fclose($fp);
    return [true, ''];
}
