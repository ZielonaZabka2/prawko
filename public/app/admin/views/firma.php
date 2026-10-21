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
    <div class="grid-2">
      <?= field('hours', 'Godziny otwarcia (widoczne na stronie)', $s['hours'], ['type' => 'textarea', 'rows' => 3, 'help' => 'Każda linia osobno, np. „Sobota: 9:00–13:00”.']) ?>
      <?= field('hours_schema', 'Godziny dla Google', $s['hours_schema'], ['help' => 'Format: <code>Mo-Fr 08:00-18:00, Sa 09:00-13:00</code> (Mo Tu We Th Fr Sa Su).']) ?>
    </div>
  </fieldset>
  <fieldset>
    <legend>Wygląd strony głównej</legend>
    <div class="variants">
      <?php foreach (['a' => ['Asfalt', 'Ciemny, cena na pierwszym planie, auto na drodze'], 'b' => ['Trasa', 'Jasny, redakcyjny, kroki jako trasa z mapą'], 'c' => ['Pas startowy', 'Pełnoekranowe zdjęcie, przewijany pasek, karty kursów']] as $k => [$label, $desc]): ?>
        <label class="variant">
          <input type="radio" name="home_variant" value="<?= $k ?>"<?= ($s['home_variant'] ?? 'a') === $k ? ' checked' : '' ?>>
          <span><strong><?= strtoupper($k) ?> · <?= e($label) ?></strong><small><?= e($desc) ?></small><a href="/?wariant=<?= $k ?>" target="_blank" rel="noopener">Podgląd</a></span>
        </label>
      <?php endforeach; ?>
    </div>
  </fieldset>
  <fieldset>
    <legend>Komunikat na górze strony <span class="muted">(opcjonalnie)</span></legend>
    <p class="help">Np. „1 i 11 listopada biuro nieczynne” albo „Nowy termin kursu od 3 listopada”. Pokazuje się w żółtym pasku nad menu, tylko w wybranych dniach.</p>
    <?= field('notice_text', 'Treść komunikatu', $s['notice_text'] ?? '', ['maxlength' => 160, 'help' => 'Puste = brak komunikatu.']) ?>
    <div class="grid-3">
      <?= field('notice_start', 'Od dnia', $s['notice_start'] ?? '', ['type' => 'date']) ?>
      <?= field('notice_end', 'Do dnia', $s['notice_end'] ?? '', ['type' => 'date']) ?>
      <?= field('notice_link', 'Link „Szczegóły” (opcjonalnie)', $s['notice_link'] ?? '', ['placeholder' => '/kontakt']) ?>
    </div>
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
    <div class="grid-2">
      <?= field('gsc_verification', 'Kod weryfikacyjny Google Search Console', $s['gsc_verification'] ?? '', ['help' => 'Z Search Console → Weryfikacja → Tag HTML: wklej tylko wartość <code>content</code>.']) ?>
      <?= field('bing_verification', 'Kod weryfikacyjny Bing Webmaster', $s['bing_verification'] ?? '', ['help' => 'Wartość <code>content</code> z tagu msvalidate.01.']) ?>
    </div>
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
