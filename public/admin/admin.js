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

/* ---------- podgląd popupu na żywo (panel → Promocja) ---------- */
(function () {
  'use strict';
  var form = document.getElementById('promo-form');
  var frame = document.getElementById('pe-iframe');
  if (!form || !frame) { return; }
  var esc = function (s) { return String(s).replace(/[&<>"']/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]; }); };
  var zl = function (n) { return String(n).replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + ' zł'; };
  var val = function (n) { var el = form.elements[n]; return el ? el.value : ''; };
  var on = function (n) { var el = form.elements[n]; return !!(el && el.checked); };
  var check = '<svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 12.5 4.5 4.5L19 7.5"/></svg>';
  var close = '<svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>';
  var regular = {};
  try { regular = JSON.parse(form.elements.course.dataset.regular || '{}'); } catch (e) {}
  var fileUrl = '';
  var fileInput = form.elements.image_file;
  if (fileInput) {
    fileInput.addEventListener('change', function () {
      if (fileUrl) { URL.revokeObjectURL(fileUrl); }
      fileUrl = fileInput.files[0] ? URL.createObjectURL(fileInput.files[0]) : '';
      render();
    });
  }
  var fmtDate = function (v) { var p = v.split('-'); return p.length === 3 ? p[2] + '.' + p[1] + '.' + p[0] : ''; };

  function render() {
    var theme = (form.querySelector('[name=theme]:checked') || {}).value || 'dark';
    var picked = form.querySelector('[name=image]:checked');
    var src = fileUrl || (picked ? picked.dataset.src : '');
    var price = parseInt(val('price'), 10) || 0;
    var reg = regular[val('course')] || 0;
    var perks = val('perk').split('\n').map(function (s) { return s.trim(); }).filter(Boolean);
    var h = '';
    h += '<dialog open class="promo-dialog promo-theme-' + theme + '">';
    h += '<div class="promo-close-form"><span class="promo-close">' + close + '</span></div>';
    if (src) { h += '<div class="promo-media"><img src="' + esc(src) + '" alt=""></div>'; }
    h += '<div class="promo-dialog-in">';
    if (val('label')) { h += '<p class="promo-tag">' + esc(val('label')) + '</p>'; }
    h += '<h2>' + esc(val('title') || 'Tytuł promocji') + '</h2>';
    if (price > 0 && price < reg) { h += '<p class="promo-price"><span class="price-new">' + zl(price) + '</span><s class="price-old">' + zl(reg) + '</s></p>'; }
    if (val('text')) { h += '<p class="promo-text">' + esc(val('text')) + '</p>'; }
    perks.forEach(function (p) { h += '<p class="promo-perk">' + check + ' ' + esc(p) + '</p>'; });
    h += '<div class="promo-actions"><a class="btn btn-primary">' + esc(val('cta_label') || 'Zapisz się') + '</a>';
    if (on('show_phone')) { h += '<a class="btn btn-ghost-dark">' + esc(frame.dataset.phone) + '</a>'; }
    h += '</div>';
    if (on('show_terms')) { h += '<p class="promo-terms">Oferta ważna od ' + fmtDate(val('start')) + ' do ' + fmtDate(val('end')) + '. <a>Warunki promocji</a></p>'; }
    h += '</div></dialog>';
    var doc = '<!doctype html><html lang="pl"><head><meta charset="utf-8"><link rel="stylesheet" href="' + esc(frame.dataset.css) + '">' +
      '<style>html,body{margin:0;min-height:100%;background:rgba(10,11,13,.62)!important;padding:0!important}body{display:grid;place-items:center;padding:16px!important}' +
      '.promo-dialog{position:static;margin:16px auto;animation:none!important}.price-old::after{animation:none!important;transform:scaleX(1)!important}</style></head><body>' + h + '</body></html>';
    frame.srcdoc = doc;
  }
  var t;
  form.addEventListener('input', function () { clearTimeout(t); t = setTimeout(render, 150); });
  form.addEventListener('change', render);
  render();
})();

/* ---------- listy z dodawaniem i usuwaniem wierszy ---------- */
(function () {
  'use strict';
  document.querySelectorAll('[data-repeater]').forEach(function (rep) {
    var rows = rep.querySelector('.rep-rows');
    var tpl = rep.querySelector('template');
    var n = rows.children.length + 100;
    rep.querySelector('.rep-add').addEventListener('click', function () {
      var html = tpl.innerHTML.replace(/__i/g, String(n++));
      rows.insertAdjacentHTML('beforeend', html);
      var first = rows.lastElementChild.querySelector('input, textarea');
      if (first) { first.focus(); }
    });
    rows.addEventListener('click', function (e) {
      var del = e.target.closest('.rep-del');
      if (del) { del.closest('.rep-row').remove(); }
    });
  });
})();
