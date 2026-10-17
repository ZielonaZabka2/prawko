<?php /** @var array $c @var array $promo @var string $cat */ $light = $light ?? false; ?>
<aside class="price-card<?= $light ? ' price-card-light' : '' ?>" aria-label="Cena kursu <?= e($cat) ?>">
  <?php if (course_discounted($c)): ?>
    <p class="price-card-flag"><?= e($promo['label']) ?> · do <?= e(date_short($promo['end'])) ?></p>
  <?php endif; ?>
  <p class="price-card-label">Kurs prawa jazdy <?= e($cat) ?></p>
  <p class="price-card-price">
    <span class="price-new"><?= zl(course_price($c)) ?></span>
    <?php if (course_discounted($c)): ?><s class="price-old"><span class="sr-only">zamiast </span><?= zl(course_regular_price($c)) ?></s><?php endif; ?>
  </p>
  <ul class="ticks<?= $light ? ' ticks-dark' : '' ?>">
    <li><?= icon('check') ?> <?= (int) $c['theory_hours'] ?> h zajęć teoretycznych</li>
    <li><?= icon('check') ?> <?= (int) $c['practice_hours'] ?> h jazd z instruktorem</li>
    <?php if ((int) $c['bonus_hours'] > 0): ?><li><?= icon('check') ?> <strong>+<?= (int) $c['bonus_hours'] ?> h jazdy gratis</strong></li><?php endif; ?>
    <?php foreach (course_includes($c) as $inc): ?><li><?= icon('check') ?> <?= e($inc) ?></li><?php endforeach; ?>
    <?php if (!empty(prices()['installments'])): ?><li><?= icon('check') ?> możliwość płatności w ratach</li><?php endif; ?>
  </ul>
  <a class="price-card-link" href="<?= e(course_url($c)) ?>">Szczegóły kursu <?= icon('arrow', 'icon icon-sm') ?></a>
</aside>
