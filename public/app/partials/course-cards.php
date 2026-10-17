<?php
/** @var array $list */
$withServices = $withServices ?? false;
$p = prices();
?>
<ul class="course-grid" data-reveal-group>
  <?php foreach ($list as $c): ?>
    <li class="course-card<?= course_discounted($c) ? ' is-promo' : '' ?>">
      <a href="<?= e(course_url($c)) ?>" class="course-card-link">
        <span class="course-cat" aria-hidden="true"><?= e($c['category']) ?></span>
        <span class="course-body">
          <span class="course-short"><?= e($c['short']) ?></span>
          <span class="course-name"><?= e($c['name']) ?></span>
          <span class="course-meta"><?= (int) $c['theory_hours'] ?> h teorii · <?= (int) $c['practice_hours'] ?> h jazd<?= (int) $c['bonus_hours'] ? ' · +' . (int) $c['bonus_hours'] . ' h gratis' : '' ?></span>
        </span>
        <span class="course-price">
          <?php if (course_discounted($c)): ?><s><?= zl(course_regular_price($c)) ?></s><?php endif; ?>
          <strong><?= zl(course_price($c)) ?></strong>
        </span>
        <span class="course-go" aria-hidden="true"><?= icon('arrow') ?></span>
      </a>
    </li>
  <?php endforeach; ?>
  <?php if ($withServices): ?>
    <li class="course-card course-card-alt">
      <a href="/jazdy-doszkalajace" class="course-card-link">
        <span class="course-cat course-cat-icon" aria-hidden="true"><?= icon('route') ?></span>
        <span class="course-body">
          <span class="course-short">Dla kursantów i kierowców</span>
          <span class="course-name">Jazdy doszkalające</span>
          <span class="course-meta">przed egzaminem lub po przerwie</span>
        </span>
        <span class="course-price"><strong><?= zl((int) $p['extra_hour']) ?></strong><small>za godzinę</small></span>
        <span class="course-go" aria-hidden="true"><?= icon('arrow') ?></span>
      </a>
    </li>
    <li class="course-card course-card-alt">
      <a href="/noclegi-dla-kursantow" class="course-card-link">
        <span class="course-cat course-cat-icon" aria-hidden="true"><?= icon('bed') ?></span>
        <span class="course-body">
          <span class="course-short">Kurs z zakwaterowaniem</span>
          <span class="course-name">Mieszkanie dla kursantów</span>
          <span class="course-meta">2 pokoje, kuchnia, łazienka</span>
        </span>
        <span class="course-price"><strong><?= zl((int) $p['apartment_day']) ?></strong><small>za dobę</small></span>
        <span class="course-go" aria-hidden="true"><?= icon('arrow') ?></span>
      </a>
    </li>
  <?php endif; ?>
</ul>
