# Jak wrzucić stronę na serwer klienta (LH.pl): krok po kroku

Czas: ok. 30–45 minut. Potrzebne: dostęp do panelu LH.pl klienta i komputer z programem **FileZilla** (darmowy, filezilla-project.org).

> Nazwy przycisków w panelu LH.pl mogą się nieznacznie różnić, ale kolejność kroków jest ta sama.

---

## Krok 1. Pobierz stronę

**Sposób A (najprościej):** plik `osk-strona-public.zip`, który dostałeś w czacie. Rozpakuj go na pulpit do folderu np. `strona-osk`.

**Sposób B (z GitHuba):** repozytorium `ZielonaZabka2/prawko` → zielony przycisk **Code** → **Download ZIP** (gałąź `claude/cool-rubin-7o3p7p` albo `main` po scaleniu). Po rozpakowaniu potrzebujesz **tylko zawartości folderu `public`**.

W środku folderu muszą być m.in.: `index.php`, `.htaccess`, foldery `admin`, `app`, `assets`, `data`, `uploads`.

> Plik `.htaccess` jest „ukryty”. Na Macu pokażesz go skrótem **Cmd + Shift + .**, w Windows: Eksplorator → Widok → **Ukryte elementy**.

---

## Krok 2. Przygotuj serwer w panelu LH.pl

