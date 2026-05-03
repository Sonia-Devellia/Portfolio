/* ============================================================
   Portfolio Sonia Habibi — main.js
   ============================================================ */

document.addEventListener('DOMContentLoaded', () => {

  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

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
    nav?.classList.toggle('nav--scrolled', window.scrollY > 20);
  }, { passive: true });

  // ─── BURGER MOBILE ─────────────────────────────────────
  const burger   = document.getElementById('navBurger');
  const navLinks = document.querySelector('.nav__links');

  burger?.addEventListener('click', () => {
    const isOpen = burger.getAttribute('aria-expanded') === 'true';
    burger.setAttribute('aria-expanded', String(!isOpen));
    navLinks?.classList.toggle('nav__links--open');
  });

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

  // ─── FADE-UP ANIMATIONS ────────────────────────────────
  if (!prefersReducedMotion) {
    const fadeObserver = new IntersectionObserver(
      (entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) entry.target.classList.add('is-visible');
        });
      },
      { threshold: 0.08 }
    );

    document.querySelectorAll(
      '.service-card, .project-card, .timeline-item, .ai-scale__assertion, .ai-scale__text, .mobile-first__content'
    ).forEach(el => {
      el.classList.add('fade-up');
      fadeObserver.observe(el);
    });
  }

  // ─── COUNTER ANIMATION ─────────────────────────────────
  const counters = document.querySelectorAll('.ai-scale__num[data-target]');

  if (counters.length && !prefersReducedMotion) {
    const counterObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting && !entry.target.dataset.counted) {
          entry.target.dataset.counted = '1';
          animateCounter(entry.target);
        }
      });
    }, { threshold: 0.5 });

    counters.forEach(el => counterObserver.observe(el));
  }

  function animateCounter(el) {
    const target   = parseInt(el.dataset.target, 10);
    const suffix   = el.dataset.suffix || '';
    const duration = 900;
    const start    = performance.now();

    function update(now) {
      const progress = Math.min((now - start) / duration, 1);
      const eased    = 1 - Math.pow(1 - progress, 4); // ease-out-quart
      el.textContent = Math.round(target * eased) + suffix;
      if (progress < 1) requestAnimationFrame(update);
    }
    requestAnimationFrame(update);
  }

  // ─── CUSTOM CURSOR ─────────────────────────────────────
  const cursorDot  = document.getElementById('cursorDot');
  const cursorRing = document.getElementById('cursorRing');

  if (cursorDot && cursorRing && !('ontouchstart' in window)) {
    let mouseX = 0, mouseY = 0;
    let ringX  = 0, ringY  = 0;

    document.addEventListener('mousemove', (e) => {
      mouseX = e.clientX;
      mouseY = e.clientY;
      cursorDot.style.left = mouseX + 'px';
      cursorDot.style.top  = mouseY + 'px';
    });

    (function animateRing() {
      ringX += (mouseX - ringX) * 0.12;
      ringY += (mouseY - ringY) * 0.12;
      cursorRing.style.left = ringX + 'px';
      cursorRing.style.top  = ringY + 'px';
      requestAnimationFrame(animateRing);
    })();

    document.querySelectorAll('a, button').forEach(el => {
      el.addEventListener('mouseenter', () => {
        cursorRing.style.width   = '52px';
        cursorRing.style.height  = '52px';
        cursorRing.style.opacity = '1';
      });
      el.addEventListener('mouseleave', () => {
        cursorRing.style.width   = '32px';
        cursorRing.style.height  = '32px';
        cursorRing.style.opacity = '0.6';
      });
    });

    document.addEventListener('mouseleave', () => {
      cursorDot.style.opacity  = '0';
      cursorRing.style.opacity = '0';
    });
    document.addEventListener('mouseenter', () => {
      cursorDot.style.opacity  = '1';
      cursorRing.style.opacity = '0.6';
    });
  } else if (cursorDot && cursorRing) {
    cursorDot.style.display  = 'none';
    cursorRing.style.display = 'none';
  }

  // ─── SECTION NAV BREADCRUMB ────────────────────────────
  const sectionNav = document.getElementById('sectionNav');

  if (sectionNav) {
    const dots = sectionNav.querySelectorAll('[data-target]');

    const sectionObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        const dot = sectionNav.querySelector(`[data-target="${entry.target.id}"]`);
        dot?.classList.toggle('is-active', entry.isIntersecting);
      });
    }, { threshold: 0.3, rootMargin: '-10% 0px -10% 0px' });

    dots.forEach(dot => {
      const section = document.getElementById(dot.dataset.target);
      if (section) sectionObserver.observe(section);

      dot.addEventListener('click', () => {
        document.getElementById(dot.dataset.target)
          ?.scrollIntoView({ behavior: 'smooth', block: 'start' });
      });
    });
  }

});
