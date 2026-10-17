<?php $p = prices(); ?>
<section class="section section-alt" aria-labelledby="noclegi">
  <div class="wrap">
    <div class="section-head" data-reveal>
      <div>
        <p class="eyebrow">Kurs z zakwaterowaniem</p>
        <h2 id="noclegi">Mieszkasz daleko? Zamieszkaj w Łomży na czas kursu</h2>
      </div>
      <a class="text-link" href="/noclegi-dla-kursantow">Zobacz mieszkanie <?= icon('arrow', 'icon icon-sm') ?></a>
    </div>
    <p class="bluf narrow-text" data-reveal>Kursantom spoza Łomży wynajmujemy komfortowe mieszkanie z dwoma pokojami: <?= zl((int) $p['apartment_day']) ?> za dobę albo <?= zl((int) $p['apartment_2weeks']) ?> za dwa tygodnie. Razem z intensywnym kursem to najszybsza droga do prawa jazdy.</p>
    <div class="mosaic" data-reveal-group>
      <a href="/noclegi-dla-kursantow" class="mosaic-main"><?= img('mieszkanie-salon', 'Salon z aneksem kuchennym w mieszkaniu dla kursantów', '(min-width: 960px) 640px, 100vw') ?></a>
      <a href="/noclegi-dla-kursantow"><?= img('mieszkanie-sypialnia', 'Sypialnia z podwójnym łóżkiem', '(min-width: 960px) 320px, 50vw') ?></a>
      <a href="/noclegi-dla-kursantow"><?= img('mieszkanie-jadalnia', 'Jadalnia i część wypoczynkowa', '(min-width: 960px) 320px, 50vw') ?></a>
    </div>
  </div>
</section>
