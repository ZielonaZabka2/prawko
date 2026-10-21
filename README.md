# OSK Artur Żyliński – Szkoła Bezpiecznej Jazdy (Łomża)

Strona internetowa ośrodka szkolenia kierowców: kurs prawa jazdy kat. B, jazdy doszkalające, noclegi dla kursantów, blog, popup z promocją i panel administratora.

- **Technologia:** PHP 8.1+ bez bazy danych (dane w plikach JSON), czysty HTML/CSS/JS, czcionki trzymane na serwerze.
- **Panel:** `/admin`, gdzie Artur zmienia kursy (kolejne kategorie A, C…), ceny, promocję i popup (z podglądem na żywo: treść, zdjęcie, styl, kiedy i gdzie się pokazuje), treści strony głównej, własne FAQ, opinie, komunikat na górze strony, wpisy bloga, dane firmy, czyta wiadomości i pobiera kopię zapasową.
- **SEO/GEO:** dane strukturalne (DrivingSchool, FAQPage, BlogPosting, Service, Breadcrumbs) generowane z tych samych danych co treść, sitemap.xml, robots.txt z dostępem dla botów AI, llms.txt.

Dokumenty:
- [docs/PLAN.md](docs/PLAN.md): plan, struktura strony, SEO, design, rzeczy do potwierdzenia z klientem
- [docs/WDROZENIE.md](docs/WDROZENIE.md): jak wgrać stronę na LH.pl
- [docs/DESIGN.md](docs/DESIGN.md): 3 warianty strony głównej i zasady animacji
- [docs/AUDYT.md](docs/AUDYT.md): audyt SEO, AI-slop i animacji (Lighthouse przed/po)
- `.claude/skills/`: skille projektu – `emil-kowalski-motion` (animacje) i `anti-slop`

Podgląd lokalny:

```bash
php -S localhost:8000 -t public dev-router.php
```
