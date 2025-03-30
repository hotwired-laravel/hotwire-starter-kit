import { BridgeComponent } from "@hotwired/hotwire-native-bridge";
import { BridgeElement } from "@hotwired/hotwire-native-bridge";

export default class extends BridgeComponent {
    static component = "form";
    static targets = ["submit"];

    connect() {
        super.connect();

        if (this.enabled) {
            this.#addButton();
        }
    }

    disconnect() {
        super.disconnect();

        if (this.enabled) {
            this.#removeButton();
        }
    }

    submitStart(event) {
        this.submitTarget.disabled = true;
        this.send("disableSubmit");
    }

    submitEnd(event) {
        this.submitTarget.disabled = false;
        this.send("enableSubmit");
    }

    #addButton() {
        const submit = new BridgeElement(this.submitTarget);

        this.send(
            "connect",
            {
                title: submit.title,
                destructive: submit.bridgeAttribute("destructive"),
            },
            () => {
                this.submitTarget.click();
            },
        );
    }

    #removeButton() {
        this.send("disconnect");
    }
}
