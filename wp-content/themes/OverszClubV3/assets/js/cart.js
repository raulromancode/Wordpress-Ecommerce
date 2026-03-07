document.addEventListener('DOMContentLoaded', () => {
  const drawer = document.querySelector('[data-cart-drawer]');
  const content = document.querySelector('[data-cart-content]');
  const countNodes = document.querySelectorAll('[data-cart-count]');
  const liveRegion = document.querySelector('[data-live-region]');
  const drawerPanel = drawer ? drawer.querySelector('.cart-drawer__panel') : null;
  let previousActiveElement = null;

  const announce = (message) => {
    if (!liveRegion || !message) return;
    liveRegion.textContent = '';
    window.setTimeout(() => {
      liveRegion.textContent = message;
    }, 10);
  };

  const syncCartUi = (payload) => {
    if (!payload) return;
    if (typeof payload.cart_html === 'string' && content) {
      content.innerHTML = payload.cart_html;
    }
    if (typeof payload.cart_count !== 'undefined') {
      countNodes.forEach((node) => {
        node.textContent = String(payload.cart_count);
      });
    }
  };

  const openDrawer = () => {
    if (!drawer) return;
    previousActiveElement = document.activeElement;
    drawer.classList.add('is-active');
    drawer.setAttribute('aria-hidden', 'false');
    document.body.classList.add('has-cart-open');
    if (drawerPanel) drawerPanel.focus();
  };

  const closeDrawer = () => {
    if (!drawer) return;
    drawer.classList.remove('is-active');
    drawer.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('has-cart-open');
    if (previousActiveElement && typeof previousActiveElement.focus === 'function') {
      previousActiveElement.focus();
    }
  };

  document.querySelectorAll('[data-cart-toggle]').forEach((button) => {
    button.addEventListener('click', openDrawer);
  });

  document.addEventListener('click', (event) => {
    if (event.target.closest('[data-cart-close]')) {
      closeDrawer();
    }

    const removeButton = event.target.closest('[data-cart-remove]');
    if (!removeButton) return;

    const body = new URLSearchParams({
      action: 'overszclub_remove_from_cart',
      nonce: OverszClubData.nonce,
      cart_key: removeButton.getAttribute('data-cart-remove') || '',
    });

    fetch(OverszClubData.ajaxUrl, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
      body,
    })
      .then((response) => response.json())
      .then((response) => {
        if (response.success) {
          syncCartUi(response.data);
          announce('Producto eliminado del carrito.');
        }
      });
  });

  document.addEventListener('submit', (event) => {
    const form = event.target.closest('[data-product-form]');
    if (!form) return;

    event.preventDefault();

    const feedback = form.querySelector('[data-product-feedback]');
    const submit = form.querySelector('button[type="submit"]');
    const formData = new FormData(form);
    const selectedSize = formData.get('size') || '';
    const sizeOptions = form.querySelectorAll('input[name="size"]');
    const body = new URLSearchParams({
      action: 'overszclub_add_to_cart',
      nonce: OverszClubData.nonce,
      product_id: formData.get('product_id') || '',
      quantity: formData.get('quantity') || '1',
      size: selectedSize,
    });

    if (sizeOptions.length > 0 && !selectedSize) {
      if (feedback) feedback.textContent = OverszClubData.i18n.selectSize;
      announce(OverszClubData.i18n.selectSize);
      return;
    }

    if (feedback) feedback.textContent = OverszClubData.i18n.loading;
    if (submit) submit.disabled = true;

    fetch(OverszClubData.ajaxUrl, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
      body,
    })
      .then((response) => response.json())
      .then((response) => {
        if (!response.success) {
          throw new Error(response?.data?.message || OverszClubData.i18n.error);
        }

        syncCartUi(response.data);
        if (feedback) feedback.textContent = response.data.message || OverszClubData.i18n.added;
        announce(response.data.message || OverszClubData.i18n.added);
        openDrawer();
      })
      .catch((error) => {
        if (feedback) feedback.textContent = error.message || OverszClubData.i18n.error;
        announce(error.message || OverszClubData.i18n.error);
      })
      .finally(() => {
        if (submit) submit.disabled = false;
      });
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
      closeDrawer();
    }
  });
});
