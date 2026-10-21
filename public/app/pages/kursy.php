<?php
$p = prices();
$list = courses();
$crumbs = [['Kursy', '/kursy']];
$cats = implode(', ', array_unique(array_column($list, 'category')));
$meta = [
    'title' => 'Kursy prawa jazdy w Łomży – kat. ' . $cats . ' | OSK Żyliński',
    'description' => 'Kursy prawa jazdy w Łomży (kat. ' . $cats . '), jazdy doszkalające i mieszkanie dla kursantów. Aktualne ceny, raty, zapisy: ' . settings()['phone'] . '.',
    'path' => '/kursy',
    'breadcrumbs' => $crumbs,
    'schema' => [[
        '@type' => 'ItemList',
        'name' => 'Kursy prawa jazdy – OSK Artur Żyliński',
        'itemListElement' => array_map(fn($c, $i) => ['@type' => 'ListItem', 'position' => $i + 1, 'url' => site_url(ltrim(course_url($c), '/')), 'name' => $c['name']], $list, array_keys($list)),
    ]],
];
?>
<section class="page-hero">
  <div class="wrap">
    <?= breadcrumbs_html($crumbs) ?>
    <h1>Kursy prawa jazdy w Łomży</h1>
    <p class="lead narrow-text">Kurs standardowy albo Express w 14 dni. Każdy obejmuje teorię, jazdy z instruktorem i egzamin wewnętrzny, a płatność możesz rozłożyć na raty.</p>
  </div>
</section>
<section class="section">
  <div class="wrap">
    <?php partial('course-cards', ['list' => $list, 'withServices' => true]); ?>
  </div>
</section>

<?php if (count($list) > 1): ?>
<section class="section section-alt" aria-labelledby="porownanie">
  <div class="wrap narrow">
    <h2 id="porownanie" data-reveal>Który kurs wybrać?</h2>
    <p class="bluf" data-reveal>Zakres szkolenia jest taki sam: <?= (int) $list[0]['theory_hours'] ?> godzin teorii i <?= (int) $list[0]['practice_hours'] ?> godzin jazd. Kursy różnią się tempem i ceną. Jeśli możesz jeździć codziennie i chcesz mieć prawo jazdy jak najszybciej, wybierz Express. Jeśli wolisz rozłożyć naukę w czasie, wybierz kurs standardowy.</p>
    <div class="table-wrap" data-reveal>
      <table class="compare-table">
        <caption class="sr-only">Porównanie kursów prawa jazdy</caption>
        <thead><tr><th scope="col"></th><?php foreach ($list as $c): ?><th scope="col"><a href="<?= e(course_url($c)) ?>"><?= e($c['name']) ?></a></th><?php endforeach; ?></tr></thead>
        <tbody>
          <tr><th scope="row">Cena</th><?php foreach ($list as $c): ?><td><strong><?= zl(course_price($c)) ?></strong><?= course_discounted($c) ? ' <s>' . zl(course_regular_price($c)) . '</s>' : '' ?></td><?php endforeach; ?></tr>
          <tr><th scope="row">Czas trwania</th><?php foreach ($list as $c): ?><td><?= e($c['duration'] ?: 'do ustalenia') ?></td><?php endforeach; ?></tr>
          <tr><th scope="row">Teoria</th><?php foreach ($list as $c): ?><td><?= (int) $c['theory_hours'] ?> h</td><?php endforeach; ?></tr>
          <tr><th scope="row">Jazdy</th><?php foreach ($list as $c): ?><td><?= (int) $c['practice_hours'] ?> h<?= (int) $c['bonus_hours'] ? ' + ' . (int) $c['bonus_hours'] . ' h gratis' : '' ?></td><?php endforeach; ?></tr>
          <tr><th scope="row">W cenie</th><?php foreach ($list as $c): ?><td><?= e(implode(', ', course_includes($c)) ?: '–') ?></td><?php endforeach; ?></tr>
          <tr><th scope="row">Pojazd</th><?php foreach ($list as $c): ?><td><?= e($c['vehicle'] ?: '–') ?></td><?php endforeach; ?></tr>
          <tr><th scope="row">Nocleg</th><?php foreach ($list as $c): ?><td>nie (mieszkanie do wynajęcia osobno: <?= zl((int) $p['apartment_day']) ?>/doba)</td><?php endforeach; ?></tr>
          <tr><th scope="row">Raty</th><?php foreach ($list as $c): ?><td><?= !empty($p['installments']) ? 'tak' : 'zapytaj' ?></td><?php endforeach; ?></tr>
        </tbody>
      </table>
    </div>
    <p data-reveal>Poza ceną kursu opłacasz badanie lekarskie i egzamin państwowy w WORD. Szczegóły w <a href="/cennik">cenniku</a> i w poradniku <a href="/blog/jak-zrobic-prawo-jazdy-kat-b-w-lomzy">jak zrobić prawo jazdy krok po kroku</a>.</p>
  </div>
</section>
<?php endif; ?>
