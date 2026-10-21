<?php
$p = promo();
[$st, $stLabel] = promo_status($p);
$gallery = [
    '' => 'bez zdjęcia',
    'flota-pod-biurem' => 'Oba auta pod biurem',
    'kia-biala-przod' => 'Biała Kia',
    'kia-granatowa-przod' => 'Granatowa Kia',
    'biuro-szyld' => 'Biuro z szyldem',
    'kia-rio-szkoleniowa' => 'Kia z boku (szeroka)',
    'mieszkanie-salon' => 'Mieszkanie – salon',
];
$imgDir = ($p['image_dir'] ?? 'assets/img') === 'uploads' ? 'uploads' : 'assets/img';
$ownImage = $imgDir === 'uploads' && !empty($p['image']);
$thumb = function (string $name, string $dir) {
    $v = image_variants($name, $dir);
    return $v ? '/' . $dir . '/' . $v[0]['file'] : '';
};
$regular = [];
foreach (courses(false) as $c) {
    $regular[$c['slug']] = (int) $c['price'];
}
?>
<h1>Promocja i popup</h1>
<p class="muted">Promocja włącza się i wyłącza sama według dat. Gdy jest aktywna, cena promocyjna pojawia się w całym serwisie, a okienko (popup) wyskakuje na środku ekranu. Podgląd po prawej zmienia się na bieżąco.</p>
<p><span class="status status-<?= e($st) ?>"><?= e($stLabel) ?></span></p>

