# OSK Artur Żyliński – Szkoła Bezpiecznej Jazdy · plan strony

Klient: Artur Żyliński, OSK w Łomży (ul. Dworna 1 lok. 6U, tel. 792 830 005, azylinski33@gmail.com)
Domena: oskszkolabezpiecznejjazdy.lomza.pl (hosting LH.pl)
Cel strony: telefon lub zapis na kurs kat. B. Drugi cel: jazdy doszkalające i wynajem mieszkania kursantom.

---

## 1. Technologia: dlaczego PHP bez bazy danych

- Hosting LH.pl ma PHP i Apache/LiteSpeed (.htaccess). Strona to **zwykłe pliki**: wgrywasz FTP-em do `public_html` i działa.
- **Bez MySQL i bez WordPressa.** Dane (ceny, promocja, wpisy na bloga, wiadomości) trzymamy w plikach JSON w `/data`, zablokowanych dla przeglądarki.
- Panel administratora pod `/admin`, chroniony hasłem (bcrypt), z tokenem CSRF i limitem prób logowania.
- Bez frameworków JS i bez zewnętrznych skryptów. Czcionki trzymamy na serwerze, więc nie ma Google Fonts ani problemów z RODO. Mapa Google ładuje się dopiero po kliknięciu.
- Nie ma ciasteczek śledzących, więc **nie trzeba baneru cookies** (popup zapamiętuje zamknięcie w localStorage, a to dane techniczne niezbędne do działania).

## 2. Mapa strony (URL)

| URL | Strona | Główna fraza (intencja) |
|---|---|---|
| `/` | Strona główna | nauka jazdy Łomża, szkoła jazdy Łomża (nawigacyjna/transakcyjna) |
| `/kurs-prawa-jazdy-kat-b` | Kurs kat. B: zakres, przebieg, PKK, raty | kurs prawa jazdy kat B Łomża (transakcyjna) |
| `/jazdy-doszkalajace` | Jazdy doszkalające | jazdy doszkalające Łomża (transakcyjna) |
| `/noclegi-dla-kursantow` | Mieszkanie dla kursantów z innych miast + galeria | nocleg Łomża kurs prawa jazdy, kurs prawa jazdy z zakwaterowaniem |
| `/cennik` | Cennik (tabela) | cena prawa jazdy Łomża, ile kosztuje prawo jazdy kat B |
| `/blog` | Poradnik kursanta | klaster informacyjny |
| `/blog/{slug}` | Artykuł | long-tail |
| `/kontakt` | Kontakt, formularz, mapa | OSK Dworna Łomża, telefon |
| `/regulamin` | Regulamin kursu i promocji | – |
| `/polityka-prywatnosci` | RODO + cookies | – |
| `/sitemap.xml`, `/robots.txt`, `/llms.txt` | technika SEO/GEO | – |

## 3. Strona główna: sekcje (kolejność = ścieżka decyzji kursanta)

1. **Hero:** H1 „Nauka jazdy w Łomży – kurs prawa jazdy kat. B”, cena (lub cena promocyjna z przekreśleniem), 2 przyciski (Zadzwoń / Zapisz się), 3 fakty: 30 h teorii, 30 h jazd + 1 h gratis, Kia Rio IV.
2. **Pasek promocji** (tylko gdy aktywna, sterowany z panelu).
3. **Co dostajesz w cenie kursu:** tabela, bo to najczęściej cytowany format przez AI.
4. **Przebieg kursu krok po kroku:** profil PKK, teoria, jazdy, egzamin wewnętrzny, egzamin w WORD. Lista numerowana pod HowTo.
5. **Dlaczego my:** konkrety zamiast ogólników (jeden instruktor, elastyczne godziny, kurs nawet w miesiąc).
6. **Samochód szkoleniowy:** Kia Rio IV (zdjęcie).
7. **Noclegi:** zajawka mieszkania ze zdjęciami i ceną.
8. **Biuro:** zdjęcie lokalu przy Dwornej, „tu się zapisujesz”.
9. **FAQ:** 6–8 pytań, BLUF, schema FAQPage.
10. **Kontakt:** telefon, adres, godziny, mapa po kliknięciu.

## 4. Design: kierunek, który nie wygląda jak „AI slop”

- **Kolory z marki klienta:** grafit `#15171a` / `#202328`, pomarańcz z logo `#f26a1b`, biel złamana `#f6f4f0`. Bez fioletowych gradientów, glassmorphismu i „blobów”.
- **Typografia:** *Barlow Condensed* (nagłówki) + *Barlow* (tekst). Barlow zaprojektowano na wzór oznakowania dróg w Kalifornii, czyli krój „drogowy” z natury. Self-hosting.
- **Motyw graficzny:** przerywana linia pasa ruchu jako separator sekcji, tablica „L” jako znacznik, numeracja kroków jak tabliczki kilometrowe. Oszczędnie, bez przesady.
- **Prawdziwe zdjęcia klienta** (lokal, mieszkanie, auto). Żadnych stocków.
- Ikony: własne SVG liniowe, spójna grubość. **Zero emoji w UI.**
- Dostępność: kontrast min. 4.5:1, widoczny focus, `prefers-reduced-motion`, alt na każdym zdjęciu, cele dotykowe ≥ 44 px.
- Mobile-first: przyklejony dolny pasek „Zadzwoń / Zapisz się” na telefonie, bo ruch lokalny to w 70%+ telefony.

