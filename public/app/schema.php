<?php
declare(strict_types=1);

/*
 * Dane strukturalne (JSON-LD). Wszystko liczone z tych samych danych co treść strony,
 * więc cena w schemie zawsze zgadza się z ceną widoczną na stronie.
 */

function schema_id(string $frag): string
{
    return site_url('#' . $frag);
}

function schema_business(): array
{
    $s = settings();
    $p = prices();
    $sameAs = array_values(array_filter([$s['facebook'], $s['google_maps']]));

    $biz = [
        '@type' => ['DrivingSchool', 'LocalBusiness'],
        '@id' => schema_id('osk'),
        'name' => $s['name'] . ' – ' . $s['tagline'],
        'alternateName' => ['Nauka Jazdy Artur Żyliński', 'Szkoła Bezpiecznej Jazdy Łomża'],
        'url' => site_url(),
        'logo' => site_url('icon-512.png'),
        'image' => [site_url('assets/img/og-image.jpg'), image_url('biuro-dworna-lomza')],
        'description' => 'Ośrodek szkolenia kierowców w Łomży. Kursy prawa jazdy (kat. ' . implode(', ', array_unique(array_column(courses(), 'category'))) . '), jazdy doszkalające oraz mieszkanie dla kursantów spoza miasta.',
        'telephone' => '+48 ' . $s['phone'],
        'email' => $s['email'],
        'priceRange' => zl((int) $p['extra_hour']) . ' – ' . zl(max(array_map('course_regular_price', courses()) ?: [0])),
        'currenciesAccepted' => 'PLN',
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => preg_replace('~^ul\.\s*~u', 'ul. ', $s['street']),
            'postalCode' => $s['postal_code'],
            'addressLocality' => $s['city'],
            'addressRegion' => $s['region'],
            'addressCountry' => 'PL',
        ],
        'areaServed' => array_map(fn($c) => ['@type' => 'City', 'name' => $c], ['Łomża', 'Piątnica', 'Nowogród', 'Zambrów', 'Kolno', 'Jedwabne']),
        'founder' => ['@type' => 'Person', '@id' => schema_id('artur'), 'name' => $s['owner']],
        'makesOffer' => schema_offers(),
    ];
    if ($s['lat'] !== '' && $s['lng'] !== '') {
        $biz['geo'] = ['@type' => 'GeoCoordinates', 'latitude' => (float) $s['lat'], 'longitude' => (float) $s['lng']];
    }
    if (!empty($s['hours_schema'])) {
        $biz['openingHours'] = array_values(array_filter(array_map('trim', explode(',', $s['hours_schema']))));
    }
    if ($sameAs) {
        $biz['sameAs'] = $sameAs;
    }
    if ($s['nip'] !== '') {
        $biz['vatID'] = 'PL' . preg_replace('~\D~', '', $s['nip']);
    }
    return $biz;
}

function schema_course_offer(array $c): array
{
    $offer = [
        '@type' => 'Offer',
        'name' => $c['name'],
        'url' => site_url(ltrim(course_url($c), '/')),
        'price' => course_price($c),
        'priceCurrency' => 'PLN',
        'availability' => 'https://schema.org/InStock',
        'itemOffered' => [
            '@type' => 'Service',
            'name' => 'Kurs prawa jazdy kategorii ' . $c['category'],
            'description' => trim("{$c['theory_hours']} godzin zajęć teoretycznych, {$c['practice_hours']} godzin jazd"
                . ((int) $c['bonus_hours'] ? ", {$c['bonus_hours']} h jazdy gratis" : '')
                . (course_includes($c) ? ', ' . implode(', ', course_includes($c)) : '') . '.'),
        ],
    ];
    if (course_discounted($c)) {
        $offer['priceValidUntil'] = promo()['end'];
    }
    return $offer;
}

