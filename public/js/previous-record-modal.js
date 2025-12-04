class PreviousRecordModal {
    constructor(modalId, loadButtonId, createButtonId) {
        this.modal = document.getElementById(modalId);
        this.loadButton = document.getElementById(loadButtonId);
        this.createButton = document.getElementById(createButtonId);

        if (this.modal && this.loadButton && this.createButton) {
            this.init();
        }
    }

    show(data = null) {
        if (this.modal) {
            this.modal.classList.remove("hidden");
            this.modal.classList.add("flex");
        } else {
            console.error("Modal no encontrado");
        }
    }

    hide() {
        this.modal.classList.add("hidden");
        this.modal.classList.remove("flex");
    }

    init() {
        this.loadButton.addEventListener("click", () => {
            this.hide();
        });

        this.createButton.addEventListener("click", () => {
            this.hide();
        });

        this.modal.addEventListener("click", (e) => {
            if (e.target === this.modal) {
                this.hide();
            }
        });
    }
}
