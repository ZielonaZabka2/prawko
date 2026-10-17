<?php $s = settings(); ?>
<section class="section section-dark cta-section" id="zapis-home" aria-labelledby="zapis-h">
  <div class="wrap split">
    <div data-reveal>
      <p class="eyebrow">Zapisy</p>
      <h2 id="zapis-h">Zostaw numer – oddzwonimy i ustalimy start kursu</h2>
      <p>Wolisz porozmawiać od razu? Zadzwoń<?= $s['hours'] ? ' (' . e(str_replace("\n", ', ', $s['hours'])) . ')' : '' ?>. Zapisy przyjmujemy też osobiście w biurze przy ul. Dworna 1.</p>
      <ul class="contact-list">
        <li><?= icon('phone') ?> <a href="<?= e(phone_href()) ?>"><?= e($s['phone']) ?></a></li>
        <li><?= icon('mail') ?> <a href="mailto:<?= e($s['email']) ?>"><?= e($s['email']) ?></a></li>
        <li><?= icon('pin') ?> <span><?= e(full_address()) ?></span></li>
        <?php if ($s['hours']): ?><li><?= icon('clock') ?> <span><?= e(str_replace("\n", ', ', $s['hours'])) ?></span></li><?php endif; ?>
      </ul>
    </div>
    <div data-reveal><?php partial('contact-form', ['compact' => true]); ?></div>
  </div>
</section>
