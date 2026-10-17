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
        <span class="ml"><span style="--d:1">Nauka jazdy</span></span>
        <span class="ml"><span style="--d:2">w Łomży</span></span>
        <span class="ml h1-sub"><span style="--d:3">Kurs prawa jazdy <?= e($cat) ?></span></span>
      </h1>
      <p class="lead anim-load" style="--d:4">Przygotujemy Cię do egzaminu w WORD i do jazdy, która zaczyna się dzień po nim. Spokojnie, bez krzyku, w terminach dopasowanych do Twojego planu dnia.</p>
      <div class="hero-actions anim-load" style="--d:5">
        <a class="btn btn-primary btn-lg" href="/kontakt#zapis">Zapisz się na kurs <?= icon('arrow') ?></a>
        <a class="btn btn-ghost btn-lg" href="<?= e(phone_href()) ?>"><?= icon('phone') ?> <?= e($s['phone']) ?></a>
      </div>
    </div>
    <div class="anim-load anim-rise" style="--d:3"><?php partial('home/price-card', compact('c', 'promo', 'cat')); ?></div>
  </div>
  <figure class="hero-car hero-car-drive">
    <?= img('kia-rio-szkoleniowa', 'Samochód szkoleniowy ' . ($c['vehicle'] ?: '') . ' w barwach OSK Artur Żyliński', '(min-width: 1200px) 1140px, 100vw', ['eager' => true, 'default_w' => 1024]) ?>
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
    <p class="eyebrow" data-reveal>Przebieg</p>
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
      <h2 id="dlaczego" data-reveal>Uczymy bezpiecznej jazdy, nie tylko zdawania egzaminu</h2>
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
        <p class="eyebrow">Samochód szkoleniowy</p>
        <h2 id="auto">Jeździsz <?= e($c['vehicle'] ?: 'nowoczesnym autem') ?></h2>
        <p>Nowoczesne, zwrotne auto miejskie z dobrą widocznością dookoła. Łatwo wyczuć jego wymiary, więc szybciej opanujesz parkowanie i manewry na placu.</p>
        <ul class="ticks ticks-dark">
          <li><?= icon('check') ?> podwójne pedały i dodatkowe lusterka dla instruktora</li>
          <li><?= icon('check') ?> auto z segmentu B, typowe dla egzaminów na <?= e($cat) ?></li>
          <li><?= icon('check') ?> regularnie serwisowane</li>
        </ul>
      </div>
      <figure class="car-photo" data-reveal="right">
        <?= img('kia-rio-tyl', 'Samochód do nauki jazdy z oznaczeniem L', '(min-width: 960px) 640px, 100vw') ?>
      </figure>
    </div>
  </div>
</section>

<?php partial('home/stay'); ?>
<?= faq_html($faq, 'Pytania, które słyszymy najczęściej') ?>
<?php partial('home/contact'); ?>
