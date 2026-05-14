/* ============================================================
   Portfolio Sonia Habibi — main.js
   ============================================================ */

(function () {

  const REDUCE_MOTION = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // ─── DARK MODE ─────────────────────────────────────────
  const html        = document.documentElement;
  const themeToggle = document.getElementById('themeToggle');
  const savedTheme  = localStorage.getItem('theme') || 'light';
  html.setAttribute('data-theme', savedTheme);

  themeToggle?.addEventListener('click', () => {
    const next = html.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
    html.setAttribute('data-theme', next);
    localStorage.setItem('theme', next);
  });

  // ─── NAV SCROLL ────────────────────────────────────────
  const nav = document.getElementById('nav');
  window.addEventListener('scroll', () => {
    nav?.classList.toggle('nav--scrolled', window.scrollY > 20);
  }, { passive: true });

  // ─── CONFIRM DIALOGS ───────────────────────────────────
  document.querySelectorAll('[data-confirm]').forEach(btn => {
    btn.addEventListener('click', e => {
      if (!confirm(btn.dataset.confirm)) e.preventDefault();
    });
  });

  // ─── CUSTOM CURSOR — 5 états contextuels ───────────────
  const cursorDot  = document.getElementById('cursorDot');
  const cursorRing = document.getElementById('cursorRing');
  const ringLabel  = cursorRing?.querySelector('.cursor-ring__label');

  if (!REDUCE_MOTION && cursorDot && cursorRing && !('ontouchstart' in window)) {
    let mouseX = 0, mouseY = 0, ringX = 0, ringY = 0;
    let usingKeyboard = false;

    const setRingState = state => {
      cursorDot.dataset.state  = state;
      cursorRing.dataset.state = state;
      if (ringLabel) {
        const isFr = html.lang === 'fr';
        ringLabel.textContent = state === 'image'
          ? (isFr ? "Voir l'étude · ↗" : 'View case · ↗')
          : '';
      }
    };

    // Position tracking
    document.addEventListener('mousemove', e => {
      mouseX = e.clientX;
      mouseY = e.clientY;
      cursorDot.style.left = mouseX + 'px';
      cursorDot.style.top  = mouseY + 'px';
      if (usingKeyboard) {
        usingKeyboard = false;
        cursorDot.style.display  = '';
        cursorRing.style.display = '';
      }
    });

    (function loop() {
      ringX += (mouseX - ringX) * 0.12;
      ringY += (mouseY - ringY) * 0.12;
      cursorRing.style.left = ringX + 'px';
      cursorRing.style.top  = ringY + 'px';
      requestAnimationFrame(loop);
    })();

    // Visibility on document edges
    document.addEventListener('mouseleave', () => {
      cursorDot.style.opacity  = '0';
      cursorRing.style.opacity = '0';
    });
    document.addEventListener('mouseenter', () => {
      cursorDot.style.opacity  = '1';
      cursorRing.style.opacity = '1';
    });

    // État 'image' — images projet
    const imageSelectors = '.frame-macbook, .home-project-card img, .case-study img, .project-card__thumb';
    document.querySelectorAll(imageSelectors).forEach(el => {
      el.addEventListener('mouseenter', () => setRingState('image'));
      el.addEventListener('mouseleave', () => setRingState('rest'));
    });

    // État 'dark' — CTA band et ses enfants
    document.querySelector('.cta-band')?.addEventListener('mouseenter', () => setRingState('dark'));
    document.querySelector('.cta-band')?.addEventListener('mouseleave', () => setRingState('rest'));

    // État 'link' — liens éditoriaux (a sans .btn, sans logo, sans skip)
    document.querySelectorAll('a:not(.btn):not(.nav__logo):not(.skip-link)').forEach(el => {
      el.addEventListener('mouseenter', () => {
        if (cursorRing.dataset.state !== 'image' && cursorRing.dataset.state !== 'dark') {
          setRingState('link');
        }
      });
      el.addEventListener('mouseleave', () => {
        if (cursorRing.dataset.state === 'link') setRingState('rest');
      });
    });

    // Keyboard detection — masquer curseur custom jusqu'au prochain mousemove
    document.addEventListener('keydown', e => {
      if (e.key === 'Tab' && !usingKeyboard) {
        usingKeyboard = true;
        cursorDot.style.display  = 'none';
        cursorRing.style.display = 'none';
      }
    });

    setRingState('rest');
  } else if (cursorDot && cursorRing) {
    cursorDot.style.display = cursorRing.style.display = 'none';
  }

  // ─── PROJECTS — clickable tabs ─────────────────────────
  const slides  = document.querySelectorAll('.mac-slide');
  const details = document.querySelectorAll('.project-detail');
  if (slides.length) {
    const show = i => {
      slides.forEach((s, j)  => s.classList.toggle('mac-slide--active',      i === j));
      details.forEach((d, j) => d.classList.toggle('project-detail--active', i === j));
    };
    show(0);
    slides.forEach((s, i)  => s.addEventListener('click',  () => show(i)));
    details.forEach((d, i) => d.addEventListener('click',  () => show(i)));
  }

  // ─── FOOTER LOCAL TIME ─────────────────────────────────
  const localTimeEl = document.getElementById('localTime');
  if (localTimeEl) {
    const fmt = new Intl.DateTimeFormat(html.lang === 'en' ? 'en-GB' : 'fr',
      { timeZone: 'Europe/Paris', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false });
    const tick = () => { localTimeEl.textContent = fmt.format(new Date()); };
    tick(); setInterval(tick, 1000);
  }

}());
