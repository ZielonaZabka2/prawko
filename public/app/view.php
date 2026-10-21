<?php
declare(strict_types=1);

/**
 * Renderuje podstronę z /app/pages/<nazwa>.php w layoucie.
 * Podstrona ustawia $meta (title, description, schema, breadcrumbs …) i wypisuje treść.
 */
function render(string $page, array $vars = []): void
{
    extract($vars, EXTR_SKIP);
    $meta = [];
    ob_start();
    require APP . "/pages/$page.php";
    $content = (string) ob_get_clean();

    $meta += [
        'title' => settings()['name'] . ' – ' . settings()['tagline'],
        'description' => '',
        'path' => '/',
        'og_image' => site_url('assets/img/og-image.jpg'),
        'og_type' => 'website',
        'schema' => [],
        'breadcrumbs' => [],
        'noindex' => false,
        'body_class' => '',
    ];
    $meta['nav'] = $page;
    header('Content-Type: text/html; charset=UTF-8');
    require APP . '/partials/layout.php';
}

function partial(string $name, array $vars = []): void
{
    extract($vars, EXTR_SKIP);
    require APP . "/partials/$name.php";
}

/** Okruszki (wizualne) – dane strukturalne generuje layout z tej samej listy. */
function breadcrumbs_html(array $items): string
{
    if (!$items) {
        return '';
    }
    $out = '<nav class="crumbs" aria-label="Okruszki"><ol>';
    $out .= '<li><a href="/">Strona główna</a></li>';
    $last = count($items) - 1;
    foreach ($items as $i => [$label, $url]) {
        $out .= $i === $last
            ? '<li aria-current="page">' . e($label) . '</li>'
            : '<li><a href="' . e($url) . '">' . e($label) . '</a></li>';
    }
    return $out . '</ol></nav>';
}

/** Lista pytań FAQ – ta sama dla treści i dla schemy FAQPage (brak rozjazdu). */
function faq_items(string $set = 'home', ?array $course = null): array
{
    $c = $course ?? main_course();
    $p = prices();
    $promo = promo();
    $price = zl(course_price($c));
    $priceNote = course_discounted($c)
        ? ' To cena promocyjna obowiązująca do ' . date_pl($promo['end']) . ' (cena regularna: ' . zl(course_regular_price($c)) . ').'
        : '';
    $bonus = (int) $c['bonus_hours'] > 0 ? ', ' . (int) $c['bonus_hours'] . ' h jazdy gratis' : '';
    $extra = course_includes($c) ? ', ' . implode(', ', course_includes($c)) : '';
    $catName = 'kat.' . "\u{00A0}" . $c['category'];

    $items = [
        'cena' => [
            "Ile kosztuje kurs prawa jazdy $catName w Łomży?",
            "Kurs prawa jazdy $catName w OSK Artur Żyliński kosztuje $price.$priceNote W cenie masz {$c['theory_hours']} godzin teorii, {$c['practice_hours']} godzin jazd$bonus$extra.",
        ],
        'raty' => [
            'Czy za kurs można zapłacić w ratach?',
            !empty($p['installments']) ? 'Tak. ' . $p['installments_note'] : 'Szczegóły płatności ustalisz przy zapisie – zadzwoń, a wszystko wyjaśnimy.',
        ],
        'czas' => [
            'Jak szybko można zrobić prawo jazdy?',
            'Cały kurs da się zrealizować nawet w około miesiąc, jeśli możesz jeździć kilka razy w tygodniu. Godziny jazd ustalamy indywidualnie. Termin egzaminu państwowego zależy od wolnych miejsc w WORD.',
        ],
        'start' => [
            'Co trzeba załatwić przed rozpoczęciem kursu?',
            'Potrzebujesz numeru PKK (profil kandydata na kierowcę). Najpierw robisz badanie lekarskie, potem składasz wniosek w wydziale komunikacji. Miejsce na kursie możesz zarezerwować wcześniej – pomożemy przy formalnościach.',
        ],
        'auto' => [
            'Na jakim pojeździe odbywają się jazdy?',
            $c['vehicle'] ? 'Jazdy na kursie ' . $catName . ' odbywają się pojazdem ' . $c['vehicle'] . '.' : 'Szczegóły pojazdu szkoleniowego podamy przy zapisie.',
        ],
        'doszkalajace' => [
            'Ile kosztuje jazda doszkalająca?',
            'Godzina jazdy doszkalającej kosztuje ' . zl((int) $p['extra_hour']) . '. Jazdy są dla kursantów przed egzaminem i dla kierowców z prawem jazdy, którzy chcą odzyskać pewność za kierownicą.',
        ],
        'nocleg' => [
            'Czy można zamieszkać w Łomży na czas kursu?',
            'Tak. Kursantom spoza Łomży wynajmujemy komfortowe, dwupokojowe mieszkanie: ' . zl((int) $p['apartment_day']) . ' za dobę albo ' . zl((int) $p['apartment_2weeks']) . ' za 2 tygodnie pobytu.',
        ],
        'gdzie' => [
            'Gdzie mieści się biuro szkoły jazdy?',
            'Biuro OSK Artur Żyliński znajduje się w Łomży przy ' . settings()['street'] . '. Zapisy przyjmujemy też telefonicznie pod numerem ' . settings()['phone'] . (settings()['hours'] ? ' (' . str_replace("\n", ', ', settings()['hours']) . ')' : '') . '.',
        ],
    ];
    if (count(courses()) > 1) {
        $items['kategorie'] = [
            'Jakie kursy prawa jazdy prowadzi OSK Artur Żyliński?',
            'Prowadzimy kursy: ' . implode(', ', array_map(fn($x) => $x['name'] . ' (' . zl(course_price($x)) . ')', courses())) . '.',
        ];
    }

    $sets = [
        'home' => ['cena', 'kategorie', 'raty', 'czas', 'start', 'auto', 'doszkalajace', 'nocleg', 'gdzie'],
        'kurs' => ['cena', 'raty', 'czas', 'start', 'auto'],
        'doszkalajace' => ['doszkalajace', 'gdzie'],
        'noclegi' => ['nocleg', 'czas', 'gdzie'],
    ];
    $keys = array_filter($sets[$set] ?? $sets['home'], fn($k) => isset($items[$k]));
    $list = array_values(array_map(fn($k) => $items[$k], $keys));
    // własne pytania z panelu → FAQ
    foreach (load_data('faq') as $f) {
        $where = $f['where'] ?? 'home';
        if (!empty($f['q']) && !empty($f['a']) && ($where === 'all' || $where === $set)) {
            $list[] = [$f['q'], $f['a']];
        }
    }
    return $list;
}

function faq_html(array $items, string $heading = 'Najczęstsze pytania'): string
{
    $out = '<section class="section faq" id="faq" aria-labelledby="faq-h"><div class="wrap narrow">';
    $out .= '<h2 id="faq-h">' . e($heading) . '</h2><div class="faq-list">';
    foreach ($items as $i => [$q, $a]) {
        $out .= '<details' . ($i === 0 ? ' open' : '') . '><summary><h3>' . e($q) . '</h3>' . icon('chevron', 'icon faq-ico') . '</summary><p>' . e($a) . '</p></details>';
    }
    return $out . '</div></div></section>';
}
