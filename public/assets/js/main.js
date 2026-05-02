/* ============================================================
   Portfolio Sonia Habibi — main.js
   ============================================================ */

document.addEventListener('DOMContentLoaded', () => {

  // ─── DARK MODE ─────────────────────────────────────────
  const html         = document.documentElement;
  const themeToggle  = document.getElementById('themeToggle');
  const savedTheme   = localStorage.getItem('theme') || 'light';

  html.setAttribute('data-theme', savedTheme);

  themeToggle?.addEventListener('click', () => {
    const current = html.getAttribute('data-theme');
    const next    = current === 'light' ? 'dark' : 'light';
    html.setAttribute('data-theme', next);
    localStorage.setItem('theme', next);
  });

  // ─── NAV SCROLL ────────────────────────────────────────
  const nav = document.getElementById('nav');

  window.addEventListener('scroll', () => {
    if (window.scrollY > 20) {
      nav?.classList.add('nav--scrolled');
    } else {
      nav?.classList.remove('nav--scrolled');
    }
  }, { passive: true });

  // ─── BURGER MOBILE ─────────────────────────────────────
  const burger   = document.getElementById('navBurger');
  const navLinks = document.querySelector('.nav__links');

  burger?.addEventListener('click', () => {
    const isOpen = burger.getAttribute('aria-expanded') === 'true';
    burger.setAttribute('aria-expanded', String(!isOpen));
    navLinks?.classList.toggle('nav__links--open');
  });

  // Fermer le menu au clic sur un lien
  navLinks?.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => {
      navLinks.classList.remove('nav__links--open');
      burger?.setAttribute('aria-expanded', 'false');
    });
  });

  // ─── CONFIRM DIALOGS ───────────────────────────────────
  document.querySelectorAll('[data-confirm]').forEach(btn => {
    btn.addEventListener('click', (e) => {
      if (!confirm(btn.dataset.confirm)) e.preventDefault();
    });
  });

  // ─── CSRF TOKEN ────────────────────────────────────────
  // Injecté côté PHP, rien à faire ici

  // ─── ANIMATIONS AU SCROLL ──────────────────────────────
  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  if (!prefersReducedMotion) {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
          }
        });
      },
      { threshold: 0.08 }
    );

    const animated = [
      '.service-card',
      '.project-card',
      '.timeline-item',
      '.ai-scale__assertion',
      '.ai-scale__text',
      '.mobile-first__content',
      '.mobile-first__device',
    ];

    document.querySelectorAll(animated.join(', ')).forEach(el => {
      el.classList.add('fade-up');
      observer.observe(el);
    });
  }

});
