import { Controller } from "@hotwired/stimulus";

// Connects to data-controller="clipboard"
export default class extends Controller {
    static values = { copied: { type: Boolean, default: false } };

    static targets = ["source"];

    #timeout = null;

    copy() {
        navigator.clipboard.writeText(this.sourceTarget.value)

        this.copiedValue = true;

        if (this.#timeout) clearTimeout(this.#timeout);

        this.#timeout = setTimeout(() => {
            this.copiedValue = false;
            this.#timeout = null;
        }, 2000);
    }
}
