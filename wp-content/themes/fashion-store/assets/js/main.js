/* ══════════════════════════════════════════════════════════════
   FASHION STORE — main.js
   ══════════════════════════════════════════════════════════════ */

(function ($) {
    'use strict';

    /* ─── Carrito lateral ──────────────────────────────────────── */
    var $drawer   = $('#cart-drawer');
    var $backdrop = $('#cart-backdrop');

    function openCart()  {
        $drawer.addClass('open');
        $backdrop.addClass('open');
        $('body').css('overflow', 'hidden');
    }
    function closeCart() {
        $drawer.removeClass('open');
        $backdrop.removeClass('open');
        $('body').css('overflow', '');
    }

    $(document).on('click', '#cart-trigger', openCart);
    $(document).on('click', '#cart-close, #cart-backdrop', closeCart);

    // Abre el drawer cuando WooCommerce añade algo al carrito (AJAX)
    $(document.body).on('added_to_cart', function () {
        openCart();
        refreshCart();
    });

    /* ─── Refrescar drawer via AJAX ────────────────────────────── */
    function refreshCart() {
        $.post(fashionAjax.ajax_url, { action: 'fashion_cart_fragment' }, function (res) {
            if (!res.success) return;
            var d = res.data;
            $('#cart-drawer-items').html(d.items_html);
            $('#cart-drawer-foot')[d.count > 0 ? 'show' : 'hide']();
            $('#cart-drawer-total').text(d.total);
            $('#cart-item-count').text(d.count).toggle(d.count > 0);
            bindRemove();
        });
    }

    function bindRemove() {
        $(document).on('click', '.cart-line__rm', function () {
            var key = $(this).data('key');
            $.post(fashionAjax.ajax_url,
                { action: 'fashion_remove_item', cart_key: key },
                function () {
                    refreshCart();
                    $(document.body).trigger('wc_fragment_refresh');
                }
            );
        });
    }
    bindRemove();

    /* ─── Menú móvil ───────────────────────────────────────────── */
    $(document).on('click', '#hamburger', function () {
        $('#mobile-nav').toggleClass('is-open');
    });

    /* ─── Filtros de categoría (shop) ──────────────────────────── */
    $(document).on('click', '.cat-filter', function () {
        var cat = $(this).data('cat');
        $('.cat-filter').removeClass('active');
        $(this).addClass('active');

        if (cat === 'all') {
            $('.product-card').show();
        } else {
            $('.product-card').each(function () {
                $(this).toggle($(this).data('cat') === cat);
            });
        }
    });

    /* ─── Galería de producto (thumbnails) ─────────────────────── */
    $(document).on('click', '.single-product__thumb', function () {
        var src = $(this).attr('src');
        $('.single-product__main-img').attr('src', src);
        $('.single-product__thumb').removeClass('active');
        $(this).addClass('active');
    });

})(jQuery);
