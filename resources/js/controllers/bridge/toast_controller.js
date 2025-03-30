import { BridgeComponent } from "@hotwired/hotwire-native-bridge";

// Connects to data-controller="bridge--toast"
export default class extends BridgeComponent {
    static component = "toast";

    connect() {
        super.connect();

        this.#showToast();

        this.bridgeElement.delete();
    }

    #showToast() {
        const message = this.bridgeElement.bridgeAttribute("message");

        this.send("show", { message }, () => {});
    }
}
