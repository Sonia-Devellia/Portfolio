// ─── Typewriter — H1[data-typewriter] sur la home uniquement ─────────────────
// Geste de marque : frappe le H1 en 1.2s max, caret indigo, scroll-skip.
// Si prefers-reduced-motion, rendu immédiat sans animation.

(function () {
  var el = document.querySelector('[data-typewriter]');
  if (!el) return;

  var full = el.getAttribute('aria-label') || el.textContent.trim();

  // Honour prefers-reduced-motion: render the full text immediately, no typing
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    el.setAttribute('aria-label', full);
    return;
  }

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

  // Caret element
  var caret = document.createElement('span');
  caret.className = 'tw-caret';
  caret.setAttribute('aria-hidden', 'true');
  caret.textContent = '|';

  el.innerHTML = '';
  el.classList.add('is-typing');
  el.appendChild(caret);

  var idx    = 0;
  var liveEm = null;
  var done   = false;

  function renderFull() {
    if (done) return;
    done = true;
    el.innerHTML = '';
    // Reconstruct with proper segments
    segments.forEach(function (seg) {
      if (seg.tag === 'em') {
        var em = document.createElement('em');
        em.textContent = seg.text;
        el.appendChild(em);
      } else {
        el.appendChild(document.createTextNode(seg.text));
      }
    });
    el.classList.remove('is-typing');
  }

  // Skip on scroll within the first 600ms
  window.addEventListener('scroll', function () {
    renderFull();
  }, { once: true, passive: true });

  function type() {
    if (done) return;
    if (idx >= ops.length) {
      done = true;
      el.classList.remove('is-typing');
      // Caret blinks twice (600ms) then fades out
      el.appendChild(caret);
      setTimeout(function () { caret.classList.add('is-done'); }, 600);
      return;
    }

    var op = ops[idx];

    if (op.tag === 'em') {
      if (!liveEm) {
        liveEm = document.createElement('em');
        // Insert before caret
        el.insertBefore(liveEm, caret);
      }
      liveEm.textContent += op.char;
    } else {
      liveEm = null;
      var prev = caret.previousSibling;
      if (prev && prev.nodeType === Node.TEXT_NODE) {
        prev.textContent += op.char;
      } else {
        el.insertBefore(document.createTextNode(op.char), caret);
      }
    }

    var c     = op.char;
    var delay = 28 + (Math.random() * 8 - 4);
    if (c === ' ') delay = 1;
    idx++;
    setTimeout(type, delay);
  }

  setTimeout(type, 120);
}());
