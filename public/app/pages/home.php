<?php
$s = settings();
$p = prices();
$promo = promo();
$faq = faq_items('home');
$meta = [
    'title' => 'Nauka jazdy Łomża – kurs prawa jazdy kat. B | OSK Żyliński',
    'description' => 'Szkoła jazdy w Łomży: kurs prawa jazdy kat. B za ' . zl(course_price()) . ', jazdy doszkalające ' . zl((int) $p['extra_hour']) . '/h, raty i mieszkanie dla kursantów. ul. Dworna 1. Zadzwoń: ' . $s['phone'] . '.',
    'path' => '/',
    'schema' => [schema_faq($faq), schema_person()],
];
?>
<section class="hero">
  <div class="wrap hero-grid">
    <div class="hero-copy">
      <p class="kicker"><span class="lplate" aria-hidden="true">L</span> Ośrodek szkolenia kierowców w Łomży</p>
      <h1>Nauka jazdy w Łomży <span class="h1-sub">Kurs prawa jazdy kat. B</span></h1>
      <p class="lead">Przygotujemy Cię do egzaminu w WORD i do jazdy, która zaczyna się dzień po nim. Spokojnie, bez krzyku, w terminach dopasowanych do Twojego planu dnia.</p>
      <div class="hero-actions">
        <a class="btn btn-primary btn-lg" href="/kontakt#zapis">Zapisz się na kurs <?= icon('arrow') ?></a>
        <a class="btn btn-ghost btn-lg" href="<?= e(phone_href()) ?>"><?= icon('phone') ?> <?= e($s['phone']) ?></a>
      </div>
    </div>

    <aside class="price-card" aria-label="Cena kursu kat. B">
      <?php if (course_discounted()): ?>
        <p class="price-card-flag"><?= e($promo['label']) ?> · do <?= e(date_short($promo['end'])) ?></p>
      <?php endif; ?>
      <p class="price-card-label">Kurs prawa jazdy kat. B</p>
      <p class="price-card-price">
        <span class="price-new"><?= zl(course_price()) ?></span>
        <?php if (course_discounted()): ?><s class="price-old"><span class="sr-only">zamiast </span><?= zl(course_regular_price()) ?></s><?php endif; ?>
      </p>
      <ul class="ticks">
        <li><?= icon('check') ?> <?= (int) $p['theory_hours'] ?> h zajęć teoretycznych</li>
        <li><?= icon('check') ?> <?= (int) $p['practice_hours'] ?> h jazd z instruktorem</li>
        <?php if ((int) $p['bonus_hours'] > 0): ?><li><?= icon('check') ?> <strong>+<?= (int) $p['bonus_hours'] ?> h jazdy gratis</strong></li><?php endif; ?>
        <li><?= icon('check') ?> materiały szkoleniowe w cenie</li>
        <?php if (!empty($p['installments'])): ?><li><?= icon('check') ?> możliwość płatności w ratach</li><?php endif; ?>
      </ul>
      <a class="price-card-link" href="/cennik">Pełny cennik <?= icon('arrow', 'icon icon-sm') ?></a>
    </aside>
  </div>

  <figure class="hero-car">
    <?= img('kia-rio-szkoleniowa', 'Samochód szkoleniowy Kia Rio IV w barwach OSK Artur Żyliński', '(min-width: 1200px) 1140px, 100vw', ['eager' => true, 'default_w' => 1024]) ?>
  </figure>
</section>

<section class="facts" aria-label="Najważniejsze informacje">
  <div class="wrap facts-grid">
    <div class="fact"><span class="fact-num"><?= (int) $p['theory_hours'] ?> h</span><span class="fact-label">teorii z instruktorem</span></div>
    <div class="fact"><span class="fact-num"><?= (int) $p['practice_hours'] ?><?= (int) $p['bonus_hours'] ? '+' . (int) $p['bonus_hours'] : '' ?> h</span><span class="fact-label">jazd, w tym godzina gratis</span></div>
    <div class="fact"><span class="fact-num">~1 mies.</span><span class="fact-label">tyle może trwać cały kurs</span></div>
    <div class="fact"><span class="fact-num">Kia Rio IV</span><span class="fact-label">nowoczesne auto szkoleniowe</span></div>
  </div>
</section>

