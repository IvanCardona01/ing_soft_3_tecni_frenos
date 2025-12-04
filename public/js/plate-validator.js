class PlateValidator {
    constructor(inputId, feedbackId, buttonId, onValidPlateCallback = null) {
        this.input = document.getElementById(inputId);
        this.feedback = document.getElementById(feedbackId);
        this.button = document.getElementById(buttonId);
        this.hasStartedTyping = false;
        this.onValidPlateCallback = onValidPlateCallback;

        if (this.input && this.feedback && this.button) {
            this.init();
        }
    }

    validate(value) {
        const trimmedValue = value.trim();
        let message = "";
        let isValid = true;

        if (this.hasStartedTyping && trimmedValue === "") {
            message = "La placa no puede estar vacía";
            isValid = false;
        } else if (trimmedValue.length > 0 && trimmedValue.length < 3) {
            message = "La placa debe tener mínimo 3 caracteres";
            isValid = false;
        } else if (trimmedValue.length > 6) {
            message = "La placa debe tener máximo 6 caracteres";
            isValid = false;
        } else if (
            trimmedValue.length > 0 &&
            /[*@.\-+_=!?#$%&(){}[\]|\\/<>~`]/.test(trimmedValue)
        ) {
            message =
                "La placa no puede contener caracteres especiales como asteriscos, arrobas, puntos ni signos";
            isValid = false;
        } else if (trimmedValue.length >= 3 && trimmedValue.length <= 6) {
            message = "Placa válida";
            isValid = true;
        }

        return { isValid, message };
    }

    updateValidation(value) {
        const validation = this.validate(value);

        if (!this.hasStartedTyping && value === "") {
            this.feedback.style.display = "none";
            this.input.classList.remove("border-red-500", "border-green-500");
            this.input.classList.add("border-[#372C97]");
            this.disableButton();
        } else {
            this.feedback.style.display = "block";
            this.feedback.textContent = validation.message;

            if (validation.isValid) {
                this.setValidState();
            } else {
                this.setInvalidState();
            }
        }
    }

    setValidState() {
        this.input.classList.remove("border-[#372C97]", "border-red-500");
        this.input.classList.add("border-green-500");
        this.feedback.classList.remove("text-red-500", "text-red-600");
        this.feedback.classList.add("text-green-500");
        this.feedback.style.color = "#22c55e";
        this.enableButton();
    }

    setInvalidState() {
        this.input.classList.remove("border-[#372C97]", "border-green-500");
        this.input.classList.add("border-red-500");
        this.feedback.classList.remove("text-green-500", "text-green-600");
        this.feedback.classList.add("text-red-500");
        this.feedback.style.color = "#ef4444";
        this.disableButton();
    }

    enableButton() {
        this.button.disabled = false;
        this.button.style.opacity = "1";
        this.button.style.cursor = "pointer";
    }

    disableButton() {
        this.button.disabled = true;
        this.button.style.opacity = "0.6";
        this.button.style.cursor = "not-allowed";
    }

    reset() {
        this.input.value = "";
        this.hasStartedTyping = false;
        this.feedback.style.display = "none";
        this.input.classList.remove("border-red-500", "border-green-500");
        this.input.classList.add("border-[#372C97]");
        this.disableButton();
    }

    getPlateValue() {
        return this.input ? this.input.value.trim().toUpperCase() : "";
    }

    init() {
        this.input.addEventListener("input", (e) => {
            const value = e.target.value;
            if (!this.hasStartedTyping && value.length > 0) {
                this.hasStartedTyping = true;
            }
            this.updateValidation(value);
        });

        this.input.addEventListener("blur", () => {
            if (this.hasStartedTyping) {
                this.updateValidation(this.input.value);
            }
        });

        this.button.addEventListener("click", (e) => {
            e.preventDefault();
            e.stopPropagation();

            if (!this.button.disabled && this.onValidPlateCallback) {
                const plate = this.getPlateValue();
                if (plate.length >= 3 && plate.length <= 6) {
                    this.onValidPlateCallback(plate);
                }
            }
        });

        this.disableButton();
    }
}
