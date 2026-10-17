<?php
$s = settings();
$p = prices();
$promo = promo();
$crumbs = [['Regulamin', '/regulamin']];
$meta = [
    'title' => 'Regulamin szkolenia i promocji – OSK Artur Żyliński',
    'description' => 'Regulamin kursu prawa jazdy, jazd doszkalających, płatności, odwoływania jazd i promocji w OSK Artur Żyliński w Łomży.',
    'path' => '/regulamin',
    'breadcrumbs' => $crumbs,
];
$company = $s['legal_name'] ?: $s['name'] . ' – ' . $s['tagline'];
$ids = array_filter([
    $s['nip'] ? 'NIP ' . $s['nip'] : '',
    $s['regon'] ? 'REGON ' . $s['regon'] : '',
]);
?>
<section class="page-hero">
  <div class="wrap narrow">
    <?= breadcrumbs_html($crumbs) ?>
    <h1>Regulamin</h1>
    <p class="lead">Zasady szkolenia, płatności i promocji w ośrodku szkolenia kierowców <?= e($s['name']) ?>.</p>
  </div>
</section>

<section class="section">
  <div class="wrap narrow prose legal">
    <nav class="toc" aria-label="Spis treści">
      <ol>
        <li><a href="#ogolne">Postanowienia ogólne</a></li>
        <li><a href="#zapisy">Zapisy i umowa</a></li>
        <li><a href="#szkolenie">Przebieg szkolenia</a></li>
        <li><a href="#platnosci">Ceny i płatności</a></li>
        <li><a href="#jazdy">Terminy i odwoływanie jazd</a></li>
        <li><a href="#rezygnacja">Rezygnacja i odstąpienie od umowy</a></li>
        <li><a href="#promocje">Promocje</a></li>
        <li><a href="#mieszkanie">Mieszkanie dla kursantów</a></li>
        <li><a href="#reklamacje">Reklamacje</a></li>
        <li><a href="#koncowe">Postanowienia końcowe</a></li>
      </ol>
    </nav>

    <h2 id="ogolne">§ 1. Postanowienia ogólne</h2>
    <ol>
      <li>Regulamin określa zasady świadczenia usług szkoleniowych przez <?= e($company) ?>, <?= e(full_address()) ?><?= $ids ? ', ' . e(implode(', ', $ids)) : '' ?> (dalej: „Ośrodek”).</li>
      <?php if ($s['osk_number']): ?><li>Ośrodek jest wpisany do rejestru przedsiębiorców prowadzących ośrodek szkolenia kierowców pod numerem <?= e($s['osk_number']) ?>.</li><?php endif; ?>
      <li>Ośrodek prowadzi szkolenia kandydatów na kierowców w kategoriach podanych na stronie, jazdy doszkalające oraz wynajmuje kursantom mieszkanie na czas szkolenia.</li>
      <li>Kontakt z Ośrodkiem: tel. <?= e($s['phone']) ?>, e-mail <?= e($s['email']) ?>, osobiście w biurze przy <?= e($s['street']) ?> w Łomży.</li>
    </ol>

    <h2 id="zapisy">§ 2. Zapisy i umowa</h2>
    <ol>
      <li>Zapisu na kurs można dokonać osobiście w biurze, telefonicznie lub przez formularz na stronie. Zgłoszenie przez formularz nie jest zawarciem umowy – Ośrodek kontaktuje się z osobą zgłaszającą w celu ustalenia szczegółów.</li>
      <li>Umowa szkoleniowa zawierana jest w formie pisemnej lub dokumentowej przed rozpoczęciem zajęć.</li>
      <li>Warunkiem rozpoczęcia szkolenia jest posiadanie profilu kandydata na kierowcę (PKK). Osoba niepełnoletnia przedstawia pisemną zgodę rodzica lub opiekuna prawnego.</li>
    </ol>

    <h2 id="szkolenie">§ 3. Przebieg szkolenia</h2>
    <ol>
      <li>Zakres i liczba godzin szkolenia dla danej kategorii są zgodne z obowiązującymi przepisami dotyczącymi szkolenia kierowców i podane w opisie kursu na stronie (np. <?php $mc = main_course(); ?>kurs kat. <?= e($mc['category']) ?>: <?= (int) $mc['theory_hours'] ?> godzin zajęć teoretycznych i <?= (int) $mc['practice_hours'] ?> godzin zajęć praktycznych<?= (int) $mc['bonus_hours'] ? ' oraz dodatkowo ' . (int) $mc['bonus_hours'] . ' h jazdy gratis' : '' ?>).</li>
      <li>Zajęcia praktyczne odbywają się samochodem przystosowanym do nauki jazdy, w terminach uzgodnionych między kursantem a instruktorem.</li>
      <li>Szkolenie kończy się egzaminem wewnętrznym (teoretycznym i praktycznym). Po jego zaliczeniu Ośrodek potwierdza ukończenie szkolenia w profilu kandydata na kierowcę.</li>
      <li>Kursant zgłasza się na jazdy trzeźwy, wypoczęty, z dokumentem tożsamości i w obuwiu umożliwiającym bezpieczne prowadzenie pojazdu. Instruktor może odmówić przeprowadzenia jazdy, jeśli stan kursanta zagraża bezpieczeństwu; taka jazda jest traktowana jak odwołana w terminie krótszym niż wskazany w § 5.</li>
    </ol>

    <h2 id="platnosci">§ 4. Ceny i płatności</h2>
    <ol>
      <li>Obowiązują ceny z <a href="/cennik">cennika</a> aktualnego w dniu zawarcia umowy. Ceny są cenami brutto.</li>
      <li>Opłaty za badanie lekarskie oraz za egzamin państwowy w WORD nie są częścią ceny kursu i kursant wnosi je bezpośrednio do tych podmiotów.</li>
      <?php if (!empty($p['installments'])): ?><li>Na życzenie kursanta płatność za kurs może zostać rozłożona na raty. Liczba rat, ich wysokość i terminy płatności określane są w umowie.</li><?php endif; ?>
      <li>Całość opłaty za kurs powinna zostać uregulowana najpóźniej przed egzaminem wewnętrznym, chyba że umowa stanowi inaczej.</li>
    </ol>

    <h2 id="jazdy">§ 5. Terminy i odwoływanie jazd</h2>
    <ol>
      <li>Terminy jazd ustalane są indywidualnie z instruktorem.</li>
      <li>Kursant może odwołać lub przełożyć jazdę bez konsekwencji, jeśli zrobi to najpóźniej 24 godziny przed jej rozpoczęciem.</li>
      <li>Jazda odwołana później niż 24 godziny przed terminem lub nieodbyta z winy kursanta może zostać uznana za zrealizowaną. Nie dotyczy to sytuacji losowych, które kursant uzasadni.</li>
      <li>Jeśli jazda nie odbędzie się z przyczyn leżących po stronie Ośrodka, zostanie przeprowadzona w innym, uzgodnionym terminie bez dodatkowych opłat.</li>
    </ol>

    <h2 id="rezygnacja">§ 6. Rezygnacja i odstąpienie od umowy</h2>
    <ol>
      <li>Kursant może zrezygnować ze szkolenia w każdym czasie. W takim przypadku Ośrodek zwraca wpłaconą kwotę pomniejszoną o wartość zrealizowanych zajęć, liczoną proporcjonalnie do zakresu kursu.</li>
      <li>Konsument, który zawarł umowę na odległość (np. telefonicznie lub e-mailowo), może od niej odstąpić w terminie 14 dni bez podania przyczyny. Jeśli na wyraźne żądanie konsumenta szkolenie rozpoczęło się przed upływem tego terminu, konsument płaci za zajęcia zrealizowane do chwili odstąpienia.</li>
      <li>Rezygnację lub oświadczenie o odstąpieniu można złożyć osobiście, e-mailem lub pisemnie na adres Ośrodka.</li>
    </ol>

    <h2 id="promocje">§ 7. Promocje</h2>
    <ol>
      <li>Ośrodek może prowadzić promocje cenowe. Warunki każdej promocji, w tym okres jej obowiązywania, publikowane są na stronie internetowej.</li>
      <li>Cena promocyjna obowiązuje przy zawarciu umowy szkoleniowej w okresie trwania promocji, niezależnie od terminu zakończenia kursu.</li>
      <li>Promocje nie łączą się ze sobą, chyba że ich warunki stanowią inaczej.</li>
      <?php if (promo_active($promo)): ?>
      <li><strong>Aktualna promocja – „<?= e($promo['label']) ?>”:</strong> <?= e($promo['title']) ?> <?php $pc = find_course(promo_course_slug()); if ($pc): ?>– dotyczy kursu: <?= e($pc['name']) ?>, cena regularna: <?= zl(course_regular_price($pc)) ?><?php endif; ?>. Okres obowiązywania: od <?= e(date_short($promo['start'])) ?> do <?= e(date_short($promo['end'])) ?>. <?= e($promo['text']) ?></li>
      <?php endif; ?>
      <li>Deklarowany w promocjach czas realizacji kursu (np. „do 1 miesiąca”) dotyczy szkolenia w Ośrodku i zakłada dostępność kursanta na jazdy kilka razy w tygodniu oraz posiadanie numeru PKK w dniu rozpoczęcia kursu. Nie obejmuje terminów egzaminu państwowego, które wyznacza WORD.</li>
    </ol>

    <h2 id="mieszkanie">§ 8. Mieszkanie dla kursantów</h2>
    <ol>
      <li>Ośrodek wynajmuje kursantom mieszkanie na czas szkolenia według stawek z cennika (doba lub pobyt dwutygodniowy).</li>
      <li>Termin pobytu, zasady zameldowania, ewentualna kaucja i zasady korzystania z mieszkania ustalane są indywidualnie przed przyjazdem.</li>
      <li>Najemca odpowiada za szkody w mieszkaniu powstałe z jego winy.</li>
    </ol>

    <h2 id="reklamacje">§ 9. Reklamacje</h2>
    <ol>
      <li>Reklamacje dotyczące usług można składać osobiście, e-mailem na adres <?= e($s['email']) ?> lub pisemnie na adres Ośrodka.</li>
      <li>Ośrodek odpowiada na reklamację w terminie 14 dni od jej otrzymania.</li>
      <li>Konsument może skorzystać z pomocy miejskiego lub powiatowego rzecznika konsumentów oraz z pozasądowych sposobów rozpatrywania sporów.</li>
    </ol>

    <h2 id="koncowe">§ 10. Postanowienia końcowe</h2>
    <ol>
      <li>W sprawach nieuregulowanych w regulaminie zastosowanie mają przepisy Kodeksu cywilnego, ustawy o prawach konsumenta oraz ustawy o kierujących pojazdami.</li>
      <li>Zasady przetwarzania danych osobowych opisuje <a href="/polityka-prywatnosci">polityka prywatności</a>.</li>
      <li>Regulamin obowiązuje od 16 października 2026 r.</li>
    </ol>
  </div>
</section>
