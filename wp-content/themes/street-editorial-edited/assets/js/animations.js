/**
 * Street Editorial — animations.js
 * Reveal on scroll + microinteracciones
 */
(function () {
  'use strict';

  /* ── Intersection Observer: revelar elementos al scroll ── */
  if (!('IntersectionObserver' in window)) return;

  const observer = new IntersectionObserver(
    function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('in-view');
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.12, rootMargin: '0px 0px -40px 0px' }
  );

  // Aplicar clase reveal a las cards de producto con delay escalonado
  document.querySelectorAll('.product-card').forEach(function (el, i) {
    el.classList.add('reveal');
    el.style.transitionDelay = (i % 4) * 80 + 'ms';
    observer.observe(el);
  });

  // Otras secciones con reveal
  document.querySelectorAll(
    '.section-head, .about-strip__body, .about-block, .lookbook-item, .footer-grid > div'
  ).forEach(function (el) {
    el.classList.add('reveal');
    observer.observe(el);
  });

  /* ── Hamburguesa móvil ────────────────────────────────── */
  const hamburger  = document.getElementById('hamburger');
  const mobileMenu = document.getElementById('mobile-menu');

  if (hamburger && mobileMenu) {
    hamburger.addEventListener('click', function () {
      const open = mobileMenu.classList.toggle('open');
      hamburger.classList.toggle('open', open);
      hamburger.setAttribute('aria-expanded', open);
    });
  }

  /* ── Cursor personalizado (sutil) ─────────────────────── */
  // Solo en desktop
  if (window.matchMedia('(pointer:fine)').matches) {
    const dot = document.createElement('div');
    Object.assign(dot.style, {
      position:      'fixed',
      width:         '6px',
      height:        '6px',
      background:    '#e8c547',
      borderRadius:  '50%',
      pointerEvents: 'none',
      zIndex:        '9999',
      transition:    'transform .1s',
      top:           '-10px',
      left:          '-10px',
    });
    document.body.appendChild(dot);

    document.addEventListener('mousemove', function (e) {
      dot.style.top  = e.clientY - 3 + 'px';
      dot.style.left = e.clientX - 3 + 'px';
    });

    document.querySelectorAll('a, button, .product-card, .lookbook-item').forEach(function (el) {
      el.addEventListener('mouseenter', () => { dot.style.transform = 'scale(3)'; });
      el.addEventListener('mouseleave', () => { dot.style.transform = 'scale(1)'; });
    });
  }

  /* ── Parallax suave en el hero ────────────────────────── */
  const heroBg = document.querySelector('.hero__bg');
  if (heroBg) {
    window.addEventListener('scroll', function () {
      const scrolled = window.pageYOffset;
      heroBg.style.transform = 'scale(1.03) translateY(' + scrolled * 0.25 + 'px)';
    }, { passive: true });
  }

})();
