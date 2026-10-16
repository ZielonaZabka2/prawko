<?php $msgs = load_data('messages'); ?>
<div class="head-row">
  <h1>Wiadomości z formularza</h1>
  <?php if (unread_count()): ?>
  <form method="post" action="?p=wiadomosci"><?= csrf_field() ?><input type="hidden" name="action" value="read_all"><button class="btn btn-ghost" type="submit">Oznacz wszystkie jako przeczytane</button></form>
  <?php endif; ?>
</div>
<p class="muted">Każde zgłoszenie trafia też na e-mail <?= e(settings()['form_email'] ?: settings()['email']) ?>. Tutaj jest kopia na wypadek, gdyby mail nie doszedł. Zgodnie z polityką prywatności usuwaj zgłoszenia starsze niż 12 miesięcy.</p>
<?php if (!$msgs): ?>
  <p>Na razie brak zgłoszeń.</p>
<?php endif; ?>
<div class="msgs">
<?php foreach ($msgs as $m): ?>
  <article class="msg<?= empty($m['read']) ? ' msg-new' : '' ?>">
    <header>
      <strong><?= e($m['name']) ?></strong>
      <a href="tel:<?= e(preg_replace('~[^\d+]~', '', $m['phone'])) ?>" class="msg-phone"><?= icon('phone', 'icon icon-sm') ?> <?= e($m['phone']) ?></a>
      <?php if ($m['email']): ?><a href="mailto:<?= e($m['email']) ?>"><?= e($m['email']) ?></a><?php endif; ?>
      <span class="muted"><?= e($m['topic']) ?> · <?= e(date('d.m.Y H:i', strtotime($m['date']))) ?></span>
    </header>
    <?php if ($m['message']): ?><p><?= nl2br(e($m['message'])) ?></p><?php endif; ?>
    <div class="msg-actions">
      <form method="post" action="?p=wiadomosci"><?= csrf_field() ?><input type="hidden" name="id" value="<?= e($m['id']) ?>"><input type="hidden" name="action" value="toggle"><button class="link-btn" type="submit"><?= empty($m['read']) ? 'Oznacz jako przeczytane' : 'Oznacz jako nowe' ?></button></form>
      <form method="post" action="?p=wiadomosci" data-confirm="Usunąć to zgłoszenie?"><?= csrf_field() ?><input type="hidden" name="id" value="<?= e($m['id']) ?>"><input type="hidden" name="action" value="delete"><button class="link-btn danger" type="submit">Usuń</button></form>
    </div>
  </article>
<?php endforeach; ?>
</div>
