/**
 * Gestor de Cotización
 * Maneja la creación, edición y eliminación de segmentos e items de cotización
 */
class QuotationManager {
    constructor() {
        this.segmentIndex = document.querySelectorAll('.quotation-segment').length;
        this.init();
    }

    init() {
        this.setupEventListeners();
        // Inicializar totales de todos los items y segmentos existentes
        this.initializeTotals();
        this.updateGrandTotal();
        // Configurar validación dinámica según el tamaño de pantalla
        this.setupRequiredFields();
        window.addEventListener('resize', () => this.setupRequiredFields());
    }

    setupRequiredFields() {
        // Remover required de todos los campos primero
        document.querySelectorAll('[data-required="true"]').forEach(field => {
            field.removeAttribute('required');
        });

        // Agregar required solo a los campos visibles
        const isMobile = window.innerWidth < 768;
        
        if (isMobile) {
            // En mobile, solo los campos mobile tienen required
            document.querySelectorAll('.item-name-mobile, .item-quantity-mobile, .item-unit-value-mobile').forEach(field => {
                if (field.offsetParent !== null) { // Verificar que esté visible
                    field.setAttribute('required', 'required');
                }
            });
        } else {
            // En desktop, solo los campos desktop tienen required
            document.querySelectorAll('.item-name-desktop, .item-quantity-desktop, .item-unit-value-desktop').forEach(field => {
                if (field.offsetParent !== null) { // Verificar que esté visible
                    field.setAttribute('required', 'required');
                }
            });
        }
    }

    initializeTotals() {
        const segments = document.querySelectorAll('.quotation-segment');
        segments.forEach(segment => {
            const items = segment.querySelectorAll('.quotation-item');
            items.forEach(item => {
                // Sincronizar valores entre desktop y mobile
                this.syncDesktopMobileFields(item);
                this.updateItemTotal(item);
            });
            this.updateSegmentTotal(segment);
        });
    }

    syncDesktopMobileFields(itemElement) {
        // Sincronizar nombre
        const nameDesktop = itemElement.querySelector('.item-name-desktop');
        const nameMobile = itemElement.querySelector('.item-name-mobile');
        if (nameDesktop && nameMobile) {
            const value = nameDesktop.value || nameMobile.value;
            if (value) {
                nameDesktop.value = value;
                nameMobile.value = value;
            }
        }

        // Sincronizar cantidad
        const quantityDesktop = itemElement.querySelector('.item-quantity-desktop');
        const quantityMobile = itemElement.querySelector('.item-quantity-mobile');
        if (quantityDesktop && quantityMobile) {
            const value = quantityDesktop.value || quantityMobile.value;
            if (value) {
                quantityDesktop.value = value;
                quantityMobile.value = value;
            }
        }

        // Sincronizar precio unitario
        const unitValueDesktop = itemElement.querySelector('.item-unit-value-desktop');
        const unitValueMobile = itemElement.querySelector('.item-unit-value-mobile');
        if (unitValueDesktop && unitValueMobile) {
            const value = unitValueDesktop.value || unitValueMobile.value;
            if (value) {
                unitValueDesktop.value = value;
                unitValueMobile.value = value;
            }
        }

        // Sincronizar checkbox de autorización - usar el primero que esté marcado o el valor del primero
        const allAuthorizedCheckboxes = itemElement.querySelectorAll('.item-authorized');
        if (allAuthorizedCheckboxes.length > 0) {
            // Buscar el primer checkbox que esté marcado, o usar el primero
            let checkedState = false;
            for (let checkbox of allAuthorizedCheckboxes) {
                if (checkbox.checked) {
                    checkedState = true;
                    break;
                }
            }
            // Si no hay ninguno marcado, verificar el valor del atributo checked inicial
            if (!checkedState && allAuthorizedCheckboxes[0].hasAttribute('checked')) {
                checkedState = true;
            }
            // Sincronizar todos los checkboxes
            allAuthorizedCheckboxes.forEach(checkbox => {
                checkbox.checked = checkedState;
            });
        }
    }

