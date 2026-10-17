<?php
$s = settings();
$p = prices();
$faq = faq_items('doszkalajace');
$crumbs = [['Jazdy doszkalające', '/jazdy-doszkalajace']];
$meta = [
    'title' => 'Jazdy doszkalające Łomża – ' . zl((int) $p['extra_hour']) . '/h | OSK Artur Żyliński',
    'description' => 'Jazdy doszkalające w Łomży za ' . zl((int) $p['extra_hour']) . ' za godzinę: przed egzaminem w WORD i dla kierowców z prawem jazdy, którzy chcą odzyskać pewność za kierownicą.',
    'path' => '/jazdy-doszkalajace',
    'breadcrumbs' => $crumbs,
    'schema' => [
        schema_service('Jazdy doszkalające', '/jazdy-doszkalajace', 'Dodatkowe godziny jazdy z instruktorem w Łomży – przed egzaminem lub dla kierowców po przerwie.', schema_extra_offer()),
        schema_faq($faq),
    ],
];
?>
<section class="page-hero">
  <div class="wrap">
    <?= breadcrumbs_html($crumbs) ?>
    <div class="page-hero-grid">
      <div>
        <h1>Jazdy doszkalające w Łomży</h1>
        <p class="lead">Godzina jazdy z instruktorem kosztuje <strong><?= zl((int) $p['extra_hour']) ?></strong>. Dla kursantów przed egzaminem w WORD i dla kierowców, którzy mają prawo jazdy, ale nie czują się pewnie.</p>
        <div class="hero-actions">
          <a class="btn btn-primary btn-lg" href="/kontakt?temat=doszkalajace#zapis">Umów jazdę <?= icon('arrow') ?></a>
          <a class="btn btn-ghost btn-lg" href="<?= e(phone_href()) ?>"><?= icon('phone') ?> <?= e($s['phone']) ?></a>
        </div>
      </div>
      <div class="hero-price-tag" aria-label="Cena">
        <span class="tag-num"><?= (int) $p['extra_hour'] ?> zł</span>
        <span class="tag-unit">za 60 minut jazdy</span>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="audience">
      <article class="audience-card">
        <span class="audience-ico"><?= icon('route') ?></span>
        <h2>Przed egzaminem w WORD</h2>
        <p class="bluf">Kilka godzin tuż przed egzaminem przypomina odruchy i oswaja trasy w okolicy WORD Łomża. To najczęstszy powód, dla którego kursanci do nas wracają.</p>
        <ul class="ticks ticks-dark">
          <li><?= icon('check') ?> trasy i skrzyżowania z egzaminów</li>
          <li><?= icon('check') ?> manewry, które sprawiają Ci problem</li>
          <li><?= icon('check') ?> jazda o porze, o której masz egzamin</li>
        </ul>
      </article>
      <article class="audience-card">
        <span class="audience-ico"><?= icon('shield') ?></span>
        <h2>Dla kierowców z prawem jazdy</h2>
        <p class="bluf">Masz prawo jazdy od lat, ale prawie nie jeździsz? Doszkolimy Cię w spokojnym tempie, na aucie z podwójnymi pedałami, więc nie musisz się bać.</p>
        <ul class="ticks ticks-dark">
          <li><?= icon('check') ?> powrót za kierownicę po przerwie</li>
          <li><?= icon('check') ?> jazda w mieście, parkowanie, ronda</li>
          <li><?= icon('check') ?> trasa, której się obawiasz – np. do pracy</li>
        </ul>
      </article>
    </div>
  </div>
</section>

<section class="section section-alt">
  <div class="wrap narrow prose">
    <h2>Ile godzin doszkalania potrzebuję?</h2>
    <p class="bluf">Przed egzaminem zwykle wystarczają 2–4 godziny. Kierowcom po dłuższej przerwie proponujemy zacząć od 2 godzin i ocenić razem, co dalej. Płacisz za każdą godzinę osobno, bez kupowania pakietu z góry.</p>
    <h2>Jak umówić jazdę?</h2>
    <p class="bluf">Zadzwoń pod <?= e($s['phone']) ?> albo <a href="/kontakt?temat=doszkalajace#zapis">zostaw numer w formularzu</a>. Jeśli masz już termin egzaminu, podaj go – ułożymy jazdy tak, żeby ostatnia była jak najbliżej tej daty.</p>
  </div>
</section>

<?= faq_html($faq, 'Pytania o jazdy doszkalające') ?>
