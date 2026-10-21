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
  <fieldset id="poczta">
    <legend>Poczta i formularz kontaktowy</legend>
    <p class="help">Zalecane: załóż w panelu LH.pl skrzynkę <strong>biuro@<?= e(preg_replace('~^www\.~', '', (string) parse_url($s['site_url'], PHP_URL_HOST))) ?></strong> i wpisz jej dane poniżej. Wiadomości z formularza będą wtedy wysyłane z adresu w domenie strony – nie trafią do spamu. Instrukcja: <code>docs/WDROZENIE.md</code>.</p>
    <div class="grid-2">
      <?= field('form_email', 'Gdzie wysyłać wiadomości z formularza', $s['form_email'], ['type' => 'email', 'help' => 'Np. biuro@… albo prywatny Gmail. Puste = e-mail widoczny na stronie.']) ?>
      <?= field('mail_from', 'Adres nadawcy (skrzynka w domenie)', $s['mail_from'] ?? '', ['type' => 'email', 'placeholder' => 'biuro@' . preg_replace('~^www\.~', '', (string) parse_url($s['site_url'], PHP_URL_HOST))]) ?>
    </div>
    <div class="field">
      <span class="label">Sposób wysyłki</span>
      <div class="seg">
        <label><input type="radio" name="mail_mode" value="smtp"<?= ($s['mail_mode'] ?? 'php') === 'smtp' ? ' checked' : '' ?>> Przez skrzynkę (SMTP) – zalecane</label>
        <label><input type="radio" name="mail_mode" value="php"<?= ($s['mail_mode'] ?? 'php') !== 'smtp' ? ' checked' : '' ?>> Funkcja serwera (mail)</label>
      </div>
    </div>
    <div class="grid-3">
      <?= field('smtp_host', 'Serwer poczty (SMTP)', $s['smtp_host'] ?? '', ['placeholder' => 'np. serwer123456.lh.pl', 'help' => 'Panel LH.pl → Poczta → skrzynka → „Konfiguracja programu pocztowego”.']) ?>
      <?= field('smtp_port', 'Port', $s['smtp_port'] ?? 465, ['type' => 'number', 'min' => 1, 'max' => 65535, 'help' => '465 (SSL) albo 587 (TLS).']) ?>
      <div class="field">
        <label for="f-smtp_secure">Szyfrowanie</label>
        <select id="f-smtp_secure" name="smtp_secure">
          <?php foreach (['ssl' => 'SSL (port 465)', 'tls' => 'TLS / STARTTLS (port 587)', 'none' => 'brak (niezalecane)'] as $k => $l): ?><option value="<?= $k ?>"<?= ($s['smtp_secure'] ?? 'ssl') === $k ? ' selected' : '' ?>><?= $l ?></option><?php endforeach; ?>
        </select>
      </div>
    </div>
    <div class="grid-2">
      <?= field('smtp_user', 'Login skrzynki', $s['smtp_user'] ?? '', ['placeholder' => 'pełny adres, np. biuro@…', 'autocomplete' => 'off']) ?>
      <?= field('smtp_pass', 'Hasło skrzynki', '', ['type' => 'password', 'autocomplete' => 'new-password', 'placeholder' => !empty($s['smtp_pass']) ? '•••••••• (zapisane – zostaw puste, żeby nie zmieniać)' : '', 'help' => 'Hasło jest zapisane na serwerze w katalogu zablokowanym dla przeglądarki.']) ?>
    </div>
    <?= field('mail_from_name', 'Nazwa nadawcy', $s['mail_from_name'] ?? '', ['placeholder' => $s['name']]) ?>
    <?= field('autoreply', 'Wysyłaj automatyczne potwierdzenie do osoby, która napisała (jeśli podała e-mail)', $s['autoreply'] ?? true, ['type' => 'checkbox']) ?>
    <?= field('autoreply_text', 'Treść potwierdzenia', $s['autoreply_text'] ?? '', ['type' => 'textarea', 'rows' => 3, 'maxlength' => 600, 'help' => 'Zaczyna się od „Dzień dobry [imię],”, a kończy podpisem i danymi kontaktowymi.']) ?>
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

<form method="post" action="?p=mailtest" class="panel narrow-panel">
  <?= csrf_field() ?>
  <h2 style="margin-top:0">Test poczty</h2>
  <p class="muted">Najpierw zapisz ustawienia powyżej, potem wyślij test. Wiadomość przyjdzie na adres: <strong><?= e(contact_inbox()) ?></strong>.</p>
  <button class="btn btn-primary" type="submit">Wyślij testowy e-mail</button>
</form>
