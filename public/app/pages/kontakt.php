<?php
$s = settings();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    [$ok, $errors, $old] = handle_contact_form();
    if ($ok) {
        header('Location: /kontakt?wyslano=1#zapis', true, 303);
        exit;
    }
    $GLOBALS['contact_form'] = ['ok' => false, 'errors' => $errors, 'old' => $old];
} elseif (isset($_GET['wyslano'])) {
    $GLOBALS['contact_form'] = ['ok' => true, 'errors' => [], 'old' => []];
}

$crumbs = [['Kontakt', '/kontakt']];
$mapQuery = rawurlencode('Dworna 1, ' . $s['postal_code'] . ' ' . $s['city']);
$meta = [
    'title' => 'Kontakt i zapisy – OSK Artur Żyliński, ul. Dworna 1, Łomża',
    'description' => 'Zapisz się na kurs prawa jazdy w Łomży: tel. ' . $s['phone'] . ', ' . $s['email'] . ', biuro ' . $s['street'] . '. Zostaw numer – oddzwonimy.',
    'path' => '/kontakt',
    'breadcrumbs' => $crumbs,
    'noindex' => isset($_GET['wyslano']),
];
?>
<section class="page-hero">
  <div class="wrap">
    <?= breadcrumbs_html($crumbs) ?>
    <h1>Kontakt i zapisy</h1>
    <p class="lead narrow-text">Zadzwoń albo napisz przez formularz – odpowiemy telefonicznie lub mailem, jak wolisz.</p>
  </div>
</section>

<section class="section">
  <div class="wrap split split-top">
    <div class="contact-cards">
      <a class="contact-card contact-card-main" href="<?= e(phone_href()) ?>">
        <?= icon('phone', 'icon icon-lg') ?>
        <span><span class="cc-label">Telefon</span><span class="cc-value"><?= e($s['phone']) ?></span></span>
      </a>
      <a class="contact-card" href="mailto:<?= e($s['email']) ?>">
        <?= icon('mail', 'icon icon-lg') ?>
        <span><span class="cc-label">E-mail</span><span class="cc-value"><?= e($s['email']) ?></span></span>
      </a>
      <div class="contact-card">
        <?= icon('pin', 'icon icon-lg') ?>
        <span><span class="cc-label">Biuro</span><span class="cc-value"><?= e($s['street']) ?><br><?= e($s['postal_code'] . ' ' . $s['city']) ?></span></span>
      </div>
      <?php if (trim($s['hours']) !== ''): ?>
      <div class="contact-card">
        <?= icon('clock', 'icon icon-lg') ?>
        <span><span class="cc-label">Godziny</span><span class="cc-value cc-small"><?= nl2br(e($s['hours'])) ?></span></span>
      </div>
      <?php endif; ?>
      <figure class="photo-frame photo-frame-sm">
        <?= img('biuro-wejscie', 'Wejście do biura Szkoły Bezpiecznej Jazdy przy ul. Dworna 1 w Łomży', '(min-width: 960px) 520px, 100vw') ?>
        <figcaption>Wejście do biura – szyld „Szkoła Bezpiecznej Jazdy”</figcaption>
      </figure>
    </div>
    <div>
      <h2 class="form-title">Napisz do nas</h2>
      <?php partial('contact-form', ['compact' => false]); ?>
    </div>
  </div>
</section>

<section class="section section-alt" aria-labelledby="mapa-h">
  <div class="wrap">
    <h2 id="mapa-h">Jak dojechać</h2>
    <div class="map" data-map-src="https://www.google.com/maps?q=<?= $mapQuery ?>&amp;output=embed">
      <div class="map-placeholder" style="--map-bg: url('<?= e('/assets/img/' . (image_variants('biuro-szyld')[0]['file'] ?? '')) ?>')">
        <?= icon('pin', 'icon icon-xl') ?>
        <p><strong><?= e(full_address()) ?></strong></p>
        <p class="map-note">Mapa Google ładuje się dopiero po kliknięciu (Google może wtedy zapisać pliki cookie).</p>
        <button type="button" class="btn btn-primary" data-map-load>Pokaż mapę</button>
        <a class="text-link" href="https://www.google.com/maps/search/?api=1&amp;query=<?= $mapQuery ?>" target="_blank" rel="noopener">Otwórz w Mapach Google <?= icon('arrow', 'icon icon-sm') ?></a>
      </div>
    </div>
  </div>
</section>
