<?php
$posts = posts();
$crumbs = [['Poradnik kursanta', '/blog']];
$meta = [
    'title' => 'Poradnik kursanta – prawo jazdy w Łomży krok po kroku',
    'description' => 'Praktyczne porady instruktora z Łomży: PKK, przebieg kursu, egzamin w WORD Łomża, jazdy doszkalające. Bez lania wody.',
    'path' => '/blog',
    'breadcrumbs' => $crumbs,
    'schema' => [[
        '@type' => 'Blog',
        'name' => 'Poradnik kursanta – OSK Artur Żyliński',
        'url' => site_url('blog'),
        'publisher' => ['@id' => schema_id('osk')],
        'blogPost' => array_map(fn($p) => ['@type' => 'BlogPosting', 'headline' => $p['title'], 'url' => site_url('blog/' . $p['slug']), 'datePublished' => $p['date']], $posts),
    ]],
];
?>
<section class="page-hero">
  <div class="wrap">
    <?= breadcrumbs_html($crumbs) ?>
    <h1>Poradnik kursanta</h1>
    <p class="lead narrow-text">Wszystko, co warto wiedzieć przed kursem i egzaminem na prawo jazdy w Łomży. Piszemy to, co tłumaczymy kursantom w aucie.</p>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <?php if (!$posts): ?>
      <p>Pierwsze artykuły pojawią się wkrótce.</p>
    <?php else: ?>
      <div class="post-grid">
        <?php foreach ($posts as $i => $post): ?>
          <article class="post-card<?= $i === 0 ? ' post-card-lead' : '' ?>">
            <a href="/blog/<?= e($post['slug']) ?>" class="post-card-img" tabindex="-1" aria-hidden="true">
              <?= !empty($post['cover']) ? img($post['cover'], '', $i === 0 ? '(min-width: 960px) 640px, 100vw' : '(min-width: 960px) 360px, 100vw', ['dir' => $post['cover_dir'] ?? 'uploads', 'default_w' => 480]) : '<span class="post-card-ph">' . icon('book', 'icon icon-xl') . '</span>' ?>
            </a>
            <div class="post-card-body">
              <time datetime="<?= e($post['date']) ?>"><?= e(date_pl($post['date'])) ?></time>
              <h2><a href="/blog/<?= e($post['slug']) ?>"><?= e($post['title']) ?></a></h2>
              <p><?= e($post['excerpt']) ?></p>
              <span class="text-link" aria-hidden="true">Czytaj <?= icon('arrow', 'icon icon-sm') ?></span>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
