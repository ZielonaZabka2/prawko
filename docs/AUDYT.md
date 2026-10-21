# Audyt SEO, AI-slop i animacji – 21.10.2026

Narzędzia: Lighthouse 12 (telefon, symulowane 4G), własny crawler (wszystkie podstrony i linki), skrypt liczący sygnały AI-slop, skille projektu `.claude/skills/emil-kowalski-motion` i `.claude/skills/anti-slop`.

## 1. Wyniki Lighthouse (telefon)

| Strona | Przed: wydajność / LCP | Po: wydajność / LCP | Dostępność · Dobre praktyki · SEO |
|---|---|---|---|
| Główna – wariant A | 90 / 3,1 s | **99 / 2,1 s** | 100 · 100 · 100 |
| Główna – wariant B | 87 / 3,5 s | **98 / 2,3 s** | 100 · 100 · 100 |
| Główna – wariant C | 97 / 2,3 s | **99 / 2,1 s** | 100 · 100 · 100 |
| Kurs kat. B | 94 / 2,8 s | **98 / 2,3 s** | 100 · 100 · 100 |
| Kursy | – | **100 / 1,7 s** | 100 · 100 · 100 |

CLS = 0 na wszystkich stronach. Pomiary „po” z kompresją gzip, tak jak na serwerze LH.pl (`.htaccess`).

## 2. SEO – znalezione i naprawione

| | Problem | Status |
|---|---|---|
| 🔴 | LCP 3,1–3,5 s: nagłówek i tekst w hero startowały jako niewidoczne (animacja), Google liczył je dopiero po jej końcu | naprawione: tekst widoczny od pierwszej klatki, wchodzi z rozmyciem |
| 🔴 | 12 plików czcionek (~250 KB), część nieużywana | naprawione: 6 plików, polskie znaki ładowane od razu |
| 🟡 | Strona „Kursy” zbyt krótka (264 słowa) | naprawione: porównanie kursów Standard / Express w tabeli |
| 🟡 | Opis kursu Express dla Google za krótki (107 znaków) | naprawione |
| 🟡 | Brak natychmiastowego zgłaszania zmian do wyszukiwarek | dodane IndexNow: zapis wpisu lub kursu w panelu powiadamia Bing (i ChatGPT Search) |
| 🟢 | Pusty `src` w podglądzie zdjęć | naprawione |
| ✅ | 14 podstron, 0 martwych linków, 1×H1, bez przeskoków nagłówków, alt na zdjęciach, canonical, poprawne JSON-LD, brak duplikatów tytułów, sitemap, robots z botami AI, llms.txt | OK |

### Do zrobienia po stronie klienta (największy wpływ na pozycje lokalne)
1. 🔴 **Wizytówka Google (Google Business Profile)**: kategoria „Szkoła nauki jazdy”, ten sam adres, telefon i godziny co na stronie, zdjęcia, link do strony. Link wkleić w panelu → Dane firmy.
2. 🔴 **Opinie w Google**: prosić każdego kursanta po zdanym egzaminie. Wybrane wkleić w panelu → Opinie.
3. 🟡 **Zdjęcie i 2–3 zdania o instruktorze** (staż, uprawnienia): ważne dla zaufania (E-E-A-T), na razie brak.
4. 🟡 **Facebook**: link w panelu (trafia do `sameAs` w danych dla Google), a na Facebooku link do strony.
5. 🟡 **Search Console i Bing Webmaster**: kody weryfikacyjne wkleja się w panelu → Dane firmy, potem zgłosić `sitemap.xml`.
6. 🟢 **Blog**: 1 artykuł miesięcznie z pytań kursantów. Proponowane tematy: „Kurs prawa jazdy w 14 dni – czy to realne?”, „Najczęstsze błędy na egzaminie w WORD Łomża”, „Prawo jazdy na raty – jak to działa”.

## 3. AI-slop – przed i po

| Sygnał | Przed | Po |
|---|---|---|
| „nie tylko… / nie X, tylko Y” | 3 | **0** |
| „Spokojnie”, „bez krzyku”, „stres” (powtarzane hasła) | 5 | **0** |
| Aforyzmy („Stres to najgorszy nauczyciel…”, „jazda, która zaczyna się dzień po nim”) | 3 | **0** |
| Etykiety z kreską nad każdym nagłówkiem (najbardziej „szablonowy” element) | 26 | **7** (bez kreski) |
| Myślniki „ – ” | 42 | 39 (większość to zakresy godzin i dat, poprawne) |
| Wymyślone liczby, opinie, statystyki | 0 | 0 |

Zmiany w tekstach: hasła zastąpione konkretem z danych (godziny, adres, Express), np. tekst pod nagłówkiem: *„30 h teorii, 30 h jazd i 1 h gratis. Terminy jazd ustalasz z instruktorem, zapisy telefonicznie albo w biurze przy ul. Dwornej.”* Teoria opisana na przykładach z łomżyńskich skrzyżowań zamiast „nie uczymy pod test”. Wszystkie te teksty można zmienić w panelu → Treści strony.

## 4. Animacje – audyt wg zasad Emila Kowalskiego

| Element | Było | Jest |
|---|---|---|
| Nagłówek hero | maska linii, tekst ukryty do końca animacji (🔴 LCP) | słowa wchodzą po kolei z rozmyciem (55 ms odstępu), widoczne od razu |
| Tekst i przyciski hero | start od niewidoczności | rozmycie + przesunięcie, bez opóźniania LCP |
| Auto w hero (A) | wjazd, potem statycznie | wjazd w ramce bez dziur + przy przewijaniu kamera „jedzie” za autem |
| Popup | płynne wejście, znikał „cięciem” (🔴) | wejście 380 ms, wyjście 180 ms, tło się rozjaśnia, zdjęcie lekko się „ustawia” |
| Menu mobilne | otwierało się bez przejścia | panel wjeżdża, linki pojawiają się po kolei (30 ms), ikona się obraca |
| Podgląd zdjęć (lightbox) | zdjęcia przeskakiwały | przenikanie z lekkim rozmyciem, płynne otwarcie i zamknięcie |
| Zdjęcia przy ładowaniu | „wyskakiwały” | łagodne pojawienie po załadowaniu |
| Hover na kartach/zdjęciach | „przyklejał się” na telefonach | tylko na urządzeniach z kursorem; na dotyku reakcja na wciśnięcie |
| Ograniczenie ruchu w systemie | wyłączało wszystko | krótkie przenikanie zamiast ruchu |
| Wejścia sekcji, trasa (B), auto na drodze (C), liczniki, FAQ, przekreślenie ceny | OK | bez zmian |

Krzywe i czasy w całym serwisie: `--ease-out: cubic-bezier(.23, 1, .32, 1)`, wejścia 0,4–0,8 s, wyjścia krótsze, przyciski 0,16 s. Bez bibliotek, cały skrypt strony ≈ 11 KB.
