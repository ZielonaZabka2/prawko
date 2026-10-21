<?php
$s = settings();
$p = prices();
$faq = faq_items('noclegi');
$crumbs = [['Noclegi dla kursantów', '/noclegi-dla-kursantow']];
$gallery = [
    ['mieszkanie-salon', 'Salon z kanapą, telewizorem i aneksem kuchennym'],
    ['mieszkanie-jadalnia', 'Okrągły stół jadalniany i część wypoczynkowa'],
    ['mieszkanie-salon-okno', 'Salon z dużym przeszkleniem i wyjściem na balkon'],
    ['mieszkanie-salon-tv', 'Część dzienna z telewizorem i kuchnią'],
    ['mieszkanie-sypialnia', 'Sypialnia z podwójnym łóżkiem i dużym oknem'],
    ['mieszkanie-sypialnia-2', 'Sypialnia z łóżkiem dwuosobowym'],
    ['mieszkanie-sypialnia-3', 'Sypialnia z oknem balkonowym'],
    ['mieszkanie-sypialnia-4', 'Sypialnia z dekoracyjnym oświetleniem'],
    ['mieszkanie-kuchnia', 'Aneks kuchenny z płytą i czajnikiem'],
    ['mieszkanie-aneks', 'Kuchnia i korytarz w mieszkaniu'],
    ['mieszkanie-lazienka', 'Łazienka z kabiną prysznicową i pralką'],
    ['mieszkanie-lazienka-2', 'Łazienka z prysznicem i oświetlonym lustrem'],
    ['mieszkanie-lazienka-3', 'Łazienka z prysznicem, pralką i grzejnikiem na ręczniki'],
    ['mieszkanie-przedpokoj', 'Przedpokój z zabudowaną szafą'],
    ['mieszkanie-budynek', 'Nowy budynek mieszkalny z parkingiem'],
];
$meta = [
    'title' => 'Kurs prawa jazdy z noclegiem – mieszkanie w Łomży',
    'description' => 'Mieszkanie dla kursantów spoza Łomży: dwa pokoje, kuchnia, łazienka. ' . zl((int) $p['apartment_day']) . '/doba lub ' . zl((int) $p['apartment_2weeks']) . ' za 2 tygodnie. Zrób kurs prawa jazdy intensywnie, na miejscu.',
    'path' => '/noclegi-dla-kursantow',
    'og_image' => image_url('mieszkanie-salon'),
    'breadcrumbs' => $crumbs,
    'schema' => [
        [
            '@type' => 'Apartment',
            'name' => 'Mieszkanie dla kursantów OSK Artur Żyliński',
            'url' => site_url('noclegi-dla-kursantow'),
            'numberOfRooms' => 2,
            'address' => ['@type' => 'PostalAddress', 'addressLocality' => $s['city'], 'addressCountry' => 'PL'],
            'image' => array_map(fn($g) => image_url($g[0]), array_slice($gallery, 0, 5)),
        ],
        schema_service('Mieszkanie dla kursantów', '/noclegi-dla-kursantow', 'Wynajem dwupokojowego mieszkania w Łomży na czas kursu prawa jazdy.', schema_stay_offer()),
        schema_faq($faq),
    ],
];
?>
<section class="page-hero">
  <div class="wrap">
    <?= breadcrumbs_html($crumbs) ?>
    <div class="page-hero-grid">
      <div>
        <h1>Kurs prawa jazdy z noclegiem w Łomży</h1>
        <p class="lead">Mieszkasz daleko od Łomży albo przyjeżdżasz na kilka tygodni? Zamieszkaj w komfortowym mieszkaniu z dwoma pokojami i zrób kurs w trybie intensywnym.</p>
        <div class="hero-actions">
          <a class="btn btn-primary btn-lg" href="/kontakt?temat=nocleg#zapis">Zapytaj o termin <?= icon('arrow') ?></a>
          <a class="btn btn-ghost btn-lg" href="<?= e(phone_href()) ?>"><?= icon('phone') ?> <?= e($s['phone']) ?></a>
        </div>
      </div>
      <dl class="stay-prices">
        <div><dt>Doba</dt><dd><?= zl((int) $p['apartment_day']) ?></dd></div>
        <div><dt>2 tygodnie</dt><dd><?= zl((int) $p['apartment_2weeks']) ?></dd></div>
      </dl>
    </div>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="split">
      <div>
        <h2>Dla kogo jest mieszkanie?</h2>
        <p class="bluf">Mieszkanie wynajmujemy przede wszystkim kursantom spoza Łomży, którzy chcą zrobić prawo jazdy szybko i bez codziennych dojazdów. Dwa tygodnie pobytu kosztują <?= zl((int) $p['apartment_2weeks']) ?>, czyli taniej niż płacąc za każdą dobę osobno.</p>
        <p>Takie rozwiązanie wybierają m.in. osoby pracujące za granicą, które przyjeżdżają do Polski na urlop, studenci w czasie wakacji i osoby z mniejszych miejscowości regionu, z których dojazd na jazdy zajmuje za dużo czasu.</p>
      </div>
      <div>
        <h2>Co jest w mieszkaniu?</h2>
        <ul class="amenities">
          <li><?= icon('bed') ?> dwa pokoje z wygodnymi łóżkami</li>
          <li><?= icon('home') ?> salon z aneksem kuchennym i stołem</li>
          <li><?= icon('check') ?> w pełni wyposażona kuchnia</li>
          <li><?= icon('check') ?> łazienka z prysznicem i pralką</li>
          <li><?= icon('check') ?> telewizor, nowoczesne wnętrze</li>
          <li><?= icon('car') ?> nowy budynek z parkingiem</li>
        </ul>
      </div>
    </div>
  </div>
