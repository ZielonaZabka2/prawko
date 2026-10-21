<?php
/* Wariant A – „Asfalt”: ciemny hero, karta ceny, auto wjeżdża na drogę. */
$steps = require __DIR__ . '/steps-data.php';
$reasons = require __DIR__ . '/reasons-data.php';
?>
<section class="hero hero-a">
  <div class="wrap hero-grid">
    <div class="hero-copy">
      <p class="kicker anim-load" style="--d:0"><span class="lplate" aria-hidden="true">L</span> Ośrodek szkolenia kierowców w Łomży</p>
      <h1 class="mask-lines">
        <span class="ml"><span class="w" style="--w:1">Nauka</span> <span class="w" style="--w:2">jazdy</span></span>
        <span class="ml"><span class="w" style="--w:3">w</span> <span class="w" style="--w:4">Łomży</span></span>
        <span class="ml h1-sub"><span class="w" style="--w:5">Kurs</span> <span class="w" style="--w:6">prawa</span> <span class="w" style="--w:7">jazdy</span> <span class="w" style="--w:8"><?= e($cat) ?></span></span>
      </h1>
      <p class="lead anim-load" style="--d:4"><?= e(load_data('content')['hero_lead'] ?: (int) $c['theory_hours'] . ' h teorii, ' . (int) $c['practice_hours'] . ' h jazd' . ((int) $c['bonus_hours'] ? ' i ' . (int) $c['bonus_hours'] . ' h gratis' : '') . '. Terminy jazd ustalasz z instruktorem, zapisy telefonicznie albo w biurze przy ul. Dwornej.') ?></p>
      <div class="hero-actions anim-load" style="--d:5">
        <a class="btn btn-primary btn-lg" href="/kontakt#zapis">Zapisz się na kurs <?= icon('arrow') ?></a>
        <a class="btn btn-ghost btn-lg" href="<?= e(phone_href()) ?>"><?= icon('phone') ?> <?= e($s['phone']) ?></a>
      </div>
    </div>
    <div class="anim-load anim-rise" style="--d:3"><?php partial('home/price-card', compact('c', 'promo', 'cat')); ?></div>
  </div>
  <figure class="hero-car hero-car-drive">
    <span class="car-clip"><?= img('kia-rio-szkoleniowa', 'Samochód szkoleniowy ' . ($c['vehicle'] ?: '') . ' w barwach OSK Artur Żyliński', '(min-width: 1200px) 1140px, 100vw', ['eager' => true, 'default_w' => 1024]) ?></span>
    <span class="road road-moving" aria-hidden="true"></span>
  </figure>
</section>

<section class="facts" aria-label="Najważniejsze informacje">
  <div class="wrap facts-grid" data-reveal-group>
    <div class="fact"><span class="fact-num"><?= (int) $c['theory_hours'] ?> h</span><span class="fact-label">teorii z instruktorem</span></div>
    <div class="fact"><span class="fact-num"><?= (int) $c['practice_hours'] ?><?= (int) $c['bonus_hours'] ? '+' . (int) $c['bonus_hours'] : '' ?> h</span><span class="fact-label">jazd<?= (int) $c['bonus_hours'] ? ', w tym godzina gratis' : ' z instruktorem' ?></span></div>
    <div class="fact"><span class="fact-num">~1 mies.</span><span class="fact-label">tyle może trwać cały kurs</span></div>
    <div class="fact"><span class="fact-num"><?= e($c['vehicle'] ?: 'Raty') ?></span><span class="fact-label"><?= $c['vehicle'] ? 'nowoczesne auto szkoleniowe' : 'płatność rozłożona w czasie' ?></span></div>
  </div>
</section>

<?php if (count(courses()) > 1): ?>
<section class="section" aria-labelledby="oferta-a">
  <div class="wrap">
    <p class="eyebrow" data-reveal>Oferta</p>
    <h2 id="oferta-a" data-reveal>Kursy i usługi</h2>
    <?php partial('course-cards', ['list' => courses(), 'withServices' => true]); ?>
  </div>
</section>
<?php else: ?>
<?php partial('home/included', compact('c', 'cat')); ?>
<?php endif; ?>

<div class="lane" aria-hidden="true"></div>

<section class="section section-alt" aria-labelledby="kroki">
  <div class="wrap">
    <h2 id="kroki" data-reveal>Jak zdobyć prawo jazdy? 6 kroków</h2>
    <p class="bluf narrow-text" data-reveal>Od pierwszego telefonu do egzaminu w WORD Łomża prowadzimy Cię przez sześć etapów. Formalności po stronie szkoły bierzemy na siebie, Ty skupiasz się na nauce.</p>
    <ol class="steps" data-reveal-group>
      <?php foreach ($steps as [$t, $d]): ?><li><h3><?= e($t) ?></h3><p><?= $d ?></p></li><?php endforeach; ?>
    </ol>
  </div>
</section>

<section class="section section-dark" aria-labelledby="dlaczego">
  <div class="wrap split">
    <div>
      <p class="eyebrow" data-reveal>Dlaczego ta szkoła</p>
      <h2 id="dlaczego" data-reveal>Jak uczymy w Szkole Bezpiecznej Jazdy</h2>
      <ul class="reasons" data-reveal-group>
        <?php foreach ($reasons as [$t, $d]): ?><li><h3><?= e($t) ?></h3><p><?= e($d) ?></p></li><?php endforeach; ?>
      </ul>
    </div>
    <figure class="photo-frame" data-reveal="clip">
      <?= img('biuro-dworna-lomza', 'Biuro Szkoły Bezpiecznej Jazdy przy ul. Dworna 1 w Łomży', '(min-width: 960px) 520px, 100vw') ?>
      <figcaption>Biuro szkoły: <?= e($s['street']) ?>, Łomża</figcaption>
    </figure>
  </div>
</section>

<?php if (count(courses()) > 1) { partial('home/included', compact('c', 'cat')); } ?>

<section class="section" aria-labelledby="auto">
  <div class="wrap">
    <div class="car-block">
      <div class="car-copy" data-reveal>
        <h2 id="auto">Dwie Kie Rio do nauki jazdy</h2>
        <p>Jeździmy białą i granatową Kią Rio IV. To auta miejskie z dobrą widocznością, łatwo wyczuć ich wymiary przy parkowaniu i na placu. Dwa samochody to też więcej wolnych terminów jazd.</p>
        <ul class="ticks ticks-dark">
          <li><?= icon('check') ?> podwójne pedały i dodatkowe lusterka dla instruktora</li>
          <li><?= icon('check') ?> auto z segmentu B, typowe dla egzaminów na <?= e($cat) ?></li>
          <li><?= icon('check') ?> regularnie serwisowane</li>
        </ul>
      </div>
      <div class="car-gallery" data-reveal-group>
        <figure class="car-main"><?= img('flota-pod-biurem', 'Dwa samochody szkoleniowe Kia Rio przed biurem szkoły jazdy', '(min-width: 960px) 640px, 100vw') ?></figure>
        <figure><?= img('kia-biala-przod', 'Biała Kia Rio do nauki jazdy', '(min-width: 960px) 320px, 50vw') ?></figure>
        <figure><?= img('kia-granatowa-przod', 'Granatowa Kia Rio do nauki jazdy', '(min-width: 960px) 320px, 50vw') ?></figure>
      </div>
    </div>
  </div>
</section>

<?php partial('home/stay'); ?>
<?php partial('home/reviews'); ?>
<?= faq_html($faq, 'Pytania, które słyszymy najczęściej') ?>
<?php partial('home/contact'); ?>
