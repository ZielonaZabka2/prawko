<?php
declare(strict_types=1);

/* Obsługa zapisów z panelu. $page = nazwa formularza. */

switch ($page) {

    case 'cennik':
        $p = prices();
        $p['extra_hour'] = post_int('extra_hour', 0, 10000);
        $p['apartment_day'] = post_int('apartment_day', 0, 10000);
        $p['apartment_2weeks'] = post_int('apartment_2weeks', 0, 100000);
        $p['installments'] = post_bool('installments');
        $p['installments_note'] = post_str('installments_note', 400);
        $items = [];
        foreach ((array) ($_POST['extra'] ?? []) as $row) {
            $name = trim(mb_substr((string) ($row['name'] ?? ''), 0, 120));
            if ($name === '') {
                continue;
            }
            $items[] = [
                'name' => $name,
                'note' => trim(mb_substr((string) ($row['note'] ?? ''), 0, 160)),
                'price' => max(0, (int) ($row['price'] ?? 0)),
                'unit' => trim(mb_substr((string) ($row['unit'] ?? ''), 0, 20)),
            ];
        }
        $p['extra_items'] = $items;
        save_data('prices', $p) ? flash('Cennik zapisany. Nowe ceny są już na stronie.') : flash('Nie udało się zapisać. Sprawdź uprawnienia katalogu /data.', 'error');
        redirect('?p=cennik');

    case 'promocja':
        $old = promo();
        $p = $old;
        $p['enabled'] = post_bool('enabled');
        $p['label'] = post_str('label', 40) ?: 'Promocja';
        $p['title'] = post_str('title', 80);
        $p['text'] = post_str('text', 400);
        $p['price'] = post_int('price', 0, 100000);
        $p['perk'] = post_str('perk', 120);
        $p['start'] = post_date('start');
        $p['end'] = post_date('end');
        $p['popup'] = post_bool('popup');
        $p['delay'] = post_int('delay', 0, 120);
        $p['repeat_days'] = post_int('repeat_days', 0, 60);
        $p['cta_label'] = post_str('cta_label', 40) ?: 'Zapisz się';
        $p['cta_url'] = post_url('cta_url') ?: '/kontakt#zapis';
        $p['course'] = (string) ($_POST['course'] ?? '');
        if ($p['start'] && $p['end'] && $p['end'] < $p['start']) {
            flash('Data końca jest wcześniejsza niż data startu – popraw daty.', 'error');
            redirect('?p=promocja');
        }
        // nowa treść promocji = popup pokaże się ponownie także tym, którzy go zamknęli
        $changed = false;
        foreach (['title', 'text', 'price', 'start', 'end', 'course'] as $k) {
            $changed = $changed || (string) $old[$k] !== (string) $p[$k];
        }
        if ($changed || post_bool('reset_popup')) {
            $p['version'] = (string) time();
        }
        save_data('promo', $p) ? flash('Promocja zapisana.') : flash('Nie udało się zapisać.', 'error');
        redirect('?p=promocja');

    case 'wpis':
        $posts = load_data('posts');
        $id = (string) ($_POST['id'] ?? '');
        $idx = null;
        foreach ($posts as $i => $p) {
            if ($p['id'] === $id) { $idx = $i; break; }
        }

        if (post_bool('delete') && $idx !== null) {
            if (($posts[$idx]['cover_dir'] ?? '') === 'uploads') {
                delete_upload($posts[$idx]['cover']);
            }
            array_splice($posts, $idx, 1);
            save_data('posts', $posts);
            flash('Wpis usunięty.');
            redirect('?p=blog');
        }

        $title = post_str('title', 160);
        if ($title === '') {
            flash('Wpis musi mieć tytuł.', 'error');
            redirect('?p=wpis' . ($id ? '&id=' . urlencode($id) : ''));
        }
        $slug = slugify(post_str('slug', 120) ?: $title);
        // unikalny adres
        $base = $slug; $n = 2;
        while (array_filter($posts, fn($p) => $p['slug'] === $slug && $p['id'] !== $id)) {
            $slug = $base . '-' . $n++;
        }
        $post = $idx !== null ? $posts[$idx] : ['id' => bin2hex(random_bytes(5)), 'cover' => '', 'cover_dir' => 'uploads'];
        $post['title'] = $title;
        $post['slug'] = $slug;
        $post['excerpt'] = post_str('excerpt', 400);
        $post['content'] = post_str('content', 60000);
        $post['meta_title'] = post_str('meta_title', 70);
        $post['meta_description'] = post_str('meta_description', 170);
        $post['date'] = post_date('date') ?: date('Y-m-d');
        $post['updated'] = date('Y-m-d');
        $post['published'] = post_bool('published');

        if (post_bool('remove_cover') && $post['cover']) {
            if ($post['cover_dir'] === 'uploads') {
                delete_upload($post['cover']);
            }
            $post['cover'] = '';
            $post['cover_dir'] = 'uploads';
        }
        if (!empty($_FILES['cover']['name'])) {
            $res = process_upload($_FILES['cover'], $slug);
            if (str_starts_with($res, '!')) {
                flash('Zdjęcie: ' . substr($res, 1), 'error');
            } else {
                if ($post['cover'] && $post['cover_dir'] === 'uploads') {
                    delete_upload($post['cover']);
                }
                $post['cover'] = $res;
                $post['cover_dir'] = 'uploads';
            }
        }

        if ($idx !== null) {
            $posts[$idx] = $post;
        } else {
            $posts[] = $post;
        }
        save_data('posts', $posts) ? flash($post['published'] ? 'Wpis zapisany i opublikowany.' : 'Wpis zapisany jako szkic (niewidoczny na stronie).') : flash('Nie udało się zapisać.', 'error');
        redirect('?p=wpis&id=' . urlencode($post['id']));

    case 'kurs':
        $all = load_data('courses');
        $id = (string) ($_POST['id'] ?? '');
        $idx = null;
        foreach ($all as $i => $c) {
            if (($c['id'] ?? '') === $id) { $idx = $i; break; }
        }
        if (post_bool('delete') && $idx !== null) {
            if (count($all) <= 1) {
                flash('Nie można usunąć jedynego kursu. Możesz go najwyżej ukryć.', 'error');
                redirect('?p=kurs&id=' . urlencode($id));
            }
            array_splice($all, $idx, 1);
            save_data('courses', $all);
            flash('Kurs usunięty.');
            redirect('?p=kursy');
        }
        $category = strtoupper(preg_replace('~[^A-Za-z0-9+]~', '', post_str('category', 10)));
        if ($category === '') {
            flash('Podaj kategorię prawa jazdy (np. B, A, C+E).', 'error');
            redirect('?p=kurs' . ($id ? '&id=' . urlencode($id) : ''));
        }
        $slug = slugify(post_str('slug', 40) ?: 'kat-' . str_replace('+', '', $category));
        $base = $slug; $n = 2;
        while (array_filter($all, fn($c) => ($c['slug'] ?? '') === $slug && ($c['id'] ?? '') !== $id)) {
            $slug = $base . '-' . $n++;
        }
        $c = $idx !== null ? $all[$idx] : ['id' => bin2hex(random_bytes(5))];
        $oldSlug = $c['slug'] ?? '';
        $c['category'] = $category;
        $c['slug'] = $slug;
        $c['name'] = post_str('name', 100) ?: 'Kurs prawa jazdy kat. ' . $category;
        $c['short'] = post_str('short', 60);
        $c['lead'] = post_str('lead', 300);
        $c['price'] = post_int('price', 0, 100000);
        $c['theory_hours'] = post_int('theory_hours', 0, 300);
        $c['practice_hours'] = post_int('practice_hours', 0, 300);
        $c['bonus_hours'] = post_int('bonus_hours', 0, 50);
        $c['vehicle'] = post_str('vehicle', 80);
        $c['includes'] = post_str('includes', 600);
        $c['content'] = post_str('content', 60000);
        $c['meta_title'] = post_str('meta_title', 70);
        $c['meta_description'] = post_str('meta_description', 170);
        $c['order'] = post_int('order', 1, 99);
        $c['active'] = post_bool('active');
        $c['featured'] = post_bool('featured');
        if ($c['featured']) {
            foreach ($all as &$other) { $other['featured'] = false; }
            unset($other);
        }
        if ($idx !== null) { $all[$idx] = $c; } else { $all[] = $c; }
        // zmiana adresu kursu objętego promocją → promocja idzie za kursem
        $promo = promo();
        if ($oldSlug && $oldSlug !== $slug && ($promo['course'] ?? '') === $oldSlug) {
            $promo['course'] = $slug;
            save_data('promo', $promo);
        }
        save_data('courses', $all) ? flash('Kurs zapisany.') : flash('Nie udało się zapisać.', 'error');
        redirect('?p=kurs&id=' . urlencode($c['id']));

    case 'wiadomosci':
        $msgs = load_data('messages');
        $id = (string) ($_POST['id'] ?? '');
        $act = (string) ($_POST['action'] ?? '');
        if ($act === 'read_all') {
            foreach ($msgs as &$m) { $m['read'] = true; }
            unset($m);
        } else {
            foreach ($msgs as $i => &$m) {
                if ($m['id'] !== $id) { continue; }
                if ($act === 'delete') { unset($msgs[$i]); }
                if ($act === 'toggle') { $m['read'] = empty($m['read']); }
            }
            unset($m);
        }
        save_data('messages', array_values($msgs));
        redirect('?p=wiadomosci');

    case 'firma':
        $s = settings();
        foreach (['name' => 80, 'tagline' => 80, 'owner' => 80, 'phone' => 30, 'email' => 120, 'form_email' => 120, 'street' => 120, 'postal_code' => 10, 'city' => 60, 'hours' => 400, 'hours_schema' => 120, 'legal_name' => 200, 'nip' => 20, 'regon' => 20, 'osk_number' => 40, 'lat' => 20, 'lng' => 20] as $k => $max) {
            $s[$k] = post_str($k, $max);
        }
        foreach (['facebook', 'google_maps'] as $k) {
            $v = post_str($k, 300);
            $s[$k] = preg_match('~^https://~i', $v) ? $v : '';
        }
        $s['home_variant'] = in_array($_POST['home_variant'] ?? '', ['a', 'b', 'c'], true) ? $_POST['home_variant'] : 'a';
        $url = post_str('site_url', 200);
        if (preg_match('~^https?://[a-z0-9.-]+~i', $url)) {
            $s['site_url'] = rtrim($url, '/');
        }
        foreach (['email', 'form_email'] as $k) {
            if ($s[$k] !== '' && !filter_var($s[$k], FILTER_VALIDATE_EMAIL)) {
                flash('Adres e-mail „' . $s[$k] . '” jest niepoprawny – nie zapisano.', 'error');
                redirect('?p=firma');
            }
        }
        save_data('settings', $s) ? flash('Dane firmy zapisane.') : flash('Nie udało się zapisać.', 'error');
        redirect('?p=firma');

    case 'haslo':
        $auth = load_data('auth');
        $new = (string) ($_POST['new'] ?? '');
        if (!password_verify((string) ($_POST['current'] ?? ''), (string) $auth['hash'])) {
            flash('Obecne hasło jest nieprawidłowe.', 'error');
        } elseif (mb_strlen($new) < 10) {
            flash('Nowe hasło musi mieć co najmniej 10 znaków.', 'error');
        } elseif ($new !== (string) ($_POST['repeat'] ?? '')) {
            flash('Hasła nie są takie same.', 'error');
        } else {
            save_data('auth', ['hash' => password_hash($new, PASSWORD_DEFAULT), 'initial' => false]);
            session_regenerate_id(true);
            flash('Hasło zmienione.');
        }
        redirect('?p=haslo');

    default:
        redirect('?p=start');
}
