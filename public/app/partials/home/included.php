<?php /** @var array $c @var string $cat */ $p = prices(); ?>
<section class="section" aria-labelledby="w-cenie">
  <div class="wrap split">
    <div data-reveal>
      <h2 id="w-cenie">Co dostajesz w cenie kursu <?= e($cat) ?>?</h2>
      <p class="bluf">W cenie <?= zl(course_price($c)) ?> masz komplet szkolenia: <?= (int) $c['theory_hours'] ?> godzin teorii, <?= (int) $c['practice_hours'] ?> godzin jazd z instruktorem<?= (int) $c['bonus_hours'] ? ', godzinę jazdy gratis' : '' ?><?= course_includes($c) ? ', ' . e(implode(', ', course_includes($c))) : '' ?>. Nie dopłacasz za nic, co jest potrzebne do ukończenia kursu.</p>
      <p>Poza ceną kursu zostają tylko dwie rzeczy, które opłacasz u innych instytucji: badanie lekarskie i opłaty za egzamin państwowy w WORD. Mówimy o tym od razu, żeby nie było niespodzianek.</p>
      <a class="text-link" href="<?= e(course_url($c)) ?>">Jak wygląda kurs krok po kroku <?= icon('arrow', 'icon icon-sm') ?></a>
    </div>
    <div class="table-wrap" data-reveal>
      <table class="offer-table">
        <caption class="sr-only">Zakres kursu prawa jazdy <?= e($cat) ?></caption>
        <thead><tr><th scope="col">Element kursu</th><th scope="col">W cenie</th></tr></thead>
        <tbody>
          <tr><th scope="row">Zajęcia teoretyczne</th><td><?= (int) $c['theory_hours'] ?> h</td></tr>
          <tr><th scope="row">Jazdy z instruktorem</th><td><?= (int) $c['practice_hours'] ?> h</td></tr>
          <?php if ((int) $c['bonus_hours']): ?><tr><th scope="row">Dodatkowa jazda</th><td><?= (int) $c['bonus_hours'] ?> h gratis</td></tr><?php endif; ?>
          <?php foreach (course_includes($c) as $inc): ?><tr><th scope="row"><?= e(mb_strtoupper(mb_substr($inc, 0, 1)) . mb_substr($inc, 1)) ?></th><td>tak</td></tr><?php endforeach; ?>
          <tr><th scope="row">Płatność w ratach</th><td><?= !empty($p['installments']) ? 'możliwa' : 'zapytaj' ?></td></tr>
          <tr class="row-total"><th scope="row">Cena kursu</th><td><?= zl(course_price($c)) ?></td></tr>
        </tbody>
      </table>
    </div>
  </div>
</section>
