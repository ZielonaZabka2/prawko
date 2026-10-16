<?php $all = posts(false); ?>
<div class="head-row">
  <h1>Blog</h1>
  <a class="btn btn-primary" href="?p=wpis">+ Nowy wpis</a>
</div>
<p class="muted">Artykuły w „Poradniku kursanta”. Dobre tematy: pytania, które kursanci zadają najczęściej. Jeden konkretny artykuł miesięcznie wystarczy, żeby Google widział, że strona żyje.</p>
<?php if (!$all): ?>
  <p>Brak wpisów.</p>
<?php else: ?>
<table class="table">
  <thead><tr><th>Tytuł</th><th>Data</th><th>Status</th><th></th></tr></thead>
  <tbody>
  <?php foreach ($all as $p): ?>
    <tr>
      <td><a href="?p=wpis&amp;id=<?= e(urlencode($p['id'])) ?>"><strong><?= e($p['title']) ?></strong></a><br><span class="muted small">/blog/<?= e($p['slug']) ?></span></td>
      <td><?= e(date_short($p['date'])) ?></td>
      <td><?= !empty($p['published']) ? '<span class="status status-on">Opublikowany</span>' : '<span class="status status-off">Szkic</span>' ?></td>
      <td class="right"><a href="/blog/<?= e($p['slug']) ?>" target="_blank" rel="noopener">Zobacz</a></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>
