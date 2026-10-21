<?php
/** @var array $promo @var bool $preview */
$s = settings();
$theme = in_array($promo['theme'] ?? 'dark', ['dark', 'orange', 'light'], true) ? ($promo['theme'] ?? 'dark') : 'dark';
$perks = array_values(array_filter(array_map('trim', explode("\n", (string) ($promo['perk'] ?? '')))));
$imgDir = ($promo['image_dir'] ?? 'assets/img') === 'uploads' ? 'uploads' : 'assets/img';
$pc = find_course(promo_course_slug());
$showPrice = $pc && (int) $promo['price'] > 0 && (int) $promo['price'] < course_regular_price($pc);
?>
<dialog class="promo-dialog promo-theme-<?= e($theme) ?>" id="promo-dialog" aria-labelledby="promo-title"
        data-delay="<?= (int) $promo['delay'] ?>" data-repeat="<?= (int) $promo['repeat_days'] ?>"
        data-trigger="<?= e($promo['trigger'] ?? 'time') ?>" data-scroll="<?= (int) ($promo['scroll_pct'] ?? 40) ?>"
        data-mobile="<?= ($promo['mobile'] ?? true) ? '1' : '0' ?>"
        data-key="promo-<?= e($promo['version'] . '-' . $promo['start']) ?>"<?= !empty($preview) ? ' data-force="1"' : '' ?>>
  <form method="dialog" class="promo-close-form">
    <button class="promo-close" value="close" aria-label="Zamknij"><?= icon('close') ?></button>
  </form>
  <?php if (!empty($promo['image']) && image_variants($promo['image'], $imgDir)): ?>
    <div class="promo-media"><?= img($promo['image'], '', '440px', ['default_w' => 480, 'dir' => $imgDir]) ?></div>
  <?php endif; ?>
  <div class="promo-dialog-in" tabindex="-1" autofocus>
    <?php if ($promo['label']): ?><p class="promo-tag"><?= e($promo['label']) ?></p><?php endif; ?>
    <h2 id="promo-title"><?= e($promo['title']) ?></h2>
    <?php if ($showPrice): ?>
      <p class="promo-price">
        <span class="price-new"><?= zl((int) $promo['price']) ?></span>
        <s class="price-old"><span class="sr-only">zamiast </span><?= zl(course_regular_price($pc)) ?></s>
      </p>
    <?php endif; ?>
    <?php if ($promo['text']): ?><p class="promo-text"><?= e($promo['text']) ?></p><?php endif; ?>
    <?php foreach ($perks as $perk): ?><p class="promo-perk"><?= icon('check') ?> <?= e($perk) ?></p><?php endforeach; ?>
    <div class="promo-actions">
      <a class="btn btn-primary" href="<?= e($promo['cta_url']) ?>"><?= e($promo['cta_label']) ?></a>
      <?php if ($promo['show_phone'] ?? true): ?><a class="btn btn-ghost-dark" href="<?= e(phone_href()) ?>"><?= icon('phone') ?> <?= e($s['phone']) ?></a><?php endif; ?>
    </div>
    <?php if ($promo['show_terms'] ?? true): ?><p class="promo-terms">Oferta ważna od <?= e(date_short($promo['start'])) ?> do <?= e(date_short($promo['end'])) ?>. <a href="/regulamin#promocje">Warunki promocji</a></p><?php endif; ?>
  </div>
</dialog>