<section class="section" aria-labelledby="w-cenie">
  <div class="wrap split">
    <div>
      <p class="eyebrow">Co obejmuje kurs</p>
      <h2 id="w-cenie">Co dostajesz w cenie kursu kat. B?</h2>
      <p class="bluf">W cenie <?= zl(course_price()) ?> masz komplet szkolenia: <?= (int) $p['theory_hours'] ?> godzin teorii, <?= (int) $p['practice_hours'] ?> godzin jazd z instruktorem<?= (int) $p['bonus_hours'] ? ', godzinę jazdy gratis' : '' ?>, materiały szkoleniowe i egzamin wewnętrzny. Nie dopłacasz za nic, co jest potrzebne do ukończenia kursu.</p>
      <p>Poza ceną kursu zostają tylko dwie rzeczy, które opłacasz u innych instytucji: badanie lekarskie i opłaty za egzamin państwowy w WORD. Mówimy o tym od razu, żeby nie było niespodzianek.</p>
      <a class="text-link" href="/kurs-prawa-jazdy-kat-b">Jak wygląda kurs krok po kroku <?= icon('arrow', 'icon icon-sm') ?></a>
    </div>
    <div class="table-wrap">
      <table class="offer-table">
        <caption class="sr-only">Zakres kursu prawa jazdy kat. B</caption>
        <thead><tr><th scope="col">Element kursu</th><th scope="col">W cenie</th></tr></thead>
        <tbody>
          <tr><th scope="row">Zajęcia teoretyczne</th><td><?= (int) $p['theory_hours'] ?> h</td></tr>
          <tr><th scope="row">Jazdy z instruktorem</th><td><?= (int) $p['practice_hours'] ?> h</td></tr>
          <?php if ((int) $p['bonus_hours']): ?><tr><th scope="row">Dodatkowa jazda</th><td><?= (int) $p['bonus_hours'] ?> h gratis</td></tr><?php endif; ?>
          <tr><th scope="row">Materiały szkoleniowe</th><td>tak</td></tr>
          <tr><th scope="row">Egzamin wewnętrzny</th><td>tak</td></tr>
          <tr><th scope="row">Płatność w ratach</th><td><?= !empty($p['installments']) ? 'możliwa' : 'zapytaj' ?></td></tr>
          <tr class="row-total"><th scope="row">Cena kursu</th><td><?= zl(course_price()) ?></td></tr>
        </tbody>
      </table>
    </div>
  </div>
</section>

<div class="lane" aria-hidden="true"></div>

<section class="section section-alt" aria-labelledby="kroki">
  <div class="wrap">
    <p class="eyebrow">Przebieg</p>
    <h2 id="kroki">Jak zdobyć prawo jazdy? 6 kroków</h2>
    <p class="bluf narrow-text">Od pierwszego telefonu do egzaminu w WORD Łomża prowadzimy Cię przez sześć etapów. Formalności po stronie szkoły bierzemy na siebie, Ty skupiasz się na nauce.</p>
    <ol class="steps">
      <li><h3>Badanie lekarskie</h3><p>U lekarza uprawnionego do badania kierowców. Dostajesz orzeczenie potrzebne do wniosku.</p></li>
      <li><h3>Numer PKK</h3><p>Składasz wniosek w wydziale komunikacji i dostajesz profil kandydata na kierowcę. <a href="/blog/profil-kandydata-na-kierowce-pkk-lomza">Jak to zrobić</a></p></li>
      <li><h3>Zapis i teoria</h3><p>Podpisujemy umowę, ustalamy terminy. Zajęcia teoretyczne: przepisy, znaki, sytuacje na drodze.</p></li>
      <li><h3>Jazdy</h3><p>Najpierw plac manewrowy, potem miasto i trasy poza Łomżą. Godziny ustalamy pod Ciebie.</p></li>
      <li><h3>Egzamin wewnętrzny</h3><p>Próba generalna: teoria i jazda. Po nim potwierdzamy ukończenie kursu w systemie.</p></li>
      <li><h3>Egzamin w WORD</h3><p>Zapisujesz się na egzamin państwowy. Przed nim możesz dobrać <a href="/jazdy-doszkalajace">jazdy doszkalające</a>.</p></li>
    </ol>
  </div>
</section>