<form method="post" action="?p=promocja" class="panel promo-editor" enctype="multipart/form-data" id="promo-form">
  <?= csrf_field() ?>
  <div class="pe-grid">
    <div>
      <fieldset>
        <legend>1. Promocja</legend>
        <?= field('enabled', '<strong>Promocja włączona</strong>', $p['enabled'], ['type' => 'checkbox', 'help' => 'Odznacz, żeby od razu wyłączyć promocję niezależnie od dat.']) ?>
        <div class="grid-2">
          <?= field('start', 'Od dnia', $p['start'], ['type' => 'date', 'required' => true]) ?>
          <?= field('end', 'Do dnia (włącznie)', $p['end'], ['type' => 'date', 'required' => true]) ?>
        </div>
        <div class="grid-2">
          <div class="field">
            <label for="f-course">Dotyczy kursu</label>
            <select id="f-course" name="course" data-regular='<?= e(json_encode($regular)) ?>'>
              <?php foreach (courses(false) as $c): ?><option value="<?= e($c['slug']) ?>"<?= ($p['course'] ?? '') === $c['slug'] ? ' selected' : '' ?>><?= e($c['name']) ?> (<?= zl((int) $c['price']) ?>)</option><?php endforeach; ?>
            </select>
          </div>
          <?= field('price', 'Cena promocyjna', $p['price'], ['type' => 'number', 'min' => 0, 'suffix' => 'zł', 'help' => '0 = promocja bez zmiany ceny.']) ?>
        </div>
      </fieldset>

      <fieldset>
        <legend>2. Treść okienka</legend>
        <div class="grid-2">
          <?= field('label', 'Etykieta (mały napis na górze)', $p['label'], ['maxlength' => 40]) ?>
          <?= field('title', 'Tytuł', $p['title'], ['maxlength' => 80, 'required' => true]) ?>
        </div>
        <?= field('text', 'Opis', $p['text'], ['type' => 'textarea', 'rows' => 3, 'maxlength' => 400]) ?>
        <?= field('perk', 'Punkty z ptaszkiem (każdy w nowej linii)', $p['perk'], ['type' => 'textarea', 'rows' => 3, 'maxlength' => 400, 'help' => 'Np. „Nowość: kurs Express w 14 dni”. Puste = brak punktów.']) ?>
        <div class="grid-2">
          <?= field('cta_label', 'Tekst przycisku', $p['cta_label'], ['maxlength' => 40]) ?>
          <?= field('cta_url', 'Przycisk prowadzi do', $p['cta_url'], ['maxlength' => 300, 'help' => 'Formularz zapisu: /kontakt#zapis · kurs Express: /kurs-prawa-jazdy-kat-b-express']) ?>
        </div>
        <?= field('show_phone', 'Pokaż drugi przycisk z numerem telefonu', $p['show_phone'] ?? true, ['type' => 'checkbox']) ?>
        <?= field('show_terms', 'Pokaż datę ważności i link „Warunki promocji”', $p['show_terms'] ?? true, ['type' => 'checkbox']) ?>
      </fieldset>

      <fieldset>
        <legend>3. Wygląd</legend>
        <div class="field">
          <span class="label">Styl</span>
          <div class="seg">
            <?php foreach (['dark' => 'Grafitowy', 'orange' => 'Pomarańczowy', 'light' => 'Jasny'] as $k => $label): ?>
              <label><input type="radio" name="theme" value="<?= $k ?>"<?= ($p['theme'] ?? 'dark') === $k ? ' checked' : '' ?>> <?= e($label) ?></label>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="field">
          <span class="label">Zdjęcie</span>
          <div class="img-pick">
            <?php foreach ($gallery as $k => $label): $t = $k ? $thumb($k, 'assets/img') : ''; ?>
              <label class="img-opt">
                <input type="radio" name="image" value="<?= e($k) ?>" data-src="<?= e($t) ?>"<?= !$ownImage && ($p['image'] ?? '') === $k ? ' checked' : '' ?>>
                <span><?= $t ? '<img src="' . e($t) . '" alt="" loading="lazy">' : '<em>bez zdjęcia</em>' ?><small><?= e($label) ?></small></span>
              </label>
            <?php endforeach; ?>
            <?php if ($ownImage && ($t = $thumb($p['image'], 'uploads'))): ?>
              <label class="img-opt">
                <input type="radio" name="image" value="__own" data-src="<?= e($t) ?>" checked>
                <span><img src="<?= e($t) ?>" alt=""><small>Twoje zdjęcie</small></span>
              </label>
            <?php endif; ?>
          </div>
          <div class="field" style="margin-top:10px">
            <label for="f-image_file">…albo wgraj własne (JPG/PNG z telefonu)</label>
            <input type="file" id="f-image_file" name="image_file" accept="image/jpeg,image/png,image/webp">
            <p class="help">Najlepiej poziome. Zostanie zmniejszone automatycznie.</p>
          </div>
        </div>
      </fieldset>

      <fieldset>
        <legend>4. Kiedy i gdzie się pokazuje</legend>
        <?= field('popup', '<strong>Pokazuj popup</strong>, gdy promocja jest aktywna', $p['popup'], ['type' => 'checkbox']) ?>
        <div class="grid-2">
          <div class="field">
            <label for="f-trigger">Kiedy wyskakuje</label>
            <select id="f-trigger" name="trigger">
              <?php foreach (['time' => 'po kilku sekundach', 'scroll' => 'po przewinięciu części strony', 'exit' => 'gdy ktoś chce wyjść ze strony (komputer)'] as $k => $label): ?>
                <option value="<?= $k ?>"<?= ($p['trigger'] ?? 'time') === $k ? ' selected' : '' ?>><?= e($label) ?></option>
              <?php endforeach; ?>
            </select>
            <p class="help">„Gdy ktoś chce wyjść” działa na komputerze (kursor ucieka do kart przeglądarki). Na telefonie zamienia się na przewinięcie.</p>
          </div>
          <div class="field">
            <label for="f-pages">Na których stronach</label>
            <select id="f-pages" name="pages">
              <?php foreach (['all' => 'na wszystkich', 'courses' => 'główna, kursy i cennik', 'home' => 'tylko na stronie głównej'] as $k => $label): ?>
                <option value="<?= $k ?>"<?= ($p['pages'] ?? 'all') === $k ? ' selected' : '' ?>><?= e($label) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="grid-3">
          <?= field('delay', 'Po ilu sekundach', $p['delay'], ['type' => 'number', 'min' => 0, 'max' => 120, 'suffix' => 's']) ?>
          <?= field('scroll_pct', 'Po przewinięciu', $p['scroll_pct'] ?? 40, ['type' => 'number', 'min' => 5, 'max' => 95, 'suffix' => '%']) ?>
          <?= field('repeat_days', 'Pokaż ponownie po', $p['repeat_days'], ['type' => 'number', 'min' => 0, 'max' => 60, 'suffix' => 'dniach', 'help' => '0 = tylko raz.']) ?>
        </div>
        <?= field('mobile', 'Pokazuj także na telefonach', $p['mobile'] ?? true, ['type' => 'checkbox']) ?>
        <?= field('bar', 'Pokazuj też pasek z promocją na górze strony', $p['bar'] ?? false, ['type' => 'checkbox']) ?>
        <?= field('reset_popup', 'Pokaż popup ponownie wszystkim (także tym, którzy go już zamknęli)', false, ['type' => 'checkbox', 'help' => 'Dzieje się to automatycznie, gdy zmienisz tytuł, opis, cenę, zdjęcie lub daty.']) ?>
      </fieldset>

      <div class="form-actions">
        <button class="btn btn-primary" type="submit">Zapisz promocję</button>
        <a class="btn btn-ghost" href="/?podglad-popup=1" target="_blank" rel="noopener">Podgląd na stronie (po zapisaniu)</a>
      </div>
    </div>

    <aside class="pe-preview" aria-label="Podgląd popupu">
      <p class="side-h">Podgląd</p>
      <div class="pe-frame">
        <iframe id="pe-iframe" title="Podgląd popupu" data-css="<?= e(asset('assets/css/style.css')) ?>" data-phone="<?= e(settings()['phone']) ?>"></iframe>
      </div>
      <p class="help">Tak będzie wyglądało okienko na stronie. Animacje i dokładny rozmiar zobaczysz w „Podglądzie na stronie”.</p>
    </aside>
  </div>
</form>

<div class="panel tip">
  <h2>Jak zrobić następną promocję?</h2>
  <ol>
    <li>Zmień daty „od” i „do” (np. na kolejny miesiąc).</li>
    <li>Zmień tytuł, cenę i opis – podgląd od razu pokaże efekt.</li>
    <li>Zapisz. Popup pokaże się od nowa wszystkim odwiedzającym.</li>
  </ol>
</div>
