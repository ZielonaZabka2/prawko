# Wdrożenie na serwer LH.pl

Strona to zwykłe pliki PHP, bez bazy danych. Wgrywasz zawartość folderu **`public/`** i to wszystko.

## Wymagania

- PHP **8.1 lub nowsze** (zalecane 8.3) z biblioteką GD. Na LH.pl jest domyślnie.
- Apache/LiteSpeed z obsługą `.htaccess` (LH.pl: tak).

## Pierwsze wdrożenie (krok po kroku)

1. **Panel LH.pl → Strony WWW → domena `oskszkolabezpiecznejjazdy.lomza.pl`**
   - sprawdź, do jakiego katalogu na serwerze wskazuje domena (np. `public_html/oskszkolabezpiecznejjazdy.lomza.pl`),
   - ustaw wersję PHP na 8.3.
2. **Zrób kopię i usuń rozpoczętego WordPressa** z tego katalogu (pliki `wp-*`, `index.php`, `.htaccess`). Bazę danych WordPressa możesz zostawić albo usunąć w panelu. Nowa strona jej nie używa.
3. **Wgraj zawartość folderu `public/`** (nie sam folder, tylko to, co jest w środku) do katalogu domeny. Najwygodniej przez FileZillę (dane FTP są w panelu LH.pl).
   - **Ważne:** włącz w FileZilli pokazywanie ukrytych plików (*Serwer → Wymuś pokazywanie ukrytych plików*), bo pliki `.htaccess` muszą trafić na serwer, także te w `app/`, `data/` i `uploads/`.
4. **Uprawnienia:** katalogi `data/` i `uploads/` muszą być zapisywalne (zwykle 755 wystarczy, w razie błędu zapisu ustaw 775).
5. **SSL (https):** w panelu LH.pl włącz darmowy certyfikat Let's Encrypt dla domeny. Po aktywacji otwórz `.htaccess` i usuń `#` z dwóch linii pod komentarzem „HTTPS”. Strona domyślnie zakłada adres `https://`.
6. **Sprawdź zabezpieczenia:** wejdź na `https://oskszkolabezpiecznejjazdy.lomza.pl/data/` i `/app/bootstrap.php`. Musi być błąd 403 (brak dostępu).
7. **Zaloguj się do panelu:** `https://oskszkolabezpiecznejjazdy.lomza.pl/admin/` i od razu **zmień hasło startowe** (Panel → Hasło).
8. **Uzupełnij w panelu → Dane firmy:** godziny, Facebook, pełną nazwę firmy, NIP, REGON i numer w rejestrze OSK. Te dane trafiają do regulaminu, polityki prywatności i danych dla Google.
9. **Wyślij testowe zgłoszenie z formularza** (strona Kontakt). Sprawdź, czy przyszedł mail (zajrzyj też do spamu) i czy zgłoszenie widać w panelu → Wiadomości.

## Po wdrożeniu (SEO/GEO – 1 godzina pracy)

1. **Google Search Console** → dodaj domenę → zgłoś mapę strony `https://oskszkolabezpiecznejjazdy.lomza.pl/sitemap.xml`.
2. **Bing Webmaster Tools** → zaimportuj z Search Console (Bing zasila też ChatGPT Search).
3. **Wizytówka Google (Google Business Profile)**: kategoria „Szkoła nauki jazdy”, adres, telefon i godziny **identyczne** jak na stronie, link do strony, zdjęcia z folderu `public/assets/img`. Link do wizytówki wklej w panelu → Dane firmy.
4. Na Facebooku w opisie strony ustaw link do nowej strony.
5. Przetestuj dane strukturalne: <https://search.google.com/test/rich-results> i <https://validator.schema.org>.
6. Sprawdź szybkość: <https://pagespeed.web.dev>.

## Aktualizacje strony w przyszłości

Wgrywaj nowe pliki **z pominięciem katalogów `data/` i `uploads/`**. Tam są ceny, promocje, wpisy na blogu, wiadomości i hasło ustawione w panelu. Nadpisanie ich cofnie zmiany Artura.

Kopia zapasowa = pobranie przez FTP katalogów `data/` i `uploads/`.

## Gdzie co jest

```
public/                  ← to wgrywasz na serwer
├── index.php            router strony (czyste adresy URL)
├── .htaccess            przekierowania, blokady, cache
├── admin/               panel administratora (/admin)
├── app/                 kod PHP (zablokowany dla przeglądarki)
│   ├── pages/           treść podstron
│   ├── partials/        nagłówek, stopka, popup, formularz
│   ├── defaults.php     wartości startowe (ceny, promocja, dane firmy)
│   └── seed-posts.php   3 startowe artykuły bloga
├── data/                dane zapisywane z panelu (JSON, zablokowane)
├── uploads/             zdjęcia wgrane do bloga
└── assets/              CSS, JS, czcionki, zdjęcia (WebP)
```

## Lokalny podgląd (dla programisty)

```bash
php -S localhost:8000 -t public dev-router.php
```
