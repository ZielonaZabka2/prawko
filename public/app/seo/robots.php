<?php
header('Content-Type: text/plain; charset=UTF-8');
?>
User-agent: *
Allow: /
Disallow: /admin/
Disallow: /kontakt?wyslano

# Boty wyszukiwarek i asystentów AI – mile widziane (GEO)
User-agent: GPTBot
Allow: /

User-agent: OAI-SearchBot
Allow: /

User-agent: ChatGPT-User
Allow: /

User-agent: PerplexityBot
Allow: /

User-agent: ClaudeBot
Allow: /

User-agent: Claude-SearchBot
Allow: /

User-agent: Google-Extended
Allow: /

User-agent: Applebot-Extended
Allow: /

Sitemap: <?= site_url('sitemap.xml') ?>

