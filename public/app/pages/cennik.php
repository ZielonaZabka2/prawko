<?php
$s = settings();
$p = prices();
$promo = promo();
$crumbs = [['Cennik', '/cennik']];
$meta = [
    'title' => 'Cennik – ile kosztuje prawo jazdy kat. B w Łomży?',
    'description' => 'Aktualny cennik OSK Artur Żyliński w Łomży: kurs kat. B ' . zl(course_price()) . ', jazdy doszkalające ' . zl((int) $p['extra_hour']) . '/h, mieszkanie dla kursantów od ' . zl((int) $p['apartment_day']) . '/doba. Raty.',
    'path' => '/cennik',
    'breadcrumbs' => $crumbs,
];
$rows = [
    ['Kurs prawa jazdy kat. B', (int) $p['theory_hours'] . ' h teorii, ' . (int) $p['practice_hours'] . ' h jazd' . ((int) $p['bonus_hours'] ? ', +' . (int) $p['bonus_hours'] . ' h gratis' : '') . ', materiały', course_price(), course_discounted() ? course_regular_price() : null, '/kurs-prawa-jazdy-kat-b'],
    ['Jazda doszkalająca', '60 minut z instruktorem', (int) $p['extra_hour'], null, '/jazdy-doszkalajace', 'h'],
    ['Mieszkanie dla kursantów', 'doba, 2 pokoje', (int) $p['apartment_day'], null, '/noclegi-dla-kursantow'],
    ['Mieszkanie dla kursantów', 'pobyt 2-tygodniowy', (int) $p['apartment_2weeks'], null, '/noclegi-dla-kursantow'],
];
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
    <p class="lead narrow-text">Ile kosztuje prawo jazdy kat. B w Łomży? U nas kurs kosztuje <strong><?= zl(course_price()) ?></strong><?= course_discounted() ? ' w promocji do ' . e(date_pl($promo['end'])) : '' ?>. Poniżej wszystkie ceny w jednym miejscu, aktualne na <?= e(date_pl(date('Y-m-d'))) ?>.</p>
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

    <?php if (course_discounted()): ?>
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