</section>

<section class="section section-alt" aria-labelledby="galeria">
  <div class="wrap">
    <h2 id="galeria">Zdjęcia mieszkania</h2>
    <ul class="gallery" data-gallery>
      <?php foreach ($gallery as $i => [$name, $alt]):
          $v = image_variants($name); $big = end($v); ?>
        <li>
          <a href="/assets/img/<?= e($big['file']) ?>" data-w="<?= $big['w'] ?>" data-h="<?= $big['h'] ?>" aria-label="Powiększ: <?= e($alt) ?>">
            <?= img($name, $alt, '(min-width: 960px) 280px, 50vw', ['default_w' => 480]) ?>
            <span class="gallery-zoom" aria-hidden="true"><?= icon('zoom') ?></span>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

<dialog class="lightbox" id="lightbox" aria-label="Podgląd zdjęcia">
  <button class="lb-btn lb-close" type="button" data-lb="close" aria-label="Zamknij"><?= icon('close') ?></button>
  <button class="lb-btn lb-prev" type="button" data-lb="prev" aria-label="Poprzednie zdjęcie"><?= icon('chevron-left') ?></button>
  <figure><img alt=""><figcaption></figcaption></figure>
  <button class="lb-btn lb-next" type="button" data-lb="next" aria-label="Następne zdjęcie"><?= icon('chevron') ?></button>
</dialog>

<section class="section">
  <div class="wrap narrow prose">
    <h2>Jak połączyć kurs z noclegiem?</h2>
    <p class="bluf">Najlepiej zarezerwować mieszkanie razem z kursem: ustalamy daty jazd i pobytu tak, żeby w ciągu dwóch–czterech tygodni zrobić całe szkolenie i egzamin wewnętrzny.</p>
    <ol>
      <li>Wyrób numer PKK jeszcze przed przyjazdem (<a href="/blog/profil-kandydata-na-kierowce-pkk-lomza">instrukcja</a>).</li>
      <li>Zadzwoń i podaj, kiedy możesz przyjechać i na jak długo.</li>
      <li>Rezerwujemy mieszkanie i układamy plan jazd na cały pobyt.</li>
    </ol>
    <p>Sprawdź też <a href="<?= e(course_url(main_course())) ?>">szczegóły kursu kat. <?= e(main_course()['category']) ?></a> i <a href="/cennik">pełny cennik</a>.</p>
  </div>
</section>

<?= faq_html($faq, 'Pytania o nocleg') ?>