## 5. Panel administratora (`/admin`)

| Moduł | Co Artur może zmienić |
|---|---|
| **Cennik** | cena kursu kat. B, jazdy doszkalające, nocleg doba / 2 tygodnie, info o ratach, dowolne dodatkowe pozycje |
| **Promocja / popup** | wł./wył., tytuł, opis, cena promocyjna, data od–do, **opóźnienie popupu (domyślnie 10 s)**, co ile dni pokazywać ponownie, tekst przycisku. Po dacie końca promocja znika sama. |
| **Blog** | dodawanie, edycja i usuwanie wpisów, zdjęcie główne, szkic/publikacja, prosty edytor (nagłówki, pogrubienie, listy, linki) |
| **Dane firmy** | telefon, e-mail, adres, godziny otwarcia, Facebook, NIP/REGON/nr ewidencyjny OSK (do regulaminu) |
| **Wiadomości** | zgłoszenia z formularza (zapisują się też na serwerze, gdyby mail nie doszedł) |
| **Hasło** | zmiana hasła |

**Promocja startowa:** 2800 zł zamiast 3000 zł, kurs w max. 1 miesiąc, **16.10.2026 – 16.11.2026**, popup po 10 s.

## 6. SEO (techniczne + on-page)

- Unikalne `<title>` (≤ 60 znaków) i `meta description` (≤ 160 znaków) na każdej podstronie, canonical, Open Graph.
- Jeden H1 na stronę. H2 jako pytania tam, gdzie to naturalne (FAQ, poradnik).
- Obrazy WebP w kilku rozmiarach, `width`/`height` (CLS = 0), `loading="lazy"` poza hero, `fetchpriority="high"` na hero.
- Sitemap generowana automatycznie razem z wpisami bloga.
- **Schema (JSON-LD) generowana z tych samych danych co treść**, więc nie ma rozjazdu cen między schemą a stroną:
  - `DrivingSchool` (+ `LocalBusiness`): adres, telefon, geo, godziny, `sameAs` → Facebook, `makesOffer` z cenami
  - `WebSite`, `BreadcrumbList`
  - `FAQPage` na stronie głównej i na stronie kursu
  - `Service` + `Offer` na podstronach usług
  - `BlogPosting` z autorem `Person` (Artur Żyliński, instruktor) i `dateModified`
  - `Accommodation`/`Apartment` dla mieszkania

### Słowa kluczowe (lokalne)
- główne: nauka jazdy Łomża, szkoła jazdy Łomża, OSK Łomża, prawo jazdy Łomża, kurs prawa jazdy Łomża, kurs kat B Łomża
- cenowe: ile kosztuje prawo jazdy Łomża, cennik prawa jazdy Łomża, prawo jazdy na raty Łomża
- usługi: jazdy doszkalające Łomża, jazdy przed egzaminem WORD Łomża, kurs prawa jazdy z noclegiem
- okolice (w treści, bez spamowania): Piątnica, Nowogród, Zambrów, Kolno, Jedwabne

### Klaster bloga (pillar → satelity)
- **Pillar:** „Jak zrobić prawo jazdy kat. B w Łomży: krok po kroku” *(na start)*
- „Profil kandydata na kierowcę (PKK): jak go uzyskać” *(na start)*
- „Jak przygotować się do egzaminu praktycznego w WORD Łomża” *(na start)*
- „Jazdy doszkalające: kiedy warto i ile godzin potrzeba”
- „Kurs prawa jazdy w miesiąc: czy to realne?”
- „Prawo jazdy na raty: jak to działa w OSK”
- „Najczęstsze błędy na egzaminie praktycznym”
- „Kurs prawa jazdy z noclegiem: dla kogo”

## 7. GEO (widoczność w ChatGPT / Perplexity / AI Overviews)

- BLUF: pierwsze 1–2 zdania każdej sekcji to bezpośrednia odpowiedź z konkretną liczbą (cena, liczba godzin).
- Tabela HTML z ofertą i cennikiem.
- `robots.txt` wpuszcza GPTBot, OAI-SearchBot, PerplexityBot, ClaudeBot, Google-Extended.
- `/llms.txt`: zwięzły opis firmy, oferty i cen dla modeli językowych (generowany z danych, zawsze aktualny).
- Spójny NAP (nazwa, adres, telefon) wszędzie: strona, schema, Google Business Profile, Facebook.

## 8. Do potwierdzenia z klientem

- [ ] godziny otwarcia biura
- [ ] adres strony na Facebooku (do `sameAs` i stopki)
- [ ] pełna nazwa firmy, NIP, REGON, numer w rejestrze OSK (do regulaminu i polityki prywatności)
- [ ] czy mieszkanie jest tylko dla kursantów, czy dla wszystkich
- [ ] ile osób zmieści mieszkanie, adres/okolica (zdjęcia pokazują nowe osiedle)
- [ ] warunki rat (ile rat, czy bez odsetek)
- [ ] czy egzamin wewnętrzny i jazda na egzamin („wynajem auta na egzamin”) są w ofercie
- [ ] zdjęcia samochodu to wizualizacje: przydałoby się 2–3 prawdziwe zdjęcia auta
- [ ] zdjęcie instruktora (bardzo mocno buduje zaufanie, E-E-A-T)
- [ ] założenie / uzupełnienie wizytówki Google (Google Business Profile)

## 9. Wdrożenie

Patrz `docs/WDROZENIE.md`.
