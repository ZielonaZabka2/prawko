<?php
declare(strict_types=1);

/*
 * Mały, bezpieczny parser Markdown dla wpisów bloga.
 * Obsługuje: ## / ### nagłówki, akapity, listy (- oraz 1.), cytaty (>), tabele (| a | b |),
 * **pogrubienie**, *kursywę*, [link](adres). Cały surowy HTML jest escapowany.
 */

function md_inline(string $text): string
{
    $text = htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

    $text = preg_replace_callback('~\[([^\]]+)\]\(([^)\s]+)\)~u', function ($m) {
        $url = html_entity_decode($m[2], ENT_QUOTES, 'UTF-8');
        if (!preg_match('~^(https?://|/|#|tel:|mailto:)~i', $url)) {
            return $m[1];
        }
        $external = preg_match('~^https?://~i', $url) === 1;
        $attrs = $external ? ' target="_blank" rel="noopener"' : '';
        return '<a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '"' . $attrs . '>' . $m[1] . '</a>';
    }, $text) ?? $text;

    $text = preg_replace('~\*\*(.+?)\*\*~u', '<strong>$1</strong>', $text) ?? $text;
    $text = preg_replace('~(?<![\w*])\*(?!\s)(.+?)(?<!\s)\*(?![\w*])~u', '<em>$1</em>', $text) ?? $text;
    return $text;
}

function md_to_html(string $md): string
{
    $lines = preg_split('~\R~u', str_replace("\t", '    ', $md)) ?: [];
    $html = [];
    $para = [];
    $list = null; // ['type' => 'ul'|'ol', 'items' => []]
    $quote = [];
    $table = [];

    $flushPara = function () use (&$para, &$html) {
        if ($para) {
            $html[] = '<p>' . md_inline(implode(' ', $para)) . '</p>';
            $para = [];
        }
    };
    $flushList = function () use (&$list, &$html) {
        if ($list) {
            $items = array_map(fn($i) => '<li>' . md_inline($i) . '</li>', $list['items']);
            $html[] = "<{$list['type']}>" . implode('', $items) . "</{$list['type']}>";
            $list = null;
        }
    };
    $flushQuote = function () use (&$quote, &$html) {
        if ($quote) {
            $html[] = '<blockquote><p>' . md_inline(implode(' ', $quote)) . '</p></blockquote>';
            $quote = [];
        }
    };
    $flushTable = function () use (&$table, &$html) {
        if (!$table) {
            return;
        }
        $rows = array_values(array_filter($table, fn($r) => !preg_match('~^\|?\s*:?-{2,}~', $r)));
        $cells = fn($r) => array_map('trim', explode('|', trim(trim($r), '|')));
        $out = '<div class="table-wrap"><table><thead><tr>';
        foreach ($cells(array_shift($rows)) as $c) {
            $out .= '<th scope="col">' . md_inline($c) . '</th>';
        }
        $out .= '</tr></thead><tbody>';
        foreach ($rows as $r) {
            $out .= '<tr>';
            foreach ($cells($r) as $c) {
                $out .= '<td>' . md_inline($c) . '</td>';
            }
            $out .= '</tr>';
        }
        $html[] = $out . '</tbody></table></div>';
        $table = [];
    };
    $flushAll = function () use ($flushPara, $flushList, $flushQuote, $flushTable) {
        $flushPara(); $flushList(); $flushQuote(); $flushTable();
    };

    foreach ($lines as $raw) {
        $line = rtrim($raw);
        $trim = ltrim($line);

        if ($trim === '') {
            $flushAll();
            continue;
        }
        if (preg_match('~^(#{2,4})\s+(.+)$~u', $trim, $m)) {
            $flushAll();
            $level = strlen($m[1]);
            $id = slugify($m[2]);
            $html[] = "<h$level id=\"" . e($id) . "\">" . md_inline($m[2]) . "</h$level>";
            continue;
        }
        if (str_starts_with($trim, '|')) {
            $flushPara(); $flushList(); $flushQuote();
            $table[] = $trim;
            continue;
        }
        if (preg_match('~^[-*]\s+(.+)$~u', $trim, $m) || preg_match('~^\d+[.)]\s+(.+)$~u', $trim, $m2)) {
            $type = isset($m[1]) ? 'ul' : 'ol';
            $text = $m[1] ?? $m2[1];
            $flushPara(); $flushQuote(); $flushTable();
            if ($list && $list['type'] !== $type) {
                $flushList();
            }
            $list ??= ['type' => $type, 'items' => []];
            $list['items'][] = $text;
            unset($m, $m2);
            continue;
        }
        if (str_starts_with($trim, '>')) {
            $flushPara(); $flushList(); $flushTable();
            $quote[] = ltrim(substr($trim, 1));
            continue;
        }
        if ($list && preg_match('~^\s{2,}~', $line)) {
            $list['items'][count($list['items']) - 1] .= ' ' . $trim;
            continue;
        }
        $flushList(); $flushQuote(); $flushTable();
        $para[] = $trim;
    }
    $flushAll();
    return implode("\n", $html);
}

/** Pierwszy akapit tekstu (do opisu / BLUF). */
function md_plain(string $md, int $limit = 0): string
{
    $text = preg_replace(['~\[([^\]]+)\]\([^)]+\)~u', '~[*#>|]~u'], ['$1', ''], $md) ?? $md;
    $text = trim(preg_replace('~\s+~u', ' ', $text) ?? $text);
    if ($limit && mb_strlen($text) > $limit) {
        $text = rtrim(mb_substr($text, 0, $limit - 1)) . '…';
    }
    return $text;
}

/** Wyciąga nagłówki ## jako spis treści. */
function md_headings(string $md): array
{
    preg_match_all('~^##\s+(.+)$~mu', $md, $m);
    return array_map(fn($h) => ['id' => slugify($h), 'text' => $h], $m[1]);
}
