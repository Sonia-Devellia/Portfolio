// ─── Typewriter — H1[data-typewriter] sur la home uniquement ─────────────────
// Lit le contenu, ré-écrit caractère à caractère avec curseur indigo.
// Si ce script ne se charge pas, le H1 reste visible normalement.

(function () {
  var el = document.querySelector('[data-typewriter]');
  if (!el) return;

  var full  = el.getAttribute('aria-label') || el.textContent.trim();
  var chars = Array.from(full);

  el.setAttribute('aria-label', full);
  el.setAttribute('aria-live', 'off');
  el.textContent = '';
  el.classList.add('is-typing');

  var idx = 0;
  function type() {
    if (idx >= chars.length) {
      setTimeout(function () { el.classList.remove('is-typing'); }, 600);
      return;
    }
    el.textContent += chars[idx];
    var c = chars[idx];
    var delay = 36 + (Math.random() * 14 - 7);
    if (c === ' ') delay = 1;
    if ('.,;:—!?'.indexOf(c) !== -1) delay += 120;
    idx++;
    setTimeout(type, delay);
  }

  setTimeout(type, 350);
}());
