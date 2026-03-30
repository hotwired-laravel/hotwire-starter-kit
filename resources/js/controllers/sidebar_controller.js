import { Controller } from "@hotwired/stimulus";

// Connects to data-controller="sidebar"
export default class extends Controller {
    static targets = ["checkbox", "collapse"];

    connect() {
        if (this.hasCollapseTarget) {
            this.collapseTarget.checked = localStorage.getItem("sidebar-collapsed") === "true";
            this.collapseTarget.addEventListener("change", this.#persistCollapse);
        }
    }

    disconnect() {
        if (this.hasCollapseTarget) {
            this.collapseTarget.removeEventListener("change", this.#persistCollapse);
        }
    }

    close() {
        if (this.hasCheckboxTarget) {
            this.checkboxTarget.checked = false;
        }
    }

    #persistCollapse = (event) => {
        localStorage.setItem("sidebar-collapsed", event.target.checked);
    };
}
