<?php
header('Content-Type: text/plain; charset=UTF-8');
$s = settings();
$p = prices();
$promo = promo();
$lines = [];
$lines[] = '# ' . $s['name'] . ' – ' . $s['tagline'];
$lines[] = '';
$lines[] = '> Ośrodek szkolenia kierowców (OSK) w Łomży, woj. podlaskie. Kursy prawa jazdy, jazdy doszkalające oraz mieszkanie dla kursantów spoza Łomży.';
$lines[] = '';
$lines[] = '## Dane kontaktowe';
$lines[] = '- Adres biura: ' . full_address();
$lines[] = '- Telefon: +48 ' . $s['phone'];
$lines[] = '- E-mail: ' . $s['email'];
$lines[] = '- Właściciel i instruktor: ' . $s['owner'];
if ($s['hours']) {
    $lines[] = '- Godziny: ' . str_replace("\n", '; ', $s['hours']);
}
$lines[] = '- Strona: ' . site_url();
$lines[] = '';
$lines[] = '## Oferta i ceny (aktualne na ' . date('Y-m-d') . ')';
foreach (courses() as $c) {
    $lines[] = '- ' . $c['name'] . ': ' . zl(course_price($c)) . (course_discounted($c) ? ' (cena promocyjna do ' . $promo['end'] . ', regularnie ' . zl(course_regular_price($c)) . ')' : '') . '. W cenie: ' . $c['theory_hours'] . ' h teorii, ' . $c['practice_hours'] . ' h jazd' . ((int) $c['bonus_hours'] ? ', ' . $c['bonus_hours'] . ' h jazdy gratis' : '') . (course_includes($c) ? ', ' . implode(', ', course_includes($c)) : '') . ($c['vehicle'] ? '. Pojazd: ' . $c['vehicle'] : '') . '.';
}
if (!empty($p['installments'])) {
    $lines[] = '- Płatność za kurs w ratach: tak.';
}
$lines[] = '- Jazdy doszkalające: ' . zl((int) $p['extra_hour']) . ' za godzinę.';
$lines[] = '- Mieszkanie dla kursantów (2 pokoje): ' . zl((int) $p['apartment_day']) . ' za dobę lub ' . zl((int) $p['apartment_2weeks']) . ' za 2 tygodnie.';
$lines[] = '- Czas realizacji kursu: nawet ok. 1 miesiąc przy jazdach kilka razy w tygodniu.';
$lines[] = '- Nie wliczone w cenę kursu: badanie lekarskie i opłaty egzaminacyjne w WORD.';
$lines[] = '';
$lines[] = '## Najważniejsze strony';
foreach (courses() as $c) {
    $lines[] = '- [' . $c['name'] . '](' . site_url(ltrim(course_url($c), '/')) . ')';
}
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