function schema_extra_offer(): array
{
    return [
        '@type' => 'Offer',
        'name' => 'Jazdy doszkalające',
        'url' => site_url('jazdy-doszkalajace'),
        'priceCurrency' => 'PLN',
        'priceSpecification' => ['@type' => 'UnitPriceSpecification', 'price' => (int) prices()['extra_hour'], 'priceCurrency' => 'PLN', 'unitText' => 'godzina'],
    ];
}

function schema_stay_offer(): array
{
    return [
        '@type' => 'Offer',
        'name' => 'Mieszkanie dla kursantów',
        'url' => site_url('noclegi-dla-kursantow'),
        'priceCurrency' => 'PLN',
        'priceSpecification' => ['@type' => 'UnitPriceSpecification', 'price' => (int) prices()['apartment_day'], 'priceCurrency' => 'PLN', 'unitText' => 'doba'],
    ];
}

function schema_offers(): array
{
    return array_merge(array_map('schema_course_offer', courses()), [schema_extra_offer(), schema_stay_offer()]);
}

function schema_website(): array
{
    return [
        '@type' => 'WebSite',
        '@id' => schema_id('website'),
        'url' => site_url(),
        'name' => settings()['name'] . ' – ' . settings()['tagline'],
        'inLanguage' => 'pl-PL',
        'publisher' => ['@id' => schema_id('osk')],
    ];
}

function schema_person(): array
{
    $s = settings();
    return [
        '@type' => 'Person',
        '@id' => schema_id('artur'),
        'name' => $s['owner'],
        'jobTitle' => 'Instruktor nauki jazdy, właściciel OSK',
        'worksFor' => ['@id' => schema_id('osk')],
        'knowsAbout' => ['nauka jazdy', 'prawo jazdy', 'egzamin na prawo jazdy', 'bezpieczna jazda'],
    ];
}

function schema_faq(array $items): array
{
    return [
        '@type' => 'FAQPage',
        'mainEntity' => array_map(fn($qa) => [
            '@type' => 'Question',
            'name' => $qa[0],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $qa[1]],
        ], $items),
    ];
}

function schema_breadcrumbs(array $items): array
{
    $list = [['@type' => 'ListItem', 'position' => 1, 'name' => 'Strona główna', 'item' => site_url()]];
    foreach ($items as $i => [$label, $url]) {
        $list[] = ['@type' => 'ListItem', 'position' => $i + 2, 'name' => $label, 'item' => site_url($url)];
    }
    return ['@type' => 'BreadcrumbList', 'itemListElement' => $list];
}

function schema_service(string $name, string $path, string $description, ?array $offer = null): array
{
    $service = [
        '@type' => 'Service',
        'name' => $name,
        'url' => site_url($path),
        'description' => $description,
        'provider' => ['@id' => schema_id('osk')],
        'areaServed' => ['@type' => 'City', 'name' => 'Łomża'],
    ];
    if ($offer) {
        $service['offers'] = $offer;
    }
    return $service;
}

function schema_post(array $post): array
{
    $dir = $post['cover_dir'] ?? 'uploads';
    return [
        '@type' => 'BlogPosting',
        'headline' => $post['title'],
        'description' => $post['meta_description'] ?: $post['excerpt'],
        'datePublished' => $post['date'],
        'dateModified' => $post['updated'] ?: $post['date'],
        'mainEntityOfPage' => site_url('blog/' . $post['slug']),
        'image' => !empty($post['cover']) ? image_url($post['cover'], $dir) : site_url('assets/img/og-image.jpg'),
        'inLanguage' => 'pl-PL',
        'author' => schema_person(),
        'publisher' => ['@id' => schema_id('osk')],
    ];
}

function schema_script(array $nodes): string
{
    if (!$nodes) {
        return '';
    }
    $graph = ['@context' => 'https://schema.org', '@graph' => array_values($nodes)];
    $json = json_encode($graph, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG);
    return '<script type="application/ld+json">' . $json . '</script>';
}
