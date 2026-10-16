/* OSK Artur Żyliński – drobne skrypty strony (bez bibliotek) */
(function () {
  'use strict';

  var store = {
    get: function (k) { try { return window.localStorage.getItem(k); } catch (e) { return null; } },
    set: function (k, v) { try { window.localStorage.setItem(k, v); } catch (e) { /* tryb prywatny */ } }
  };

  /* ---------- menu mobilne ---------- */
  var toggle = document.querySelector('.nav-toggle');
  if (toggle) {
    toggle.addEventListener('click', function () {
      var header = document.querySelector('.site-header');
      document.documentElement.style.setProperty('--nav-top', Math.max(0, header.getBoundingClientRect().bottom) + 'px');
      var open = document.body.classList.toggle('nav-open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && document.body.classList.contains('nav-open')) {
        document.body.classList.remove('nav-open');
        toggle.setAttribute('aria-expanded', 'false');
        toggle.focus();
      }
    });
  }

  /* ---------- popup promocji ----------
     Pokazuje się po X sekundach (ustawiane w panelu). Po zamknięciu nie wraca
     przez N dni. Nie przerywa wypełniania formularza ani otwartego menu. */
  var promo = document.getElementById('promo-dialog');
  if (promo && typeof promo.showModal === 'function') {
    var key = promo.dataset.key;
    var delay = Math.max(0, parseInt(promo.dataset.delay, 10) || 10) * 1000;
    var repeatDays = Math.max(0, parseInt(promo.dataset.repeat, 10) || 0);
    var last = parseInt(store.get(key) || '0', 10);
    var due = !last || (repeatDays > 0 && Date.now() - last > repeatDays * 864e5);
    var onContactPage = document.body.classList.contains('page-kontakt');

    var force = promo.dataset.force === '1';
    if (force) { delay = 600; }
    if (force || (due && !onContactPage)) {
      var timer = window.setTimeout(function tryOpen() {
        var active = document.activeElement;
        var busy = document.body.classList.contains('nav-open') ||
          (active && /^(INPUT|TEXTAREA|SELECT)$/.test(active.tagName)) ||
          document.querySelector('dialog[open]');
        if (busy) { timer = window.setTimeout(tryOpen, 4000); return; }
        promo.showModal();
        if (!force) { store.set(key, String(Date.now())); }
      }, delay);
    }
    promo.addEventListener('click', function (e) {
      if (e.target === promo) { promo.close(); } // klik w tło zamyka
    });
    promo.addEventListener('close', function () { if (!force) { store.set(key, String(Date.now())); } });
  }

  /* ---------- galeria / lightbox ---------- */
  var gallery = document.querySelector('[data-gallery]');
  var lb = document.getElementById('lightbox');
  if (gallery && lb && typeof lb.showModal === 'function') {
    var links = Array.prototype.slice.call(gallery.querySelectorAll('a'));
    var lbImg = lb.querySelector('img');
    var lbCap = lb.querySelector('figcaption');
    var idx = 0;
    var show = function (i) {
      idx = (i + links.length) % links.length;
      var a = links[idx];
      var alt = a.querySelector('img').getAttribute('alt');
      lbImg.src = a.getAttribute('href');
      lbImg.alt = alt;
      lbImg.width = a.dataset.w; lbImg.height = a.dataset.h;
      lbCap.textContent = alt + ' (' + (idx + 1) + '/' + links.length + ')';
    };
    links.forEach(function (a, i) {
      a.addEventListener('click', function (e) {
        e.preventDefault();
        show(i);
        lb.showModal();
      });
    });
    lb.addEventListener('click', function (e) {
      var btn = e.target.closest('[data-lb]');
      if (btn) {
        var act = btn.dataset.lb;
        if (act === 'close') { lb.close(); }
        if (act === 'prev') { show(idx - 1); }
        if (act === 'next') { show(idx + 1); }
      } else if (e.target === lb) {
        lb.close();
      }
    });
    lb.addEventListener('keydown', function (e) {
      if (e.key === 'ArrowLeft') { show(idx - 1); }
      if (e.key === 'ArrowRight') { show(idx + 1); }
    });
    var touchX = null;
    lb.addEventListener('touchstart', function (e) { touchX = e.touches[0].clientX; }, { passive: true });
    lb.addEventListener('touchend', function (e) {
      if (touchX === null) { return; }
      var dx = e.changedTouches[0].clientX - touchX;
      if (Math.abs(dx) > 50) { show(idx + (dx < 0 ? 1 : -1)); }
      touchX = null;
    });
  }

  /* ---------- mapa po kliknięciu ---------- */
  var map = document.querySelector('[data-map-src]');
  if (map) {
    var btn = map.querySelector('[data-map-load]');
    if (btn) {
      btn.addEventListener('click', function () {
        var f = document.createElement('iframe');
        f.src = map.dataset.mapSrc;
        f.title = 'Mapa dojazdu do biura szkoły jazdy';
        f.loading = 'lazy';
        f.referrerPolicy = 'no-referrer-when-downgrade';
        f.allowFullscreen = true;
        map.innerHTML = '';
        map.appendChild(f);
      });
    }
  }
})();
