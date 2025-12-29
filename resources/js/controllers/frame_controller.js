import { Controller } from "@hotwired/stimulus"

// Connects to data-controller="frame"
export default class extends Controller {
    breakoutWhenMissing(event) {
        event.preventDefault()
        event.detail.visit(event.detail.response)
    }
}
