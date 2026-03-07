document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-marquee-track]').forEach((track) => {
    const width = track.scrollWidth;
    if (width > 0) {
      const duration = Math.max(18, Math.round(width / 120));
      track.style.animationDuration = `${duration}s`;
    }
  });
});
