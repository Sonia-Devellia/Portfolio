/* ============================================================
   Portfolio Sonia Habibi — main.js
   ============================================================ */

(function () {

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

  // ─── CUSTOM CURSOR ─────────────────────────────────────
  const cursorDot  = document.getElementById('cursorDot');
  const cursorRing = document.getElementById('cursorRing');

  if (cursorDot && cursorRing && !('ontouchstart' in window)) {
    let mouseX = 0, mouseY = 0, ringX = 0, ringY = 0;

    document.addEventListener('mousemove', e => {
      mouseX = e.clientX; mouseY = e.clientY;
      cursorDot.style.left = mouseX + 'px';
      cursorDot.style.top  = mouseY + 'px';
    });
    (function loop() {
      ringX += (mouseX - ringX) * 0.12;
      ringY += (mouseY - ringY) * 0.12;
      cursorRing.style.left = ringX + 'px';
      cursorRing.style.top  = ringY + 'px';
      requestAnimationFrame(loop);
    })();

    document.querySelectorAll('a, button').forEach(el => {
      el.addEventListener('mouseenter', () => { cursorRing.style.width = cursorRing.style.height = '52px'; cursorRing.style.opacity = '1'; });
      el.addEventListener('mouseleave', () => { cursorRing.style.width = cursorRing.style.height = '32px'; cursorRing.style.opacity = '0.6'; });
    });

    document.addEventListener('mouseleave', () => { cursorDot.style.opacity = '0'; cursorRing.style.opacity = '0'; });
    document.addEventListener('mouseenter', () => { cursorDot.style.opacity = '1'; cursorRing.style.opacity = '0.6'; });

    document.querySelector('.cta-band')?.addEventListener('mouseenter', () => {
      cursorDot.style.background   = '#f0eeeb';
      cursorRing.style.borderColor = '#f0eeeb';
    });
    document.querySelector('.cta-band')?.addEventListener('mouseleave', () => {
      cursorDot.style.background   = 'var(--text)';
      cursorRing.style.borderColor = 'var(--text)';
    });
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
