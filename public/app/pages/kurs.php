<?php
$s = settings();
$p = prices();
$promo = promo();
$faq = faq_items('kurs');
$crumbs = [['Kurs prawa jazdy kat. B', '/kurs-prawa-jazdy-kat-b']];
$meta = [
    'title' => 'Kurs prawa jazdy kat. B Łomża – ' . zl(course_price()) . ' | OSK Żyliński',
    'description' => 'Kurs prawa jazdy kat. B w Łomży: ' . $p['theory_hours'] . ' h teorii, ' . $p['practice_hours'] . ' h jazd + godzina gratis, raty, Kia Rio IV. Przebieg kursu, formalności i cena ' . zl(course_price()) . '.',
    'path' => '/kurs-prawa-jazdy-kat-b',
    'breadcrumbs' => $crumbs,
    'schema' => [
        schema_service('Kurs prawa jazdy kategorii B', '/kurs-prawa-jazdy-kat-b', 'Pełny kurs prawa jazdy kat. B w Łomży: teoria, jazdy, egzamin wewnętrzny.', schema_offers()[0]),
        schema_faq($faq),
    ],
];
?>
<section class="page-hero">
  <div class="wrap">
    <?= breadcrumbs_html($crumbs) ?>
    <div class="page-hero-grid">
      <div>
        <h1>Kurs prawa jazdy kat. B w Łomży</h1>
        <p class="lead">Pełne szkolenie od pierwszej lekcji teorii do egzaminu wewnętrznego. Cena kursu: <strong><?= zl(course_price()) ?></strong><?= course_discounted() ? ' (promocja do ' . e(date_pl($promo['end'])) . ', regularnie ' . zl(course_regular_price()) . ')' : '' ?>.</p>
        <div class="hero-actions">
          <a class="btn btn-primary btn-lg" href="/kontakt?temat=kurs#zapis">Zapisz się <?= icon('arrow') ?></a>
          <a class="btn btn-ghost btn-lg" href="<?= e(phone_href()) ?>"><?= icon('phone') ?> <?= e($s['phone']) ?></a>
        </div>
      </div>
      <ul class="hero-points">
        <li><?= icon('book') ?><span><strong><?= (int) $p['theory_hours'] ?> h</strong> teorii</span></li>
        <li><?= icon('wheel') ?><span><strong><?= (int) $p['practice_hours'] ?> h</strong> jazd<?= (int) $p['bonus_hours'] ? ' + ' . (int) $p['bonus_hours'] . ' h gratis' : '' ?></span></li>
        <li><?= icon('wallet') ?><span><strong>raty</strong> do ustalenia</span></li>
        <li><?= icon('calendar') ?><span><strong>~1 miesiąc</strong> przy intensywnym trybie</span></li>
      </ul>
    </div>
  </div>
</section>

