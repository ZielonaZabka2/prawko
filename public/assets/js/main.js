/* OSK Artur Żyliński – drobne skrypty strony (bez bibliotek) */
(function () {
  'use strict';

  var store = {
    get: function (k) { try { return window.localStorage.getItem(k); } catch (e) { return null; } },
    set: function (k, v) { try { window.localStorage.setItem(k, v); } catch (e) { /* tryb prywatny */ } }
  };

  document.documentElement.classList.add('motion-ready');
  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ---------- wejścia przy przewijaniu ----------
     Treść jest widoczna bez JS – ukrywamy ją tylko, gdy ten skrypt działa (klasa .js). */
  var revealEls = document.querySelectorAll('[data-reveal], [data-reveal-group]');
  document.querySelectorAll('[data-reveal-group]').forEach(function (g) {
    Array.prototype.forEach.call(g.children, function (ch, i) { ch.style.setProperty('--i', Math.min(i, 8)); });
  });
  if ('IntersectionObserver' in window && !reduceMotion) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (en.isIntersecting) { en.target.classList.add('is-in'); io.unobserve(en.target); }
      });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.12 });
    revealEls.forEach(function (el) { io.observe(el); });
  } else {
    revealEls.forEach(function (el) { el.classList.add('is-in'); });
  }

  /* ---------- postęp przewijania: trasa (wariant B) i auto na drodze (wariant C) ---------- */
  var route = document.querySelector('[data-route]');
  var drive = document.querySelector('[data-drive]');
  var road = drive && drive.querySelector('.drive-road');
  var ticking = false;
  var progress = function (el, startAt, endAt) {
    var r = el.getBoundingClientRect();
    var vh = window.innerHeight;
    var p = (vh * startAt - r.top) / (r.height + vh * (startAt - endAt));
    return Math.max(0, Math.min(1, p));
  };
  var onScroll = function () {
    ticking = false;
    document.documentElement.classList.toggle('is-scrolled', window.scrollY > 8);
    if (reduceMotion) { return; }
    if (route) { route.style.setProperty('--route-p', progress(route, 0.75, 0.45).toFixed(3)); }
    if (road) {
      drive.style.setProperty('--drive-w', road.clientWidth + 'px');
      var dp = progress(drive, 0.85, 0.35);
      drive.style.setProperty('--drive-p', dp.toFixed(3));
      var stops = drive.querySelectorAll('.drive-steps > li');
      Array.prototype.forEach.call(stops, function (li, i) { li.classList.toggle('passed', dp >= i / Math.max(1, stops.length - 1) - 0.02); });
    }
  };
  window.addEventListener('scroll', function () {
    if (!ticking) { ticking = true; window.requestAnimationFrame(onScroll); }
  }, { passive: true });
  window.addEventListener('resize', onScroll, { passive: true });
  onScroll();

  /* ---------- liczniki (wariant C): krótko, z wyhamowaniem ---------- */
  var counters = document.querySelectorAll('[data-count]');
  if (counters.length && 'IntersectionObserver' in window && !reduceMotion) {
    var cio = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (!en.isIntersecting) { return; }
        cio.unobserve(en.target);
        var el = en.target, to = parseInt(el.dataset.count, 10) || 0, t0 = null, dur = 900;
        var step = function (t) {
          if (!t0) { t0 = t; }
          var k = Math.min(1, (t - t0) / dur);
          el.textContent = Math.round(to * (1 - Math.pow(1 - k, 3)));
          if (k < 1) { window.requestAnimationFrame(step); }
        };
        window.requestAnimationFrame(step);
      });
    }, { threshold: 0.6 });
    counters.forEach(function (c) { cio.observe(c); });
  }

  /* ---------- droga w hero: przerywana linia jedzie tylko, gdy ją widać ---------- */
  var movingRoad = document.querySelector('.road-moving');
  if (movingRoad && 'IntersectionObserver' in window) {
    new IntersectionObserver(function (entries) {
      movingRoad.style.setProperty('--lane-state', entries[0].isIntersecting ? 'running' : 'paused');
    }).observe(movingRoad);
  }

  /* ---------- zdjęcia: łagodne pojawienie po załadowaniu (bez „wyskakiwania”) ---------- */
  Array.prototype.forEach.call(document.querySelectorAll('main img[loading="lazy"]'), function (img) {
    if (img.complete && img.naturalWidth) { return; }
    img.classList.add('fade-load');
    var done = function () { img.classList.add('is-loaded'); };
    img.addEventListener('load', done, { once: true });
    img.addEventListener('error', done, { once: true });
  });

  /* ---------- wariant A: auto „odjeżdża” w prawo przy przewijaniu w dół ---------- */
  var carImg = document.querySelector('.hero-car-drive img');
  if (carImg && !reduceMotion) {
    carImg.addEventListener('animationend', function () {
      carImg.classList.add('is-driven');
      var hero = document.querySelector('.hero-a');
      var drive = function () {
        var r = hero.getBoundingClientRect();
        var p = Math.max(0, Math.min(1, -r.top / Math.max(1, r.height)));
        carImg.closest('.hero-car-drive').style.setProperty('--car-x', (p * 6).toFixed(2));
      };
      window.addEventListener('scroll', function () { window.requestAnimationFrame(drive); }, { passive: true });
      drive();
    }, { once: true });
  }

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
     Moment pokazania ustawiany w panelu: po X sekundach, po przewinięciu X% strony
     albo przy próbie wyjścia (kursor ucieka do paska kart). Po zamknięciu nie wraca
     przez N dni. Nie przerywa wypełniania formularza ani otwartego menu. */
  var promo = document.getElementById('promo-dialog');
  if (promo && typeof promo.showModal === 'function') {
    var key = promo.dataset.key;
    var d = parseInt(promo.dataset.delay, 10);
    var delay = (isNaN(d) ? 10 : Math.max(0, d)) * 1000;
    var repeatDays = Math.max(0, parseInt(promo.dataset.repeat, 10) || 0);
    var trigger = promo.dataset.trigger || 'time';
    var scrollPct = Math.min(95, Math.max(5, parseInt(promo.dataset.scroll, 10) || 40));
    var isMobile = window.matchMedia('(max-width: 760px)').matches;
    var last = parseInt(store.get(key) || '0', 10);
    var due = !last || (repeatDays > 0 && Date.now() - last > repeatDays * 864e5);
    var onContactPage = document.body.classList.contains('page-kontakt');
    var force = promo.dataset.force === '1';
    var opened = false;
    if (isMobile && trigger === 'exit') { trigger = 'scroll'; } // na telefonie nie ma „wyjścia kursorem”

    var tryOpen = function () {
      if (opened) { return; }
      var active = document.activeElement;
      var busy = document.body.classList.contains('nav-open') ||
        (active && /^(INPUT|TEXTAREA|SELECT)$/.test(active.tagName)) ||
        document.querySelector('dialog[open]');
      if (busy) { window.setTimeout(tryOpen, 4000); return; }
      opened = true;
      promo.showModal();
      if (!force) { store.set(key, String(Date.now())); }
    };

    if (force) {
      window.setTimeout(tryOpen, 600);
    } else if (due && !onContactPage && !(isMobile && promo.dataset.mobile === '0')) {
      if (trigger === 'scroll') {
        var onSc = function () {
          var max = document.documentElement.scrollHeight - window.innerHeight;
          if (max > 0 && window.scrollY / max * 100 >= scrollPct) { window.removeEventListener('scroll', onSc); tryOpen(); }
        };
        window.addEventListener('scroll', onSc, { passive: true });
      } else if (trigger === 'exit') {
        document.addEventListener('mouseout', function onOut(e) {
          if (!e.relatedTarget && e.clientY <= 0) { document.removeEventListener('mouseout', onOut); tryOpen(); }
        });
        window.setTimeout(tryOpen, Math.max(delay, 45000)); // zapas: jeśli ktoś długo czyta
      } else {
        window.setTimeout(tryOpen, delay);
      }
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
      var apply = function () {
        lbImg.src = a.getAttribute('href');
        lbImg.alt = alt;
        lbImg.width = a.dataset.w; lbImg.height = a.dataset.h;
        lbCap.textContent = alt + ' (' + (idx + 1) + '/' + links.length + ')';
        var reveal = function () { lbImg.classList.remove('is-switching'); };
        if (lbImg.decode) { lbImg.decode().then(reveal, reveal); } else { reveal(); }
      };
      if (lb.open && !reduceMotion) {
        lbImg.classList.add('is-switching');
        window.setTimeout(apply, 140); // przenikanie: stare znika, nowe wchodzi ostre
      } else {
        apply();
      }
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
