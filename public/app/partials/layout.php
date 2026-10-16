<?php
/** @var array $meta */
/** @var string $content */
$s = settings();
$promo = promo();
$promoOn = promo_active($promo);
// podgląd popupu z panelu: /?podglad-popup=1 (tylko zalogowany administrator)
$promoPreview = isset($_GET['podglad-popup']) && is_admin();
$canonical = site_url(ltrim($meta['path'], '/'));
$nav = [
    'kurs' => ['/kurs-prawa-jazdy-kat-b', 'Kurs kat. B'],
    'doszkalajace' => ['/jazdy-doszkalajace', 'Jazdy doszkalające'],
    'noclegi' => ['/noclegi-dla-kursantow', 'Noclegi'],
    'cennik' => ['/cennik', 'Cennik'],
    'blog' => ['/blog', 'Poradnik'],
    'kontakt' => ['/kontakt', 'Kontakt'],
];
$current = $meta['nav'] === 'post' ? 'blog' : $meta['nav'];

$schema = array_merge([schema_business(), schema_website()], $meta['schema']);
if ($meta['breadcrumbs']) {
    $schema[] = schema_breadcrumbs($meta['breadcrumbs']);
}
?><!doctype html>
<html lang="pl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($meta['title']) ?></title>
<?php if ($meta['description']): ?><meta name="description" content="<?= e($meta['description']) ?>">
<?php endif; ?>
<link rel="canonical" href="<?= e($canonical) ?>">
<meta name="robots" content="<?= $meta['noindex'] ? 'noindex, follow' : 'index, follow, max-image-preview:large' ?>">
<meta property="og:locale" content="pl_PL">
<meta property="og:type" content="<?= e($meta['og_type']) ?>">
<meta property="og:site_name" content="<?= e($s['name'] . ' – ' . $s['tagline']) ?>">
<meta property="og:title" content="<?= e($meta['title']) ?>">
<?php if ($meta['description']): ?><meta property="og:description" content="<?= e($meta['description']) ?>">
<?php endif; ?>
<meta property="og:url" content="<?= e($canonical) ?>">
<meta property="og:image" content="<?= e($meta['og_image']) ?>">
<meta name="twitter:card" content="summary_large_image">
<meta name="theme-color" content="#15171a">
<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/assets/img/logo-192.png" type="image/png">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">
<link rel="preload" href="/assets/fonts/barlow-condensed-latin-700-normal.woff2" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="/assets/fonts/barlow-latin-400-normal.woff2" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>">
<?= schema_script($schema) ?>

</head>
<body class="<?= e(trim('page-' . $meta['nav'] . ' ' . $meta['body_class'])) ?>">
<a class="skip" href="#main">Przejdź do treści</a>

<?php if ($promoOn): ?>
<div class="promo-bar">
  <div class="wrap promo-bar-in">
    <span class="promo-bar-tag"><?= e($promo['label']) ?></span>
    <span class="promo-bar-text"><?= e($promo['title']) ?> · do <?= e(date_pl($promo['end'])) ?></span>
    <a href="<?= e($promo['cta_url']) ?>" class="promo-bar-link">Zapisz się <?= icon('arrow', 'icon icon-sm') ?></a>
  </div>
</div>
<?php endif; ?>

<header class="site-header">
  <div class="wrap header-in">
    <a class="brand" href="/" aria-label="<?= e($s['name']) ?> – strona główna">
      <img src="/assets/img/logo-96.webp" srcset="/assets/img/logo-96.webp 1x, /assets/img/logo-192.webp 2x" width="48" height="48" alt="">
      <span class="brand-text">
        <span class="brand-name">Artur Żyliński</span>
        <span class="brand-sub">Szkoła Bezpiecznej Jazdy</span>
      </span>
    </a>
    <nav class="nav" id="nav" aria-label="Główne menu">
      <ul>
        <?php foreach ($nav as $key => [$href, $label]): ?>
          <li><a href="<?= $href ?>"<?= $current === $key ? ' aria-current="page"' : '' ?>><?= e($label) ?></a></li>
        <?php endforeach; ?>
      </ul>
      <a class="nav-phone" href="<?= e(phone_href()) ?>"><?= icon('phone') ?> <?= e($s['phone']) ?></a>
    </nav>
    <a class="btn btn-primary header-cta" href="<?= e(phone_href()) ?>"><?= icon('phone') ?><span><?= e($s['phone']) ?></span></a>
    <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="nav">
      <?= icon('menu', 'icon icon-open') ?><?= icon('close', 'icon icon-close') ?>
      <span class="sr-only">Menu</span>
    </button>
  </div>
</header>

<main id="main">
<?= $content ?>
</main>

