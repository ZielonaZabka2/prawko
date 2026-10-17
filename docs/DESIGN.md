# Design i animacje

## 3 warianty strony głównej

Podgląd: `/?wariant=a`, `/?wariant=b`, `/?wariant=c`. Wybór na stałe: **panel → Dane firmy → Wygląd strony głównej**. Podstrony, panel i treści są wspólne, warianty różnią się tylko stroną główną.

| | A · Asfalt | B · Trasa | C · Pas startowy |
|---|---|---|---|
| Charakter | ciemny, „sprzedażowy” | jasny, redakcyjny, spokojny | dynamiczny, najbardziej „reklamowy” |
| Pierwszy ekran | nagłówek + karta ceny, pod spodem auto wjeżdżające na drogę | wielki nagłówek z podkreśleniem, zdjęcie biura w łuku, cena jako znak drogowy | auto w prawym dolnym rogu wtopione w tło, cena w nagłówku |
| Kroki kursu | siatka 6 kart | pionowa trasa, która wypełnia się przy przewijaniu | pozioma droga, po której jedzie auto razem z przewijaniem |
| Wyróżnik | przerywana linia drogi „jedzie” pod autem | przystanki zapalają się po kolei | przewijany pasek z ofertą, liczniki |
| Dla kogo | gdy najważniejsza jest cena | gdy chcemy wyglądać solidnie i „dorośle” | gdy reklamujemy się do młodych kursantów |

## Zasady (anti-slop)

Czego **nie ma** celowo, bo zdradza szablon albo AI:
- fioletowych gradientów, glassmorphismu, „blobów” w tle,
- emoji jako ikon i stockowych zdjęć uśmiechniętych ludzi,
- haseł typu „Odkryj swój potencjał”, „Twoja podróż zaczyna się tutaj”,
- identycznych siatek „3 karty z ikonką” w każdej sekcji,
- wszystkiego wyśrodkowanego.

Co **jest**:
- kolory z logo klienta (grafit + pomarańcz) i niebieski z tablicy „L”,
- krój Barlow, zaprojektowany na wzór oznakowania dróg,
- motywy z prawdziwej drogi: przerywana linia pasa, znak z kategorią, przystanki trasy,
- prawdziwe zdjęcia biura i mieszkania, konkretne liczby zamiast przymiotników.

## Animacje (zasady Emila Kowalskiego)

- Animujemy tylko `transform`, `opacity` i `clip-path` (GPU, bez przeliczania układu → płynnie na słabych telefonach).
- Wejścia: `ease-out` (`cubic-bezier(.23, 1, .32, 1)`), krótkie dystanse 12–28 px, nigdy `scale(0)`.
- Czasy: przyciski 150–200 ms, sekcje 600–700 ms, hero do 1,4 s. Odstęp między elementami w grupie (stagger) 70 ms.
- Każde wejście odtwarza się raz, nic nie mruga w pętli. Wyjątek: linia drogi w wariancie A, która zatrzymuje się, gdy zniknie z ekranu.
- Przyciski reagują na wciśnięcie (`scale(.97)`), strzałka przesuwa się o 3 px na hover.
- Ruch związany z przewijaniem (trasa B, auto C) liczony jest raz na klatkę (`requestAnimationFrame`).
- `prefers-reduced-motion`: bez ruchu, treść od razu widoczna.
- Bez JavaScriptu treść jest widoczna. Ukrywanie przed animacją włącza się tylko, gdy skrypt działa, a po 2,5 s jest bezpiecznik.
- Zero bibliotek: cały skrypt strony to ok. 9 KB bez kompresji.
