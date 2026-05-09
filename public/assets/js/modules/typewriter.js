// ─── Typewriter — H1 du hero uniquement, une seule fois au chargement ────────
// Lit data-typewriter sur le H1, vide le textContent, ré-écrit caractère à caractère.
// Curseur | clignote pendant la frappe puis disparaît 600ms après la fin.
(function () {
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  var el = document.querySelector('[data-typewriter]');
  if (!el) return;

  var full = el.getAttribute('aria-label') || el.textContent.trim();
  var chars = Array.from(full);

  // Préserver aria-label pour les screen readers, cacher le rendu live
  if (!el.getAttribute('aria-label')) {
    el.setAttribute('aria-label', full);
  }
  el.setAttribute('aria-live', 'off');
  el.textContent = '';
  el.classList.add('is-typing');

  var i = 0;
  function step() {
    if (i >= chars.length) {
      setTimeout(function () { el.classList.remove('is-typing'); }, 600);
      return;
    }
    el.textContent += chars[i];
    var c = chars[i];
    var delay = 36 + (Math.random() * 14 - 7);
    if (c === ' ') delay = 1;
    if ('.,;:—!?'.indexOf(c) !== -1) delay += 120;
    i++;
    setTimeout(step, delay);
  }

  setTimeout(step, 350);
}());
