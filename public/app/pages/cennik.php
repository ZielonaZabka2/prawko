<?php
$s = settings();
$p = prices();
$promo = promo();
$crumbs = [['Cennik', '/cennik']];
$meta = [
    'title' => 'Cennik – ile kosztuje prawo jazdy w Łomży? | OSK Żyliński',
    'description' => 'Aktualny cennik OSK Artur Żyliński w Łomży: kurs kat. ' . main_course()['category'] . ' ' . zl(course_price()) . ', jazdy doszkalające ' . zl((int) $p['extra_hour']) . '/h, mieszkanie dla kursantów od ' . zl((int) $p['apartment_day']) . '/doba. Raty.',
    'path' => '/cennik',
    'breadcrumbs' => $crumbs,
];
$rows = [];
foreach (courses() as $c) {
    $rows[] = [$c['name'], (int) $c['theory_hours'] . ' h teorii, ' . (int) $c['practice_hours'] . ' h jazd' . ((int) $c['bonus_hours'] ? ', +' . (int) $c['bonus_hours'] . ' h gratis' : '') . (course_includes($c) ? ', ' . implode(', ', course_includes($c)) : ''), course_price($c), course_discounted($c) ? course_regular_price($c) : null, course_url($c)];
}
$rows = array_merge($rows, [
    ['Jazda doszkalająca', '60 minut z instruktorem', (int) $p['extra_hour'], null, '/jazdy-doszkalajace', 'h'],
    ['Mieszkanie dla kursantów', 'doba, 2 pokoje', (int) $p['apartment_day'], null, '/noclegi-dla-kursantow'],
    ['Mieszkanie dla kursantów', 'pobyt 2-tygodniowy', (int) $p['apartment_2weeks'], null, '/noclegi-dla-kursantow'],
]);
foreach ($p['extra_items'] ?? [] as $item) {
    if (!empty($item['name'])) {
        $rows[] = [$item['name'], $item['note'] ?? '', (int) ($item['price'] ?? 0), null, null, $item['unit'] ?? ''];
    }
}
?>
<section class="page-hero">
  <div class="wrap">
    <?= breadcrumbs_html($crumbs) ?>
    <h1>Cennik</h1>
    <p class="lead narrow-text">Ile kosztuje prawo jazdy kat. <?= e(main_course()['category']) ?> w Łomży? U nas kurs kosztuje <strong><?= zl(course_price()) ?></strong><?= course_discounted() ? ' w promocji do ' . e(date_pl($promo['end'])) : '' ?>. Poniżej wszystkie ceny w jednym miejscu, aktualne na <?= e(date_pl(date('Y-m-d'))) ?>.</p>
  </div>
</section>

<section class="section">
  <div class="wrap narrow">
    <div class="table-wrap">
      <table class="price-table">
        <caption class="sr-only">Cennik usług OSK Artur Żyliński</caption>
        <thead><tr><th scope="col">Usługa</th><th scope="col">Zakres</th><th scope="col" class="num">Cena</th></tr></thead>
        <tbody>
          <?php foreach ($rows as $r): [$name, $note, $price, $old, $url] = $r; $unit = $r[5] ?? ''; ?>
            <tr>
              <th scope="row"><?= $url ? '<a href="' . e($url) . '">' . e($name) . '</a>' : e($name) ?></th>
              <td><?= e($note) ?></td>
              <td class="num">
                <strong><?= zl($price) ?><?= $unit ? ' / ' . e($unit) : '' ?></strong>
                <?php if ($old): ?><s class="price-old-sm"><span class="sr-only">zamiast </span><?= zl($old) ?></s><?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <?php if (promo_active($promo)): ?>
      <div class="note note-promo">
        <?= icon('calendar') ?>
        <p><strong><?= e($promo['label']) ?>:</strong> <?= e($promo['text']) ?> Promocja obowiązuje od <?= e(date_short($promo['start'])) ?> do <?= e(date_short($promo['end'])) ?>. <a href="/regulamin#promocje">Warunki</a></p>
      </div>
    <?php endif; ?>

    <?php if (!empty($p['installments'])): ?>
      <div class="note">
        <?= icon('wallet') ?>
        <p><strong>Raty:</strong> <?= e($p['installments_note']) ?></p>
      </div>
    <?php endif; ?>

    <div class="prose">
      <h2>Co nie jest wliczone w cenę kursu?</h2>
      <p class="bluf">W cenie kursu nie ma dwóch opłat, które wnosisz poza szkołą: za badanie lekarskie kierowcy i za egzamin państwowy w WORD. Wszystko, co dotyczy samego szkolenia, jest w cenie.</p>
      <p>Aktualne opłaty egzaminacyjne sprawdzisz na stronie WORD Łomża. Jeśli przed egzaminem chcesz dodatkowo poćwiczyć, skorzystaj z <a href="/jazdy-doszkalajace">jazd doszkalających</a>.</p>
    </div>

    <div class="cta-inline">
      <p>Masz pytanie o cenę albo raty?</p>
      <a class="btn btn-primary" href="<?= e(phone_href()) ?>"><?= icon('phone') ?> <?= e($s['phone']) ?></a>
      <a class="btn btn-outline" href="/kontakt#zapis">Napisz do nas</a>
    </div>
  </div>
</section>
