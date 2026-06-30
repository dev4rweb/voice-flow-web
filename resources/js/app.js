import './bootstrap';

const themeKey = 'voice-flow-theme';
const mobileMenuQuery = window.matchMedia('(max-width: 1359px)');

const applyTheme = (theme) => {
    document.documentElement.dataset.theme = theme;
    localStorage.setItem(themeKey, theme);

    const text = document.querySelector('[data-theme-text]');
    if (text) {
        text.textContent = theme === 'dark' ? text.dataset.darkLabel : text.dataset.lightLabel;
    }
};

const initialTheme = document.documentElement.dataset.theme || localStorage.getItem(themeKey) || 'light';
applyTheme(initialTheme);

document.querySelector('[data-theme-toggle]')?.addEventListener('click', () => {
    applyTheme(document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark');

    if (mobileMenuQuery.matches) {
        setMenuOpen(false);
    }
});

document.querySelector('[data-locale-switcher]')?.addEventListener('change', (event) => {
    if (event.target.value) {
        window.location.href = event.target.value;
    }
});

const appendDownloadTimezone = (link) => {
    try {
        const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;

        if (!timezone) {
            return;
        }

        const url = new URL(link.href, window.location.origin);
        url.searchParams.set('tz', timezone);
        link.href = url.toString();
    } catch (_) {
        // Ignore unsupported environments.
    }
};

document.querySelectorAll('[data-download-link]').forEach((link) => {
    appendDownloadTimezone(link);
    link.addEventListener('click', () => appendDownloadTimezone(link), { capture: true });
});

const header = document.querySelector('[data-site-header]');
const menuPanel = document.querySelector('[data-site-menu]');
const menuBackdrop = document.querySelector('[data-menu-backdrop]');
const nav = document.querySelector('[data-site-nav]');
const navToggle = document.querySelector('[data-nav-toggle]');
const scrollTopButton = document.querySelector('[data-scroll-top]');
const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const scrollTopThreshold = 480;

const syncMenuAccessibility = () => {
    if (!menuPanel) {
        return;
    }

    if (mobileMenuQuery.matches) {
        menuPanel.setAttribute('aria-hidden', menuPanel.classList.contains('is-open') ? 'false' : 'true');
        return;
    }

    closeMenu();
    menuPanel.setAttribute('aria-hidden', 'false');
};

const closeMenu = () => {
    menuPanel?.classList.remove('is-open');
    menuBackdrop?.classList.remove('is-visible');
    navToggle?.setAttribute('aria-expanded', 'false');
    menuPanel?.setAttribute('aria-hidden', 'true');
    menuBackdrop?.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('menu-open');
};

const setMenuOpen = (isOpen) => {
    if (!menuPanel || !navToggle || !mobileMenuQuery.matches) {
        return;
    }

    if (isOpen) {
        menuPanel.classList.add('is-open');
        menuBackdrop?.classList.add('is-visible');
        navToggle.setAttribute('aria-expanded', 'true');
        menuPanel.setAttribute('aria-hidden', 'false');
        menuBackdrop?.setAttribute('aria-hidden', 'false');
        document.body.classList.add('menu-open');
        return;
    }

    closeMenu();
};

syncMenuAccessibility();

navToggle?.addEventListener('click', () => {
    setMenuOpen(navToggle.getAttribute('aria-expanded') !== 'true');
});

menuBackdrop?.addEventListener('click', () => {
    setMenuOpen(false);
});

document.querySelector('[data-menu-close]')?.addEventListener('click', () => {
    setMenuOpen(false);
});

nav?.querySelectorAll('a').forEach((link) => {
    link.addEventListener('click', () => {
        if (mobileMenuQuery.matches) {
            setMenuOpen(false);
        }
    });
});

mobileMenuQuery.addEventListener('change', () => {
    syncMenuAccessibility();
});

const updateHeaderState = () => {
    header?.classList.toggle('is-scrolled', window.scrollY > 12);
};

const updateScrollTop = () => {
    if (!scrollTopButton) {
        return;
    }

    const show = window.scrollY > scrollTopThreshold;
    scrollTopButton.classList.toggle('is-visible', show);
    scrollTopButton.hidden = !show;
};

const onScroll = () => {
    updateHeaderState();
    updateScrollTop();
};

scrollTopButton?.addEventListener('click', () => {
    window.scrollTo({
        top: 0,
        behavior: prefersReducedMotion ? 'auto' : 'smooth',
    });
});

onScroll();
window.addEventListener('scroll', onScroll, { passive: true });

if (!prefersReducedMotion) {
    const revealObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.12, rootMargin: '0px 0px -40px 0px' }
    );

    document.querySelectorAll('[data-reveal]').forEach((element) => revealObserver.observe(element));
} else {
    document.querySelectorAll('[data-reveal]').forEach((element) => element.classList.add('is-visible'));
}
