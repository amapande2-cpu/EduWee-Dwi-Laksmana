(() => {
  const footer = document.querySelector('.footer');
  if (!footer) return;

  const VISIBLE_CLASS = 'is-visible';
  const THRESHOLD_PX = 120; // how close to bottom before revealing

  const update = () => {
    const doc = document.documentElement;
    const scrollTop = window.scrollY || doc.scrollTop || 0;
    const viewportBottom = scrollTop + window.innerHeight;
    const pageBottom = doc.scrollHeight;

    const nearBottom = viewportBottom >= pageBottom - THRESHOLD_PX;
    footer.classList.toggle(VISIBLE_CLASS, nearBottom);
  };

  let ticking = false;
  const scheduleUpdate = () => {
    if (ticking) return;
    ticking = true;
    window.requestAnimationFrame(() => {
      update();
      ticking = false;
    });
  };

  window.addEventListener('scroll', scheduleUpdate, { passive: true });
  window.addEventListener('resize', scheduleUpdate);

  // Initial state (also handles short pages)
  update();
})();

