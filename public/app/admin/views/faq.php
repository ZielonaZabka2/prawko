<?php $faq = load_data('faq'); ?>
<h1>Pytania i odpowiedzi (FAQ)</h1>
<p class="muted">Podstawowe pytania (cena, raty, PKK, auto, nocleg…) tworzą się same z danych kursów i cennika. Tutaj dodasz <strong>własne</strong> pytania – trafią na stronę i do Google (wynik z rozwijanymi pytaniami). Pisz odpowiedź od konkretu, np. od liczby lub ceny.</p>
<form method="post" action="?p=faq" class="panel">
  <?= csrf_field() ?>
  <div class="repeater" data-repeater>
    <div class="rep-rows">
      <?php foreach ($faq as $i => $f): ?>
        <div class="rep-row rep-faq">
          <input name="faq[<?= $i ?>][q]" value="<?= e($f['q']) ?>" placeholder="Pytanie" maxlength="160" aria-label="Pytanie">
          <select name="faq[<?= $i ?>][where]" aria-label="Gdzie pokazać"><?php foreach (['home' => 'strona główna', 'kurs' => 'strony kursów', 'doszkalajace' => 'jazdy doszkalające', 'noclegi' => 'noclegi', 'all' => 'wszędzie'] as $k => $l): ?><option value="<?= $k ?>"<?= ($f['where'] ?? 'home') === $k ? ' selected' : '' ?>><?= $l ?></option><?php endforeach; ?></select>
          <textarea name="faq[<?= $i ?>][a]" rows="3" maxlength="800" placeholder="Odpowiedź" aria-label="Odpowiedź"><?= e($f['a']) ?></textarea>
          <button type="button" class="rep-del" aria-label="Usuń">×</button>
        </div>
      <?php endforeach; ?>
    </div>
    <template><div class="rep-row rep-faq"><input name="faq[__i][q]" placeholder="Pytanie" maxlength="160" aria-label="Pytanie"><select name="faq[__i][where]" aria-label="Gdzie pokazać"><option value="home">strona główna</option><option value="kurs">strony kursów</option><option value="doszkalajace">jazdy doszkalające</option><option value="noclegi">noclegi</option><option value="all">wszędzie</option></select><textarea name="faq[__i][a]" rows="3" maxlength="800" placeholder="Odpowiedź" aria-label="Odpowiedź"></textarea><button type="button" class="rep-del" aria-label="Usuń">×</button></div></template>
    <button type="button" class="btn btn-ghost rep-add">+ Dodaj pytanie</button>
  </div>
  <div class="form-actions"><button class="btn btn-primary" type="submit">Zapisz pytania</button></div>
</form>
