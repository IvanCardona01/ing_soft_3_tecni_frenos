class PreviousRecordModal {
    constructor(modalId, loadButtonId, createButtonId) {
        this.modal = document.getElementById(modalId);
        this.loadButton = document.getElementById(loadButtonId);
        this.createButton = document.getElementById(createButtonId);
        this.data = null;

        if (this.modal && this.loadButton && this.createButton) {
            this.init();
        }
    }

    show(data = null) {
        this.data = data;
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
            if (this.data && this.data.data) {
                this.mapDataToForm(this.data);
            }
            this.hide();
        });

        this.modal.addEventListener("click", (e) => {
            if (e.target === this.modal) {
                this.hide();
            }
        });
    }

    mapDataToForm(data) {
        const vehicle = data.data;
        const lastOrder = data.lastOrder;

        if (vehicle && vehicle.client) {
            const client = vehicle.client;
            this.setFieldValue("client_full_name", client.full_name);
            this.setSelectValue("client_document_type", client.document_type);
            this.setFieldValue(
                "client_document_number",
                client.document_number
            );
            this.setFieldValue("phone", client.phone);
            this.setFieldValue("address", client.address);
        }

        if (vehicle) {
            this.setFieldValue("brand", vehicle.brand);
            this.setFieldValue("year", vehicle.year);
            this.setFieldValue("plate", vehicle.plate);
            this.setFieldValue("cilindraje", vehicle.cilindraje);
            this.setFieldValue("model", vehicle.model);
            this.setFieldValue("vin", vehicle.vin);
            this.setFieldValue("engine", vehicle.motor);
            this.setFieldValue("Mileage", vehicle.kilometraje);
            this.setFieldValue(
                "vehicle_observaciones",
                vehicle.observaciones || ""
            );
        }

        if (lastOrder) {
            this.setFieldValue("name_driver", lastOrder.driver_name || "");
            this.setFieldValue("phone_driver", lastOrder.driver_phone || "");
            this.setFieldValue("email", lastOrder.driver_email || "");
        }

        const form = document.querySelector("form");
        if (form) {
            form.scrollIntoView({ behavior: "smooth", block: "start" });
        }
    }

    setFieldValue(fieldId, value) {
        const field = document.getElementById(fieldId);
        if (field && value !== null && value !== undefined) {
            field.value = value;
            field.dispatchEvent(new Event("input", { bubbles: true }));
            field.dispatchEvent(new Event("change", { bubbles: true }));
        }
    }

    setSelectValue(fieldId, value) {
        const field = document.getElementById(fieldId);
        if (field && value !== null && value !== undefined) {
            field.value = value;
            field.dispatchEvent(new Event("change", { bubbles: true }));
        }
    }
}