1. Zaloguj się na **panel.lh.pl** (dane klienta – po wdrożeniu poproś go o zmianę hasła, bo przyszło zwykłym mailem).
2. **Strony WWW** → domena `oskszkolabezpiecznejjazdy.lomza.pl`:
   - sprawdź **katalog domeny** na serwerze (np. `public_html/oskszkolabezpiecznejjazdy.lomza.pl` albo `public_html`) – tam wgrasz pliki,
   - ustaw **wersję PHP: 8.3** (minimum 8.1),
   - włącz **certyfikat SSL (Let's Encrypt)** – darmowy, aktywuje się zwykle w kilka–kilkanaście minut.
3. **Poczta** → **Dodaj skrzynkę**: `biuro@oskszkolabezpiecznejjazdy.lomza.pl`, ustaw mocne hasło i zapisz je.
   - W szczegółach skrzynki znajdź **Konfiguracja programu pocztowego**. Zanotuj **serwer SMTP** (np. `serwerXXXXXX.lh.pl`) i **port** (465 SSL albo 587 TLS). Przyda się w kroku 6.
   - Jeśli Artur ma odbierać pocztę na Gmailu: w skrzynce ustaw **przekierowanie** na `azylinski33@gmail.com` (albo wpisz Gmail w panelu strony jako adres odbiorcy formularza – patrz krok 6).
4. **FTP** → zanotuj **serwer FTP**, **login** i **hasło** (albo utwórz nowe konto FTP z dostępem do katalogu domeny).

---

## Krok 3. Usuń rozpoczętego WordPressa

1. Połącz się FileZillą: **Plik → Menedżer stron → Nowa strona**, protokół **FTP – z szyfrowaniem TLS (jawne)**, host, login, hasło z kroku 2.4 → **Połącz**.
2. Wejdź do katalogu domeny (prawe okno).
3. **Na wszelki wypadek pobierz kopię** wszystkiego (zaznacz → prawy przycisk → Pobierz).
4. Usuń pliki WordPressa: `wp-admin`, `wp-content`, `wp-includes`, wszystkie pliki `wp-*.php`, `index.php`, `.htaccess`, `xmlrpc.php`, `license.txt`, `readme.html`.

---

## Krok 4. Wgraj stronę

1. W FileZilli włącz **Serwer → Wymuś pokazywanie ukrytych plików** (żeby wysłać `.htaccess`).
2. Lewe okno: wejdź **do środka** rozpakowanego folderu (`strona-osk` / `public`).
3. Zaznacz **wszystko** (Ctrl/Cmd + A) i przeciągnij do katalogu domeny w prawym oknie. Poczekaj, aż kolejka się opróżni (ok. 140 plików, 5 MB).
4. Sprawdź, że na serwerze są: `.htaccess`, `index.php`, `admin/`, `app/`, `assets/`, `data/`, `uploads/` – a w `app/`, `data/` i `uploads/` także ich własne pliki `.htaccess`.
5. Uprawnienia: prawy przycisk na `data` → **Uprawnienia pliku** → `755` (zaznacz „Przenieś do podkatalogów”, tylko katalogi). To samo dla `uploads`. Jeśli później panel zgłosi błąd zapisu – ustaw `775`.

---

## Krok 5. Włącz https i sprawdź stronę

1. Gdy SSL jest aktywny (krok 2.2), otwórz na serwerze plik `.htaccess` (FileZilla → prawy przycisk → Wyświetl/Edytuj) i **usuń `#`** z dwóch linii pod komentarzem „HTTPS”:
   ```
   RewriteCond %{HTTPS} !=on
   RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
   ```
   Zapisz – FileZilla zapyta o wysłanie zmienionego pliku → **Tak**.
2. Otwórz `https://oskszkolabezpiecznejjazdy.lomza.pl` – powinna być nowa strona.
3. Test bezpieczeństwa – te adresy muszą pokazać **błąd 403 (brak dostępu)**:
   - `https://oskszkolabezpiecznejjazdy.lomza.pl/data/`
   - `https://oskszkolabezpiecznejjazdy.lomza.pl/app/bootstrap.php`

---

## Krok 6. Ustaw panel strony i pocztę

1. Wejdź na `https://oskszkolabezpiecznejjazdy.lomza.pl/admin/` i zaloguj się hasłem startowym (podanym w czacie).
2. **Hasło** → ustaw nowe (min. 10 znaków). Hasło startowe przestaje działać.
3. **Dane firmy → Poczta i formularz kontaktowy**:
   - *Gdzie wysyłać wiadomości z formularza*: `biuro@oskszkolabezpiecznejjazdy.lomza.pl` (albo Gmail Artura),
   - *Adres nadawcy*: `biuro@oskszkolabezpiecznejjazdy.lomza.pl`,
   - *Sposób wysyłki*: **Przez skrzynkę (SMTP)**,
   - *Serwer poczty*, *Port*, *Szyfrowanie*: z kroku 2.3 (465 → SSL, 587 → TLS),
   - *Login*: pełny adres skrzynki, *Hasło*: hasło skrzynki,
   - zostaw zaznaczone **automatyczne potwierdzenie** dla osoby, która napisała.
   - **Zapisz dane**, potem na dole strony **Wyślij testowy e-mail** – wiadomość powinna dojść w ciągu minuty (sprawdź też Spam).
4. **Dane firmy → Wygląd strony głównej**: wybierz **A · Asfalt** i zapisz.
5. **Dane firmy**: uzupełnij godziny, link do Facebooka, pełną nazwę firmy, NIP, REGON, numer w rejestrze OSK.
6. Wyślij próbną wiadomość z formularza na stronie (Kontakt). Powinieneś dostać powiadomienie na biuro@…, a osoba „pisząca” – potwierdzenie. Wiadomość widać też w panelu → **Wiadomości**.

---

## Krok 7. Google (raz, ok. 20 minut)

1. **Google Search Console** (search.google.com/search-console) → Dodaj usługę → *Prefiks adresu URL* → `https://oskszkolabezpiecznejjazdy.lomza.pl` → metoda **Tag HTML** → skopiuj samą wartość `content="…"` → wklej w panelu → Dane firmy → *Kod weryfikacyjny Google Search Console* → zapisz → w Search Console kliknij **Zweryfikuj**.
2. W Search Console: **Mapy witryn** → wpisz `sitemap.xml` → Prześlij.
3. **Bing Webmaster Tools** (bing.com/webmasters) → *Importuj z Google Search Console* (jedno kliknięcie). Bing zasila też wyszukiwarkę ChatGPT.
4. **Wizytówka Google** (business.google.com): kategoria „Szkoła nauki jazdy”, adres, telefon i godziny **dokładnie** jak na stronie, link do strony, zdjęcia z folderu `assets/img`. Link do wizytówki wklej w panelu → Dane firmy.

---

## Później: aktualizacje strony

Wgrywaj nowe pliki tak samo, **ale nie nadpisuj katalogów `data/` i `uploads/`** – tam są ceny, kursy, promocje, wpisy, opinie, wiadomości, hasło i ustawienia poczty zapisane w panelu.

**Kopia zapasowa:** panel → Pulpit → **Pobierz kopię zapasową** (ZIP). Przywrócenie = wgranie folderów `data/` i `uploads/` z kopii przez FTP.

## Najczęstsze problemy

| Objaw | Rozwiązanie |
|---|---|
| Błąd 500 po wgraniu | sprawdź wersję PHP (min. 8.1) w panelu LH.pl |
| Strona główna działa, podstrony dają 404 | nie wgrał się plik `.htaccess` – włącz pokazywanie ukrytych plików i wgraj ponownie |
| Panel: „Nie udało się zapisać” | uprawnienia katalogów `data/` i `uploads/` → 755 albo 775 |
| Test poczty: „nieprawidłowy login lub hasło” | login = pełny adres skrzynki; sprawdź hasło skrzynki w panelu LH.pl |
| Test poczty: „brak połączenia” | zła nazwa serwera SMTP lub port; spróbuj 587 + TLS |
| Maile lądują w spamie | używaj wysyłki przez skrzynkę (SMTP) z adresu w domenie, nie przez „funkcję serwera” |
| Strona bez kłódki https | SSL jeszcze się aktywuje – odczekaj; dopiero potem odkomentuj linie w `.htaccess` |

## Gdzie co jest (dla programisty)

```
public/                  ← to wgrywasz na serwer
├── index.php            router strony (czyste adresy URL)
├── .htaccess            przekierowania, blokady, kompresja, cache
├── admin/               panel administratora (/admin)
├── app/                 kod PHP (zablokowany dla przeglądarki)
│   ├── lib/mailer.php   wysyłka maili (SMTP / mail())
│   ├── pages/           treść podstron
│   ├── partials/        nagłówek, stopka, popup, formularz, warianty strony głównej
│   └── defaults.php     wartości startowe
├── data/                dane z panelu (JSON, zablokowane)
├── uploads/             zdjęcia wgrane w panelu
└── assets/              CSS, JS, czcionki, zdjęcia (WebP)
```

Lokalny podgląd: `php -S localhost:8000 -t public dev-router.php`
