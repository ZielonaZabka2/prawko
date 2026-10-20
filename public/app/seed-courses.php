<?php
/*
 * Kursy startowe. Kolejne kategorie (A, A2, C, C+E …) dodaje się w panelu → Kursy.
 * W opisie można używać znaczników: {cena} {cena_regularna} {teoria} {praktyka} {gratis} {auto} {kategoria}
 */
return [
    [
        'id' => 'kat-b',
        'slug' => 'kat-b',
        'category' => 'B',
        'name' => 'Kurs prawa jazdy kat. B',
        'short' => 'Samochód osobowy',
        'lead' => 'Pełne szkolenie od pierwszej lekcji teorii do egzaminu wewnętrznego.',
        'price' => 3000,
        'theory_hours' => 30,
        'practice_hours' => 30,
        'bonus_hours' => 1,
        'vehicle' => 'Kia Rio IV',
        'includes' => "materiały szkoleniowe\negzamin wewnętrzny",
        'meta_title' => '',
        'meta_description' => '',
        'active' => true,
        'featured' => true,
        'order' => 1,
        'content' => <<<'MD'
## Ile kosztuje kurs prawa jazdy kat. B?

Kurs kat. B kosztuje {cena} i obejmuje {teoria} godzin zajęć teoretycznych, {praktyka} godzin jazd, dodatkową godzinę jazdy gratis, materiały szkoleniowe i egzamin wewnętrzny.

Poza kursem opłacasz badanie lekarskie (u lekarza uprawnionego do badań kierowców) oraz egzamin państwowy w WORD. Te opłaty trafiają do tych instytucji, a nie do szkoły jazdy.

## Jak wyglądają zajęcia teoretyczne?

Teoria to {teoria} godzin zajęć z instruktorem: przepisy ruchu drogowego, znaki, pierwszeństwo, zachowanie w trudnych sytuacjach i podstawy pierwszej pomocy.

Nie uczymy wyłącznie „pod test”. Tłumaczymy, dlaczego przepis jest taki, a nie inny – wtedy łatwiej go zapamiętać i zastosować na skrzyżowaniu, a nie tylko na ekranie egzaminacyjnym.

## Jak wyglądają jazdy?

Jazdy odbywają się samochodem {auto}. Zaczynamy od placu manewrowego, potem przechodzimy do ruchu miejskiego w Łomży i tras poza miastem, także w okolicy WORD.

1. **Plac manewrowy** – ruszanie, hamowanie, jazda po łuku, parkowanie.
2. **Spokojne ulice** – obserwacja, pierwszeństwo, zmiana pasa.
3. **Ruch miejski** – ronda, skrzyżowania ze światłami, przejścia dla pieszych.
4. **Trasy poza miastem** – większe prędkości, wyprzedzanie, jazda po zmroku.
5. **Trasy egzaminacyjne** – miejsca, w których kursanci najczęściej popełniają błędy.

## Jak szybko można zrobić kurs?

Przy jazdach kilka razy w tygodniu cały kurs da się zrealizować w około miesiąc. Tempo zależy od Ciebie – możesz też jeździć rzadziej, np. tylko w weekendy.

Jeśli mieszkasz poza Łomżą, połącz intensywny kurs z pobytem w [mieszkaniu dla kursantów](/noclegi-dla-kursantow).

## Co trzeba mieć, żeby zacząć?

Do rozpoczęcia jazd potrzebny jest numer PKK, czyli profil kandydata na kierowcę. Uzyskasz go w wydziale komunikacji po badaniu lekarskim.

- orzeczenie lekarskie o braku przeciwwskazań do kierowania pojazdami,
- fotografia i dowód osobisty,
- zgoda rodzica lub opiekuna, jeśli nie masz 18 lat.

Dokładną instrukcję znajdziesz w artykule [jak wyrobić PKK w Łomży](/blog/profil-kandydata-na-kierowce-pkk-lomza). Miejsce na kursie możesz zarezerwować, zanim urząd nada numer.
MD,
    ],
    [
        'id' => 'kat-b-14-dni',
        'slug' => 'kat-b-14-dni',
        'category' => 'B',
        'name' => 'Kurs kat. B w 14 dni',
        'short' => 'Kurs intensywny',
        'lead' => 'Cały kurs prawa jazdy w dwa tygodnie – dla osób, które chcą zrobić prawo jazdy szybko.',
        'price' => 3400,
        'theory_hours' => 30,
        'practice_hours' => 30,
        'bonus_hours' => 0,
        'vehicle' => 'Kia Rio IV',
        'includes' => "materiały szkoleniowe\negzamin wewnętrzny\nplan zajęć na 14 dni",
        'meta_title' => 'Kurs prawa jazdy w 14 dni – Łomża | OSK Żyliński',
        'meta_description' => '',
        'active' => true,
        'featured' => false,
        'order' => 2,
        'content' => <<<'MD'
## Ile kosztuje kurs prawa jazdy w 14 dni?

Intensywny kurs kat. B trwa 14 dni i kosztuje {cena}. Obejmuje pełny zakres szkolenia: {teoria} godzin teorii, {praktyka} godzin jazd, materiały szkoleniowe i egzamin wewnętrzny.

## Dla kogo jest kurs 14-dniowy?

Dla osób, które mają czas na codzienne zajęcia i chcą zamknąć kurs w dwa tygodnie: studentów w czasie wakacji, osób na urlopie, pracujących za granicą, którzy przyjeżdżają do Polski na krótko.

## Jak wygląda plan zajęć?

Teoria i jazdy odbywają się codziennie, według planu ustalonego indywidualnie przy zapisie. Jazdy prowadzimy samochodem {auto} – na placu manewrowym, w mieście, poza miastem i na trasach egzaminacyjnych. Kurs kończy się egzaminem wewnętrznym.

## Co trzeba mieć przed startem?

Numer PKK musi być gotowy w dniu rozpoczęcia kursu – inaczej nie zmieścimy się w 14 dniach. Jak go wyrobić, opisujemy w artykule [PKK w Łomży](/blog/profil-kandydata-na-kierowce-pkk-lomza).

Przyjeżdżasz spoza Łomży? Na czas kursu możesz zamieszkać w naszym [mieszkaniu dla kursantów](/noclegi-dla-kursantow).
MD,
    ],
];
