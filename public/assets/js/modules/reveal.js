// ─── Reveal — IntersectionObserver unique pour .reveal ───────────────────────
// Ajoute .is-visible quand un élément entre à 15% dans le viewport.
// Stagger auto via --i CSS var (0 à 7 en modulo, reset à chaque groupe de 8).
(function () {
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    document.querySelectorAll('.reveal, .reveal--mask').forEach(function (el) {
      el.classList.add('is-visible');
    });
    return;
  }

  var io = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        io.unobserve(entry.target);
      }
    });
  }, { threshold: 0.15, rootMargin: '0px 0px -10% 0px' });

  document.querySelectorAll('.reveal, .reveal--mask').forEach(function (el, i) {
    el.style.setProperty('--i', i % 8);
    io.observe(el);
  });
}());
