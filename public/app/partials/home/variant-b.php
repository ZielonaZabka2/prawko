<?php
/* Wariant B – „Trasa”: jasny, redakcyjny; cena jak znak drogowy, kroki jako trasa. */
$steps = require __DIR__ . '/steps-data.php';
$reasons = require __DIR__ . '/reasons-data.php';
?>
<section class="hero-b">
  <div class="wrap hb-grid">
    <div class="hb-copy">
      <p class="kicker kicker-light anim-load" style="--d:0"><span class="lplate" aria-hidden="true">L</span> OSK Artur Żyliński · ul. Dworna 1</p>
      <h1 class="hb-title mask-lines">
        <span class="ml"><span style="--d:1">Nauka jazdy</span></span>
        <span class="ml"><span style="--d:2">w&nbsp;Łomży<svg class="hb-swoosh" viewBox="0 0 300 24" preserveAspectRatio="none" aria-hidden="true"><path d="M3 17 C 80 5, 190 4, 297 12" /></svg></span></span>
      </h1>
      <p class="hb-sub anim-load" style="--d:3">Kurs prawa jazdy <?= e($cat) ?> od pierwszej lekcji do egzaminu w WORD. Spokojnie, konkretnie i w terminach, które pasują do Twojego dnia.</p>
      <div class="hero-actions anim-load" style="--d:4">
        <a class="btn btn-primary btn-lg" href="/kontakt#zapis">Zapisz się na kurs <?= icon('arrow') ?></a>
        <a class="btn btn-outline btn-lg" href="<?= e(phone_href()) ?>"><?= icon('phone') ?> <?= e($s['phone']) ?></a>
      </div>
      <dl class="hb-posts anim-load" style="--d:5">
        <div><dt>Teoria</dt><dd><?= (int) $c['theory_hours'] ?> h</dd></div>
        <div><dt>Jazdy</dt><dd><?= (int) $c['practice_hours'] ?><?= (int) $c['bonus_hours'] ? '+' . (int) $c['bonus_hours'] : '' ?> h</dd></div>
        <div><dt>Cały kurs</dt><dd>~1 mies.</dd></div>
      </dl>
    </div>
    <div class="hb-visual">
      <figure class="hb-photo anim-load anim-clip" style="--d:1">
        <?= img('biuro-front', 'Biuro Szkoły Bezpiecznej Jazdy przy ul. Dworna 1 w Łomży', '(min-width: 1000px) 460px, 90vw', ['eager' => true, 'default_w' => 960]) ?>
      </figure>
      <a class="hb-sign anim-load anim-pop" style="--d:4" href="<?= e(course_url($c)) ?>" aria-label="Kurs <?= e($cat) ?>: <?= e(zl(course_price($c))) ?>">
        <span class="hb-sign-cat"><?= e($c['category']) ?></span>
        <span class="hb-sign-body">
          <?php if (course_discounted($c)): ?><span class="hb-sign-flag"><?= e($promo['label']) ?></span><?php endif; ?>
          <span class="hb-sign-price"><?= zl(course_price($c)) ?></span>
          <?php if (course_discounted($c)): ?><s class="price-old"><?= zl(course_regular_price($c)) ?></s><span class="hb-sign-until">do <?= e(date_short($promo['end'])) ?></span><?php else: ?><span class="hb-sign-until">cały kurs</span><?php endif; ?>
        </span>
      </a>
    </div>
  </div>
</section>

<section class="section" aria-labelledby="oferta-b">
  <div class="wrap">
    <div class="section-head" data-reveal>
      <div><p class="eyebrow">Oferta</p><h2 id="oferta-b">Wybierz, od czego zaczynasz</h2></div>
      <a class="text-link" href="/cennik">Pełny cennik <?= icon('arrow', 'icon icon-sm') ?></a>
    </div>
    <?php partial('course-cards', ['list' => courses(), 'withServices' => true]); ?>
  </div>
</section>

<section class="section section-alt route-section" aria-labelledby="trasa">
  <div class="wrap route-grid">
    <div class="route-intro">
      <p class="eyebrow" data-reveal>Trasa do prawa jazdy</p>
      <h2 id="trasa" data-reveal>Sześć przystanków od telefonu do egzaminu</h2>
      <p class="bluf" data-reveal>Formalności po stronie szkoły bierzemy na siebie. Ty skupiasz się na nauce, a my pilnujemy kolejności.</p>
      <a class="btn btn-primary" href="/kontakt#zapis" data-reveal>Zacznij od pierwszego kroku</a>
    </div>
    <div class="route" data-route>
      <span class="route-road" aria-hidden="true"><span class="route-fill"></span></span>
      <ol class="route-list">
      <?php foreach ($steps as $i => [$t, $d]): ?>
        <li class="route-stop" data-reveal>
          <span class="route-pin" aria-hidden="true"><?= $i + 1 ?></span>
          <h3><?= e($t) ?></h3>
          <p><?= $d ?></p>
        </li>
      <?php endforeach; ?>
      </ol>
    </div>
  </div>
</section>

<section class="section" aria-labelledby="dlaczego-b">
  <div class="wrap">
    <p class="eyebrow" data-reveal>Dlaczego my</p>
    <h2 id="dlaczego-b" class="h2-wide" data-reveal>Uczymy bezpiecznej jazdy, nie tylko zdawania egzaminu</h2>
    <ol class="numbered" data-reveal-group>
      <?php foreach ($reasons as $i => [$t, $d]): ?>
        <li><span class="num"><?= sprintf('%02d', $i + 1) ?></span><h3><?= e($t) ?></h3><p><?= e($d) ?></p></li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>

<section class="photo-duo" aria-label="Biuro i samochód szkoleniowy">
  <figure data-reveal="clip"><?= img('biuro-dworna-lomza', 'Wejście do biura szkoły jazdy przy ul. Dworna 1', '(min-width: 760px) 50vw, 100vw') ?><figcaption>Biuro · <?= e($s['street']) ?></figcaption></figure>
  <figure data-reveal="clip"><?= img('kia-rio-tyl', 'Samochód szkoleniowy z oznaczeniem L', '(min-width: 760px) 50vw, 100vw') ?><figcaption>Auto szkoleniowe · <?= e($c['vehicle'] ?: 'kat. ' . $c['category']) ?></figcaption></figure>
</section>

<?php partial('home/included', compact('c', 'cat')); ?>
<?php partial('home/stay'); ?>
<div class="faq-split"><?= faq_html($faq, 'Pytania, które słyszymy najczęściej') ?></div>
<?php partial('home/contact'); ?>
