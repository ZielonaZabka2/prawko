<?php
/** @var bool $compact */
$form = $GLOBALS['contact_form'] ?? ['ok' => false, 'errors' => [], 'old' => []];
$err = $form['errors'];
$old = $form['old'] + ['name' => '', 'phone' => '', 'email' => '', 'topic' => $_GET['temat'] ?? 'kurs', 'message' => '', 'consent' => false];
$compact = $compact ?? false;
$fid = $compact ? 'cf-c' : 'cf';
$fieldErr = function (string $k) use ($err, $fid) {
    return isset($err[$k]) ? '<p class="field-error" id="' . $fid . '-' . $k . '-err">' . e($err[$k]) . '</p>' : '';
};
$describedBy = fn(string $k) => isset($err[$k]) ? ' aria-invalid="true" aria-describedby="' . $fid . '-' . $k . '-err"' : '';
?>
<?php if ($form['ok']): ?>
  <div class="form-card form-success" role="status" id="zapis">
    <?= icon('check', 'icon icon-xl') ?>
    <h3>Dziękujemy, zgłoszenie dotarło</h3>
    <p>Oddzwonimy najszybciej, jak to możliwe – zwykle tego samego dnia. Jeśli sprawa jest pilna, zadzwoń: <a href="<?= e(phone_href()) ?>"><?= e(settings()['phone']) ?></a>.</p>
  </div>
<?php else: ?>
  <form class="form-card" method="post" action="/kontakt#zapis" <?= $compact ? '' : 'id="zapis"' ?> novalidate>
    <?= csrf_field() ?>
    <input type="hidden" name="ts" value="<?= time() ?>">
    <div class="hp" aria-hidden="true"><label>Strona www <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
    <?php if (isset($err['form'])): ?><p class="form-alert" role="alert"><?= e($err['form']) ?></p><?php endif; ?>

    <div class="field-row">
      <div class="field">
        <label for="<?= $fid ?>-name">Imię</label>
        <input id="<?= $fid ?>-name" name="name" type="text" autocomplete="given-name" required maxlength="100" value="<?= e($old['name']) ?>"<?= $describedBy('name') ?>>
        <?= $fieldErr('name') ?>
      </div>
      <div class="field">
        <label for="<?= $fid ?>-phone">Telefon</label>
        <input id="<?= $fid ?>-phone" name="phone" type="tel" autocomplete="tel" inputmode="tel" required maxlength="30" value="<?= e($old['phone']) ?>"<?= $describedBy('phone') ?>>
        <?= $fieldErr('phone') ?>
      </div>
    </div>
    <?php if (!$compact): ?>
    <div class="field">
      <label for="<?= $fid ?>-email">E-mail <span class="opt">(opcjonalnie)</span></label>
      <input id="<?= $fid ?>-email" name="email" type="email" autocomplete="email" maxlength="150" value="<?= e($old['email']) ?>"<?= $describedBy('email') ?>>
      <?= $fieldErr('email') ?>
    </div>
    <?php endif; ?>
    <div class="field">
      <label for="<?= $fid ?>-topic">W jakiej sprawie?</label>
      <select id="<?= $fid ?>-topic" name="topic">
        <?php foreach (contact_topics() as $k => $label): ?>
          <option value="<?= e($k) ?>"<?= $old['topic'] === $k ? ' selected' : '' ?>><?= e($label) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="field">
      <label for="<?= $fid ?>-message">Wiadomość <span class="opt">(opcjonalnie)</span></label>
      <textarea id="<?= $fid ?>-message" name="message" rows="<?= $compact ? 3 : 5 ?>" maxlength="3000" placeholder="Np. kiedy chcesz zacząć, czy masz już PKK"<?= $describedBy('message') ?>><?= e($old['message']) ?></textarea>
      <?= $fieldErr('message') ?>
    </div>
    <div class="field field-check">
      <input id="<?= $fid ?>-consent" name="consent" type="checkbox" value="1" required<?= $old['consent'] ? ' checked' : '' ?><?= $describedBy('consent') ?>>
      <label for="<?= $fid ?>-consent">Wyrażam zgodę na kontakt w sprawie zgłoszenia. Zasady przetwarzania danych: <a href="/polityka-prywatnosci">polityka prywatności</a>.</label>
      <?= $fieldErr('consent') ?>
    </div>
    <button class="btn btn-primary btn-block" type="submit">Wyślij zgłoszenie <?= icon('arrow') ?></button>
  </form>
<?php endif; ?>
