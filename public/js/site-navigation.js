(() => {
    'use strict';
    const menuButton = document.querySelector('.site-menu-toggle');
    const menu = document.getElementById('primary-navigation');
    const productsMenu = document.querySelector('.rm-products-menu');
    if (productsMenu) {
        document.addEventListener('click', event => {
            if (!productsMenu.contains(event.target) || event.target.closest('.rm-products-dropdown a')) productsMenu.open = false;
        });
        document.addEventListener('keydown', event => {
            if (event.key === 'Escape' && productsMenu.open) {
                event.preventDefault();
                productsMenu.open = false;
                productsMenu.querySelector('summary').focus();
            }
        });
    }
    if (menuButton && menu) {
        document.documentElement.classList.add('navigation-ready');
        const setOpen = open => {
            if (!open && productsMenu) productsMenu.open = false;
            menu.classList.toggle('is-open', open);
            menuButton.setAttribute('aria-expanded', String(open));
        };
        menuButton.addEventListener('click', () => setOpen(menuButton.getAttribute('aria-expanded') !== 'true'));
        menu.addEventListener('click', event => {
            if (event.target.closest('a')) setOpen(false);
        });
        document.addEventListener('keydown', event => {
            if (!event.defaultPrevented && event.key === 'Escape' && menuButton.getAttribute('aria-expanded') === 'true') {
                setOpen(false);
                menuButton.focus();
            }
        });
        document.addEventListener('click', event => {
            if (!menu.contains(event.target) && !menuButton.contains(event.target)) setOpen(false);
        });
        window.matchMedia('(max-width: 1024px)').addEventListener('change', () => setOpen(false));
    }
    const homeLink = document.querySelector('[data-home-link]');
    const careersLink = document.querySelector('[data-careers-link]');
    if (!homeLink || !careersLink) return;

    const updateNavigation = () => {
        document.querySelectorAll('.primary-nav > a').forEach(link => {
            if (link.classList.contains('active')) {
                link.setAttribute('aria-current', 'page');
            } else {
                link.removeAttribute('aria-current');
            }
        });
    };

    window.addEventListener('hashchange', updateNavigation);
    window.addEventListener('pageshow', updateNavigation);
    updateNavigation();
})();
