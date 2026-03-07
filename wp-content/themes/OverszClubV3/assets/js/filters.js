document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('[data-shop-filters]');
  const results = document.querySelector('[data-shop-results]');
  const count = document.querySelector('[data-shop-count]');

  if (!form || !results) return;

  let timer = null;

  const runFilters = () => {
    const formData = new FormData(form);
    const body = new URLSearchParams({
      action: 'overszclub_filter_products',
      nonce: OverszClubData.nonce,
      category: formData.get('category') || '',
      size: formData.get('size') || '',
      min_price: formData.get('min_price') || '',
      max_price: formData.get('max_price') || '',
    });

    results.classList.add('is-loading');
    if (count) count.textContent = OverszClubData.i18n.loading;

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

        results.innerHTML = response.data.html;
        if (count) count.textContent = `${response.data.count} resultados`;
      })
      .catch(() => {
        if (count) count.textContent = OverszClubData.i18n.error;
      })
      .finally(() => {
        results.classList.remove('is-loading');
      });
  };

  form.addEventListener('input', () => {
    window.clearTimeout(timer);
    timer = window.setTimeout(runFilters, 220);
  });

  form.addEventListener('change', runFilters);

  form.addEventListener('reset', () => {
    window.setTimeout(runFilters, 0);
  });
});
