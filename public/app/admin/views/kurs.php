<?php
$id = (string) ($_GET['id'] ?? '');
$c = null;
foreach (courses(false) as $x) { if (($x['id'] ?? '') === $id) { $c = $x; break; } }
$isNew = $c === null;
$c ??= course_normalize(['id' => '', 'active' => false, 'order' => count(courses(false)) + 1, 'content' => "## Ile kosztuje kurs prawa jazdy kat. {kategoria}?\n\nKurs kosztuje {cena} i obejmuje {teoria} godzin teorii oraz {praktyka} godzin jazd.\n\n## Jak wygląda kurs?\n\n…"]);
?>
<div class="head-row">
  <h1><?= $isNew ? 'Nowy kurs' : e($c['name']) ?></h1>
  <?php if (!$isNew): ?><a class="btn btn-ghost" href="<?= e(course_url($c)) ?>" target="_blank" rel="noopener">Podgląd na stronie</a><?php endif; ?>
</div>
<form method="post" action="?p=kurs" class="panel">
  <?= csrf_field() ?>
  <input type="hidden" name="id" value="<?= e($c['id'] ?? '') ?>">
  <div class="post-layout">
    <div>
      <div class="grid-3">
        <?= field('category', 'Kategoria', $c['category'], ['required' => true, 'maxlength' => 10, 'placeholder' => 'np. B, A2, C+E']) ?>
        <?= field('price', 'Cena kursu', $c['price'], ['type' => 'number', 'min' => 0, 'suffix' => 'zł', 'required' => true]) ?>
        <?= field('vehicle', 'Pojazd szkoleniowy', $c['vehicle'], ['maxlength' => 80, 'placeholder' => 'np. Kia Rio IV']) ?>
      </div>
      <?= field('duration', 'Czas trwania kursu', $c['duration'], ['maxlength' => 80, 'placeholder' => 'np. 14 dni, zajęcia codziennie', 'help' => 'Widoczne w porównaniu kursów na stronie „Kursy”.']) ?>
      <div class="grid-3">
        <?= field('theory_hours', 'Godziny teorii', $c['theory_hours'], ['type' => 'number', 'min' => 0, 'suffix' => 'h']) ?>
        <?= field('practice_hours', 'Godziny jazd', $c['practice_hours'], ['type' => 'number', 'min' => 0, 'suffix' => 'h']) ?>
        <?= field('bonus_hours', 'Jazdy gratis', $c['bonus_hours'], ['type' => 'number', 'min' => 0, 'suffix' => 'h', 'help' => '0 = brak']) ?>
      </div>
      <div class="grid-2">
        <?= field('name', 'Nazwa kursu', $c['name'], ['maxlength' => 100, 'placeholder' => 'Kurs prawa jazdy kat. A']) ?>
        <?= field('short', 'Krótki opis (np. rodzaj pojazdu)', $c['short'], ['maxlength' => 60, 'placeholder' => 'Motocykl']) ?>
      </div>
      <?= field('lead', 'Wstęp (pod tytułem strony kursu)', $c['lead'], ['type' => 'textarea', 'rows' => 2, 'maxlength' => 300]) ?>
      <?= field('includes', 'Co jeszcze jest w cenie (jedna rzecz w linii)', $c['includes'], ['type' => 'textarea', 'rows' => 3, 'maxlength' => 600]) ?>
      <div class="field">
        <label for="f-content">Opis kursu</label>
        <div class="md-toolbar" role="toolbar" aria-label="Formatowanie">
          <button type="button" data-md="h2">Śródtytuł</button>
          <button type="button" data-md="bold"><strong>B</strong></button>
          <button type="button" data-md="ul">• Lista</button>
          <button type="button" data-md="ol">1. Lista</button>
          <button type="button" data-md="link">Link</button>
          <button type="button" data-md="table">Tabela</button>
        </div>
        <textarea id="f-content" name="content" rows="20" class="md-area"><?= e($c['content']) ?></textarea>
        <p class="help">Znaczniki wstawiane automatycznie: <code>{cena}</code> <code>{cena_regularna}</code> <code>{teoria}</code> <code>{praktyka}</code> <code>{gratis}</code> <code>{auto}</code> <code>{kategoria}</code>. Dzięki nim po zmianie ceny nie trzeba poprawiać opisu.</p>
      </div>
    </div>
    <aside>
      <div class="side-box">
        <?= field('active', '<strong>Widoczny na stronie</strong>', $c['active'], ['type' => 'checkbox']) ?>
        <?= field('featured', 'Kurs główny (na stronie głównej)', $c['featured'], ['type' => 'checkbox']) ?>
        <?= field('order', 'Kolejność', $c['order'], ['type' => 'number', 'min' => 1, 'max' => 99]) ?>
        <div class="form-actions"><button class="btn btn-primary btn-block" type="submit">Zapisz kurs</button></div>
      </div>
      <div class="side-box">
        <p class="side-h">Google (SEO)</p>
        <?= field('slug', 'Adres', $c['slug'], ['maxlength' => 40, 'placeholder' => 'kat-a', 'help' => 'Strona: /kurs-prawa-jazdy-<strong>adres</strong>']) ?>
        <?= field('meta_title', 'Tytuł w Google', $c['meta_title'], ['maxlength' => 70, 'help' => 'Puste = ustawi się automatycznie.']) ?>
        <?= field('meta_description', 'Opis w Google', $c['meta_description'], ['type' => 'textarea', 'rows' => 3, 'maxlength' => 170, 'help' => 'Puste = ustawi się automatycznie.']) ?>
      </div>
    </aside>
  </div>
</form>
<?php if (!$isNew): ?>
<form method="post" action="?p=kurs" class="danger-zone" data-confirm="Usunąć ten kurs? Lepiej go ukryć, jeśli wróci w przyszłości.">
  <?= csrf_field() ?>
  <input type="hidden" name="id" value="<?= e($c['id']) ?>">
  <input type="hidden" name="delete" value="1">
  <button class="btn btn-danger" type="submit">Usuń kurs</button>
</form>
<?php endif; ?>
