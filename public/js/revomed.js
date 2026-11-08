(() => {
    'use strict';
    document.querySelectorAll('[data-rm-carousel]').forEach(carousel => {
        const track = carousel.querySelector('.rm-product-track');
        const slides = [...track.children];
        const previous = carousel.querySelector('[data-rm-prev]');
        const next = carousel.querySelector('[data-rm-next]');
        const counter = carousel.querySelector('[data-rm-count]');
        const pickers = [...carousel.querySelectorAll('[data-rm-slide]')];
        const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
        let index = 0;
        const update = () => {
            index = Math.max(0, Math.min(slides.length - 1, Math.round(track.scrollLeft / track.clientWidth)));
            counter.textContent = `${String(index + 1).padStart(2, '0')} / ${String(slides.length).padStart(2, '0')}`;
            previous.disabled = index === 0;
            next.disabled = index === slides.length - 1;
            pickers.forEach((picker, position) => picker.setAttribute('aria-pressed', String(position === index)));
        };
        const go = direction => track.scrollTo({left: Math.max(0, Math.min(slides.length - 1, index + direction)) * track.clientWidth, behavior: reducedMotion.matches ? 'instant' : 'smooth'});
        previous.addEventListener('click', () => go(-1));
        next.addEventListener('click', () => go(1));
        pickers.forEach((picker, position) => picker.addEventListener('click', () => {
            track.scrollTo({left: position * track.clientWidth, behavior: reducedMotion.matches ? 'instant' : 'smooth'});
        }));
        track.addEventListener('scroll', update, {passive: true});
        track.addEventListener('keydown', event => {
            if (event.target !== track || !['ArrowLeft', 'ArrowRight'].includes(event.key)) return;
            event.preventDefault();
            go(event.key === 'ArrowRight' ? 1 : -1);
        });
        new ResizeObserver(() => { track.scrollTo({left: index * track.clientWidth, behavior: 'instant'}); update(); }).observe(track);
        update();
    });
})();
