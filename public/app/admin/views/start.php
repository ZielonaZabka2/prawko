<?php
$p = promo();
[$st, $stLabel] = promo_status($p);
$msgs = load_data('messages');
$unread = array_filter($msgs, fn($m) => empty($m['read']));
$postsAll = posts(false);
?>
<h1>Dzień dobry!</h1>
<p class="muted">Tu zmienisz ceny, promocję z wyskakującym okienkiem, wpisy na blogu i dane kontaktowe. Zmiany od razu widać na stronie.</p>

<div class="cards">
  <a class="card" href="?p=promocja">
    <span class="card-k">Promocja</span>
    <span class="status status-<?= e($st) ?>"><?= e($stLabel) ?></span>
    <span class="card-v"><?= e($p['title']) ?></span>
  </a>
  <a class="card" href="?p=cennik">
    <span class="card-k">Cena kursu kat. B na stronie</span>
    <span class="card-v big"><?= zl(course_price()) ?></span>
    <?php if (course_discounted()): ?><span class="muted">regularnie <?= zl(course_regular_price()) ?></span><?php endif; ?>
  </a>
  <a class="card" href="?p=wiadomosci">
    <span class="card-k">Nowe wiadomości</span>
    <span class="card-v big"><?= count($unread) ?></span>
    <span class="muted">wszystkich: <?= count($msgs) ?></span>
  </a>
  <a class="card" href="?p=blog">
    <span class="card-k">Wpisy na blogu</span>
    <span class="card-v big"><?= count(posts()) ?></span>
    <span class="muted">szkice: <?= count($postsAll) - count(posts()) ?></span>
  </a>
</div>

<?php if ($unread): ?>
<h2>Ostatnie zgłoszenia</h2>
<ul class="list">
  <?php foreach (array_slice($unread, 0, 5) as $m): ?>
    <li><strong><?= e($m['name']) ?></strong> · <a href="tel:<?= e(preg_replace('~[^\d+]~', '', $m['phone'])) ?>"><?= e($m['phone']) ?></a> · <?= e($m['topic']) ?> <span class="muted">· <?= e(date_short($m['date'])) ?></span></li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>

<h2>Szybkie akcje</h2>
<div class="actions">
  <a class="btn" href="?p=wpis">+ Nowy wpis na blogu</a>
  <a class="btn btn-ghost" href="/?podglad-popup=1" target="_blank" rel="noopener">Podejrzyj popup promocji</a>
</div>
