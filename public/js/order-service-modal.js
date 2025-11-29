class OrderServiceModal {
    constructor(modalId, closeButtonId, isEdit = false) {
        this.modal = document.getElementById(modalId);
        this.closeButton = document.getElementById(closeButtonId);
        this.storageKey = "orderService-modal-shown";
        this.isEdit = isEdit;

        if (this.modal && this.closeButton) {
            this.init();
        }
    }

    shouldShow() {
        const navigationType =
            performance.getEntriesByType("navigation")[0]?.type;
        const isRefresh = navigationType === "reload";
        const modalAlreadyShown = sessionStorage.getItem(this.storageKey);

        return !isRefresh && !this.isEdit && !modalAlreadyShown;
    }

    show() {
        this.modal.classList.remove("hidden");
        this.modal.classList.add("flex");
    }

    hide() {
        this.modal.classList.add("hidden");
        this.modal.classList.remove("flex");
        sessionStorage.setItem(this.storageKey, "true");

        const event = new CustomEvent("modalClosed", {
            detail: { modalId: this.modal.id },
        });
        window.dispatchEvent(event);
    }

    init() {
        if (this.shouldShow()) {
            this.show();
        } else {
            this.hide();
        }

        this.closeButton.addEventListener("click", () => {
            this.hide();
        });

        this.modal.addEventListener("click", (e) => {
            if (e.target === this.modal) {
                this.hide();
            }
        });

        window.addEventListener("beforeunload", () => {
            sessionStorage.removeItem(this.storageKey);
        });
    }
}
