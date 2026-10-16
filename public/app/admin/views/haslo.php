<h1>Zmiana hasła</h1>
<form method="post" action="?p=haslo" class="panel narrow-panel">
  <?= csrf_field() ?>
  <?= field('current', 'Obecne hasło', '', ['type' => 'password', 'required' => true, 'autocomplete' => 'current-password']) ?>
  <?= field('new', 'Nowe hasło', '', ['type' => 'password', 'required' => true, 'autocomplete' => 'new-password', 'help' => 'Minimum 10 znaków. Dobre hasło to np. 3–4 przypadkowe słowa.']) ?>
  <?= field('repeat', 'Powtórz nowe hasło', '', ['type' => 'password', 'required' => true, 'autocomplete' => 'new-password']) ?>
  <div class="form-actions"><button class="btn btn-primary" type="submit">Zmień hasło</button></div>
</form>
