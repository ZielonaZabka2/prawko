<?php
$p = prices();
$list = courses();
$crumbs = [['Kursy', '/kursy']];
$cats = implode(', ', array_column($list, 'category'));
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
    <p class="lead narrow-text">Wybierz kategorię. Każdy kurs obejmuje teorię, jazdy z instruktorem i egzamin wewnętrzny. Płatność możesz rozłożyć na raty.</p>
  </div>
</section>
<section class="section">
  <div class="wrap">
    <?php partial('course-cards', ['list' => $list, 'withServices' => true]); ?>
  </div>
</section>