<section class="section section-dark" aria-labelledby="dlaczego">
  <div class="wrap split split-reverse">
    <div>
      <p class="eyebrow">Dlaczego ta szkoła</p>
      <h2 id="dlaczego">Uczymy bezpiecznej jazdy, nie tylko zdawania egzaminu</h2>
      <ul class="reasons">
        <li><h3>Indywidualne podejście</h3><p>Tempo nauki dopasowane do Ciebie. Jeśli coś nie wychodzi, wracamy do tego, aż zacznie.</p></li>
        <li><h3>Cierpliwy, doświadczony instruktor</h3><p>Spokojna atmosfera w aucie. Stres to najgorszy nauczyciel, więc go nie dokładamy.</p></li>
        <li><h3>Elastyczne terminy jazd</h3><p>Rano, po szkole, po pracy. Plan jazd układamy razem, a nie z góry narzuconego grafiku.</p></li>
        <li><h3>Szybka realizacja kursu</h3><p>Masz czas jeździć częściej? Cały kurs można zamknąć nawet w około miesiąc.</p></li>
        <li><h3>Pomoc na każdym etapie</h3><p>Od wniosku o PKK po termin w WORD. Podpowiemy, co i kiedy załatwić.</p></li>
        <li><h3>Jazdy także po kursie</h3><p>Doszkalamy kursantów przed egzaminem i kierowców, którzy chcą wrócić za kierownicę.</p></li>
      </ul>
    </div>
    <figure class="photo-frame">
      <?= img('biuro-dworna-lomza', 'Biuro Szkoły Bezpiecznej Jazdy przy ul. Dworna 1 w Łomży', '(min-width: 960px) 520px, 100vw') ?>
      <figcaption>Biuro szkoły: <?= e($s['street']) ?>, Łomża</figcaption>
    </figure>
  </div>
</section>

<section class="section" aria-labelledby="auto">
  <div class="wrap">
    <div class="car-block">
      <div class="car-copy">
        <p class="eyebrow">Samochód szkoleniowy</p>
        <h2 id="auto">Jeździsz Kią Rio IV</h2>
        <p>Nowoczesne, zwrotne auto miejskie z dobrą widocznością dookoła. Łatwo wyczuć jego wymiary, więc szybciej opanujesz parkowanie i manewry na placu.</p>
        <ul class="ticks ticks-dark">
          <li><?= icon('check') ?> podwójne pedały i dodatkowe lusterka dla instruktora</li>
          <li><?= icon('check') ?> auto z segmentu B, typowe dla egzaminów na kat. B</li>
          <li><?= icon('check') ?> regularnie serwisowane</li>
        </ul>
      </div>
      <figure class="car-photo">
        <?= img('kia-rio-tyl', 'Kia Rio IV – samochód do nauki jazdy z oznaczeniem L', '(min-width: 960px) 640px, 100vw') ?>
      </figure>
    </div>
  </div>
</section>

<section class="section section-alt" aria-labelledby="noclegi">
  <div class="wrap">
    <div class="section-head">
      <div>
        <p class="eyebrow">Kurs z zakwaterowaniem</p>
        <h2 id="noclegi">Mieszkasz daleko? Zamieszkaj w Łomży na czas kursu</h2>
      </div>
      <a class="text-link" href="/noclegi-dla-kursantow">Zobacz mieszkanie <?= icon('arrow', 'icon icon-sm') ?></a>
    </div>
    <p class="bluf narrow-text">Kursantom spoza Łomży wynajmujemy komfortowe mieszkanie z dwoma pokojami: <?= zl((int) $p['apartment_day']) ?> za dobę albo <?= zl((int) $p['apartment_2weeks']) ?> za dwa tygodnie. Razem z intensywnym kursem to najszybsza droga do prawa jazdy.</p>
    <div class="mosaic">
      <a href="/noclegi-dla-kursantow" class="mosaic-main"><?= img('mieszkanie-salon', 'Salon z aneksem kuchennym w mieszkaniu dla kursantów', '(min-width: 960px) 640px, 100vw') ?></a>
      <a href="/noclegi-dla-kursantow"><?= img('mieszkanie-sypialnia', 'Sypialnia z podwójnym łóżkiem', '(min-width: 960px) 320px, 50vw') ?></a>
      <a href="/noclegi-dla-kursantow"><?= img('mieszkanie-jadalnia', 'Jadalnia i część wypoczynkowa', '(min-width: 960px) 320px, 50vw') ?></a>
    </div>
  </div>
</section>

<?= faq_html($faq, 'Pytania, które słyszymy najczęściej') ?>

<section class="section section-dark cta-section" id="zapis-home" aria-labelledby="zapis-h">
  <div class="wrap split">
    <div>
      <p class="eyebrow">Zapisy</p>
      <h2 id="zapis-h">Zostaw numer – oddzwonimy i ustalimy start kursu</h2>
      <p>Wolisz porozmawiać od razu? Zadzwoń. Zapisy przyjmujemy też osobiście w biurze przy ul. Dworna 1.</p>
      <ul class="contact-list">
        <li><?= icon('phone') ?> <a href="<?= e(phone_href()) ?>"><?= e($s['phone']) ?></a></li>
        <li><?= icon('mail') ?> <a href="mailto:<?= e($s['email']) ?>"><?= e($s['email']) ?></a></li>
        <li><?= icon('pin') ?> <span><?= e(full_address()) ?></span></li>
      </ul>
    </div>
    <?php partial('contact-form', ['compact' => true]); ?>
  </div>
</section>
