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
    const imageSelectors = '.frame-macbook, .home-project-card img, .case-study img';
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

  // ─── STACK SLIDER — navigation onglets ────────────────
  const stackGrid = document.querySelector('.stack__grid');
  if (stackGrid) {
    const cols = [...stackGrid.children];

    // Construire la nav numérotée
    const nav = document.createElement('div');
    nav.className = 'stack__nav';
    nav.setAttribute('aria-label', html.lang === 'fr' ? 'Navigation stack technique' : 'Technical stack navigation');

    cols.forEach((col, i) => {
      const num   = col.querySelector('.stack__col-num')?.textContent.trim() ?? String(i + 1).padStart(2, '0');
      const label = col.querySelector('.stack__col-role')?.textContent.trim() ?? '';
      const btn   = document.createElement('button');
      btn.className = 'stack__nav-btn' + (i === 0 ? ' is-active' : '');
      btn.setAttribute('aria-label', label || num);
      btn.innerHTML = `<span class="stack__nav-num">${num}</span><span class="stack__nav-label">${label}</span>`;
      btn.addEventListener('click', () => {
        stackGrid.scrollTo({ left: cols[i].offsetLeft, behavior: 'smooth' });
      });
      nav.appendChild(btn);
    });

    stackGrid.before(nav);

    const navBtns = [...nav.children];

    const fillBars = col => {
      col.querySelectorAll('.stack__tech-bar').forEach(bar => bar.classList.add('is-filled'));
    };
    const resetBars = col => {
      col.querySelectorAll('.stack__tech-bar').forEach(bar => bar.classList.remove('is-filled'));
    };

    // Animer les barres de la 1re card au chargement
    fillBars(cols[0]);

    stackGrid.addEventListener('scroll', () => {
      const mid = stackGrid.scrollLeft + stackGrid.clientWidth / 2;
      let active = 0;
      cols.forEach((col, i) => { if (col.offsetLeft <= mid) active = i; });
      navBtns.forEach((btn, i) => btn.classList.toggle('is-active', i === active));
      cols.forEach((col, i) => (i === active ? fillBars : resetBars)(col));
    }, { passive: true });
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
