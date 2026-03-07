/**
 * Street Editorial — cart.js
 * 
 * CARRITO: Compatible 100% con WooCommerce nativo.
 *   - Productos SIMPLES:   form submit nativo → WC procesa → added_to_cart event
 *   - Productos VARIABLES: selecciona variación → submit con variation_id
 *   - Drawer lateral:      se actualiza automáticamente via WC fragments
 *
 * SINGLE PRODUCT: galería, selector tallas, cantidad, acordeón
 */
(function ($) {
    'use strict';

    /* ══════════════════════════════════════════════════════════
       1. DRAWER — Abrir / Cerrar
       ══════════════════════════════════════════════════════════ */
    const DRAWER  = document.getElementById('cart-drawer');
    const OVERLAY = document.getElementById('cart-overlay');

    function openCart() {
        if (!DRAWER) return;
        DRAWER.classList.add('open');
        if (OVERLAY) OVERLAY.classList.add('open');
        document.body.style.overflow = 'hidden';
        refreshDrawer();
    }

    function closeCart() {
        if (!DRAWER) return;
        DRAWER.classList.remove('open');
        if (OVERLAY) OVERLAY.classList.remove('open');
        document.body.style.overflow = '';
    }

    document.getElementById('cart-trigger')?.addEventListener('click', openCart);
    document.getElementById('cart-close')?.addEventListener('click', closeCart);
    OVERLAY?.addEventListener('click', closeCart);
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeCart(); });

    /* ══════════════════════════════════════════════════════════
       2. BADGE + NOTIFICACIÓN
       ══════════════════════════════════════════════════════════ */
    function updateBadge(count) {
        const badge = document.getElementById('cart-count');
        if (!badge) return;
        badge.textContent = count;
        badge.style.display = count > 0 ? 'flex' : 'none';
    }

    function showNotif(msg) {
        let n = document.getElementById('added-notif');
        if (!n) return;
        n.textContent = msg || '✓  Añadido al carrito';
        n.classList.add('show');
        setTimeout(() => n.classList.remove('show'), 2500);
    }

    /* ══════════════════════════════════════════════════════════
       3. REFRESH DEL DRAWER (AJAX)
       ══════════════════════════════════════════════════════════ */
    function refreshDrawer() {
        if (!window.streetEditorial) return;
        $.post(streetEditorial.ajax_url, { action: 'se_cart_fragment' }, function (res) {
            if (!res.success) return;
            const d = res.data;
            const el = document.getElementById('cart-items');
            if (el) el.innerHTML = d.items_html;
            const tot = document.getElementById('cart-total');
            if (tot) tot.textContent = d.total;
            updateBadge(d.count);
            const foot = document.getElementById('cart-footer');
            if (foot) foot.style.display = d.count > 0 ? 'block' : 'none';
            bindRemove();
        });
    }

    /* ══════════════════════════════════════════════════════════
       4. ELIMINAR ITEM DEL DRAWER
       ══════════════════════════════════════════════════════════ */
    function bindRemove() {
        document.querySelectorAll('.cart-item__remove').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const key = this.dataset.key;
                $.post(streetEditorial.ajax_url,
                    { action: 'se_remove_item', cart_key: key },
                    function () {
                        refreshDrawer();
                        $(document.body).trigger('wc_fragment_refresh');
                    }
                );
            });
        });
    }
    bindRemove();

    /* ══════════════════════════════════════════════════════════
       5. HOOK: WooCommerce dispara este evento al añadir al carrito
          Funciona tanto con el form nativo como con AJAX
       ══════════════════════════════════════════════════════════ */
    $(document.body).on('added_to_cart', function (e, fragments, cart_hash, $button) {
        openCart();
        refreshDrawer();
        showNotif('✓  Añadido al carrito');
        // Restaurar botón si estaba en estado loading
        const btn = document.getElementById('sp-atc-btn');
        if (btn) setAtcLoading(btn, false);
    });

    $(document.body).on('wc_fragments_refreshed', function () {
        // WooCommerce actualizó sus propios fragmentos: sincronizar badge
        const count = parseInt($('.cart-count').first().text()) || 0;
        updateBadge(count);
    });

    /* ══════════════════════════════════════════════════════════
       6. SINGLE PRODUCT — Formulario principal
       ══════════════════════════════════════════════════════════ */
    const spForm  = document.getElementById('sp-atc-form');
    const spBtn   = document.getElementById('sp-atc-btn');
    const sizeErr = document.getElementById('sp-size-error');

    function setAtcLoading(btn, loading) {
        if (!btn) return;
        const textEl    = btn.querySelector('.sp-atc-btn__text');
        const loadingEl = btn.querySelector('.sp-atc-btn__loading');
        btn.disabled = loading;
        if (textEl)    textEl.style.display    = loading ? 'none' : 'flex';
        if (loadingEl) loadingEl.style.display = loading ? 'flex' : 'none';
    }

    // ── Selector de tallas ────────────────────────────────────
    document.querySelectorAll('.sp-size-opt').forEach(function (opt) {
        opt.addEventListener('click', function () {
            document.querySelectorAll('.sp-size-opt').forEach(o => o.classList.remove('active'));
            this.classList.add('active');
            if (sizeErr) sizeErr.style.display = 'none';

            // Rellenar el campo oculto con la talla para que el form la envíe
            const size = this.dataset.size;
            // Intentar asignar al primer campo attribute_ que exista
            const attrInput = spForm?.querySelector('[id^="sp-attr-"]');
            if (attrInput) attrInput.value = size;
        });
    });

    // ── Selector de cantidad ──────────────────────────────────
    document.getElementById('sp-qty-minus')?.addEventListener('click', function () {
        const input = document.getElementById('sp-qty-input');
        if (!input) return;
        const val = parseInt(input.value) || 1;
        if (val > 1) input.value = val - 1;
    });

    document.getElementById('sp-qty-plus')?.addEventListener('click', function () {
        const input  = document.getElementById('sp-qty-input');
        if (!input) return;
        const val    = parseInt(input.value) || 1;
        const maxVal = parseInt(input.max) || 99;
        if (val < maxVal) input.value = val + 1;
    });

    // ── Submit del formulario: AJAX para no recargar la página ─
    if (spForm && spBtn) {
        const isVariable = spBtn.dataset.isVariable === '1';
        const hasSizes   = document.querySelectorAll('.sp-size-opt').length > 0;

        spForm.addEventListener('submit', function (e) {
            e.preventDefault();

            // Validar: si hay selector de tallas, debe haber una activa
            if (hasSizes) {
                const active = document.querySelector('.sp-size-opt.active');
                if (!active) {
                    if (sizeErr) sizeErr.style.display = 'block';
                    document.getElementById('sp-size-grid')?.classList.add('shake');
                    setTimeout(() => document.getElementById('sp-size-grid')?.classList.remove('shake'), 500);
                    return;
                }
            }

            setAtcLoading(spBtn, true);

            const formData = new FormData(spForm);
            const params   = new URLSearchParams(formData);

            // WooCommerce add-to-cart por URL (método más robusto)
            const pid = spBtn.dataset.productId;
            const qty = document.getElementById('sp-qty-input')?.value || 1;

            // Construir data para el AJAX nativo de WC
            const ajaxData = {
                action:     'woocommerce_ajax_add_to_cart',
                product_id: pid,
                quantity:   qty,
            };

            // Añadir atributos de variación si existen
            spForm.querySelectorAll('[id^="sp-attr-"]').forEach(function (inp) {
                if (inp.value) ajaxData[inp.name] = inp.value;
            });

            // Añadir variation_id si existe
            const varInput = document.getElementById('sp-variation-id');
            if (varInput && varInput.value) {
                ajaxData.variation_id = varInput.value;
            }

            $.post(
                wc_add_to_cart_params?.ajax_url || streetEditorial.ajax_url,
                ajaxData,
                function (res) {
                    if (res && res.error && res.product_url) {
                        // Producto necesita configuración → redirigir
                        window.location.href = res.product_url;
                        return;
                    }
                    // Éxito: disparar evento nativo de WC
                    $(document.body).trigger('added_to_cart', [
                        res.fragments,
                        res.cart_hash,
                        spBtn
                    ]);
                }
            ).fail(function () {
                // Fallback: submit normal del form si AJAX falla
                spForm.submit();
            }).always(function () {
                setAtcLoading(spBtn, false);
            });
        });
    }

    // ── Galería: cambio de imagen principal ───────────────────
    document.querySelectorAll('.sp-thumb').forEach(function (thumb) {
        thumb.addEventListener('click', function () {
            const mainImg = document.getElementById('sp-main-img');
            if (!mainImg) return;

            // Fade out → cambiar src → fade in
            mainImg.style.opacity = '0';
            setTimeout(function () {
                mainImg.src = thumb.dataset.full || thumb.querySelector('img')?.src;
                mainImg.style.opacity = '1';
            }, 160);

            document.querySelectorAll('.sp-thumb').forEach(t => t.classList.remove('active'));
            thumb.classList.add('active');
        });
    });

    // Transición suave en imagen principal
    const mainImg = document.getElementById('sp-main-img');
    if (mainImg) mainImg.style.transition = 'opacity .16s ease';

    // ── Acordeón: descripción completa ───────────────────────
    const accToggle = document.getElementById('sp-desc-toggle');
    const accBody   = document.getElementById('sp-desc-body');
    if (accToggle && accBody) {
        accToggle.addEventListener('click', function () {
            const isOpen = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', !isOpen);
            accBody.setAttribute('aria-hidden', isOpen);
            accBody.classList.toggle('open', !isOpen);
        });
    }

    /* ══════════════════════════════════════════════════════════
       7. PRODUCT CARDS — Quick Add (tienda)
       ══════════════════════════════════════════════════════════ */
    document.addEventListener('click', function (e) {
        // Selector de talla en tarjeta
        const sizeBtn = e.target.closest('.size-btn');
        if (sizeBtn) {
            const card = sizeBtn.closest('.product-card');
            card?.querySelectorAll('.size-btn').forEach(b => b.classList.remove('selected'));
            sizeBtn.classList.add('selected');
            e.stopPropagation();
            return;
        }

        // Botón Quick Add
        const addBtn = e.target.closest('.quick-add-btn');
        if (!addBtn) return;

        const card = addBtn.closest('.product-card');
        const pid  = addBtn.dataset.productId;
        const size = card?.querySelector('.size-btn.selected')?.dataset?.size || '';

        // Para productos sin talla obligatoria, añadir directamente
        addBtn.disabled = true;
        const originalText = addBtn.textContent;
        addBtn.textContent = '...';

        const data = {
            action:     'woocommerce_ajax_add_to_cart',
            product_id: pid,
            quantity:   1,
        };
        if (size) data['attribute_pa_talla'] = size;

        $.post(
            wc_add_to_cart_params?.ajax_url || streetEditorial.ajax_url,
            data,
            function (res) {
                if (res && res.error && res.product_url) {
                    window.location.href = res.product_url;
                    return;
                }
                $(document.body).trigger('added_to_cart', [res.fragments, res.cart_hash, addBtn]);
            }
        ).fail(function () {
            // Fallback: ir a la URL del producto
            window.location.href = addBtn.dataset.productUrl || '#';
        }).always(function () {
            addBtn.disabled = false;
            addBtn.textContent = originalText;
        });
    });

    /* ══════════════════════════════════════════════════════════
       8. CSS: animación shake (talla no seleccionada)
       ══════════════════════════════════════════════════════════ */
    const shakeStyle = document.createElement('style');
    shakeStyle.textContent = `
        @keyframes shake {
            0%,100% { transform: translateX(0); }
            20%     { transform: translateX(-6px); }
            40%     { transform: translateX(6px); }
            60%     { transform: translateX(-4px); }
            80%     { transform: translateX(4px); }
        }
        .shake { animation: shake .4s ease; }
    `;
    document.head.appendChild(shakeStyle);

})(jQuery);
