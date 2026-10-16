(function () {
  'use strict';
  var menu = document.querySelector('.a-menu');
  if (menu) {
    menu.addEventListener('click', function () {
      var open = document.body.classList.toggle('menu-open');
      menu.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  }

  // potwierdzenie przed usunięciem
  document.querySelectorAll('form[data-confirm]').forEach(function (f) {
    f.addEventListener('submit', function (e) {
      if (!window.confirm(f.dataset.confirm)) { e.preventDefault(); }
    });
  });

  // pasek formatowania Markdown
  var area = document.querySelector('.md-area');
  var bar = document.querySelector('.md-toolbar');
  if (area && bar) {
    var wrap = function (before, after, placeholder) {
      var s = area.selectionStart, e = area.selectionEnd;
      var sel = area.value.slice(s, e) || placeholder;
      area.setRangeText(before + sel + after, s, e, 'end');
      area.focus();
      area.setSelectionRange(s + before.length, s + before.length + sel.length);
    };
    var linePrefix = function (prefix, placeholder) {
      var s = area.selectionStart, e = area.selectionEnd;
      var sel = area.value.slice(s, e) || placeholder;
      var lines = sel.split('\n').map(function (l, i) {
        return (typeof prefix === 'function' ? prefix(i) : prefix) + l.replace(/^(#+\s|[-*]\s|\d+\.\s)/, '');
      }).join('\n');
      var needNl = s > 0 && area.value[s - 1] !== '\n' ? '\n\n' : '';
      area.setRangeText(needNl + lines + '\n', s, e, 'end');
      area.focus();
    };
    bar.addEventListener('click', function (ev) {
      var b = ev.target.closest('[data-md]');
      if (!b) { return; }
      switch (b.dataset.md) {
        case 'h2': linePrefix('## ', 'Śródtytuł – najlepiej jako pytanie?'); break;
        case 'bold': wrap('**', '**', 'pogrubiony tekst'); break;
        case 'ul': linePrefix('- ', 'punkt listy'); break;
        case 'ol': linePrefix(function (i) { return (i + 1) + '. '; }, 'pierwszy krok'); break;
        case 'link':
          var url = window.prompt('Adres linku (np. /cennik albo https://…):', '/');
          if (url) { wrap('[', '](' + url + ')', 'tekst linku'); }
          break;
        case 'table':
          linePrefix('', '| Kolumna 1 | Kolumna 2 |\n|---|---|\n| wartość | wartość |');
          break;
      }
    });
  }

  // adres wpisu tworzy się z tytułu (dopóki nie wpiszesz go ręcznie)
  var title = document.getElementById('f-title');
  var slug = document.getElementById('f-slug');
  if (title && slug && !slug.value) {
    var map = { 'ą': 'a', 'ć': 'c', 'ę': 'e', 'ł': 'l', 'ń': 'n', 'ó': 'o', 'ś': 's', 'ź': 'z', 'ż': 'z' };
    var touched = false;
    slug.addEventListener('input', function () { touched = true; });
    title.addEventListener('input', function () {
      if (touched) { return; }
      slug.value = title.value.toLowerCase().replace(/[ąćęłńóśźż]/g, function (c) { return map[c]; })
        .replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
    });
  }
})();
