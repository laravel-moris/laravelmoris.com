import "./bootstrap";

import Alpine from "alpinejs";
import InstantPage from "./instant.js";

const FOCUSABLE_SELECTOR = [
    "a[href]",
    "button:not([disabled])",
    "input:not([disabled])",
    "select:not([disabled])",
    "textarea:not([disabled])",
    '[tabindex]:not([tabindex="-1"])',
].join(",");

function getFocusableElements(container) {
    return Array.from(container.querySelectorAll(FOCUSABLE_SELECTOR)).filter(
        (element) => !element.hidden && element.offsetParent !== null,
    );
}

function containFocus(container, event) {
    const focusableElements = getFocusableElements(container);

    if (!focusableElements.length) {
        event.preventDefault();
        container.focus();
        return;
    }

    const first = focusableElements[0];
    const last = focusableElements.at(-1);

    if (event.shiftKey && document.activeElement === first) {
        event.preventDefault();
        last.focus();
    }

    if (!event.shiftKey && document.activeElement === last) {
        event.preventDefault();
        first.focus();
    }
}

function makeBackgroundInert(dialog) {
    const inertedElements = [];
    let current = dialog;

    while (current.parentElement) {
        for (const sibling of current.parentElement.children) {
            if (sibling === current || sibling.inert) {
                continue;
            }

            sibling.inert = true;
            inertedElements.push(sibling);
        }

        current = current.parentElement;
    }

    return inertedElements;
}

function restoreBackground(inertedElements) {
    for (const element of inertedElements) {
        element.inert = false;
    }
}

Alpine.data("accessibleDialog", () => ({
    inertedElements: [],
    previouslyFocused: null,

    init() {
        this.$watch("showModal", (isOpen) => {
            this.$nextTick(() => this.syncDialog(isOpen));
        });
    },

    syncDialog(isOpen) {
        if (!isOpen) {
            restoreBackground(this.inertedElements);
            this.inertedElements = [];
            this.previouslyFocused?.focus();
            this.previouslyFocused = null;
            return;
        }

        this.previouslyFocused = document.activeElement;
        this.inertedElements = makeBackgroundInert(this.$el);
        getFocusableElements(this.$el)[0]?.focus();
    },

    trapFocus(event) {
        containFocus(this.$el, event);
    },

    closeDialog() {
        this.showModal = false;
    },

    destroy() {
        restoreBackground(this.inertedElements);
    },
}));

// Register Alpine data for avatar upload
Alpine.data("avatarUpload", (config = {}) => ({
    fileName: null,
    originalSrc: null,
    maxFileSize: config.maxFileSize || "2MB",

    init() {
        this.originalSrc = this.$refs.preview?.src;
    },

    handleFileSelect(event) {
        const file = event.target.files[0];

        if (!file) {
            return;
        }

        // Validate file type
        if (!file.type.startsWith("image/")) {
            alert("Please select an image file.");
            this.clearFile();
            return;
        }

        // Validate file size
        const maxBytes = this.parseFileSize(this.maxFileSize);
        if (file.size > maxBytes) {
            alert(`File size must be less than ${this.maxFileSize}.`);
            this.clearFile();
            return;
        }

        this.fileName = file.name;

        // Show preview
        const reader = new FileReader();
        reader.onload = (e) => {
            if (this.$refs.preview) {
                this.$refs.preview.src = e.target.result;
            }
        };
        reader.readAsDataURL(file);
    },

    clearFile() {
        this.fileName = null;
        if (this.$refs.input) {
            this.$refs.input.value = "";
        }
        if (this.$refs.preview && this.originalSrc) {
            this.$refs.preview.src = this.originalSrc;
        }
    },

    parseFileSize(sizeStr) {
        const units = {
            B: 1,
            KB: 1024,
            MB: 1024 * 1024,
            GB: 1024 * 1024 * 1024,
        };
        const match = sizeStr.match(/^(\d+(?:\.\d+)?)\s*(B|KB|MB|GB)$/i);
        if (!match) return 2 * 1024 * 1024; // Default 2MB
        return parseFloat(match[1]) * (units[match[2].toUpperCase()] || 1);
    },
}));

window.Alpine = Alpine;
Alpine.start();
InstantPage();

import.meta.glob(["../images/**"]);

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
    let inertedElements = [];
    let previouslyFocused = null;

    function setOpen(isOpen) {
        if (closeTimer) {
            window.clearTimeout(closeTimer);
            closeTimer = null;
        }

        toggle.setAttribute("aria-expanded", String(isOpen));

        if (isOpen) {
            menu.classList.remove("hidden");
            document.body.classList.add("overflow-hidden");
            if (!inertedElements.length) {
                previouslyFocused = document.activeElement;
                inertedElements = makeBackgroundInert(menu);
            }

            if (prefersReducedMotion) {
                menu.dataset.open = "true";
                getFocusableElements(panel)[0]?.focus();
                return;
            }

            // Allow initial styles to apply before transitioning.
            menu.dataset.open = "false";
            window.requestAnimationFrame(() => {
                menu.dataset.open = "true";
                getFocusableElements(panel)[0]?.focus();
            });

            return;
        }

        menu.dataset.open = "false";
        document.body.classList.remove("overflow-hidden");

        const finishClosing = () => {
            menu.classList.add("hidden");
            restoreBackground(inertedElements);
            inertedElements = [];
            previouslyFocused?.focus();
            previouslyFocused = null;
        };

        if (prefersReducedMotion) {
            finishClosing();
            return;
        }

        closeTimer = window.setTimeout(finishClosing, 320);
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
        if (menu.classList.contains("hidden")) {
            return;
        }

        if (event.key === "Tab") {
            containFocus(panel, event);
            return;
        }

        if (event.key === "Escape") {
            event.preventDefault();
            setOpen(false);
        }
    });
}

initThemeToggle();
initRevealOnScroll();
initMobileMenu();
