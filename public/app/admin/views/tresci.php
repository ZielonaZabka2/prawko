<?php $c = load_data('content'); ?>
<h1>Treści strony głównej</h1>
<p class="muted">Teksty, które pojawiają się na stronie głównej we wszystkich wariantach wyglądu. W tekstach możesz wstawić link: <code>[tekst linku](/adres)</code>.</p>
<form method="post" action="?p=tresci" class="panel">
  <?= csrf_field() ?>
  <fieldset>
    <legend>Pierwszy ekran</legend>
    <?= field('hero_lead', 'Tekst pod głównym nagłówkiem', $c['hero_lead'], ['type' => 'textarea', 'rows' => 3, 'maxlength' => 300, 'help' => 'Zostaw puste, żeby użyć tekstu domyślnego wariantu.']) ?>
  </fieldset>
  <fieldset>
    <legend>„Dlaczego my” <span class="muted">(najlepiej 4–6 punktów)</span></legend>
    <div class="repeater" data-repeater>
      <div class="rep-rows">
        <?php foreach ($c['reasons'] as $i => [$t, $d]): ?>
          <div class="rep-row"><input name="reasons[<?= $i ?>][0]" value="<?= e($t) ?>" placeholder="Nagłówek" maxlength="80" aria-label="Nagłówek"><textarea name="reasons[<?= $i ?>][1]" rows="2" maxlength="300" placeholder="Opis" aria-label="Opis"><?= e($d) ?></textarea><button type="button" class="rep-del" aria-label="Usuń">×</button></div>
        <?php endforeach; ?>
      </div>
      <template><div class="rep-row"><input name="reasons[__i][0]" placeholder="Nagłówek" maxlength="80" aria-label="Nagłówek"><textarea name="reasons[__i][1]" rows="2" maxlength="300" placeholder="Opis" aria-label="Opis"></textarea><button type="button" class="rep-del" aria-label="Usuń">×</button></div></template>
      <button type="button" class="btn btn-ghost rep-add">+ Dodaj punkt</button>
    </div>
  </fieldset>
  <fieldset>
    <legend>Kroki do prawa jazdy</legend>
    <div class="repeater" data-repeater>
      <div class="rep-rows">
        <?php foreach ($c['steps'] as $i => [$t, $d]): ?>
          <div class="rep-row"><input name="steps[<?= $i ?>][0]" value="<?= e($t) ?>" placeholder="Krok" maxlength="60" aria-label="Krok"><textarea name="steps[<?= $i ?>][1]" rows="2" maxlength="300" placeholder="Opis" aria-label="Opis"><?= e($d) ?></textarea><button type="button" class="rep-del" aria-label="Usuń">×</button></div>
        <?php endforeach; ?>
      </div>
      <template><div class="rep-row"><input name="steps[__i][0]" placeholder="Krok" maxlength="60" aria-label="Krok"><textarea name="steps[__i][1]" rows="2" maxlength="300" placeholder="Opis" aria-label="Opis"></textarea><button type="button" class="rep-del" aria-label="Usuń">×</button></div></template>
      <button type="button" class="btn btn-ghost rep-add">+ Dodaj krok</button>
    </div>
    <p class="help">Wariant C najlepiej wygląda przy 6 krokach.</p>
  </fieldset>
  <div class="form-actions"><button class="btn btn-primary" type="submit">Zapisz treści</button><a class="btn btn-ghost" href="/" target="_blank" rel="noopener">Zobacz stronę</a></div>
</form>
