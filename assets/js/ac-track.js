/*!
 * AstroChitra Newsletter Tracker (design-agnostic)
 * ------------------------------------------------
 * Drop-in analytics for any newsletter page:
 *   <script src="../assets/js/ac-track.js" data-slug="september-2026"></script>
 *
 * Tracks, per browser session:
 *   - slide_view  : every deck slide that becomes active (deduped per session)
 *   - click       : every button / link / role=button interaction,
 *                   labelled with visible text or aria-label
 *
 * Auto-detects slides via .deck .slide (override with data-slide-selector).
 * Never touches page markup or styles.
 */
(function () {
  'use strict';

  var script = document.currentScript;
  if (!script) {
    var all = document.getElementsByTagName('script');
    script = all[all.length - 1];
  }
  var ds = script.dataset || {};

  /* ---------- identity & config ---------- */
  var SLUG = ds.slug || '';
  if (!SLUG) {
    var m = location.pathname.match(/([A-Za-z0-9_-]+)\.php/);
    SLUG = m ? m[1] : 'unknown';
  }
  var SELECTOR = ds.slideSelector || '.deck .slide';
  var API;
  try {
    API = new URL('../../api/track.php', script.src).href;
  } catch (e) {
    API = '/api/track.php';
  }

  var SID;
  try {
    SID = sessionStorage.getItem('ac_sid');
    if (!SID) {
      SID = 's-' + Date.now().toString(36) + '-' + Math.random().toString(36).slice(2, 10);
      sessionStorage.setItem('ac_sid', SID);
    }
  } catch (e) {
    SID = 's-' + Date.now().toString(36) + '-anon';
  }

  /* ---------- transport ---------- */
  function send(type, label, target, slide) {
    var body = JSON.stringify({
      slug: SLUG,
      type: type,
      label: label || '',
      target: target || '',
      slide: (typeof slide === 'number' && slide >= 0) ? slide : null,
      sid: SID
    });
    if (navigator.sendBeacon && navigator.sendBeacon(API, body)) { return; }
    try {
      fetch(API, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: body,
        keepalive: true,
        credentials: 'same-origin'
      }).catch(function () {});
    } catch (e) {}
  }

  /* ---------- slides ---------- */
  var slides = [];
  try {
    slides = [].slice.call(document.querySelectorAll(SELECTOR));
  } catch (e) {}

  var deck = document.querySelector('.deck');

  function slideName(el, i) {
    var n = el.getAttribute('aria-label')
         || (el.querySelector('h1,h2') ? el.querySelector('h1,h2').textContent : '')
         || ('Slide ' + (i + 1));
    return n.replace(/\s+/g, ' ').trim().slice(0, 60);
  }

  var seenSlides = {};
  try {
    seenSlides = JSON.parse(sessionStorage.getItem('ac_sv_' + SLUG) || '{}');
  } catch (e) {}

  function reportSlide(i) {
    if (!slides[i]) { return; }
    var name = slideName(slides[i], i);
    var key = i + '|' + name;
    if (seenSlides[key]) { return; }
    seenSlides[key] = 1;
    try { sessionStorage.setItem('ac_sv_' + SLUG, JSON.stringify(seenSlides)); } catch (e) {}
    send('slide_view', name, '#' + (slides[i].id || ('slide-' + (i + 1))), i);
  }

  function activeIndex() {
    if (!deck) { return -1; }
    var w = deck.clientWidth || window.innerWidth;
    if (!w) { return -1; }
    var x = null;
    if (typeof DOMMatrix === 'function') {
      try {
        var ctm = new DOMMatrix(getComputedStyle(deck).transform);
        x = -ctm.m41;
      } catch (e) {}
    }
    if (x === null) {
      var t = deck.style.transform || '';
      var pm = t.match(/translateX\(-?([\d.]+)%/);
      if (pm) { return Math.round(parseFloat(pm[1]) / 100); }
      var pxm = t.match(/translateX\(calc\(-([\d.]+)% \+\s?-?[\d.]+px\)\)/);
      if (pxm) { return Math.round(parseFloat(pxm[1]) / 100); }
      return -1;
    }
    return Math.max(0, Math.round(x / w));
  }

  function reportActive() {
    var i = activeIndex();
    if (i >= 0) { lastIdx = i; reportSlide(i); }
  }

  var lastIdx = -1;

  if (deck && typeof MutationObserver === 'function') {
    var mo = new MutationObserver(function () {
      clearTimeout(mo._t);
      mo._t = setTimeout(reportActive, 140);
    });
    mo.observe(deck, { attributes: true, attributeFilter: ['style'] });
  }
  window.addEventListener('hashchange', function () {
    setTimeout(reportActive, 350);
  });

  /* ---------- clicks ---------- */
  function describe(el) {
    var t = el.getAttribute('aria-label')
         || (el.value && el.type !== 'text' ? el.value : '')
         || el.getAttribute('data-goto-label')
         || el.textContent
         || el.getAttribute('title')
         || el.tagName.toLowerCase();
    return String(t).replace(/\s+/g, ' ').trim().slice(0, 60);
  }

  document.addEventListener('click', function (ev) {
    var el = ev.target && ev.target.closest
      ? ev.target.closest('a[href], button, [role="button"], input[type="submit"], input[type="button"]')
      : null;
    if (!el) { return; }

    var label = describe(el);
    if (el.closest('.menu-panel')) { label = 'Menu \u00B7 ' + label; }
    if (el.closest('.deck-nav'))   { label = 'Nav \u00B7 ' + label; }

    var target = el.getAttribute('href') || '';
    if (!target && el.dataset.goto) { target = 'goto:' + el.dataset.goto; }
    if (!target && el.formAction)   { target = el.formAction; }

    send('click', label, target, lastIdx >= 0 ? lastIdx : null);
  }, true);

  /* ---------- boot ---------- */
  function boot() {
    if (!slides.length && deck) { return; }
    setTimeout(reportActive, 250);
    if (!deck && slides.length) { slides.forEach(function (_, i) { reportSlide(i); }); }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }
})();
