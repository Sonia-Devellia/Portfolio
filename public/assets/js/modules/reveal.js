// ─── Reveal au scroll — IntersectionObserver ─────────────────────────────────

(function () {

  // Fallback prefers-reduced-motion : tout visible d'emblée
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    document.querySelectorAll('.reveal, .reveal--left, .reveal--right, .reveal--mask').forEach(function (el) {
      el.classList.add('is-visible');
    });
    document.querySelectorAll('.method__step').forEach(function (el) {
      el.classList.add('reveal', 'is-visible');
    });
    return;
  }

  // ─── Observateur générique (éléments individuels) ─────────────────────────
  var io = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (!entry.isIntersecting) return;
      entry.target.classList.add('is-visible');
      io.unobserve(entry.target);
    });
  }, { threshold: 0.12, rootMargin: '0px 0px -18% 0px' });

  // ─── Section stack — observer sur .stack__head (petit, fiable), stagger cards
  // On observe le header (pas la section 2000px) pour éviter les problèmes de
  // threshold sur un très grand conteneur overflow:hidden.
  // Quand la tête est visible → on stagger les rows via setTimeout.
  var stackSection = document.querySelector('.stack');
  var stackHead    = stackSection && stackSection.querySelector('.stack__head');
  if (stackHead) {
    var stackObserver = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        stackObserver.disconnect();
        stackSection.querySelectorAll('.stack__row').forEach(function (row, i) {
          setTimeout(function () {
            var card = row.querySelector('.stack__card');
            var dot  = row.querySelector('.stack__dot');
            if (card) card.classList.add('is-visible');
            if (dot)  dot.classList.add('is-visible');
          }, i * 250);
        });
      });
    }, { threshold: 0.4 });
    stackObserver.observe(stackHead);
  }

  // ─── Section method — idem, stagger à 200 ms ─────────────────────────────
  // Les .method__step n'ont pas .reveal en HTML → on l'ajoute ici (opacity 0),
  // puis l'observer stagger chaque étape.
  var methodSection = document.querySelector('.method');
  if (methodSection) {
    var methodSteps = methodSection.querySelectorAll('.method__step');
    methodSteps.forEach(function (step) {
      step.classList.add('reveal');
    });
    var methodObserver = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        methodObserver.unobserve(entry.target);
        methodSteps.forEach(function (step, i) {
          setTimeout(function () {
            step.classList.add('is-visible');
          }, i * 200);
        });
      });
    }, { threshold: 0.08, rootMargin: '0px 0px -5% 0px' });
    methodObserver.observe(methodSection);
  }

  // ─── Stagger automatique pour les .stagger-group (en-tête stack) ──────────
  document.querySelectorAll('.stagger-group').forEach(function (group) {
    var i = 0;
    group.querySelectorAll('.reveal, .reveal--left, .reveal--right').forEach(function (el) {
      el.style.setProperty('--i', i++);
    });
  });

  // ─── Éléments ciblés par sélecteur (progressive enhancement) ─────────────
  var TARGETS = [
    { sel: '.pillar',            cls: 'reveal'       },
    { sel: '.home-project-card', cls: 'reveal'       },
    { sel: '.realisation',       cls: 'reveal'       },
    { sel: '.casestudy-card',    cls: 'reveal'       },
    { sel: '.about__body',       cls: 'reveal'       },
    { sel: '.about__portrait',   cls: 'reveal--mask' },
  ];

  var stagger = 0;
  TARGETS.forEach(function (cfg) {
    document.querySelectorAll(cfg.sel).forEach(function (el) {
      if (!el.classList.contains('reveal') && !el.classList.contains('reveal--left') &&
          !el.classList.contains('reveal--right') && !el.classList.contains('reveal--mask')) {
        el.classList.add(cfg.cls);
      }
      if (!el.closest('.stagger-group')) {
        el.style.setProperty('--i', stagger % 8);
        stagger++;
      }
      io.observe(el);
    });
  });

  // ─── Observer les .reveal déjà en HTML (hors stack rows et method steps) ──
  // Les stack cards / dots sont gérés par stackObserver.
  // Les method steps sont gérés par methodObserver.
  document.querySelectorAll('.reveal, .reveal--left, .reveal--right, .reveal--mask').forEach(function (el) {
    if (el.closest('.stack__row') || el.classList.contains('method__step')) return;
    io.observe(el);
  });

}());
