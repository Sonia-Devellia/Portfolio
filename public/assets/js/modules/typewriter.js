// ─── Typewriter — H1[data-typewriter] sur la home uniquement ─────────────────
// Lit le contenu, ré-écrit caractère à caractère avec curseur indigo.
// Préserve les balises <em> inline pendant la frappe.
// Si ce script ne se charge pas, le H1 reste visible normalement.

(function () {
  var el = document.querySelector('[data-typewriter]');
  if (!el) return;

  var full = el.getAttribute('aria-label') || el.textContent.trim();

  // Découper le innerHTML en segments { tag: null|'em', text: '...' }
  var segments = [];
  el.childNodes.forEach(function (node) {
    if (node.nodeType === Node.TEXT_NODE) {
      segments.push({ tag: null, text: node.textContent });
    } else if (node.nodeName === 'EM') {
      segments.push({ tag: 'em', text: node.textContent });
    }
  });

  // Aplatir en opérations individuelles { tag, char }
  var ops = [];
  segments.forEach(function (seg) {
    Array.from(seg.text).forEach(function (c) {
      ops.push({ tag: seg.tag, char: c });
    });
  });

  el.setAttribute('aria-label', full);
  el.setAttribute('aria-live', 'off');
  el.innerHTML = '';
  el.classList.add('is-typing');

  var idx      = 0;
  var liveEm   = null;

  function type() {
    if (idx >= ops.length) {
      setTimeout(function () { el.classList.remove('is-typing'); }, 600);
      return;
    }

    var op = ops[idx];

    if (op.tag === 'em') {
      if (!liveEm) {
        liveEm = document.createElement('em');
        el.appendChild(liveEm);
      }
      liveEm.textContent += op.char;
    } else {
      liveEm = null;
      var last = el.lastChild;
      if (last && last.nodeType === Node.TEXT_NODE) {
        last.textContent += op.char;
      } else {
        el.appendChild(document.createTextNode(op.char));
      }
    }

    var c     = op.char;
    var delay = 72 + (Math.random() * 28 - 14);
    if (c === ' ') delay = 1;
    if ('.,;:—!?'.indexOf(c) !== -1) delay += 240;
    idx++;
    setTimeout(type, delay);
  }

  setTimeout(type, 350);
}());
