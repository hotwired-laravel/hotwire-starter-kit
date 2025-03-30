import { Controller } from "@hotwired/stimulus";

// Connects to data-controller="sidebar"
export default class extends Controller {
    static targets = ["checkbox"];

    close() {
        this.checkboxTarget.checked = false;
    }
}
