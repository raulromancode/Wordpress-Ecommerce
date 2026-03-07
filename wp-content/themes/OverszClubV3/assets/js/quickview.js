document.addEventListener('DOMContentLoaded', () => {
  const shell = document.querySelector('[data-quickview-shell]');
  const menuToggle = document.querySelector('[data-menu-toggle]');
  const menuPanel = document.querySelector('[data-menu-panel]');
  const liveRegion = document.querySelector('[data-live-region]');
  let previousActiveElement = null;

  const announce = (message) => {
    if (!liveRegion || !message) return;
    liveRegion.textContent = '';
    window.setTimeout(() => {
      liveRegion.textContent = message;
    }, 10);
  };

  const openQuickview = (productId, activeElement) => {
    if (!shell || !productId) return;

    previousActiveElement = activeElement || document.activeElement;

    const body = new URLSearchParams({
      action: 'overszclub_quickview',
      nonce: OverszClubData.nonce,
      product_id: productId,
    });

    fetch(OverszClubData.ajaxUrl, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
      body,
    })
      .then((response) => response.json())
      .then((response) => {
        if (!response.success) {
          throw new Error(OverszClubData.i18n.error);
        }

        shell.innerHTML = response.data.html;
        shell.classList.add('is-active');
        shell.setAttribute('aria-hidden', 'false');
        document.body.classList.add('has-quickview-open');
        const dialog = shell.querySelector('.quickview-modal__dialog');
        if (dialog) dialog.focus();
        announce('Vista rapida abierta.');
      })
      .catch(() => {
        announce(OverszClubData.i18n.error);
      });
  };

  const closeQuickview = () => {
    if (!shell) return;
    shell.innerHTML = '';
    shell.classList.remove('is-active');
    shell.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('has-quickview-open');
    if (previousActiveElement && typeof previousActiveElement.focus === 'function') {
      previousActiveElement.focus();
    }
  };

  const closeMenu = () => {
    if (!menuPanel || !menuToggle) return;
    menuPanel.classList.remove('is-open');
    menuToggle.setAttribute('aria-expanded', 'false');
    document.body.classList.remove('has-menu-open');
  };

  if (menuToggle && menuPanel) {
    menuToggle.addEventListener('click', () => {
      const willOpen = !menuPanel.classList.contains('is-open');
      menuPanel.classList.toggle('is-open', willOpen);
      menuToggle.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
      document.body.classList.toggle('has-menu-open', willOpen);
    });

    menuPanel.querySelectorAll('.menu-item-has-children > a').forEach((link) => {
      link.addEventListener('click', (event) => {
        if (window.innerWidth > 768) return;

        const item = link.parentElement;
        if (!item || !item.classList.contains('menu-item-has-children')) return;

        event.preventDefault();
        item.classList.toggle('is-open');
        link.setAttribute('aria-expanded', item.classList.contains('is-open') ? 'true' : 'false');
      });
    });
  }

  document.addEventListener('click', (event) => {
    const trigger = event.target.closest('[data-quickview-open]');
    if (trigger && shell) {
      event.preventDefault();
      const productId = trigger.getAttribute('data-quickview-open') || '';
      openQuickview(productId, event.target);
      return;
    }

    if (event.target.closest('[data-quickview-close]')) {
      closeQuickview();
    }

    if (menuPanel && menuPanel.classList.contains('is-open') && !event.target.closest('[data-menu-panel]') && !event.target.closest('[data-menu-toggle]')) {
      closeMenu();
    }
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
      closeQuickview();
      closeMenu();
    }
  });
});
