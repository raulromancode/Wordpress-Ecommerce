/**
 * OverszClubV2 — marquee.js
 * Ajuste dinámico de velocidad según ancho del contenido.
 */
(function() {
    'use strict';

    const strip = document.querySelector('.marquee-strip');
    const track = document.querySelector('.marquee-track');
    if (!strip || !track) return;

    function setSpeed() {
        const w   = track.scrollWidth / 2; // La mitad porque está duplicado
        const spd = Math.max(15, w / 80);  // ~80px/s mínimo 15s
        track.style.animationDuration = spd + 's';
    }

    setSpeed();
    window.addEventListener('resize', setSpeed, { passive: true });

    // Pausa al hover (ya gestionada por CSS, aquí como fallback)
    strip.addEventListener('mouseenter', function() {
        track.style.animationPlayState = 'paused';
    });
    strip.addEventListener('mouseleave', function() {
        track.style.animationPlayState = 'running';
    });
})();
