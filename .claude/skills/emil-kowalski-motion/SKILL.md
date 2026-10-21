---
name: emil-kowalski-motion
description: Projektowanie i audyt animacji w interfejsach według zasad Emila Kowalskiego (autor Sonner, Vaul, kursu „Animations on the Web”). Używaj zawsze, gdy dodajesz, poprawiasz lub oceniasz animacje, przejścia, efekty hover/press, wejścia przy przewijaniu, popupy, menu, modale, karuzele, liczniki albo „ruch” na stronie – także gdy ktoś prosi o „ciekawsze”, „żywsze”, „mniej statyczne” albo „premium” animacje. Daje checklistę audytu, gotowe krzywe, czasy i wzorce CSS/JS bez bibliotek.
---

# Ruch w interfejsie – zasady Emila Kowalskiego

Cel: animacja, której użytkownik **nie zauważa jako animacji**, ale czuje, że strona jest szybka, lekka i dopracowana. Ruch ma uzasadnienie: informuje, prowadzi wzrok albo daje informację zwrotną. Zachwyt („delight”) to przyprawa, nie danie główne.

## 1. Zanim zaczniesz animować – trzy pytania

1. **Po co?** Informacja zwrotna (kliknięcie zadziałało), orientacja (skąd to się wzięło i dokąd idzie), hierarchia (co jest ważne), opowieść (strona marketingowa). Jeśli żadne – nie animuj.
2. **Jak często to zobaczy?** Akcja wykonywana dziesiątki razy dziennie (menu, skróty, przełączniki w panelu) ma mieć ruch minimalny albo żaden. Pierwszy ekran strony marketingowej, popup raz na kilka dni – może mieć więcej.
3. **Czy to blokuje?** Animacja nie może opóźniać treści ani interakcji. Nigdy nie chowaj elementu LCP (nagłówek, zdjęcie hero) za `opacity: 0` na dłużej niż ~300 ms.

## 2. Krzywe i czasy (ściąga)

| Sytuacja | Krzywa | Czas |
|---|---|---|
| Wejście elementu, pojawienie się | `cubic-bezier(.23, 1, .32, 1)` (ease-out) | 200–500 ms |
| Wyjście / zamknięcie | ten sam ease-out | **krócej niż wejście** (≈ 60–70%) |
| Ruch elementu po ekranie (zmiana pozycji) | `cubic-bezier(.77, 0, .175, 1)` (ease-in-out) | 300–600 ms |
| Szuflady, arkusze (drawer) | `cubic-bezier(.32, .72, 0, 1)` | 400–500 ms |
| Hover, zmiana koloru | `ease` / liniowo | 150–200 ms |
| Wciśnięcie przycisku | ease-out | 100–160 ms, `scale(.97)` |
| Stagger (odstęp w grupie) | – | 30–80 ms, max ~8 elementów |
| Sekcje przy przewijaniu | ease-out | 500–800 ms |

- **Nigdy `ease-in` dla UI** – start jest powolny, więc interfejs wydaje się „zamulony”.
- Domyślne `ease`/`ease-out` z CSS są za słabe – używaj własnych krzywych.
- Gesty, przeciąganie, rzeczy przerywane w trakcie → sprężyny (spring) zamiast stałego czasu.

## 3. Rzemiosło – reguły szczegółowe

- **Animuj tylko `transform`, `opacity`, `filter`, `clip-path`.** Nigdy `width/height/top/left/margin` (przeliczanie układu = szarpanie na telefonie).
- **Nie skaluj od zera.** Startuj od `scale(.9–.96)` + `opacity`. Nic w realnym świecie nie pojawia się z punktu.
- **`transform-origin` zgodny ze źródłem.** Popover rośnie od przycisku, który go otworzył, nie od środka.
- **Blur maskuje niedoskonałości.** Przy przenikaniu dwóch stanów dodaj `filter: blur(2–4px)` w trakcie – oko nie widzi „podwójnego” obrazu. Tekst wchodzący: `blur(6–10px)` + przesunięcie `0.2–0.4em` → ostro.
- **`clip-path` zamiast zmiany rozmiaru.** Odsłanianie zdjęć (`inset(100% 0 0 0)` → `inset(0)`), zakładki, „przytrzymaj, aby usunąć”, porównania przed/po.
- **Przejścia CSS (`transition`) dla stanów, które można przerwać** (hover, otwórz/zamknij). Keyframes tylko dla jednorazowych sekwencji.
- **Hover tylko na urządzeniach z kursorem:** `@media (hover: hover) and (pointer: fine)`. Na dotyku hover „przykleja się”.
- **Wciśnięcie zawsze daje odpowiedź:** `:active { transform: scale(.97) }`. Usuń szare podświetlenie dotyku (`-webkit-tap-highlight-color: transparent`), ale tylko gdy masz własny stan `:active`.
- **Wyjście też jest animacją.** Modal/popup, który pojawia się płynnie, a znika „cięciem”, wygląda na zepsuty. Dla `<dialog>`: `@starting-style` + `transition-behavior: allow-discrete` na `display` i `overlay`.
- **Tooltipy/podpowiedzi:** pierwsza z opóźnieniem, kolejne natychmiast i bez animacji.
- **Liczby:** licznik tylko raz, krótko (≤ 1 s), z wyhamowaniem, cyfry `font-variant-numeric: tabular-nums` (bez skakania szerokości).
- **Zdjęcia nie „wyskakują”:** łagodne `opacity` po załadowaniu (`img.decode()` / zdarzenie `load`), zawsze z `width`/`height` (zero CLS).
- **Ruch związany z przewijaniem** liczony raz na klatkę (`requestAnimationFrame`) albo CSS `animation-timeline: view()`; nigdy ciężka praca w zdarzeniu `scroll`.
- **Pętle tylko w tle i tylko gdy widać** (IntersectionObserver → `animation-play-state: paused` poza ekranem).
- **`will-change` tylko na czas animacji**, nie na stałe.

