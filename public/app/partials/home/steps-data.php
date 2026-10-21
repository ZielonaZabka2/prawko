<?php
// 6 kroków do prawa jazdy – edytowane w panelu → Treści strony (tekst w formacie: [link](/adres))
return array_map(fn($s) => [$s[0], md_inline($s[1])], load_data('content')['steps'] ?? []);
