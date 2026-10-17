<?php $p = prices(); $extra = $p['extra_items'] ?? []; while (count($extra) < 4) { $extra[] = ['name' => '', 'note' => '', 'price' => '', 'unit' => '']; } ?>
<h1>Cennik i usługi</h1>
<p class="muted">Ceny usług dodatkowych (kursy edytujesz w zakładce Kursy). Trafiają do cennika, FAQ i do danych dla Google. Wpisuj same liczby (bez „zł”).</p>
<form method="post" action="?p=cennik" class="panel">
  <?= csrf_field() ?>
  <fieldset>
    <legend>Płatność za kursy</legend>
    <p class="help">Ceny i godziny poszczególnych kursów ustawiasz w zakładce <a href="?p=kursy">Kursy</a>.</p>
    <?= field('installments', 'Możliwość płatności w ratach', $p['installments'], ['type' => 'checkbox']) ?>
    <?= field('installments_note', 'Opis rat (widoczny w cenniku i FAQ)', $p['installments_note'], ['type' => 'textarea', 'rows' => 2, 'maxlength' => 400]) ?>
  </fieldset>
  <fieldset>
    <legend>Pozostałe usługi</legend>
    <div class="grid-3">
      <?= field('extra_hour', 'Jazda doszkalająca (1 h)', $p['extra_hour'], ['type' => 'number', 'min' => 0, 'suffix' => 'zł']) ?>
      <?= field('apartment_day', 'Mieszkanie – doba', $p['apartment_day'], ['type' => 'number', 'min' => 0, 'suffix' => 'zł']) ?>
      <?= field('apartment_2weeks', 'Mieszkanie – 2 tygodnie', $p['apartment_2weeks'], ['type' => 'number', 'min' => 0, 'suffix' => 'zł']) ?>
    </div>
  </fieldset>
  <fieldset>
    <legend>Dodatkowe pozycje w cenniku <span class="muted">(opcjonalnie)</span></legend>
    <p class="help">Np. „Wynajem auta na egzamin”. Puste wiersze są pomijane; żeby usunąć pozycję, wyczyść jej nazwę.</p>
    <div class="extra-rows">
      <div class="extra-row extra-head"><span>Nazwa</span><span>Opis</span><span>Cena (zł)</span><span>Jednostka</span></div>
      <?php foreach ($extra as $i => $row): ?>
        <div class="extra-row">
          <input aria-label="Nazwa" name="extra[<?= $i ?>][name]" value="<?= e($row['name']) ?>" maxlength="120">
          <input aria-label="Opis" name="extra[<?= $i ?>][note]" value="<?= e($row['note']) ?>" maxlength="160">
          <input aria-label="Cena" name="extra[<?= $i ?>][price]" value="<?= e((string) $row['price']) ?>" type="number" min="0">
          <input aria-label="Jednostka" name="extra[<?= $i ?>][unit]" value="<?= e($row['unit']) ?>" maxlength="20" placeholder="np. h">
        </div>
      <?php endforeach; ?>
    </div>
  </fieldset>
  <div class="form-actions"><button class="btn btn-primary" type="submit">Zapisz cennik</button><a class="btn btn-ghost" href="/cennik" target="_blank" rel="noopener">Zobacz cennik</a></div>
</form>