    setupEventListeners() {
        // Botón para agregar segmento
        const btnAddSegment = document.getElementById('btn-add-segment');
        if (btnAddSegment) {
            btnAddSegment.addEventListener('click', () => this.addSegment());
        }

        // Delegación de eventos para botones dinámicos
        const container = document.getElementById('quotation-segments-container');
        if (container) {
            container.addEventListener('click', (e) => {
                if (e.target.classList.contains('btn-remove-segment')) {
                    this.removeSegment(e.target.closest('.quotation-segment'));
                } else if (e.target.classList.contains('btn-remove-item')) {
                    this.removeItem(e.target.closest('.quotation-item'));
                } else if (e.target.classList.contains('btn-add-item')) {
                    this.addItem(e.target.closest('.quotation-segment'));
                }
            });

            // Eventos para calcular totales y sincronizar campos
            container.addEventListener('input', (e) => {
                const itemElement = e.target.closest('.quotation-item');
                if (itemElement) {
                    // Sincronizar valores entre desktop y mobile
                    if (e.target.classList.contains('item-name-desktop') || 
                        e.target.classList.contains('item-name-mobile')) {
                        this.syncFieldValue(itemElement, 'item-name-desktop', 'item-name-mobile', e.target.value);
                    } else if (e.target.classList.contains('item-quantity-desktop') || 
                               e.target.classList.contains('item-quantity-mobile')) {
                        this.syncFieldValue(itemElement, 'item-quantity-desktop', 'item-quantity-mobile', e.target.value);
                        this.updateItemTotal(itemElement);
                    } else if (e.target.classList.contains('item-unit-value-desktop') ||
                               e.target.classList.contains('item-unit-value-mobile')) {
                        this.syncFieldValue(itemElement, 'item-unit-value-desktop', 'item-unit-value-mobile', e.target.value);
                        this.updateItemTotal(itemElement);
                    }
                }
            });

            // Eventos para cambios en el checkbox de autorización
            container.addEventListener('change', (e) => {
                if (e.target.classList.contains('item-authorized')) {
                    const itemElement = e.target.closest('.quotation-item');
                    // Sincronizar todos los checkboxes del mismo item
                    if (itemElement) {
                        const allCheckboxes = itemElement.querySelectorAll('.item-authorized');
                        allCheckboxes.forEach(checkbox => {
                            checkbox.checked = e.target.checked;
                        });
                    }
                    const segmentElement = e.target.closest('.quotation-segment');
                    this.updateSegmentTotal(segmentElement);
                    this.updateGrandTotal();
                }
            });
        }
    }

    addSegment() {
        const container = document.getElementById('quotation-segments-container');
        const segmentHtml = this.createSegmentHtml(this.segmentIndex);
        
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = segmentHtml;
        const segmentElement = tempDiv.firstElementChild;
        
        container.appendChild(segmentElement);
        this.segmentIndex++;
        this.setupRequiredFields(); // Configurar required después de agregar
        this.updateGrandTotal();
    }

    removeSegment(segmentElement) {
        if (confirm('¿Está seguro de eliminar este segmento y todos sus items?')) {
            segmentElement.remove();
            this.renumberSegments();
            this.updateGrandTotal();
        }
    }

    addItem(segmentElement) {
        const itemsContainer = segmentElement.querySelector('.segment-items');
        const segmentIndex = segmentElement.dataset.segmentIndex;
        const itemIndex = itemsContainer.querySelectorAll('.quotation-item').length;
        
        const itemHtml = this.createItemHtml(segmentIndex, itemIndex);
        
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = itemHtml;
        const itemElement = tempDiv.firstElementChild;
        
        itemsContainer.appendChild(itemElement);
        // Sincronizar campos desktop/mobile del nuevo item
        this.syncDesktopMobileFields(itemElement);
        this.setupRequiredFields(); // Configurar required después de agregar
        this.updateSegmentTotal(segmentElement);
        this.updateGrandTotal();
    }

    removeItem(itemElement) {
        const segmentElement = itemElement.closest('.quotation-segment');
        itemElement.remove();
        this.renumberItems(segmentElement);
        this.updateSegmentTotal(segmentElement);
        this.updateGrandTotal();
    }

    createSegmentHtml(segmentIndex) {
        return `
            <div class="quotation-segment mb-4 p-4 border border-[#ccc] rounded" data-segment-index="${segmentIndex}">
                <div class="segment-header flex items-center justify-between mb-3">
                    <input type="text" 
                        name="quotation[segments][${segmentIndex}][name]" 
                        placeholder="Nombre del segmento"
                        class="segment-name flex-1 border border-[#ccc] rounded px-3 py-2 text-base font-semibold mr-2"
                        required>
                    <button type="button" class="btn-remove-segment btn-danger px-3 py-1 rounded text-white text-sm">
                        Eliminar Segmento
                    </button>
                </div>
                
                <div class="segment-items">
                    ${this.createItemHtml(segmentIndex, 0)}
                </div>
                
                <button type="button" class="btn-add-item mt-2 btn-secondary px-3 py-1 rounded text-sm">
                    + Agregar Servicio/Producto
                </button>
                
                <div class="segment-total mt-3 pt-3 border-t border-[#ccc]">
                    <strong>Total del segmento (autorizados): <span class="segment-total-value text-[#372C97]">$0,00</span></strong>
                </div>
            </div>
        `;
    }

