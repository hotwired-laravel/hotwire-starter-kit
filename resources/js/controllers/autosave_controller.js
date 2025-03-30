import { Controller } from "@hotwired/stimulus";
import { delay } from "helpers";

const SAVE_DELAY = 300;

// Connects to data-controller="autosave"
export default class extends Controller {
    async saveAutomatically() {
        if (this.saveScheduled) return;

        this.saveScheduled = true;
        await delay(SAVE_DELAY);
        this.element.requestSubmit();
        this.saveScheduled = false;
    }
}
