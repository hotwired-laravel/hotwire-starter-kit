import { Controller } from "@hotwired/stimulus";

// Connects to data-controller="password-reveal"
export default class extends Controller {
    static targets = ["input"];

    static values = {
        revealed: { type: Boolean, default: false },
    };

    connect() {
        this.revealedValue = false;
    }

    toggle() {
        this.revealedValue = !this.revealedValue;
    }

    reset() {
        this.inputTarget.type = "password";
        this.revealedValue = false;
    }

    revealedValueChanged() {
        this.inputTarget.type = this.revealedValue ? "text" : "password";
    }
}
