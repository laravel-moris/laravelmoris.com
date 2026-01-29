import './bootstrap';

const THEME_STORAGE_KEY = 'theme';

function getPreferredTheme() {
    const stored = localStorage.getItem(THEME_STORAGE_KEY);

    if (stored === 'light' || stored === 'dark') {
        return stored;
    }

    return window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark';
}

function applyTheme(theme) {
    document.documentElement.dataset.theme = theme;

    const isDark = theme === 'dark';
    const toggle = document.querySelector('[data-theme-toggle]');
    const sun = document.querySelector('[data-theme-icon="sun"]');
    const moon = document.querySelector('[data-theme-icon="moon"]');

    if (toggle) {
        toggle.setAttribute('aria-pressed', String(isDark));
    }

    if (sun && moon) {
        sun.classList.toggle('hidden', isDark);
        moon.classList.toggle('hidden', !isDark);
    }
}

function initThemeToggle() {
    applyTheme(getPreferredTheme());

    const toggle = document.querySelector('[data-theme-toggle]');

    if (!toggle) {
        return;
    }

    toggle.addEventListener('click', () => {
        const current = document.documentElement.dataset.theme === 'light' ? 'light' : 'dark';
        const next = current === 'light' ? 'dark' : 'light';

        localStorage.setItem(THEME_STORAGE_KEY, next);
        applyTheme(next);
    });

    window.matchMedia('(prefers-color-scheme: light)').addEventListener('change', () => {
        if (localStorage.getItem(THEME_STORAGE_KEY)) {
            return;
        }

        applyTheme(getPreferredTheme());
    });
}

function initRevealOnScroll() {
    const elements = Array.from(document.querySelectorAll('[data-reveal]'));

    if (!elements.length) {
        return;
    }

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (prefersReducedMotion) {
        for (const el of elements) {
            el.dataset.revealed = 'true';
        }

        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            for (const entry of entries) {
                if (!entry.isIntersecting) {
                    continue;
                }

                entry.target.dataset.revealed = 'true';
                observer.unobserve(entry.target);
            }
        },
        {
            threshold: 0.1,
        },
    );

    for (const el of elements) {
        observer.observe(el);
    }
}

initThemeToggle();
initRevealOnScroll();
