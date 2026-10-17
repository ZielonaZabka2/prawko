<?php
/** @var array $course */
$c = $course;
$s = settings();
$p = prices();
$promo = promo();
$faq = faq_items('kurs', $c);
$cat = 'kat.' . "\u{00A0}" . $c['category'];
$crumbs = count(courses()) > 1
    ? [['Kursy', '/kursy'], [$c['name'], course_url($c)]]
    : [[$c['name'], course_url($c)]];
$meta = [
    'title' => $c['meta_title'] ?: 'Kurs prawa jazdy ' . $cat . ' Łomża – ' . zl(course_price($c)) . ' | OSK Żyliński',
    'description' => $c['meta_description'] ?: 'Kurs prawa jazdy ' . $cat . ' w Łomży: ' . $c['theory_hours'] . ' h teorii, ' . $c['practice_hours'] . ' h jazd' . ((int) $c['bonus_hours'] ? ' + godzina gratis' : '') . (!empty($p['installments']) ? ', raty' : '') . '. Przebieg kursu, formalności i cena ' . zl(course_price($c)) . '.',
    'path' => course_url($c),
    'breadcrumbs' => $crumbs,
    'noindex' => empty($c['active']),
    'schema' => [
        schema_service($c['name'], ltrim(course_url($c), '/'), $c['lead'] ?: $c['name'] . ' w Łomży.', schema_course_offer($c)),
        schema_faq($faq),
    ],
];
$includes = course_includes($c);
?>
<?php if (empty($c['active'])): ?><div class="draft-bar">Kurs nieaktywny – widzisz go tylko Ty (jesteś zalogowany w panelu).</div><?php endif; ?>
<section class="page-hero">
  <div class="wrap">
    <?= breadcrumbs_html($crumbs) ?>
    <div class="page-hero-grid">
      <div data-reveal>
        <p class="cat-badge" aria-hidden="true"><span><?= e($c['category']) ?></span> <?= e($c['short']) ?></p>
        <h1>Kurs prawa jazdy <?= e($cat) ?> w Łomży</h1>
        <p class="lead"><?= e($c['lead']) ?> Cena kursu: <strong><?= zl(course_price($c)) ?></strong><?= course_discounted($c) ? ' (promocja do ' . e(date_pl($promo['end'])) . ', regularnie ' . zl(course_regular_price($c)) . ')' : '' ?>.</p>
        <div class="hero-actions">
          <a class="btn btn-primary btn-lg" href="/kontakt?temat=kurs#zapis">Zapisz się <?= icon('arrow') ?></a>
          <a class="btn btn-ghost btn-lg" href="<?= e(phone_href()) ?>"><?= icon('phone') ?> <?= e($s['phone']) ?></a>
        </div>
      </div>
      <ul class="hero-points" data-reveal-group>
        <li><?= icon('book') ?><span><strong><?= (int) $c['theory_hours'] ?> h</strong> teorii</span></li>
        <li><?= icon('wheel') ?><span><strong><?= (int) $c['practice_hours'] ?> h</strong> jazd<?= (int) $c['bonus_hours'] ? ' + ' . (int) $c['bonus_hours'] . ' h gratis' : '' ?></span></li>
        <li><?= icon('wallet') ?><span><strong><?= !empty($p['installments']) ? 'raty' : 'płatność' ?></strong> do ustalenia</span></li>
        <li><?= icon('car') ?><span><strong><?= e($c['vehicle'] ?: 'auto') ?></strong> pojazd szkoleniowy</span></li>
      </ul>
    </div>
  </div>
</section>

<section class="section">
  <div class="wrap prose-grid">
    <article class="prose" data-reveal>
      <?= md_to_html(course_tokens($c['content'], $c)) ?>
    </article>

    <aside class="sticky-card">
      <div class="price-card price-card-light">
        <?php if (course_discounted($c)): ?><p class="price-card-flag"><?= e($promo['label']) ?> · do <?= e(date_short($promo['end'])) ?></p><?php endif; ?>
        <p class="price-card-label">Kurs <?= e($cat) ?></p>
        <p class="price-card-price">
          <span class="price-new"><?= zl(course_price($c)) ?></span>
          <?php if (course_discounted($c)): ?><s class="price-old"><span class="sr-only">zamiast </span><?= zl(course_regular_price($c)) ?></s><?php endif; ?>
        </p>
        <ul class="ticks ticks-dark">
          <li><?= icon('check') ?> <?= (int) $c['theory_hours'] ?> h teorii</li>
          <li><?= icon('check') ?> <?= (int) $c['practice_hours'] ?> h jazd</li>
          <?php if ((int) $c['bonus_hours']): ?><li><?= icon('check') ?> +<?= (int) $c['bonus_hours'] ?> h jazdy gratis</li><?php endif; ?>
          <?php foreach ($includes as $inc): ?><li><?= icon('check') ?> <?= e($inc) ?></li><?php endforeach; ?>
        </ul>
        <a class="btn btn-primary btn-block" href="/kontakt?temat=kurs#zapis">Zapisz się</a>
        <a class="btn btn-outline btn-block" href="<?= e(phone_href()) ?>"><?= icon('phone') ?> <?= e($s['phone']) ?></a>
      </div>
    </aside>
  </div>
</section>

<?= faq_html($faq, 'Pytania o kurs ' . $cat) ?>
