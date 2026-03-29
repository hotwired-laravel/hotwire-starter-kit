import { Controller } from "@hotwired/stimulus";

const LOCAL_STORAGE_KEY = "theme";

const THEMES = [
    "default",
    "abyss",
    "acid",
    "aqua",
    "autumn",
    "black",
    "bumblebee",
    "business",
    "caramellatte",
    "cmyk",
    "coffee",
    "corporate",
    "cupcake",
    "cyberpunk",
    "dark",
    "dim",
    "dracula",
    "emerald",
    "fantasy",
    "forest",
    "garden",
    "halloween",
    "lemonade",
    "light",
    "lofi",
    "luxury",
    "night",
    "nord",
    "pastel",
    "retro",
    "silk",
    "sunset",
    "synthwave",
    "valentine",
    "winter",
    "wireframe",
];

// Connects to data-controller="theme"
export default class extends Controller {
    static targets = ["button", "switcher", "template"];

    static values = {
        theme: { type: String },
    };

    static classes = ["active"];

    initialize() {
        if (window.localStorage.getItem(LOCAL_STORAGE_KEY)) {
            this.themeValue = window.localStorage.getItem(LOCAL_STORAGE_KEY);
        }
    }

    connect() {
        this.#updateActiveThemeButtons();
    }

    switcherTargetConnected(ul) {
        const template = this.templateTargets.find((t) =>
            ul.contains(t),
        );

        if (!template) return;

        THEMES.forEach((theme) => {
            const clone = template.content.cloneNode(true);

            clone.querySelectorAll("[data-theme-placeholder]").forEach((el) => {
                el.removeAttribute("data-theme-placeholder");
                el.setAttribute("data-theme", theme);
            });

            const button = clone.querySelector("button");
            button.value = theme;

            const label = clone.querySelector("[data-label]");
            label.textContent = theme;
            label.removeAttribute("data-label");

            template.before(clone);
        });

        this.#updateActiveThemeButtons();
    }

    update(event) {
        this.themeValue = event.currentTarget.value;
    }

    themeValueChanged() {
        if (!this.themeValue || this.themeValue === "system") {
            this.#removeTheme();
        } else {
            this.#applyTheme(this.themeValue);
        }

        this.#updateActiveThemeButtons();
    }

    #updateActiveThemeButtons() {
        this.buttonTargets.forEach((btn) =>
            btn.value === this.themeValue
                ? btn.classList.add(...this.activeClasses)
                : btn.classList.remove(...this.activeClasses),
        );
    }

    #removeTheme() {
        window.localStorage.removeItem(LOCAL_STORAGE_KEY);
        document.documentElement.removeAttribute("data-theme");
    }

    #applyTheme(theme) {
        window.localStorage.setItem(LOCAL_STORAGE_KEY, theme);
        document.documentElement.setAttribute("data-theme", theme);
    }
}
