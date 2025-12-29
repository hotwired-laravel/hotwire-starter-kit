import { Controller } from "@hotwired/stimulus"

// Connects to data-controller="otp"
export default class extends Controller {
    static values = {
        digits: { type: Number },
    };

    static targets = ["input", "code"];

    connect() {
        this.focusFirstInputField();
        this.#updateHiddenInputValue();
    }

    handleFocus({ target }) {
        target.select();
    }

    focusFirstInputField() {
        this.inputTargets[0]?.focus();
    }

    handleKeydown(event) {
        if (this.#isValidDigitKey(event.key)) {
            this.#processDigitInput(event.target, event);
        } else if (event.key === 'Backspace') {
            this.#processBackspaceInput(event.target, event);
        }
    }

    handlePaste(pasteEvent) {
        pasteEvent.preventDefault();

        const clipboardData = (pasteEvent.clipboardData || window.clipboardData).getData('text');
        const extractedDigits = clipboardData.split('').slice(0, this.inputTargets.length);

        extractedDigits.forEach((digitValue, digitIndex) => {
            const inputElement = this.inputTargets[digitIndex];
            if (inputElement && this.#isValidDigitKey(digitValue)) {
                inputElement.value = digitValue;
            }
        });

        const nextFocusIndex = Math.min(extractedDigits.length, this.inputTargets.length - 1);
        this.inputTargets[nextFocusIndex]?.focus();

        this.#updateHiddenInputValue();
    }

    sanitizeInput({ target }) {
        target.value = target.value.replace(/[^0-9]/g, '').slice(0, 1);
    }

    clearAllInputs() {
        this.inputTargets.forEach(input => input.value = '');
        this.#updateHiddenInputValue();
        this.focusFirstInputField();
    }

    #isValidDigitKey(keyValue) {
        const parsedNumber = parseInt(keyValue);
        return !isNaN(parsedNumber) && parsedNumber >= 0 && parsedNumber <= 9;
    }

    #processDigitInput(inputElement, keyboardEvent) {
        keyboardEvent.preventDefault();
        keyboardEvent.stopPropagation();

        const nextInputIndex = this.inputTargets.indexOf(inputElement) + 1;
        const nextInputElement = this.inputTargets[nextInputIndex] ?? null;

        inputElement.value = keyboardEvent.key;
        nextInputElement?.focus();

        this.#scheduleInputUpdate();
    }

    #processBackspaceInput(inputElement, keyboardEvent) {
        keyboardEvent.preventDefault();
        keyboardEvent.stopPropagation();

        const previousInputElement = this.inputTargets[this.inputTargets.indexOf(inputElement) - 1] ?? null;

        if (inputElement.value !== '') {
            inputElement.value = '';
        } else if (previousInputElement) {
            previousInputElement.value = '';
            previousInputElement.focus();
        }

        this.#scheduleInputUpdate();
    }

    #scheduleInputUpdate() {
        setTimeout(() => {
            this.#updateHiddenInputValue();
        }, 100);
    }

    #generateCompleteCode() {
        return this.inputTargets.reduce((code, input) => code + (input.value || ''), '');
    }

    #updateHiddenInputValue() {
        const completeCode = this.#generateCompleteCode();
        const hiddenInputElement = this.codeTarget;

        if (hiddenInputElement) {
            hiddenInputElement.value = completeCode;
            hiddenInputElement.dispatchEvent(new Event('input', { bubbles: true }));
        }
    }
}
