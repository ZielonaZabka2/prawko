<!doctype html>
<html lang="pl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>Logowanie – Panel OSK</title>
<link rel="icon" href="/favicon.ico">
<link rel="stylesheet" href="<?= asset('admin/admin.css') ?>">
</head>
<body class="a-login-page">
<main class="a-login">
  <img src="/assets/img/logo-192.webp" width="72" height="72" alt="">
  <h1>Panel strony</h1>
  <p class="muted">OSK Artur Żyliński – Szkoła Bezpiecznej Jazdy</p>
  <?php if ($error): ?><div class="flash flash-error" role="alert"><?= e($error) ?></div><?php endif; ?>
  <form method="post" action="?p=logowanie">
    <?= csrf_field() ?>
    <div class="field">
      <label for="password">Hasło</label>
      <input id="password" name="password" type="password" autocomplete="current-password" required autofocus>
    </div>
    <button class="btn btn-primary btn-block" type="submit">Zaloguj</button>
  </form>
  <a class="muted small" href="/">← wróć na stronę</a>
</main>
</body>
</html>
