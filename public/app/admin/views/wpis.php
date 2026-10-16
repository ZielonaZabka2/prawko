<?php
$id = (string) ($_GET['id'] ?? '');
$post = null;
foreach (posts(false) as $p) { if ($p['id'] === $id) { $post = $p; break; } }
$post ??= ['id' => '', 'title' => '', 'slug' => '', 'excerpt' => '', 'content' => '', 'meta_title' => '', 'meta_description' => '', 'date' => date('Y-m-d'), 'published' => false, 'cover' => '', 'cover_dir' => 'uploads'];
$isNew = $post['id'] === '';
?>
<div class="head-row">
  <h1><?= $isNew ? 'Nowy wpis' : 'Edycja wpisu' ?></h1>
  <?php if (!$isNew): ?><a class="btn btn-ghost" href="/blog/<?= e($post['slug']) ?>" target="_blank" rel="noopener">Podgląd na stronie</a><?php endif; ?>
</div>
<form method="post" action="?p=wpis" enctype="multipart/form-data" class="panel" id="post-form">
  <?= csrf_field() ?>
  <input type="hidden" name="id" value="<?= e($post['id']) ?>">
  <div class="post-layout">
    <div>
      <?= field('title', 'Tytuł', $post['title'], ['required' => true, 'maxlength' => 160, 'placeholder' => 'Np. Ile trwa kurs prawa jazdy w Łomży?']) ?>
      <?= field('excerpt', 'Zajawka (2 zdania na liście wpisów)', $post['excerpt'], ['type' => 'textarea', 'rows' => 2, 'maxlength' => 400]) ?>
      <div class="field">
        <label for="f-content">Treść</label>
        <div class="md-toolbar" role="toolbar" aria-label="Formatowanie">
          <button type="button" data-md="h2" title="Śródtytuł">Śródtytuł</button>
          <button type="button" data-md="bold" title="Pogrubienie"><strong>B</strong></button>
          <button type="button" data-md="ul" title="Lista">• Lista</button>
          <button type="button" data-md="ol" title="Lista numerowana">1. Lista</button>
          <button type="button" data-md="link" title="Link">Link</button>
          <button type="button" data-md="table" title="Tabela">Tabela</button>
        </div>
        <textarea id="f-content" name="content" rows="22" class="md-area"><?= e($post['content']) ?></textarea>
        <details class="help md-help">
          <summary>Jak formatować tekst?</summary>
          <p><code>## Śródtytuł</code> – nowa sekcja (najlepiej jako pytanie, np. „## Ile kosztuje…?”)<br>
          <code>**ważne**</code> – pogrubienie · <code>- punkt</code> – lista · <code>1. krok</code> – lista numerowana<br>
          <code>[tekst linku](/cennik)</code> – link · pusta linia = nowy akapit</p>
          <p><strong>Wskazówka SEO:</strong> zacznij artykuł i każdą sekcję od 1–2 zdań z konkretną odpowiedzią (liczba, cena, czas). Tak piszą strony, które Google i ChatGPT cytują najchętniej.</p>
        </details>
      </div>
    </div>
    <aside>
      <div class="side-box">
        <?= field('published', '<strong>Opublikowany</strong> (widoczny na stronie)', $post['published'], ['type' => 'checkbox']) ?>
        <?= field('date', 'Data publikacji', $post['date'], ['type' => 'date']) ?>
        <div class="form-actions"><button class="btn btn-primary btn-block" type="submit">Zapisz wpis</button></div>
      </div>
      <div class="side-box">
        <p class="side-h">Zdjęcie główne</p>
        <?php if ($post['cover']): ?>
          <?= img($post['cover'], '', '300px', ['dir' => $post['cover_dir'], 'default_w' => 480, 'class' => 'cover-prev']) ?>
          <?= field('remove_cover', 'Usuń zdjęcie', false, ['type' => 'checkbox']) ?>
        <?php endif; ?>
        <div class="field"><label for="f-cover"><?= $post['cover'] ? 'Zmień zdjęcie' : 'Wgraj zdjęcie' ?></label><input type="file" id="f-cover" name="cover" accept="image/jpeg,image/png,image/webp"><p class="help">JPG/PNG z telefonu – zostanie automatycznie zmniejszone.</p></div>
      </div>
      <div class="side-box">
        <p class="side-h">Google (SEO)</p>
        <?= field('slug', 'Adres wpisu', $post['slug'], ['maxlength' => 120, 'placeholder' => 'utworzy się z tytułu', 'help' => 'Tylko małe litery i myślniki.']) ?>
        <?= field('meta_title', 'Tytuł w Google', $post['meta_title'], ['maxlength' => 70, 'help' => 'Do 60 znaków. Puste = tytuł wpisu.']) ?>
        <?= field('meta_description', 'Opis w Google', $post['meta_description'], ['type' => 'textarea', 'rows' => 3, 'maxlength' => 170, 'help' => 'Do 160 znaków. Puste = zajawka.']) ?>
      </div>
    </aside>
  </div>
</form>
<?php if (!$isNew): ?>
<form method="post" action="?p=wpis" class="danger-zone" data-confirm="Na pewno usunąć ten wpis? Tego nie da się cofnąć.">
  <?= csrf_field() ?>
  <input type="hidden" name="id" value="<?= e($post['id']) ?>">
  <input type="hidden" name="delete" value="1">
  <button class="btn btn-danger" type="submit">Usuń wpis</button>
</form>
<?php endif; ?>
