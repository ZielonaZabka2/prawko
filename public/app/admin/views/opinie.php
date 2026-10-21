<?php $rev = load_data('reviews'); ?>
<h1>Opinie kursantów</h1>
<p class="muted">Sekcja „Co mówią kursanci” pojawi się na stronie głównej, gdy dodasz pierwszą opinię. <strong>Wpisuj tylko prawdziwe opinie</strong> – najlepiej przepisane z Google lub Facebooka (za zgodą autora, wystarczy imię).</p>
<form method="post" action="?p=opinie" class="panel">
  <?= csrf_field() ?>
  <div class="repeater" data-repeater>
    <div class="rep-rows">
      <?php foreach ($rev as $i => $r): ?>
        <div class="rep-row rep-review">
          <input name="rev[<?= $i ?>][name]" value="<?= e($r['name']) ?>" placeholder="Imię (np. Kasia)" maxlength="60" aria-label="Imię">
          <select name="rev[<?= $i ?>][stars]" aria-label="Ocena"><?php for ($s = 5; $s >= 0; $s--): ?><option value="<?= $s ?>"<?= (int) ($r['stars'] ?? 5) === $s ? ' selected' : '' ?>><?= $s ? str_repeat('★', $s) : 'bez gwiazdek' ?></option><?php endfor; ?></select>
          <input name="rev[<?= $i ?>][source]" value="<?= e($r['source'] ?? '') ?>" placeholder="Źródło (Google, Facebook)" maxlength="40" aria-label="Źródło">
          <input type="date" name="rev[<?= $i ?>][date]" value="<?= e($r['date'] ?? '') ?>" aria-label="Data">
          <label class="rep-check"><input type="checkbox" name="rev[<?= $i ?>][visible]" value="1"<?= !empty($r['visible']) ? ' checked' : '' ?>> widoczna</label>
          <textarea name="rev[<?= $i ?>][text]" rows="3" maxlength="800" placeholder="Treść opinii" aria-label="Treść"><?= e($r['text']) ?></textarea>
          <button type="button" class="rep-del" aria-label="Usuń">×</button>
        </div>
      <?php endforeach; ?>
    </div>
    <template><div class="rep-row rep-review"><input name="rev[__i][name]" placeholder="Imię (np. Kasia)" maxlength="60" aria-label="Imię"><select name="rev[__i][stars]" aria-label="Ocena"><option value="5">★★★★★</option><option value="4">★★★★</option><option value="3">★★★</option><option value="0">bez gwiazdek</option></select><input name="rev[__i][source]" placeholder="Źródło (Google, Facebook)" maxlength="40" aria-label="Źródło"><input type="date" name="rev[__i][date]" aria-label="Data"><label class="rep-check"><input type="checkbox" name="rev[__i][visible]" value="1" checked> widoczna</label><textarea name="rev[__i][text]" rows="3" maxlength="800" placeholder="Treść opinii" aria-label="Treść"></textarea><button type="button" class="rep-del" aria-label="Usuń">×</button></div></template>
    <button type="button" class="btn btn-ghost rep-add">+ Dodaj opinię</button>
  </div>
  <div class="form-actions"><button class="btn btn-primary" type="submit">Zapisz opinie</button></div>
</form>
