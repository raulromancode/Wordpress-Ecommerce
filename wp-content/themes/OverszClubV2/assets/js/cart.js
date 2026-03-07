/**
 * OverszClubV2 — cart.js
 *
 * ✅ Carrito lateral (drawer) con AJAX
 * ✅ Add to cart: productos simples Y variables
 * ✅ Sólo valida talla si el producto tiene selector de tallas
 * ✅ Fallback: submit nativo WC si AJAX falla
 * ✅ Eliminar items del drawer
 */
(function ($) {
    'use strict';

    /* ────────────────────────────────────────────────────────
       REFERENCIAS DOM
       ──────────────────────────────────────────────────────── */
    const $drawer  = $('#oc-cart-drawer');
    const $overlay = $('#oc-cart-overlay');
    const $body    = $(document.body);
    const $notif   = $('#oc-notif');

    /* ════════════════════════════════════════════════════════
       1. DRAWER — ABRIR / CERRAR
       ════════════════════════════════════════════════════════ */
    function openDrawer() {
        $drawer.addClass('open').attr('aria-hidden','false');
        $overlay.addClass('open');
        document.body.style.overflow = 'hidden';
        $('#oc-cart-trigger').attr('aria-expanded','true');
        refreshDrawer();
    }

    function closeDrawer() {
        $drawer.removeClass('open').attr('aria-hidden','true');
        $overlay.removeClass('open');
        document.body.style.overflow = '';
        $('#oc-cart-trigger').attr('aria-expanded','false');
    }

    $('#oc-cart-trigger').on('click', openDrawer);
    $('#oc-cart-close').on('click', closeDrawer);
    $overlay.on('click', closeDrawer);
    $(document).on('keydown', function(e) { if (e.key === 'Escape') closeDrawer(); });

    /* ════════════════════════════════════════════════════════
       2. NOTIFICACIÓN
       ════════════════════════════════════════════════════════ */
    function showNotif(msg) {
        $notif.text(msg || (typeof ocData !== 'undefined' ? ocData.i18n.added : '✓ Añadido')).addClass('show');
        setTimeout(() => $notif.removeClass('show'), 2600);
    }

    /* ════════════════════════════════════════════════════════
       3. BADGE DEL CARRITO
       ════════════════════════════════════════════════════════ */
    function updateBadge(count) {
        const n = parseInt(count) || 0;
        $('#oc-cart-count').text(n).css('display', n > 0 ? 'flex' : 'none');
        $('.cart-drawer__badge').text(n);
    }

    /* ════════════════════════════════════════════════════════
       4. REFRESCAR DRAWER VIA AJAX
       ════════════════════════════════════════════════════════ */
    function refreshDrawer() {
        const ajaxUrl = typeof ocData !== 'undefined' ? ocData.ajax_url : '/wp-admin/admin-ajax.php';
        const nonce   = typeof ocData !== 'undefined' ? ocData.nonce   : '';

        $.post(ajaxUrl, { action: 'oc_cart_fragment', nonce }, function(res) {
            if (!res.success) return;
            $('#oc-cart-body').html(res.data.html);
            $('#oc-cart-total').text(res.data.total);
            updateBadge(res.data.count);

            const $foot = $('#oc-cart-foot');
            $foot.css('display', parseInt(res.data.count) > 0 ? 'block' : 'none');

            bindRemoveButtons();
        });
    }

    /* ════════════════════════════════════════════════════════
       5. ELIMINAR ITEM
       ════════════════════════════════════════════════════════ */
    function bindRemoveButtons() {
        $('#oc-cart-body').off('click.ocremove').on('click.ocremove', '.cart-item__remove', function() {
            const key     = $(this).data('key');
            const ajaxUrl = typeof ocData !== 'undefined' ? ocData.ajax_url : '/wp-admin/admin-ajax.php';
            const nonce   = typeof ocData !== 'undefined' ? ocData.nonce   : '';

            $(this).closest('.cart-item').css('opacity','.4');

            $.post(ajaxUrl, { action: 'oc_remove_item', cart_key: key, nonce }, function(res) {
                if (!res.success) return;
                $('#oc-cart-body').html(res.data.html);
                $('#oc-cart-total').text(res.data.total);
                updateBadge(res.data.count);
                $('#oc-cart-foot').css('display', parseInt(res.data.count) > 0 ? 'block' : 'none');
                $body.trigger('wc_fragment_refresh');
                bindRemoveButtons();
            });
        });
    }
    bindRemoveButtons();

    /* ════════════════════════════════════════════════════════
       6. WC EVENT: added_to_cart → abrir drawer
       ════════════════════════════════════════════════════════ */
    $body.on('added_to_cart', function(e, fragments, hash, $btn) {
        openDrawer();
        refreshDrawer();
        showNotif();
        // Restaurar botón si estaba en loading
        if ($btn) {
            $btn.removeClass('loading').prop('disabled', false);
        }
    });

    /* ════════════════════════════════════════════════════════
       7. MENÚ HAMBURGUESA (MÓVIL)
       ════════════════════════════════════════════════════════ */
    $('#oc-hamburger').on('click', function() {
        const isOpen = $('#oc-mobile-menu').hasClass('open');
        $(this).toggleClass('open').attr('aria-expanded', !isOpen);
        $('#oc-mobile-menu').toggleClass('open');
    });

    /* ════════════════════════════════════════════════════════
       8. NAVBAR SCROLL
       ════════════════════════════════════════════════════════ */
    const $header = $('#site-header');
    let lastScroll = 0;
    $(window).on('scroll.ocnav', function() {
        const y = window.scrollY;
        $header.toggleClass('scrolled', y > 60);
        lastScroll = y;
    });

    /* ════════════════════════════════════════════════════════
       9. SELECTOR DE TALLAS EN TARJETAS (tienda / home)
       ════════════════════════════════════════════════════════ */
    $(document).on('click', '.card-size-btn', function(e) {
        e.stopPropagation();
        const $card = $(this).closest('.product-card');
        $card.find('.card-size-btn').removeClass('selected');
        $(this).addClass('selected');
    });

    /* ════════════════════════════════════════════════════════
       10. AÑADIR AL CARRITO DESDE TARJETA (Quick Add)
       ════════════════════════════════════════════════════════ */
    $(document).on('click', '.card-atc-btn', function(e) {
        e.preventDefault();

        const $btn      = $(this);
        const $card     = $btn.closest('.product-card');
        const pid       = $btn.data('product-id');
        const isVar     = $btn.data('is-variable') === 1 || $btn.data('is-variable') === '1';
        const $selected = $card.find('.card-size-btn.selected');
        const size      = $selected.data('size') || '';
        const ajaxUrl   = typeof ocData !== 'undefined' ? ocData.ajax_url : '/wp-admin/admin-ajax.php';

        // Si es variable y no tiene talla, redirigir al producto
        if (isVar && !size) {
            window.location.href = $btn.data('product-url') || '#';
            return;
        }

        $btn.prop('disabled', true).text('...');

        const data = {
            action:     'woocommerce_ajax_add_to_cart',
            product_id: pid,
            quantity:   1,
        };
        if (size) data['attribute_pa_talla'] = size;

        $.post(ajaxUrl, data)
            .done(function(res) {
                if (res && res.error && res.product_url) {
                    window.location.href = res.product_url;
                    return;
                }
                $body.trigger('added_to_cart', [res.fragments, res.cart_hash, $btn]);
            })
            .fail(function() {
                // Fallback: ir a la página del producto
                window.location.href = $btn.data('product-url') || '#';
            })
            .always(function() {
                $btn.prop('disabled', false).text('Añadir al carrito');
            });
    });

    /* ════════════════════════════════════════════════════════
       11. SINGLE PRODUCT — FORMULARIO
       ════════════════════════════════════════════════════════ */
    const $spForm  = $('#sp-form');
    const $spBtn   = $('#sp-atc-btn');
    const $sizeErr = $('#sp-size-err');
    const $sizeGrid = $('#sp-size-grid');

    // ── Selector de tallas
    if ($sizeGrid.length) {
        $sizeGrid.on('click', '.sp-size-opt', function() {
            $sizeGrid.find('.sp-size-opt').removeClass('active');
            $(this).addClass('active');
            $sizeErr.hide();

            // Rellenar campos ocultos
            const size = $(this).data('size');
            $spForm.find('[id^="sp-attr-"]').first().val(size);
        });
    }

    // ── Cantidad
    $('#sp-minus').on('click', function() {
        const $input = $('#sp-qty');
        const val = parseInt($input.val()) || 1;
        if (val > 1) $input.val(val - 1);
    });
    $('#sp-plus').on('click', function() {
        const $input = $('#sp-qty');
        const val    = parseInt($input.val()) || 1;
        const max    = parseInt($input.attr('max')) || 99;
        if (val < max) $input.val(val + 1);
    });

    // ── Submit con AJAX
    if ($spForm.length && $spBtn.length) {
        const isVar   = $spBtn.data('is-variable') === 1 || $spBtn.data('is-variable') === '1';
        const hasSizes = $sizeGrid.find('.sp-size-opt').length > 0;

        $spForm.on('submit', function(e) {
            e.preventDefault();

            // Validar talla sólo si hay selector activo
            if (hasSizes && !$sizeGrid.find('.sp-size-opt.active').length) {
                $sizeErr.show();
                $sizeGrid.addClass('shake');
                setTimeout(() => $sizeGrid.removeClass('shake'), 500);
                return;
            }

            // Estado loading
            $spBtn.addClass('loading').prop('disabled', true);

            const pid     = $spBtn.data('product-id');
            const qty     = parseInt($('#sp-qty').val()) || 1;
            const ajaxUrl = typeof ocData !== 'undefined' ? ocData.ajax_url : '/wp-admin/admin-ajax.php';

            const postData = {
                action:     'woocommerce_ajax_add_to_cart',
                product_id: pid,
                quantity:   qty,
            };

            // Añadir atributos de variación
            $spForm.find('[id^="sp-attr-"]').each(function() {
                if ($(this).val()) postData[$(this).attr('name')] = $(this).val();
            });

            // variation_id si existe
            const varId = $('#sp-var-id').val();
            if (varId) postData.variation_id = varId;

            $.post(ajaxUrl, postData)
                .done(function(res) {
                    if (res && res.error && res.product_url) {
                        window.location.href = res.product_url;
                        return;
                    }
                    $body.trigger('added_to_cart', [res.fragments, res.cart_hash, $spBtn]);
                })
                .fail(function() {
                    // Fallback: submit nativo
                    $spForm.off('submit').submit();
                })
                .always(function() {
                    $spBtn.removeClass('loading').prop('disabled', false);
                });
        });
    }

    /* ════════════════════════════════════════════════════════
       12. GALERÍA SINGLE PRODUCT
       ════════════════════════════════════════════════════════ */
    if ($('#sp-main-img').length) {
        const $mainImg = $('#sp-main-img');
        $mainImg.css('transition','opacity .18s ease');

        $(document).on('click', '.sp-thumb', function() {
            const full = $(this).data('full');
            if (!full) return;

            $mainImg.css('opacity', 0);
            setTimeout(function() {
                $mainImg.attr('src', full).css('opacity', 1);
            }, 160);

            $('.sp-thumb').removeClass('active');
            $(this).addClass('active');
        });
    }

    /* ════════════════════════════════════════════════════════
       13. ACORDEÓN DESCRIPCIÓN SINGLE
       ════════════════════════════════════════════════════════ */
    $('#sp-acc-btn').on('click', function() {
        const expanded = $(this).attr('aria-expanded') === 'true';
        $(this).attr('aria-expanded', !expanded);
        $('#sp-acc-body').toggleClass('open', !expanded).attr('aria-hidden', expanded);
    });

    /* ════════════════════════════════════════════════════════
       14. CSS SHAKE (talla no seleccionada)
       ════════════════════════════════════════════════════════ */
    $('<style>')
        .text(`@keyframes shake{0%,100%{transform:translateX(0)}20%{transform:translateX(-6px)}40%{transform:translateX(6px)}60%{transform:translateX(-4px)}80%{transform:translateX(4px)}}.shake{animation:shake .4s ease}`)
        .appendTo('head');

})(jQuery);
