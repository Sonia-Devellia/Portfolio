// ─── Reveal — progressive enhancement ───────────────────────────────────────
// Ce script ajoute la classe .reveal (ou .reveal--mask) sur les éléments cibles
// puis observe leur entrée dans le viewport.
// Si ce script ne se charge pas, les éléments restent visibles (opacity: 1).

(function () {
  // Sélecteurs → classe appliquée
  var TARGETS = [
    { sel: '.pillar',             cls: 'reveal'      },
    { sel: '.home-project-card',  cls: 'reveal'      },
    { sel: '.realisation',        cls: 'reveal'      },
    { sel: '.casestudy-card',     cls: 'reveal'      },
    { sel: '.method__step',       cls: 'reveal'      },
    { sel: '.about__body',        cls: 'reveal'      },
    { sel: '.section-link',       cls: 'reveal'      },
    { sel: '.about__portrait',    cls: 'reveal--mask' },
  ];

  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  var io = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (!entry.isIntersecting) return;
      entry.target.classList.add('is-visible');
      io.unobserve(entry.target);
    });
  }, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });

  var stagger = 0;
  TARGETS.forEach(function (cfg) {
    document.querySelectorAll(cfg.sel).forEach(function (el) {
      el.classList.add(cfg.cls);
      el.style.setProperty('--i', stagger % 8);
      io.observe(el);
      stagger++;
    });
  });
}());
