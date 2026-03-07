/**
 * OverszClubV2 — animations.js
 * Reveal on scroll + custom cursor + back to top
 */
(function() {
    'use strict';

    /* ── Reveal al hacer scroll ──────────────────────────── */
    const revealEls = document.querySelectorAll('.reveal, .reveal-scale');

    if ('IntersectionObserver' in window && revealEls.length) {
        const io = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in');
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

        revealEls.forEach(function(el, i) {
            // Escalonar animaciones en hijos del mismo padre
            const siblings = el.parentElement.querySelectorAll('.reveal, .reveal-scale');
            let delay = 0;
            siblings.forEach(function(sib, idx) { if (sib === el) delay = idx * 80; });
            el.style.transitionDelay = delay + 'ms';
            io.observe(el);
        });
    } else {
        // Sin IntersectionObserver: mostrar todo
        revealEls.forEach(el => el.classList.add('in'));
    }

    /* ── Cursor personalizado ────────────────────────────── */
    const cursorDot  = document.createElement('div');
    const cursorRing = document.createElement('div');

    cursorDot.style.cssText  = 'position:fixed;width:6px;height:6px;background:var(--c-accent);border-radius:50%;pointer-events:none;z-index:9999;transform:translate(-50%,-50%);transition:opacity .3s;mix-blend-mode:difference;';
    cursorRing.style.cssText = 'position:fixed;width:32px;height:32px;border:1px solid rgba(232,197,71,.5);border-radius:50%;pointer-events:none;z-index:9998;transform:translate(-50%,-50%);transition:transform .15s ease,width .25s ease,height .25s ease,opacity .3s;';

    document.body.appendChild(cursorDot);
    document.body.appendChild(cursorRing);

    let mx = -100, my = -100;
    let rx = -100, ry = -100;

    document.addEventListener('mousemove', function(e) {
        mx = e.clientX; my = e.clientY;
        cursorDot.style.left = mx + 'px';
        cursorDot.style.top  = my + 'px';
    });

    // Lag suave para el ring
    (function animRing() {
        rx += (mx - rx) * 0.12;
        ry += (my - ry) * 0.12;
        cursorRing.style.left = rx + 'px';
        cursorRing.style.top  = ry + 'px';
        requestAnimationFrame(animRing);
    })();

    // Expandir en hover de links/buttons
    document.querySelectorAll('a, button, .product-card, .sp-thumb, .card-size-btn, .sp-size-opt').forEach(function(el) {
        el.addEventListener('mouseenter', function() {
            cursorRing.style.width   = '54px';
            cursorRing.style.height  = '54px';
            cursorDot.style.opacity  = '0';
        });
        el.addEventListener('mouseleave', function() {
            cursorRing.style.width   = '32px';
            cursorRing.style.height  = '32px';
            cursorDot.style.opacity  = '1';
        });
    });

    // Ocultar en touch
    if ('ontouchstart' in window) {
        cursorDot.style.display  = 'none';
        cursorRing.style.display = 'none';
    }

    /* ── Back to top ─────────────────────────────────────── */
    const btt = document.createElement('button');
    btt.innerHTML = '↑';
    btt.setAttribute('aria-label', 'Volver arriba');
    btt.style.cssText = [
        'position:fixed',
        'bottom:28px',
        'right:28px',
        'width:40px',
        'height:40px',
        'background:var(--c-surface)',
        'border:1px solid var(--c-border2)',
        'color:var(--c-muted)',
        'font-size:16px',
        'cursor:pointer',
        'z-index:500',
        'display:flex',
        'align-items:center',
        'justify-content:center',
        'opacity:0',
        'visibility:hidden',
        'transition:opacity .3s,visibility .3s,color .2s,border-color .2s',
    ].join(';');

    btt.addEventListener('mouseenter', function() {
        this.style.color        = 'var(--c-accent)';
        this.style.borderColor  = 'var(--c-accent)';
    });
    btt.addEventListener('mouseleave', function() {
        this.style.color        = 'var(--c-muted)';
        this.style.borderColor  = 'var(--c-border2)';
    });
    btt.addEventListener('click', function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    document.body.appendChild(btt);

    window.addEventListener('scroll', function() {
        const show = window.scrollY > 400;
        btt.style.opacity    = show ? '1' : '0';
        btt.style.visibility = show ? 'visible' : 'hidden';
    }, { passive: true });

})();
