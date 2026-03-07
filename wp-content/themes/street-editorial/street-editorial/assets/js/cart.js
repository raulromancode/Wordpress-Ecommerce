/**
 * Street Editorial — cart.js
 * Gestión del carrito lateral con AJAX
 */
(function ($) {
  'use strict';

  const DRAWER  = document.getElementById('cart-drawer');
  const OVERLAY = document.getElementById('cart-overlay');
  const BODY    = document.body;

  /* ── Abrir / cerrar drawer ─────────────────────────────── */
  function openCart() {
    DRAWER.classList.add('open');
    OVERLAY.classList.add('open');
    BODY.style.overflow = 'hidden';
  }

  function closeCart() {
    DRAWER.classList.remove('open');
    OVERLAY.classList.remove('open');
    BODY.style.overflow = '';
  }

  document.getElementById('cart-trigger')?.addEventListener('click', openCart);
  document.getElementById('cart-close')?.addEventListener('click', closeCart);
  OVERLAY?.addEventListener('click', closeCart);

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeCart();
  });

  /* ── Notificación "Añadido" ─────────────────────────────── */
  function showNotif(msg) {
    let n = document.getElementById('added-notif');
    if (!n) {
      n = document.createElement('div');
      n.id = 'added-notif';
      n.className = 'added-notif';
      document.body.appendChild(n);
    }
    n.textContent = msg || '✓  Añadido al carrito';
    n.classList.add('show');
    setTimeout(() => n.classList.remove('show'), 2200);
  }

  /* ── Actualizar badge del carrito ───────────────────────── */
  function updateBadge(count) {
    const badge = document.getElementById('cart-count');
    if (!badge) return;
    badge.textContent = count;
    badge.style.display = count > 0 ? 'flex' : 'none';
  }

  /* ── Refrescar contenido del drawer via AJAX ────────────── */
  function refreshDrawer() {
    $.post(streetEditorial.ajax_url, { action: 'se_cart_fragment' }, function (res) {
      if (!res.success) return;
      const d = res.data;
      document.getElementById('cart-items').innerHTML = d.items_html;
      document.getElementById('cart-total').textContent = d.total;
      updateBadge(d.count);
      const foot = document.getElementById('cart-footer');
      if (foot) foot.style.display = d.count > 0 ? 'block' : 'none';
      bindRemove();
    });
  }

  /* ── Eliminar item ──────────────────────────────────────── */
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

  /* ── WooCommerce: al añadir producto abrir drawer ───────── */
  $(document.body).on('added_to_cart', function () {
    openCart();
    refreshDrawer();
    showNotif('✓  Añadido al carrito');
  });

  /* ── Quick Add desde la tarjeta de producto ─────────────── */
  document.addEventListener('click', function (e) {
    const btn = e.target.closest('.quick-add-btn');
    if (!btn) return;

    const card    = btn.closest('.product-card');
    const pid     = btn.dataset.productId;
    const varId   = btn.dataset.variationId || pid;
    const size    = card?.querySelector('.size-btn.selected')?.dataset?.size || '';

    if (!size) {
      // Resaltar selector de talla
      card?.querySelector('.size-selector')?.classList.add('shake');
      setTimeout(() => card?.querySelector('.size-selector')?.classList.remove('shake'), 500);
      return;
    }

    btn.disabled = true;
    btn.textContent = '...';

    const data = {
      action:       'woocommerce_ajax_add_to_cart',
      product_id:   pid,
      variation_id: varId,
      quantity:     1,
    };
    if (size) data['attribute_pa_talla'] = size;

    $.post(streetEditorial.ajax_url, data, function (res) {
      if (res.error && res.product_url) {
        window.location = res.product_url;
        return;
      }
      $(document.body).trigger('added_to_cart', [res.fragments, res.cart_hash, btn]);
    }).always(function () {
      btn.disabled = false;
      btn.textContent = 'Quick Add';
    });
  });

  /* ── Selector de tallas en tarjeta ─────────────────────── */
  document.addEventListener('click', function (e) {
    const s = e.target.closest('.size-btn');
    if (!s) return;
    const card = s.closest('.product-card');
    card?.querySelectorAll('.size-btn').forEach(b => b.classList.remove('selected'));
    s.classList.add('selected');
    e.stopPropagation();
  });

  /* ── Galería single product (thumbnail swap) ─────────────── */
  document.addEventListener('click', function (e) {
    const thumb = e.target.closest('.single-gallery__thumb');
    if (!thumb) return;
    const main = document.querySelector('.single-gallery__main');
    if (main) main.src = thumb.src.replace(/-(150x150|300x300|medium|thumbnail)/, '-large');
    document.querySelectorAll('.single-gallery__thumb').forEach(t => t.classList.remove('active'));
    thumb.classList.add('active');
  });

  /* ── Botón Add to Cart (single product) ─────────────────── */
  document.addEventListener('click', function (e) {
    const btn = e.target.closest('.single-add-btn');
    if (!btn) return;

    const selected = document.querySelector('.size-option.active');
    const pid      = btn.dataset.productId;

    if (!selected) {
      document.querySelector('.size-grid')?.classList.add('shake');
      setTimeout(() => document.querySelector('.size-grid')?.classList.remove('shake'), 500);
      return;
    }

    btn.classList.add('loading');
    btn.textContent = 'Añadiendo...';

    const data = {
      action:                 'woocommerce_ajax_add_to_cart',
      product_id:             pid,
      quantity:               1,
      'attribute_pa_talla':   selected.dataset.size,
    };

    $.post(streetEditorial.ajax_url, data, function (res) {
      $(document.body).trigger('added_to_cart', [res.fragments, res.cart_hash]);
    }).always(function () {
      btn.classList.remove('loading');
      btn.textContent = 'Add to Cart';
    });
  });

  /* ── Size option selector (single page) ─────────────────── */
  document.addEventListener('click', function (e) {
    const opt = e.target.closest('.size-option');
    if (!opt || opt.classList.contains('unavailable')) return;
    document.querySelectorAll('.size-option').forEach(o => o.classList.remove('active'));
    opt.classList.add('active');
  });

  /* ── Shake animation CSS ─────────────────────────────────── */
  const style = document.createElement('style');
  style.textContent = `
    @keyframes shake {
      0%,100% { transform:translateX(0); }
      20%      { transform:translateX(-6px); }
      40%      { transform:translateX(6px); }
      60%      { transform:translateX(-4px); }
      80%      { transform:translateX(4px); }
    }
    .shake { animation: shake .4s ease; }
  `;
  document.head.appendChild(style);

})(jQuery);