<footer class="site-footer">
  <div class="lane" aria-hidden="true"></div>
  <div class="wrap footer-grid">
    <div class="footer-brand">
      <a class="brand brand-footer" href="/">
        <img src="/assets/img/logo-96.webp" width="56" height="56" alt="" loading="lazy">
        <span class="brand-text">
          <span class="brand-name">Artur Żyliński</span>
          <span class="brand-sub">Szkoła Bezpiecznej Jazdy</span>
        </span>
      </a>
      <p>Ośrodek szkolenia kierowców w Łomży. Kurs prawa jazdy kat. B, jazdy doszkalające i mieszkanie dla kursantów spoza miasta.</p>
      <?php if ($s['facebook']): ?>
        <a class="footer-social" href="<?= e($s['facebook']) ?>" target="_blank" rel="noopener"><?= icon('facebook') ?> Facebook</a>
      <?php endif; ?>
    </div>
    <div>
      <h2 class="footer-h">Oferta</h2>
      <ul class="footer-links">
        <li><a href="/kurs-prawa-jazdy-kat-b">Kurs prawa jazdy kat. B</a></li>
        <li><a href="/jazdy-doszkalajace">Jazdy doszkalające</a></li>
        <li><a href="/noclegi-dla-kursantow">Noclegi dla kursantów</a></li>
        <li><a href="/cennik">Cennik</a></li>
      </ul>
    </div>
    <div>
      <h2 class="footer-h">Informacje</h2>
      <ul class="footer-links">
        <li><a href="/blog">Poradnik kursanta</a></li>
        <li><a href="/regulamin">Regulamin</a></li>
        <li><a href="/polityka-prywatnosci">Polityka prywatności</a></li>
        <li><a href="/kontakt">Kontakt</a></li>
      </ul>
    </div>
    <address class="footer-contact">
      <h2 class="footer-h">Kontakt</h2>
      <a href="<?= e(phone_href()) ?>" class="footer-phone"><?= icon('phone') ?> <?= e($s['phone']) ?></a>
      <a href="mailto:<?= e($s['email']) ?>"><?= icon('mail') ?> <?= e($s['email']) ?></a>
      <span><?= icon('pin') ?> <?= e($s['street']) ?>, <?= e($s['postal_code'] . ' ' . $s['city']) ?></span>
    </address>
  </div>
  <div class="wrap footer-bottom">
    <span>© <?= date('Y') ?> <?= e($s['legal_name'] ?: $s['name'] . ' – ' . $s['tagline']) ?><?= $s['nip'] ? ' · NIP ' . e($s['nip']) : '' ?></span>
    <span>Łomża · ul. Dworna</span>
  </div>
</footer>

<div class="action-bar" aria-label="Szybki kontakt">
  <a href="<?= e(phone_href()) ?>" class="action-call"><?= icon('phone') ?> Zadzwoń</a>
  <a href="/kontakt#zapis" class="action-sign"><?= icon('calendar') ?> Zapisz się</a>
</div>

<?php if ($promoPreview || ($promoOn && !empty($promo['popup']) && $meta['nav'] !== '404')): ?>
<dialog class="promo-dialog" id="promo-dialog" aria-labelledby="promo-title"
        data-delay="<?= (int) $promo['delay'] ?>" data-repeat="<?= (int) $promo['repeat_days'] ?>"
        data-key="promo-<?= e($promo['version'] . '-' . $promo['start']) ?>"<?= $promoPreview ? ' data-force="1"' : '' ?>>
  <form method="dialog" class="promo-close-form">
    <button class="promo-close" value="close" aria-label="Zamknij"><?= icon('close') ?></button>
  </form>
  <div class="promo-dialog-in" tabindex="-1" autofocus>
    <p class="promo-tag"><?= e($promo['label']) ?></p>
    <h2 id="promo-title"><?= e($promo['title']) ?></h2>
    <?php if ((int) $promo['price'] > 0 && (int) $promo['price'] < course_regular_price()): ?>
      <p class="promo-price">
        <span class="price-new"><?= zl((int) $promo['price']) ?></span>
        <s class="price-old"><span class="sr-only">zamiast </span><?= zl(course_regular_price()) ?></s>
      </p>
    <?php endif; ?>
    <p class="promo-text"><?= e($promo['text']) ?></p>
    <?php if ($promo['perk']): ?><p class="promo-perk"><?= icon('check') ?> <?= e($promo['perk']) ?></p><?php endif; ?>
    <div class="promo-actions">
      <a class="btn btn-primary" href="<?= e($promo['cta_url']) ?>"><?= e($promo['cta_label']) ?></a>
      <a class="btn btn-ghost-dark" href="<?= e(phone_href()) ?>"><?= icon('phone') ?> <?= e($s['phone']) ?></a>
    </div>
    <p class="promo-terms">Oferta ważna od <?= e(date_short($promo['start'])) ?> do <?= e(date_short($promo['end'])) ?>. <a href="/regulamin#promocje">Warunki promocji</a></p>
  </div>
</dialog>
<?php endif; ?>

<script src="<?= asset('assets/js/main.js') ?>" defer></script>
</body>
</html>
