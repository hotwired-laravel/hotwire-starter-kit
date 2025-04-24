import { Controller } from "@hotwired/stimulus";

// Example usage: data-controller="flash"
export default class extends Controller {
    remove() {
        this.element.remove();
    }
}
