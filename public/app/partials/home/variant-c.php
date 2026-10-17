<?php
/* Wariant C – „Pas startowy”: pełnoekranowe zdjęcie, pasek przewijany, liczniki, trasa pozioma. */
$steps = require __DIR__ . '/steps-data.php';
$reasons = require __DIR__ . '/reasons-data.php';
$ticker = array_filter([
    'Kurs ' . $cat,
    (int) $c['theory_hours'] . ' h teorii',
    (int) $c['practice_hours'] . ' h jazd',
    (int) $c['bonus_hours'] ? '+' . (int) $c['bonus_hours'] . ' h gratis' : '',
    !empty($p['installments']) ? 'Raty' : '',
    $c['vehicle'],
    'Jazdy doszkalające',
    'Noclegi dla kursantów',
    'Łomża · ul. Dworna 1',
]);
?>
<section class="hero-c">
  <div class="hc-media">
    <?= img('kia-rio-szkoleniowa', 'Samochód szkoleniowy OSK Artur Żyliński z tablicą L', '(min-width: 760px) 76vw, 100vw', ['eager' => true, 'default_w' => 1024, 'class' => 'hc-img']) ?>
  </div>
  <div class="wrap hc-in">
    <p class="kicker anim-load" style="--d:0"><span class="lplate" aria-hidden="true">L</span> Szkoła Bezpiecznej Jazdy · Łomża</p>
    <h1 class="hc-title mask-lines">
      <span class="ml"><span style="--d:1">Nauka jazdy</span></span>
      <span class="ml"><span style="--d:2">w Łomży</span></span>
    </h1>
    <p class="hc-lead anim-load" style="--d:3">Kurs prawa jazdy <?= e($cat) ?> za <strong><?= zl(course_price($c)) ?></strong><?php if (course_discounted($c)): ?> <s class="price-old"><?= zl(course_regular_price($c)) ?></s> <span class="chip"><?= e($promo['label']) ?> do <?= e(date_short($promo['end'])) ?></span><?php endif; ?></p>
    <div class="hero-actions anim-load" style="--d:4">
      <a class="btn btn-primary btn-lg" href="/kontakt#zapis">Zapisz się <?= icon('arrow') ?></a>
      <a class="btn btn-ghost btn-lg" href="<?= e(phone_href()) ?>"><?= icon('phone') ?> <?= e($s['phone']) ?></a>
    </div>
  </div>
</section>

<div class="ticker" aria-hidden="true">
  <div class="ticker-track">
    <?php for ($k = 0; $k < 2; $k++): ?>
      <span class="ticker-set"><?php foreach ($ticker as $t): ?><span><?= e($t) ?></span><?php endforeach; ?></span>
    <?php endfor; ?>
  </div>
</div>

<section class="stats" aria-label="Kurs w liczbach">
  <div class="wrap stats-grid" data-reveal-group>
    <div><span class="stat-num" data-count="<?= (int) $c['theory_hours'] ?>"><?= (int) $c['theory_hours'] ?></span><span class="stat-unit">h</span><p>zajęć teoretycznych</p></div>
    <div><span class="stat-num" data-count="<?= (int) $c['practice_hours'] ?>"><?= (int) $c['practice_hours'] ?></span><span class="stat-unit">h</span><p>jazd z instruktorem<?= (int) $c['bonus_hours'] ? ' + ' . (int) $c['bonus_hours'] . ' h gratis' : '' ?></p></div>
    <div><span class="stat-num">~1</span><span class="stat-unit">mies.</span><p>może trwać cały kurs</p></div>
    <div><span class="stat-num" data-count="<?= (int) $p['extra_hour'] ?>"><?= (int) $p['extra_hour'] ?></span><span class="stat-unit">zł</span><p>za godzinę doszkalania</p></div>
  </div>
</section>

<section class="section" aria-labelledby="oferta-c">
  <div class="wrap">
    <div class="section-head" data-reveal>
      <div><p class="eyebrow">Oferta</p><h2 id="oferta-c">Kursy i usługi</h2></div>
      <a class="text-link" href="/cennik">Cennik <?= icon('arrow', 'icon icon-sm') ?></a>
    </div>
    <?php partial('course-cards', ['list' => courses(), 'withServices' => true]); ?>
  </div>
</section>

<section class="section section-dark drive-section" aria-labelledby="kroki-c">
  <div class="wrap">
    <p class="eyebrow" data-reveal>Przebieg</p>
    <h2 id="kroki-c" data-reveal>Od telefonu do egzaminu w 6 krokach</h2>
    <div class="drive" data-drive>
      <div class="drive-road" aria-hidden="true"><span class="drive-car"><?= icon('car', 'icon') ?></span></div>
      <ol class="drive-steps" data-reveal-group>
        <?php foreach ($steps as $i => [$t, $d]): ?><li><span class="drive-n"><?= $i + 1 ?></span><h3><?= e($t) ?></h3><p><?= $d ?></p></li><?php endforeach; ?>
      </ol>
    </div>
  </div>
</section>

<section class="section" aria-labelledby="dlaczego-c">
  <div class="wrap split">
    <figure class="photo-tilt" data-reveal="clip">
      <?= img('biuro-dworna-lomza', 'Biuro Szkoły Bezpiecznej Jazdy przy ul. Dworna 1 w Łomży', '(min-width: 960px) 560px, 100vw') ?>
      <figcaption><?= icon('pin', 'icon icon-sm') ?> <?= e($s['street']) ?>, Łomża</figcaption>
    </figure>
    <div>
      <p class="eyebrow" data-reveal>Dlaczego my</p>
      <h2 id="dlaczego-c" data-reveal>Spokojnie, konkretnie, bez krzyku</h2>
      <ul class="checklist" data-reveal-group>
        <?php foreach ($reasons as [$t, $d]): ?><li><?= icon('check') ?><div><strong><?= e($t) ?></strong><span><?= e($d) ?></span></div></li><?php endforeach; ?>
      </ul>
    </div>
  </div>
</section>

<?php partial('home/stay'); ?>
<?php partial('home/included', compact('c', 'cat')); ?>
<div class="faq-split"><?= faq_html($faq, 'Pytania, które słyszymy najczęściej') ?></div>
<?php partial('home/contact'); ?>
