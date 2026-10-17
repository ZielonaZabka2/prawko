<?php $all = courses(false); $promoSlug = promo_course_slug(); ?>
<div class="head-row">
  <h1>Kursy</h1>
  <a class="btn btn-primary" href="?p=kurs">+ Dodaj kurs (kategorię)</a>
</div>
<p class="muted">Każdy kurs ma własną podstronę, cenę i opis. Gdy aktywnych kursów jest więcej niż jeden, w menu pojawia się „Kursy” z listą wszystkich kategorii.</p>
<table class="table">
  <thead><tr><th>Kurs</th><th>Cena</th><th>Status</th><th></th></tr></thead>
  <tbody>
  <?php foreach ($all as $c): ?>
    <tr>
      <td><a href="?p=kurs&amp;id=<?= e(urlencode($c['id'])) ?>"><strong><?= e($c['name']) ?></strong></a><br><span class="muted small"><?= e(course_url($c)) ?></span></td>
      <td><?= zl((int) $c['price']) ?><?= $promoSlug === $c['slug'] && promo_active() ? '<br><span class="small">promocja: ' . zl(course_price($c)) . '</span>' : '' ?></td>
      <td>
        <?= !empty($c['active']) ? '<span class="status status-on">Widoczny</span>' : '<span class="status status-off">Ukryty</span>' ?>
        <?= !empty($c['featured']) ? ' <span class="status status-plan">Główny</span>' : '' ?>
      </td>
      <td class="right"><a href="<?= e(course_url($c)) ?>" target="_blank" rel="noopener">Zobacz</a></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<div class="panel tip">
  <h2>Jak dodać nową kategorię, np. A albo C?</h2>
  <ol>
    <li>Kliknij „Dodaj kurs”, wpisz kategorię, cenę i liczbę godzin.</li>
    <li>Opis możesz skopiować z kursu B i zmienić. Znaczniki <code>{cena}</code>, <code>{teoria}</code>, <code>{praktyka}</code>, <code>{auto}</code> wstawią się same.</li>
    <li>Zaznacz „Widoczny na stronie” i zapisz. Kurs pojawi się w menu, cenniku, na stronie głównej i w mapie strony dla Google.</li>
  </ol>
</div>
