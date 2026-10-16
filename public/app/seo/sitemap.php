<?php
header('Content-Type: application/xml; charset=UTF-8');
$today = date('Y-m-d');
$urls = [
    ['', '1.0', 'weekly'],
    ['kurs-prawa-jazdy-kat-b', '0.9', 'monthly'],
    ['cennik', '0.9', 'weekly'],
    ['jazdy-doszkalajace', '0.8', 'monthly'],
    ['noclegi-dla-kursantow', '0.8', 'monthly'],
    ['kontakt', '0.7', 'yearly'],
    ['blog', '0.6', 'weekly'],
    ['regulamin', '0.2', 'yearly'],
    ['polityka-prywatnosci', '0.2', 'yearly'],
];
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($urls as [$path, $prio, $freq]) {
    echo '  <url><loc>' . e(site_url($path)) . "</loc><changefreq>$freq</changefreq><priority>$prio</priority></url>\n";
}
foreach (posts() as $post) {
    echo '  <url><loc>' . e(site_url('blog/' . $post['slug'])) . '</loc><lastmod>' . e($post['updated'] ?: $post['date']) . "</lastmod><priority>0.6</priority></url>\n";
}
echo '</urlset>';
