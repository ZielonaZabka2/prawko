<?php
$unread = unread_count();
$nav = [
    'start' => ['Pulpit', 'home'],
    'promocja' => ['Promocja i popup', 'calendar'],
    'cennik' => ['Cennik', 'wallet'],
    'blog' => ['Blog', 'book'],
    'wiadomosci' => ['Wiadomości', 'mail'],
    'firma' => ['Dane firmy', 'pin'],
    'haslo' => ['Hasło', 'shield'],
];
$active = $current === 'wpis' ? 'blog' : $current;
?><!doctype html>
<html lang="pl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title><?= e($title ?? 'Panel') ?> – Panel OSK</title>
<link rel="icon" href="/favicon.ico">
<link rel="stylesheet" href="<?= asset('admin/admin.css') ?>">
</head>
<body>
<header class="a-top">
  <a class="a-brand" href="?p=start"><img src="/assets/img/logo-96.webp" width="36" height="36" alt=""> <span>Panel OSK</span></a>
  <button class="a-menu" type="button" aria-expanded="false" aria-controls="a-nav"><?= icon('menu') ?><span class="sr-only">Menu</span></button>
  <a class="a-site" href="/" target="_blank" rel="noopener">Zobacz stronę <?= icon('arrow', 'icon icon-sm') ?></a>
</header>
<div class="a-shell">
  <nav class="a-nav" id="a-nav" aria-label="Panel">
    <ul>
      <?php foreach ($nav as $k => [$label, $ico]): ?>
        <li><a href="?p=<?= $k ?>"<?= $active === $k ? ' aria-current="page"' : '' ?>><?= icon($ico) ?> <?= e($label) ?><?= $k === 'wiadomosci' && $unread ? ' <span class="badge">' . $unread . '</span>' : '' ?></a></li>
      <?php endforeach; ?>
      <li class="a-logout"><a href="?p=wyloguj"><?= icon('close') ?> Wyloguj</a></li>
    </ul>
  </nav>
  <main class="a-main">
    <?php foreach (take_flashes() as [$type, $msg]): ?>
      <div class="flash flash-<?= e($type) ?>" role="<?= $type === 'error' ? 'alert' : 'status' ?>"><?= e($msg) ?></div>
    <?php endforeach; ?>
    <?php if (!empty(load_data('auth')['initial']) && $current !== 'haslo'): ?>
      <div class="flash flash-warn">Używasz hasła startowego. <a href="?p=haslo">Ustaw własne hasło</a> – zajmie to minutę.</div>
    <?php endif; ?>
    <?= $body ?>
  </main>
</div>
<script src="<?= asset('admin/admin.js') ?>" defer></script>
</body>
</html>
