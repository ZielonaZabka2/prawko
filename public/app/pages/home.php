<?php
/*
 * Strona główna – 3 warianty wyglądu (A/B/C). Wybór: panel → Dane firmy → Wygląd.
 * Podgląd dowolnego wariantu: /?wariant=a | b | c
 */
$s = settings();
$p = prices();
$promo = promo();
$c = main_course();
$cat = 'kat.' . "\u{00A0}" . $c['category'];
$faq = faq_items('home');
$variant = in_array($_GET['wariant'] ?? '', ['a', 'b', 'c'], true) ? $_GET['wariant'] : ($s['home_variant'] ?? 'a');
if (!in_array($variant, ['a', 'b', 'c'], true)) {
    $variant = 'a';
}
$cats = array_values(array_unique(array_column(courses(), 'category')));
$meta = [
    'title' => count($cats) > 1
        ? 'Nauka jazdy Łomża – kursy prawa jazdy kat. ' . implode(', ', $cats) . ' | OSK Żyliński'
        : (count(courses()) > 1 ? 'Nauka jazdy Łomża – kursy prawa jazdy ' . $cat . ' | OSK Żyliński'
        : 'Nauka jazdy Łomża – kurs prawa jazdy ' . $cat . ' | OSK Żyliński'),
    'description' => 'Szkoła jazdy w Łomży: kurs prawa jazdy ' . $cat . ' za ' . zl(course_price($c)) . ', jazdy doszkalające ' . zl((int) $p['extra_hour']) . '/h, raty i mieszkanie dla kursantów. ul. Dworna 1. Tel. ' . $s['phone'] . '.',
    'path' => '/',
    'schema' => [schema_faq($faq), schema_person()],
    'body_class' => 'home-' . $variant,
];
$ctx = compact('s', 'p', 'promo', 'c', 'cat', 'faq');
partial("home/variant-$variant", $ctx);
