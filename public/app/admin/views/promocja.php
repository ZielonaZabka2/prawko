<?php $p = promo(); [$st, $stLabel] = promo_status($p); ?>
<h1>Promocja i popup</h1>
<p class="muted">Promocja włącza się i wyłącza sama według dat. Gdy jest aktywna: cena promocyjna pojawia się w całym serwisie, na górze strony widać pasek, a po kilku sekundach wyskakuje okienko.</p>
<p><span class="status status-<?= e($st) ?>"><?= e($stLabel) ?></span></p>

<form method="post" action="?p=promocja" class="panel">
  <?= csrf_field() ?>
  <fieldset>
    <legend>Promocja</legend>
    <?= field('enabled', '<strong>Promocja włączona</strong>', $p['enabled'], ['type' => 'checkbox', 'help' => 'Odznacz, żeby od razu wyłączyć promocję niezależnie od dat.']) ?>
    <div class="grid-3">
      <?= field('start', 'Od dnia', $p['start'], ['type' => 'date', 'required' => true]) ?>
      <?= field('end', 'Do dnia (włącznie)', $p['end'], ['type' => 'date', 'required' => true]) ?>
      <div class="field">
        <label for="f-course">Promocja dotyczy kursu</label>
        <select id="f-course" name="course">
          <?php foreach (courses(false) as $c): ?><option value="<?= e($c['slug']) ?>"<?= ($p['course'] ?? '') === $c['slug'] ? ' selected' : '' ?>><?= e($c['name']) ?> (<?= zl((int) $c['price']) ?>)</option><?php endforeach; ?>
        </select>
      </div>
    </div>
    <?= field('price', 'Cena promocyjna', $p['price'], ['type' => 'number', 'min' => 0, 'suffix' => 'zł', 'help' => 'Wpisz 0, jeśli promocja nie dotyczy ceny (np. gratis dodatkowa jazda).']) ?>
    <div class="grid-2">
      <?= field('label', 'Etykieta', $p['label'], ['maxlength' => 40, 'help' => 'Krótko, np. „Promocja jesienna”.']) ?>
      <?= field('title', 'Tytuł', $p['title'], ['maxlength' => 80, 'required' => true, 'help' => 'Np. „Kurs kat. B za 2800 zł”.']) ?>
    </div>
    <?= field('text', 'Opis', $p['text'], ['type' => 'textarea', 'rows' => 3, 'maxlength' => 400]) ?>
    <?= field('perk', 'Dodatkowa korzyść (z ptaszkiem)', $p['perk'], ['maxlength' => 120, 'help' => 'Np. „Szybka realizacja – cały kurs nawet w 1 miesiąc”. Zostaw puste, żeby ukryć.']) ?>
  </fieldset>
  <fieldset>
    <legend>Wyskakujące okienko (popup)</legend>
    <?= field('popup', '<strong>Pokazuj popup</strong> gdy promocja jest aktywna', $p['popup'], ['type' => 'checkbox']) ?>
    <div class="grid-3">
      <?= field('delay', 'Pokaż po', $p['delay'], ['type' => 'number', 'min' => 0, 'max' => 120, 'suffix' => 'sekundach']) ?>
      <?= field('repeat_days', 'Pokaż ponownie po', $p['repeat_days'], ['type' => 'number', 'min' => 0, 'max' => 60, 'suffix' => 'dniach', 'help' => 'Po zamknięciu okienka ta sama osoba nie zobaczy go przez tyle dni. 0 = tylko raz.']) ?>
    </div>
    <div class="grid-2">
      <?= field('cta_label', 'Tekst przycisku', $p['cta_label'], ['maxlength' => 40]) ?>
      <?= field('cta_url', 'Przycisk prowadzi do', $p['cta_url'], ['maxlength' => 300, 'help' => 'Domyślnie formularz zapisu: /kontakt#zapis']) ?>
    </div>
    <?= field('reset_popup', 'Pokaż popup ponownie wszystkim (także tym, którzy go już zamknęli)', false, ['type' => 'checkbox', 'help' => 'Dzieje się to automatycznie, gdy zmienisz tytuł, opis, cenę lub daty.']) ?>
  </fieldset>
  <div class="form-actions">
    <button class="btn btn-primary" type="submit">Zapisz promocję</button>
    <a class="btn btn-ghost" href="/?podglad-popup=1" target="_blank" rel="noopener">Podgląd popupu</a>
  </div>
</form>

<div class="panel tip">
  <h2>Jak zrobić następną promocję?</h2>
  <ol>
    <li>Zmień daty „od” i „do” (np. na kolejny miesiąc).</li>
    <li>Zmień tytuł, cenę i opis.</li>
    <li>Zapisz. Gotowe – popup pokaże się od nowa wszystkim odwiedzającym.</li>
  </ol>
</div>
