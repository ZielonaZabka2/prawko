<?php $s = settings(); ?>
<h1>Dane firmy</h1>
<p class="muted">Te dane widać w stopce, na stronie kontaktowej, w regulaminie i w danych dla Google. Muszą być identyczne jak w wizytówce Google i na Facebooku.</p>
<form method="post" action="?p=firma" class="panel">
  <?= csrf_field() ?>
  <fieldset>
    <legend>Kontakt</legend>
    <div class="grid-2">
      <?= field('phone', 'Telefon', $s['phone'], ['required' => true, 'inputmode' => 'tel']) ?>
      <?= field('email', 'E-mail (widoczny na stronie)', $s['email'], ['type' => 'email', 'required' => true]) ?>
      <?= field('form_email', 'E-mail do zgłoszeń z formularza', $s['form_email'], ['type' => 'email', 'help' => 'Puste = ten sam co wyżej.']) ?>
      <?= field('owner', 'Właściciel / instruktor', $s['owner']) ?>
    </div>
    <div class="grid-3">
      <?= field('street', 'Ulica i numer', $s['street']) ?>
      <?= field('postal_code', 'Kod pocztowy', $s['postal_code']) ?>
      <?= field('city', 'Miasto', $s['city']) ?>
    </div>
    <?= field('hours', 'Godziny otwarcia / kontaktu', $s['hours'], ['type' => 'textarea', 'rows' => 3, 'help' => 'Każda linia osobno, np. „Pon–Pt: 9:00–17:00”.']) ?>
  </fieldset>
  <fieldset>
    <legend>Internet</legend>
    <div class="grid-2">
      <?= field('facebook', 'Profil na Facebooku (adres)', $s['facebook'], ['type' => 'url', 'placeholder' => 'https://www.facebook.com/…']) ?>
      <?= field('google_maps', 'Wizytówka Google (link)', $s['google_maps'], ['type' => 'url', 'placeholder' => 'https://maps.app.goo.gl/…']) ?>
    </div>
    <div class="grid-2">
      <?= field('lat', 'Szerokość geogr. (opcjonalnie)', $s['lat'], ['placeholder' => 'np. 53.17']) ?>
      <?= field('lng', 'Długość geogr. (opcjonalnie)', $s['lng'], ['placeholder' => 'np. 22.06']) ?>
    </div>
    <?= field('site_url', 'Adres strony', $s['site_url'], ['type' => 'url', 'help' => 'Zmień na https://…, gdy włączysz certyfikat SSL.']) ?>
  </fieldset>
  <fieldset>
    <legend>Dane rejestrowe (do regulaminu i polityki prywatności)</legend>
    <?= field('legal_name', 'Pełna nazwa firmy (jak w CEIDG)', $s['legal_name'], ['placeholder' => 'np. Artur Żyliński Ośrodek Szkolenia Kierowców …']) ?>
    <div class="grid-3">
      <?= field('nip', 'NIP', $s['nip']) ?>
      <?= field('regon', 'REGON', $s['regon']) ?>
      <?= field('osk_number', 'Nr w rejestrze OSK', $s['osk_number']) ?>
    </div>
    <div class="grid-2">
      <?= field('name', 'Nazwa wyświetlana', $s['name']) ?>
      <?= field('tagline', 'Hasło / podtytuł', $s['tagline']) ?>
    </div>
  </fieldset>
  <div class="form-actions"><button class="btn btn-primary" type="submit">Zapisz dane</button></div>
</form>
