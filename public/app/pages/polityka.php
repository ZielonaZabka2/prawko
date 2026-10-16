<?php
$s = settings();
$crumbs = [['Polityka prywatności', '/polityka-prywatnosci']];
$meta = [
    'title' => 'Polityka prywatności i cookies – OSK Artur Żyliński',
    'description' => 'Jak OSK Artur Żyliński w Łomży przetwarza dane osobowe kursantów i osób korzystających z formularza kontaktowego. Informacja o plikach cookies.',
    'path' => '/polityka-prywatnosci',
    'breadcrumbs' => $crumbs,
];
$company = $s['legal_name'] ?: $s['owner'] . ', prowadzący działalność pod nazwą ' . $s['name'] . ' – ' . $s['tagline'];
?>
<section class="page-hero">
  <div class="wrap narrow">
    <?= breadcrumbs_html($crumbs) ?>
    <h1>Polityka prywatności</h1>
    <p class="lead">Kto przetwarza Twoje dane, w jakim celu i jakie masz prawa. Krótko i konkretnie.</p>
  </div>
</section>

<section class="section">
  <div class="wrap narrow prose legal">
    <h2>1. Administrator danych</h2>
    <p>Administratorem danych osobowych jest <?= e($company) ?>, <?= e(full_address()) ?><?= $s['nip'] ? ', NIP ' . e($s['nip']) : '' ?>. Kontakt w sprawach danych osobowych: <?= e($s['email']) ?>, tel. <?= e($s['phone']) ?>.</p>

    <h2>2. Jakie dane i po co</h2>
    <div class="table-wrap">
      <table>
        <thead><tr><th scope="col">Cel</th><th scope="col">Dane</th><th scope="col">Podstawa prawna</th><th scope="col">Jak długo</th></tr></thead>
        <tbody>
          <tr><td>Odpowiedź na zgłoszenie z formularza lub telefon</td><td>imię, telefon, e-mail, treść wiadomości</td><td>art. 6 ust. 1 lit. b i f RODO (działania przed zawarciem umowy, uzasadniony interes)</td><td>do 12 miesięcy od ostatniego kontaktu</td></tr>
          <tr><td>Zawarcie i wykonanie umowy szkoleniowej lub najmu</td><td>dane z umowy, numer PKK, dane kontaktowe</td><td>art. 6 ust. 1 lit. b RODO</td><td>przez czas trwania umowy</td></tr>
          <tr><td>Obowiązki prawne ośrodka szkolenia kierowców i księgowe</td><td>dane wymagane przepisami (m.in. ustawy o kierujących pojazdami)</td><td>art. 6 ust. 1 lit. c RODO</td><td>przez okres wymagany przepisami</td></tr>
          <tr><td>Dochodzenie lub obrona roszczeń</td><td>dane związane z umową</td><td>art. 6 ust. 1 lit. f RODO</td><td>do upływu terminu przedawnienia</td></tr>
        </tbody>
      </table>
    </div>
    <p>Podanie danych jest dobrowolne, ale bez numeru telefonu nie oddzwonimy, a bez danych wymaganych przepisami nie możemy przeprowadzić szkolenia.</p>

    <h2>3. Komu przekazujemy dane</h2>
    <p>Dane mogą trafić do podmiotów, które pomagają nam prowadzić działalność: dostawcy hostingu strony (LH.pl), dostawcy poczty e-mail, biura rachunkowego. Dane kursantów przekazujemy też organom publicznym, gdy wymagają tego przepisy (np. w systemie obsługi profilu kandydata na kierowcę). Nie sprzedajemy danych i nie przekazujemy ich w celach marketingowych.</p>
    <p>Poczta e-mail obsługiwana jest przez Google. W związku z tym dane mogą być przetwarzane również poza Europejskim Obszarem Gospodarczym, na podstawie mechanizmów przewidzianych w RODO (np. standardowych klauzul umownych).</p>

    <h2>4. Twoje prawa</h2>
    <p>Masz prawo do: dostępu do danych, ich sprostowania, usunięcia, ograniczenia przetwarzania, przeniesienia danych, wniesienia sprzeciwu wobec przetwarzania opartego na uzasadnionym interesie. Wystarczy napisać na <?= e($s['email']) ?>. Przysługuje Ci też skarga do Prezesa Urzędu Ochrony Danych Osobowych (ul. Stawki 2, 00-193 Warszawa).</p>

    <h2>5. Pliki cookies i podobne technologie</h2>
    <p class="bluf">Strona nie używa ciasteczek reklamowych ani analitycznych i nie śledzi odwiedzających. Dlatego nie wyświetlamy baneru zgody na cookies.</p>
    <ul>
      <li><strong>Pamięć przeglądarki (localStorage)</strong> – zapamiętuje, że zamknąłeś okienko z promocją, żeby nie pokazywać go przy każdej wizycie. Informacja zostaje tylko w Twojej przeglądarce.</li>
      <li><strong>Ciasteczko sesyjne</strong> – używane przy wysyłce formularza (zabezpieczenie przed nadużyciami) i w panelu administracyjnym. Usuwane po zamknięciu przeglądarki.</li>
      <li><strong>Mapa Google</strong> na stronie kontaktowej ładuje się dopiero po kliknięciu „Pokaż mapę”. Od tego momentu Google może zapisywać własne pliki cookies na zasadach opisanych w <a href="https://policies.google.com/privacy?hl=pl" target="_blank" rel="noopener">polityce prywatności Google</a>.</li>
    </ul>
    <p>Pliki cookies możesz w każdej chwili usunąć lub zablokować w ustawieniach przeglądarki.</p>

    <h2>6. Zmiany polityki</h2>
    <p>Aktualna wersja polityki prywatności jest zawsze dostępna na tej stronie. Obowiązuje od 16 października 2026 r.</p>
  </div>
</section>