    createItemHtml(segmentIndex, itemIndex) {
        return `
            <div class="quotation-item mb-3 p-4 bg-gray-50 rounded-lg border border-gray-200" data-item-index="${itemIndex}">
                <!-- Desktop/Tablet Layout -->
                <div class="hidden md:grid md:grid-cols-12 md:gap-3 md:items-center quotation-item-grid">
                    <div class="md:col-span-4 quotation-item-field">
                        <label class="block text-xs text-gray-600 mb-1">Servicio/Producto</label>
                        <input type="text" 
                            name="quotation[segments][${segmentIndex}][items][${itemIndex}][name]" 
                            placeholder="Nombre del servicio/producto"
                            class="item-name-desktop w-full border border-[#ccc] rounded-lg px-3 py-2 text-base focus:ring-2 focus:ring-[#372C97] focus:border-[#372C97]"
                            data-required="true">
                    </div>
                    <div class="md:col-span-1 quotation-item-field">
                        <label class="block text-xs text-gray-600 mb-1">Cantidad</label>
                        <input type="number" 
                            name="quotation[segments][${segmentIndex}][items][${itemIndex}][quantity]" 
                            placeholder="Cant."
                            min="1"
                            value="1"
                            class="item-quantity-desktop w-full border border-[#ccc] rounded-lg px-3 py-2 text-base focus:ring-2 focus:ring-[#372C97] focus:border-[#372C97]"
                            data-required="true">
                    </div>
                    <div class="md:col-span-2 quotation-item-field">
                        <label class="block text-xs text-gray-600 mb-1">Precio Unitario</label>
                        <input type="number" 
                            name="quotation[segments][${segmentIndex}][items][${itemIndex}][unit_value]" 
                            placeholder="Precio"
                            min="0"
                            step="0.01"
                            value="0"
                            class="item-unit-value-desktop w-full border border-[#ccc] rounded-lg px-3 py-2 text-base focus:ring-2 focus:ring-[#372C97] focus:border-[#372C97]"
                            data-required="true">
                    </div>
                    <div class="md:col-span-2 quotation-item-field">
                        <label class="block text-xs text-gray-600 mb-1">Total</label>
                        <div class="item-total font-semibold text-[#372C97] text-lg py-2">$0,00</div>
                    </div>
                    <div class="md:col-span-2 quotation-item-field">
                        <label class="block text-xs text-gray-600 mb-2 text-center">Autorizar</label>
                        <div class="flex items-center justify-center">
                            <label class="custom-checkbox">
                                <input type="checkbox" 
                                    name="quotation[segments][${segmentIndex}][items][${itemIndex}][is_authorized]" 
                                    value="1"
                                    class="item-authorized">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                    <div class="md:col-span-1 quotation-item-field">
                        <label class="block text-xs text-gray-600 mb-1 text-center">Acción</label>
                        <button type="button" class="btn-remove-item btn-danger px-3 py-2 rounded-lg text-white text-sm w-full hover:bg-red-600 transition">
                            Eliminar
                        </button>
                    </div>
                </div>

                <!-- Mobile Layout -->
                <div class="md:hidden quotation-item-field">
                    <div class="mb-3">
                        <label class="block text-xs text-gray-600 mb-1 font-medium">Servicio/Producto</label>
                        <input type="text" 
                            name="quotation[segments][${segmentIndex}][items][${itemIndex}][name]" 
                            placeholder="Nombre del servicio/producto"
                            class="item-name-mobile w-full border border-[#ccc] rounded-lg px-3 py-2.5 text-base focus:ring-2 focus:ring-[#372C97] focus:border-[#372C97]"
                            data-required="true">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1 font-medium">Cantidad</label>
                            <input type="number" 
                                name="quotation[segments][${segmentIndex}][items][${itemIndex}][quantity]" 
                                placeholder="Cant."
                                min="1"
                                value="1"
                                class="item-quantity-mobile w-full border border-[#ccc] rounded-lg px-3 py-2.5 text-base focus:ring-2 focus:ring-[#372C97] focus:border-[#372C97]"
                                data-required="true">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1 font-medium">Precio Unitario</label>
                            <input type="number" 
                                name="quotation[segments][${segmentIndex}][items][${itemIndex}][unit_value]" 
                                placeholder="Precio"
                                min="0"
                                step="0.01"
                                value="0"
                                class="item-unit-value-mobile w-full border border-[#ccc] rounded-lg px-3 py-2.5 text-base focus:ring-2 focus:ring-[#372C97] focus:border-[#372C97]"
                                data-required="true">
                        </div>
                    </div>

                    <div class="flex items-center justify-between mb-3 p-3 bg-white rounded-lg border border-gray-200">
                        <div>
                            <label class="block text-xs text-gray-600 mb-2 font-medium">Total</label>
                            <span class="item-total font-bold text-lg text-[#372C97]">$0,00</span>
                        </div>
                        <div class="text-center">
                            <label class="block text-xs text-gray-600 mb-2 font-medium">Autorizar</label>
                            <label class="custom-checkbox">
                                <input type="checkbox" 
                                    name="quotation[segments][${segmentIndex}][items][${itemIndex}][is_authorized]" 
                                    value="1"
                                    class="item-authorized">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>

                    <div class="quotation-item-actions">
                        <button type="button" class="btn-remove-item btn-danger px-4 py-2.5 rounded-lg text-white text-sm font-medium w-full hover:bg-red-600 transition">
                            Eliminar Servicio
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    syncFieldValue(itemElement, desktopClass, mobileClass, value) {
        const desktopField = itemElement.querySelector(`.${desktopClass}`);
        const mobileField = itemElement.querySelector(`.${mobileClass}`);
        
        if (desktopField && mobileField) {
            desktopField.value = value;
            mobileField.value = value;
        }
    }

    updateItemTotal(itemElement) {
        // Buscar inputs tanto en desktop como mobile (preferir desktop si está visible)
        const quantityDesktop = itemElement.querySelector('.item-quantity-desktop');
        const quantityMobile = itemElement.querySelector('.item-quantity-mobile');
        const unitValueDesktop = itemElement.querySelector('.item-unit-value-desktop');
        const unitValueMobile = itemElement.querySelector('.item-unit-value-mobile');
        const totalSpans = itemElement.querySelectorAll('.item-total');

        // Usar el valor del campo visible o el que tenga valor
        const quantity = parseFloat(
            (quantityDesktop && quantityDesktop.offsetParent !== null ? quantityDesktop.value : null) ||
            (quantityMobile && quantityMobile.offsetParent !== null ? quantityMobile.value : null) ||
            quantityDesktop?.value ||
            quantityMobile?.value || 0
        );
        
        const unitValue = parseFloat(
            (unitValueDesktop && unitValueDesktop.offsetParent !== null ? unitValueDesktop.value : null) ||
            (unitValueMobile && unitValueMobile.offsetParent !== null ? unitValueMobile.value : null) ||
            unitValueDesktop?.value ||
            unitValueMobile?.value || 0
        );
        
        const total = quantity * unitValue;

        // Actualizar todos los elementos de total (desktop y mobile)
        totalSpans.forEach(span => {
            span.textContent = this.formatCurrency(total);
        });

        const segmentElement = itemElement.closest('.quotation-segment');
        this.updateSegmentTotal(segmentElement);
        this.updateGrandTotal();
    }

    updateSegmentTotal(segmentElement) {
        const items = segmentElement.querySelectorAll('.quotation-item');
        let segmentTotal = 0;

        items.forEach(item => {
            // Solo sumar si está autorizado
            const authorizedCheckbox = item.querySelector('.item-authorized');
            if (authorizedCheckbox && authorizedCheckbox.checked) {
                const quantityDesktop = item.querySelector('.item-quantity-desktop');
                const quantityMobile = item.querySelector('.item-quantity-mobile');
                const unitValueDesktop = item.querySelector('.item-unit-value-desktop');
                const unitValueMobile = item.querySelector('.item-unit-value-mobile');
                
                const quantity = parseFloat(
                    (quantityDesktop && quantityDesktop.offsetParent !== null ? quantityDesktop.value : null) ||
                    (quantityMobile && quantityMobile.offsetParent !== null ? quantityMobile.value : null) ||
                    quantityDesktop?.value ||
                    quantityMobile?.value || 0
                );
                
                const unitValue = parseFloat(
                    (unitValueDesktop && unitValueDesktop.offsetParent !== null ? unitValueDesktop.value : null) ||
                    (unitValueMobile && unitValueMobile.offsetParent !== null ? unitValueMobile.value : null) ||
                    unitValueDesktop?.value ||
                    unitValueMobile?.value || 0
                );
                
                segmentTotal += quantity * unitValue;
            }
        });

        const totalSpan = segmentElement.querySelector('.segment-total-value');
        if (totalSpan) {
            totalSpan.textContent = this.formatCurrency(segmentTotal);
        }
    }

    updateGrandTotal() {
        const segments = document.querySelectorAll('.quotation-segment');
        let grandTotal = 0;

        segments.forEach(segment => {
            const items = segment.querySelectorAll('.quotation-item');
            items.forEach(item => {
                // Solo sumar si está autorizado
                const authorizedCheckbox = item.querySelector('.item-authorized');
                if (authorizedCheckbox && authorizedCheckbox.checked) {
                    const quantityDesktop = item.querySelector('.item-quantity-desktop');
                    const quantityMobile = item.querySelector('.item-quantity-mobile');
                    const unitValueDesktop = item.querySelector('.item-unit-value-desktop');
                    const unitValueMobile = item.querySelector('.item-unit-value-mobile');
                    
                    const quantity = parseFloat(
                        (quantityDesktop && quantityDesktop.offsetParent !== null ? quantityDesktop.value : null) ||
                        (quantityMobile && quantityMobile.offsetParent !== null ? quantityMobile.value : null) ||
                        quantityDesktop?.value ||
                        quantityMobile?.value || 0
                    );
                    
                    const unitValue = parseFloat(
                        (unitValueDesktop && unitValueDesktop.offsetParent !== null ? unitValueDesktop.value : null) ||
                        (unitValueMobile && unitValueMobile.offsetParent !== null ? unitValueMobile.value : null) ||
                        unitValueDesktop?.value ||
                        unitValueMobile?.value || 0
                    );
                    
                    grandTotal += quantity * unitValue;
                }
            });
        });

        const grandTotalSpan = document.getElementById('quotation-grand-total-value');
        if (grandTotalSpan) {
            grandTotalSpan.textContent = this.formatCurrency(grandTotal);
        }
    }

    renumberSegments() {
        const segments = document.querySelectorAll('.quotation-segment');
        segments.forEach((segment, index) => {
            segment.dataset.segmentIndex = index;
            
            // Actualizar nombres de inputs
            const segmentNameInput = segment.querySelector('.segment-name');
            if (segmentNameInput) {
                segmentNameInput.name = `quotation[segments][${index}][name]`;
            }

            // Renumerar items
            this.renumberItems(segment, index);
        });
    }

    renumberItems(segmentElement, segmentIndex = null) {
        if (segmentIndex === null) {
            segmentIndex = segmentElement.dataset.segmentIndex;
        }

        const items = segmentElement.querySelectorAll('.quotation-item');
        items.forEach((item, index) => {
            item.dataset.itemIndex = index;

            // Actualizar nombres de inputs (tanto desktop como mobile)
            const nameInputs = item.querySelectorAll('.item-name-desktop, .item-name-mobile');
            const quantityInputs = item.querySelectorAll('.item-quantity-desktop, .item-quantity-mobile');
            const unitValueInputs = item.querySelectorAll('.item-unit-value-desktop, .item-unit-value-mobile');
            const authorizedCheckbox = item.querySelector('.item-authorized');

            nameInputs.forEach(input => {
                input.name = `quotation[segments][${segmentIndex}][items][${index}][name]`;
            });
            quantityInputs.forEach(input => {
                input.name = `quotation[segments][${segmentIndex}][items][${index}][quantity]`;
            });
            unitValueInputs.forEach(input => {
                input.name = `quotation[segments][${segmentIndex}][items][${index}][unit_value]`;
            });
            if (authorizedCheckbox) {
                authorizedCheckbox.name = `quotation[segments][${segmentIndex}][items][${index}][is_authorized]`;
            }
        });
    }

    formatCurrency(value) {
        // Formato: $1.234,56 (mismo formato que PHP number_format con separador de miles . y decimal ,)
        const num = parseFloat(value) || 0;
        const parts = num.toFixed(2).split('.');
        const integerPart = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        return `$${integerPart},${parts[1]}`;
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('quotation-section')) {
        new QuotationManager();
    }
});

