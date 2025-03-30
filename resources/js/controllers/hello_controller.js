import { Controller } from "@hotwired/stimulus";

// Example usage: data-controller="hello"
export default class extends Controller {
    connect() {
        this.element.textContent = "Hello World!";
    }
}