<section class="section">
  <div class="wrap prose-grid">
    <article class="prose">
      <h2>Ile kosztuje kurs prawa jazdy kat. B?</h2>
      <p class="bluf">Kurs kat. B w OSK Artur Żyliński kosztuje <?= zl(course_price()) ?> i obejmuje <?= (int) $p['theory_hours'] ?> godzin zajęć teoretycznych, <?= (int) $p['practice_hours'] ?> godzin jazd<?= (int) $p['bonus_hours'] ? ', dodatkową godzinę jazdy gratis' : '' ?>, materiały szkoleniowe i egzamin wewnętrzny. <?= !empty($p['installments']) ? 'Płatność możesz rozłożyć na raty.' : '' ?></p>
      <p>Poza kursem opłacasz badanie lekarskie (u lekarza uprawnionego do badań kierowców) oraz egzamin państwowy w WORD. Te opłaty trafiają do tych instytucji, a nie do szkoły jazdy.</p>

      <h2>Jak wyglądają zajęcia teoretyczne?</h2>
      <p class="bluf">Teoria to <?= (int) $p['theory_hours'] ?> godzin zajęć z instruktorem: przepisy ruchu drogowego, znaki, pierwszeństwo, zachowanie w trudnych sytuacjach i podstawy pierwszej pomocy.</p>
      <p>Nie uczymy wyłącznie „pod test”. Tłumaczymy, dlaczego przepis jest taki, a nie inny, bo wtedy łatwiej go zapamiętać i zastosować na skrzyżowaniu, a nie tylko na ekranie egzaminacyjnym.</p>

      <h2>Jak wyglądają jazdy?</h2>
      <p class="bluf">Jazdy odbywają się Kią Rio IV. Zaczynamy od placu manewrowego, potem przechodzimy do ruchu miejskiego w Łomży i tras poza miastem, także w okolicy WORD.</p>
      <ol>
        <li><strong>Plac manewrowy</strong> – ruszanie, hamowanie, jazda po łuku, parkowanie.</li>
        <li><strong>Spokojne ulice</strong> – obserwacja, pierwszeństwo, zmiana pasa.</li>
        <li><strong>Ruch miejski</strong> – ronda, skrzyżowania ze światłami, przejścia dla pieszych.</li>
        <li><strong>Trasy poza miastem</strong> – większe prędkości, wyprzedzanie, jazda po zmroku.</li>
        <li><strong>Trasy egzaminacyjne</strong> – miejsca, w których kursanci najczęściej popełniają błędy.</li>
      </ol>

      <h2>Jak szybko można zrobić kurs?</h2>
      <p class="bluf">Przy jazdach kilka razy w tygodniu cały kurs da się zrealizować w około miesiąc. Tempo zależy od Ciebie – możesz też jeździć rzadziej, np. tylko w weekendy.</p>
      <p>Jeśli mieszkasz poza Łomżą, połącz intensywny kurs z pobytem w <a href="/noclegi-dla-kursantow">mieszkaniu dla kursantów</a>.</p>

      <h2>Co trzeba mieć, żeby zacząć?</h2>
      <p class="bluf">Do rozpoczęcia jazd potrzebny jest numer PKK, czyli profil kandydata na kierowcę. Uzyskasz go w wydziale komunikacji po badaniu lekarskim.</p>
      <ul>
        <li>orzeczenie lekarskie o braku przeciwwskazań do kierowania pojazdami,</li>
        <li>fotografia i dowód osobisty,</li>
        <li>zgoda rodzica lub opiekuna, jeśli nie masz 18 lat.</li>
      </ul>
      <p>Dokładną instrukcję znajdziesz w artykule <a href="/blog/profil-kandydata-na-kierowce-pkk-lomza">jak wyrobić PKK w Łomży</a>. Miejsce na kursie możesz zarezerwować, zanim urząd nada numer.</p>
    </article>

    <aside class="sticky-card">
      <div class="price-card price-card-light">
        <?php if (course_discounted()): ?><p class="price-card-flag"><?= e($promo['label']) ?> · do <?= e(date_short($promo['end'])) ?></p><?php endif; ?>
        <p class="price-card-label">Kurs kat. B</p>
        <p class="price-card-price">
          <span class="price-new"><?= zl(course_price()) ?></span>
          <?php if (course_discounted()): ?><s class="price-old"><span class="sr-only">zamiast </span><?= zl(course_regular_price()) ?></s><?php endif; ?>
        </p>
        <ul class="ticks ticks-dark">
          <li><?= icon('check') ?> <?= (int) $p['theory_hours'] ?> h teorii</li>
          <li><?= icon('check') ?> <?= (int) $p['practice_hours'] ?> h jazd</li>
          <?php if ((int) $p['bonus_hours']): ?><li><?= icon('check') ?> +<?= (int) $p['bonus_hours'] ?> h jazdy gratis</li><?php endif; ?>
          <li><?= icon('check') ?> materiały gratis</li>
        </ul>
        <a class="btn btn-primary btn-block" href="/kontakt?temat=kurs#zapis">Zapisz się</a>
        <a class="btn btn-outline btn-block" href="<?= e(phone_href()) ?>"><?= icon('phone') ?> <?= e($s['phone']) ?></a>
      </div>
    </aside>
  </div>
</section>

<?= faq_html($faq, 'Pytania o kurs kat. B') ?>