## 4. Dostępność

- `prefers-reduced-motion: reduce` → **zamień ruch na krótkie przenikanie**, nie usuwaj wszystkiego. Bez przesunięć, skalowania, paralaksy, pętli.
- Treść musi być widoczna bez JavaScriptu (ukrywanie przed wejściem tylko pod klasą `.js`, z bezpiecznikiem czasowym).
- Fokus klawiatury: animacja nie może zabrać fokusu ani go ukryć.

## 5. Smak – jak odróżnić dobrą animację od świetnej

- **Mniej, ale lepiej.** Jeden dopracowany moment (wejście hero, popup) robi więcej niż 20 efektów.
- **Spójność:** jedna rodzina krzywych i czasów w całym produkcie (tokeny CSS: `--ease-out`, `--dur-fast` …).
- **Asymetria:** wejście dłużej, wyjście krócej; hover szybko, „lądowanie” wolniej.
- **Oglądaj w zwolnionym tempie** (DevTools → Animations → 10%) i na prawdziwym telefonie.
- **Dobra animacja = niewidoczna.** Jeśli pierwsze, co ktoś mówi o stronie, to „ale animacje”, to za dużo.
- Unikaj efektów-wydmuszek: glitch, magnetyczne przyciski, kursor-kółko, smooth-scroll przejmujący przewijanie (Lenis itp. na stronie usługowej), paralaksa wszystkiego.

## 6. Procedura audytu (użyj przy „sprawdź animacje”)

Przejdź każdą animację na stronie i wypełnij tabelę:

| Element | Cel | Częstotliwość | Krzywa/czas | Właściwości | Wyjście | Reduced-motion | Ocena |
|---|---|---|---|---|---|---|---|

Następnie:
1. Zmierz wpływ na LCP/CLS (Lighthouse mobile). Element LCP nie może startować z `opacity: 0`.
2. Sprawdź dotyk: czy hover nie przykleja się, czy `:active` daje odpowiedź.
3. Sprawdź wyjścia: modal, menu, lightbox – czy znikają płynnie i szybciej niż wchodzą.
4. Wypisz: 🔴 błędy (blokuje treść, szarpie, brak reduced-motion), 🟡 do dopracowania, 🟢 okazje (gdzie ruch doda sensu: informacja zwrotna, orientacja).
5. Proponuj zmiany jako gotowy CSS/JS z tokenami, bez bibliotek, chyba że gesty wymagają sprężyn.

## 7. Gotowe wzorce

```css
:root{ --ease-out:cubic-bezier(.23,1,.32,1); --ease-in-out:cubic-bezier(.77,0,.175,1); --ease-drawer:cubic-bezier(.32,.72,0,1); }

/* tekst hero: blur + przesunięcie, element widoczny od razu dla LCP */
.reveal-word{ display:inline-block; opacity:.01; filter:blur(8px); transform:translateY(.3em);
  animation:word-in .7s var(--ease-out) forwards; animation-delay:calc(var(--i)*60ms) }
@keyframes word-in{ to{ opacity:1; filter:none; transform:none } }

/* dialog z wejściem i wyjściem */
dialog{ opacity:0; transform:translateY(12px) scale(.97);
  transition:opacity .2s var(--ease-out), transform .2s var(--ease-out), overlay .2s allow-discrete, display .2s allow-discrete }
dialog[open]{ opacity:1; transform:none; transition-duration:.35s }
@starting-style{ dialog[open]{ opacity:0; transform:translateY(16px) scale(.96) } }

/* hover tylko z kursorem, press wszędzie */
@media (hover:hover) and (pointer:fine){ .card:hover{ transform:translateY(-2px) } }
.btn:active{ transform:scale(.97) }

@media (prefers-reduced-motion:reduce){ *{ animation-duration:.01ms!important; transition-duration:.12s!important } }
```
