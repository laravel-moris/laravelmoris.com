import "./bootstrap";

const THEME_STORAGE_KEY = "theme";

function getPreferredTheme() {
    const stored = localStorage.getItem(THEME_STORAGE_KEY);

    if (stored === "light" || stored === "dark") {
        return stored;
    }

    return window.matchMedia("(prefers-color-scheme: light)").matches
        ? "light"
        : "dark";
}

function applyTheme(theme) {
    document.documentElement.dataset.theme = theme;

    const isDark = theme === "dark";

    const toggles = Array.from(
        document.querySelectorAll("[data-theme-toggle]"),
    );

    for (const toggle of toggles) {
        toggle.setAttribute("aria-pressed", String(isDark));

        const sun = toggle.querySelector('[data-theme-icon="sun"]');
        const moon = toggle.querySelector('[data-theme-icon="moon"]');

        if (!sun || !moon) {
            continue;
        }

        sun.classList.toggle("hidden", isDark);
        moon.classList.toggle("hidden", !isDark);
    }
}

function initThemeToggle() {
    applyTheme(getPreferredTheme());

    const toggles = Array.from(
        document.querySelectorAll("[data-theme-toggle]"),
    );

    for (const toggle of toggles) {
        toggle.addEventListener("click", () => {
            const current =
                document.documentElement.dataset.theme === "light"
                    ? "light"
                    : "dark";
            const next = current === "light" ? "dark" : "light";

            localStorage.setItem(THEME_STORAGE_KEY, next);
            applyTheme(next);
        });
    }

    window
        .matchMedia("(prefers-color-scheme: light)")
        .addEventListener("change", () => {
            if (localStorage.getItem(THEME_STORAGE_KEY)) {
                return;
            }

            applyTheme(getPreferredTheme());
        });
}

function initRevealOnScroll() {
    const elements = Array.from(document.querySelectorAll("[data-reveal]"));

    if (!elements.length) {
        return;
    }

    const prefersReducedMotion = window.matchMedia(
        "(prefers-reduced-motion: reduce)",
    ).matches;

    if (prefersReducedMotion) {
        for (const el of elements) {
            el.dataset.revealed = "true";
        }

        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            for (const entry of entries) {
                if (!entry.isIntersecting) {
                    continue;
                }

                // Add stagger delay based on index
                const index = Array.from(
                    entry.target.parentElement.children,
                ).indexOf(entry.target);
                setTimeout(() => {
                    entry.target.dataset.revealed = "true";
                }, index * 100);

                observer.unobserve(entry.target);
            }
        },
        {
            threshold: 0.1,
            rootMargin: "0px 0px -50px 0px",
        },
    );

    for (const el of elements) {
        observer.observe(el);
    }
}

function initMobileMenu() {
    const toggle = document.querySelector("[data-mobile-menu-toggle]");
    const menu = document.querySelector("[data-mobile-menu]");
    const panel = document.querySelector("[data-mobile-menu-panel]");

    if (!toggle || !menu || !panel) {
        return;
    }

    const closeButtons = Array.from(
        menu.querySelectorAll("[data-mobile-menu-close]"),
    );
    const prefersReducedMotion = window.matchMedia(
        "(prefers-reduced-motion: reduce)",
    ).matches;
    let closeTimer = null;

    function setOpen(isOpen) {
        if (closeTimer) {
            window.clearTimeout(closeTimer);
            closeTimer = null;
        }

        toggle.setAttribute("aria-expanded", String(isOpen));

        if (isOpen) {
            menu.classList.remove("hidden");
            document.body.classList.add("overflow-hidden");

            if (prefersReducedMotion) {
                menu.dataset.open = "true";
                closeButtons[0]?.focus();
                return;
            }

            // Allow initial styles to apply before transitioning.
            menu.dataset.open = "false";
            window.requestAnimationFrame(() => {
                menu.dataset.open = "true";
                closeButtons[0]?.focus();
            });

            return;
        }

        menu.dataset.open = "false";
        document.body.classList.remove("overflow-hidden");

        if (prefersReducedMotion) {
            menu.classList.add("hidden");
            toggle.focus();
            return;
        }

        closeTimer = window.setTimeout(() => {
            menu.classList.add("hidden");
            toggle.focus();
        }, 320);
    }

    toggle.addEventListener("click", () => {
        const isOpen = !menu.classList.contains("hidden");
        setOpen(!isOpen);
    });

    for (const btn of closeButtons) {
        btn.addEventListener("click", () => {
            setOpen(false);
        });
    }

    document.addEventListener("keydown", (event) => {
        if (event.key !== "Escape") {
            return;
        }

        if (menu.classList.contains("hidden")) {
            return;
        }

        setOpen(false);
    });
}

initThemeToggle();
initRevealOnScroll();
initMobileMenu();
