<?php
/** @var array $post */
$s = settings();
$dir = $post['cover_dir'] ?? 'uploads';
$crumbs = [['Poradnik kursanta', '/blog'], [$post['title'], '/blog/' . $post['slug']]];
$toc = md_headings($post['content']);
$meta = [
    'title' => $post['meta_title'] ?: $post['title'],
    'description' => $post['meta_description'] ?: md_plain($post['excerpt'] ?: $post['content'], 158),
    'path' => '/blog/' . $post['slug'],
    'og_type' => 'article',
    'og_image' => !empty($post['cover']) ? image_url($post['cover'], $dir) : site_url('assets/img/og-image.jpg'),
    'breadcrumbs' => $crumbs,
    'schema' => [schema_post($post)],
    'noindex' => empty($post['published']),
];
$others = array_slice(array_values(array_filter(posts(), fn($p) => $p['slug'] !== $post['slug'])), 0, 2);
?>
<?php if (empty($post['published'])): ?><div class="draft-bar">Szkic – ten wpis widzisz tylko Ty (jesteś zalogowany w panelu).</div><?php endif; ?>
<article class="post">
  <header class="page-hero post-hero">
    <div class="wrap narrow">
      <?= breadcrumbs_html($crumbs) ?>
      <h1><?= e($post['title']) ?></h1>
      <p class="byline">
        <span><?= icon('user', 'icon icon-sm') ?> <?= e($s['owner']) ?>, instruktor nauki jazdy</span>
        <span><?= icon('calendar', 'icon icon-sm') ?> <time datetime="<?= e($post['date']) ?>"><?= e(date_pl($post['date'])) ?></time></span>
        <?php if (!empty($post['updated']) && $post['updated'] !== $post['date']): ?>
          <span>aktualizacja: <time datetime="<?= e($post['updated']) ?>"><?= e(date_pl($post['updated'])) ?></time></span>
        <?php endif; ?>
      </p>
    </div>
  </header>

  <?php if (!empty($post['cover'])): ?>
    <figure class="wrap narrow post-cover">
      <?= img($post['cover'], $post['title'], '(min-width: 800px) 760px, 100vw', ['dir' => $dir, 'eager' => true]) ?>
    </figure>
  <?php endif; ?>

  <div class="wrap narrow">
    <?php if (count($toc) >= 3): ?>
      <nav class="toc" aria-label="Spis treści">
        <p class="toc-h">W artykule</p>
        <ol>
          <?php foreach ($toc as $h): ?><li><a href="#<?= e($h['id']) ?>"><?= md_inline($h['text']) ?></a></li><?php endforeach; ?>
        </ol>
      </nav>
    <?php endif; ?>
    <div class="prose post-body">
      <?= md_to_html($post['content']) ?>
    </div>

    <aside class="author-box">
      <img src="/assets/img/logo-96.webp" width="56" height="56" alt="" loading="lazy">
      <div>
        <p class="author-name"><?= e($s['owner']) ?></p>
        <p>Instruktor nauki jazdy i właściciel OSK „Szkoła Bezpiecznej Jazdy” w Łomży. Uczy kursantów kat. B i prowadzi jazdy doszkalające.</p>
      </div>
    </aside>

    <div class="cta-inline">
      <p>Chcesz zacząć kurs albo dopytać o szczegóły?</p>
      <a class="btn btn-primary" href="<?= e(phone_href()) ?>"><?= icon('phone') ?> <?= e($s['phone']) ?></a>
      <a class="btn btn-outline" href="/kontakt#zapis">Zostaw numer</a>
    </div>
  </div>
</article>

<?php if ($others): ?>
<section class="section section-alt">
  <div class="wrap">
    <h2>Przeczytaj też</h2>
    <div class="post-grid post-grid-2">
      <?php foreach ($others as $o): ?>
        <article class="post-card">
          <div class="post-card-body">
            <time datetime="<?= e($o['date']) ?>"><?= e(date_pl($o['date'])) ?></time>
            <h3><a href="/blog/<?= e($o['slug']) ?>"><?= e($o['title']) ?></a></h3>
            <p><?= e($o['excerpt']) ?></p>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>
