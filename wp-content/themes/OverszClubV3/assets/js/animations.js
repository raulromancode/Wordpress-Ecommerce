document.addEventListener('DOMContentLoaded', () => {
  const preloader = document.querySelector('[data-preloader]');
  const reveals = document.querySelectorAll('[data-reveal]');

  window.addEventListener('load', () => {
    if (preloader) {
      preloader.classList.add('is-hidden');
    }
  });

  if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15 });

    reveals.forEach((item) => observer.observe(item));
  } else {
    reveals.forEach((item) => item.classList.add('is-visible'));
  }
});
