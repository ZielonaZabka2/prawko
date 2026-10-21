<?php
// Opinie kursantów – dodawane w panelu → Opinie. Sekcja pojawia się dopiero, gdy jest co najmniej jedna opinia.
$reviews = array_values(array_filter(load_data('reviews'), fn($r) => !empty($r['visible']) && trim((string) ($r['text'] ?? '')) !== ''));
if (!$reviews) {
    return;
}
?>
<section class="section section-alt reviews" aria-labelledby="opinie-h">
  <div class="wrap">
    <p class="eyebrow" data-reveal>Opinie</p>
    <h2 id="opinie-h" data-reveal>Co mówią kursanci</h2>
    <ul class="review-grid" data-reveal-group>
      <?php foreach (array_slice($reviews, 0, 6) as $r): $stars = max(0, min(5, (int) ($r['stars'] ?? 5))); ?>
        <li class="review">
          <?php if ($stars): ?><p class="stars" aria-label="Ocena <?= $stars ?> na 5"><?= str_repeat('★', $stars) ?><span aria-hidden="true"><?= str_repeat('★', 5 - $stars) ?></span></p><?php endif; ?>
          <blockquote><p><?= nl2br(e($r['text'])) ?></p></blockquote>
          <p class="review-by"><strong><?= e($r['name'] ?: 'Kursant') ?></strong><?= !empty($r['source']) ? ' · ' . e($r['source']) : '' ?><?= !empty($r['date']) ? ' · ' . e(date_short($r['date'])) : '' ?></p>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>
