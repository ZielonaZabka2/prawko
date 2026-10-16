<?php
header('Content-Type: text/plain; charset=UTF-8');
$s = settings();
$p = prices();
$promo = promo();
$lines = [];
$lines[] = '# ' . $s['name'] . ' – ' . $s['tagline'];
$lines[] = '';
$lines[] = '> Ośrodek szkolenia kierowców (OSK) w Łomży, woj. podlaskie. Kurs prawa jazdy kategorii B, jazdy doszkalające oraz mieszkanie dla kursantów spoza Łomży.';
$lines[] = '';
$lines[] = '## Dane kontaktowe';
$lines[] = '- Adres biura: ' . full_address();
$lines[] = '- Telefon: +48 ' . $s['phone'];
$lines[] = '- E-mail: ' . $s['email'];
$lines[] = '- Właściciel i instruktor: ' . $s['owner'];
$lines[] = '- Strona: ' . site_url();
$lines[] = '';
$lines[] = '## Oferta i ceny (aktualne na ' . date('Y-m-d') . ')';
$lines[] = '- Kurs prawa jazdy kat. B: ' . zl(course_price()) . (course_discounted() ? ' (cena promocyjna do ' . $promo['end'] . ', regularnie ' . zl(course_regular_price()) . ')' : '') . '. W cenie: ' . $p['theory_hours'] . ' h teorii, ' . $p['practice_hours'] . ' h jazd' . ((int) $p['bonus_hours'] ? ', ' . $p['bonus_hours'] . ' h jazdy gratis' : '') . ', materiały szkoleniowe, egzamin wewnętrzny.';
if (!empty($p['installments'])) {
    $lines[] = '- Płatność za kurs w ratach: tak.';
}
$lines[] = '- Jazdy doszkalające: ' . zl((int) $p['extra_hour']) . ' za godzinę.';
$lines[] = '- Mieszkanie dla kursantów (2 pokoje): ' . zl((int) $p['apartment_day']) . ' za dobę lub ' . zl((int) $p['apartment_2weeks']) . ' za 2 tygodnie.';
$lines[] = '- Samochód szkoleniowy: Kia Rio IV.';
$lines[] = '- Czas realizacji kursu: nawet ok. 1 miesiąc przy jazdach kilka razy w tygodniu.';
$lines[] = '- Nie wliczone w cenę kursu: badanie lekarskie i opłaty egzaminacyjne w WORD.';
$lines[] = '';
$lines[] = '## Najważniejsze strony';
$lines[] = '- [Kurs prawa jazdy kat. B](' . site_url('kurs-prawa-jazdy-kat-b') . ')';
$lines[] = '- [Cennik](' . site_url('cennik') . ')';
$lines[] = '- [Jazdy doszkalające](' . site_url('jazdy-doszkalajace') . ')';
$lines[] = '- [Noclegi dla kursantów](' . site_url('noclegi-dla-kursantow') . ')';
$lines[] = '- [Kontakt i zapisy](' . site_url('kontakt') . ')';
$lines[] = '';
$lines[] = '## Poradnik';
foreach (posts() as $post) {
    $lines[] = '- [' . $post['title'] . '](' . site_url('blog/' . $post['slug']) . '): ' . $post['excerpt'];
}
echo implode("\n", $lines) . "\n";
