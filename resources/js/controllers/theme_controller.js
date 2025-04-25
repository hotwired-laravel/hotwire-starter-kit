import { Controller } from "@hotwired/stimulus";

const LOCAL_STORAGE_KEY = "theme";

// Connects to data-controller="theme"
export default class extends Controller {
    static targets = ["button"];

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

    updateFromSubmit(event) {
        window.Turbo.cache.clear();

        this.themeValue = event.submitter.value;
    }

    clear() {
        window.Turbo.cache.clear();
        this.themeValue = null;
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
