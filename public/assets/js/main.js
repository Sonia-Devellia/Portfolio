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

  // ─── FADE-UP (legacy — service-card, timeline-item) ────
  const fadeObs = new IntersectionObserver(
    entries => entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('is-visible'); }),
    { threshold: 0.08 }
  );
  document.querySelectorAll('.service-card, .timeline-item').forEach(el => {
    el.classList.add('fade-up');
    fadeObs.observe(el);
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

  // ─── LINE REVEAL helpers (section titles) ─────────────
  function buildLineWrap(el) {
    el.innerHTML = el.innerHTML.split(/<br\s*\/?>\s*/i)
      .map(p => `<span class="line-wrap"><span class="line">${p.trim()}</span></span>`)
      .join('');
  }
  function triggerLines(el, base) {
    el.querySelectorAll('.line').forEach((l, i) => {
      l.style.transitionDelay = ((base || 0) + i * 140) + 'ms';
      l.classList.add('is-revealed');
    });
  }

  // ─── SECTION + CTA TITLES — line reveal + underline ────
  document.querySelectorAll('.section__title, .cta-band__title').forEach(el => {
    buildLineWrap(el);
    const obs = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        triggerLines(entry.target);
        entry.target.classList.add('is-underlined');
        obs.unobserve(entry.target);
      });
    }, { threshold: 0.25 });
    obs.observe(el);
  });

  // ─── HERO SUBTITLE — word-by-word slide up ─────────────
  const heroSub = document.getElementById('heroSub');
  if (heroSub) {
    const words = heroSub.textContent.trim().split(/\s+/);
    heroSub.innerHTML = words.map(w =>
      `<span class="hw" style="opacity:0;display:inline-block;transform:translateY(8px);transition:opacity .7s,transform .7s">${w}</span>`
    ).join(' ');
    setTimeout(() => {
      heroSub.querySelectorAll('.hw').forEach((w, i) => {
        setTimeout(() => { w.style.opacity = '1'; w.style.transform = 'translateY(0)'; }, i * 100);
      });
    }, 900);
  }

  // ─── EYEBROW SLIDE IN FROM LEFT ────────────────────────
  document.querySelectorAll('.eyebrow').forEach(el => {
    el.classList.add('eyebrow--reveal');
    const obs = new IntersectionObserver(entries => {
      entries.forEach(e => {
        if (!e.isIntersecting) return;
        e.target.classList.add('is-revealed');
        obs.unobserve(e.target);
      });
    }, { threshold: 0.5 });
    obs.observe(el);
  });

  // ─── SERVICE CARD NUMBERS count 00→01/02/03 ────────────
  document.querySelectorAll('.service-card__num[data-num]').forEach(el => {
    const target = parseInt(el.dataset.num, 10);
    const obs = new IntersectionObserver(entries => {
      entries.forEach(e => {
        if (!e.isIntersecting || e.target.dataset.counted) return;
        e.target.dataset.counted = '1';
        const start = performance.now();
        (function tick(now) {
          const p = Math.min((now - start) / 400, 1);
          const v = Math.round(target * (1 - Math.pow(1 - p, 3)));
          e.target.textContent = String(v).padStart(2, '0');
          if (p < 1) requestAnimationFrame(tick);
        })(start);
      });
    }, { threshold: 0.5 });
    obs.observe(el);
  });

  // ─── ABOUT PULL QUOTE — word reveal ────────────────────
  const pullQuote = document.querySelector('.about__pullquote');
  if (pullQuote) {
    const words = pullQuote.textContent.trim().split(/\s+/);
    pullQuote.innerHTML = words
      .map(w => `<span class="pq-word" style="opacity:0;transition:opacity .35s">${w}</span>`)
      .join(' ');
    const obs = new IntersectionObserver(entries => {
      entries.forEach(e => {
        if (!e.isIntersecting) return;
        e.target.querySelectorAll('.pq-word').forEach((w, i) => setTimeout(() => { w.style.opacity = '1'; }, i * 90));
        obs.unobserve(e.target);
      });
    }, { threshold: 0.3 });
    obs.observe(pullQuote);
  }

  // ─── PROJECTS — auto-cycle ─────────────────────────────
  const slides = document.querySelectorAll('.mac-slide');
  const details = document.querySelectorAll('.project-detail');
  if (slides.length) {
    let cur = 0;
    const show = i => {
      slides.forEach((s,j) => s.classList.toggle('mac-slide--active', i===j));
      details.forEach((d,j) => d.classList.toggle('project-detail--active', i===j));
      cur = i;
    };
    show(0);
    setInterval(() => show((cur+1) % slides.length), 3500);
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
