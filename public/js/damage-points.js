(() => {
    /**
     * Modal para registrar un nuevo daño
     */
    class DamageModal {
        constructor() {
            this.modal = null;
            this.resolve = null;
            this.createModal();
        }

        createModal() {
            const modalHTML = `
                <div id="damage-register-modal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-[10000]">
                    <div class="bg-white rounded-[20px] p-6 w-[90%] md:max-w-[500px]">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-semibold text-[#372C97]">Registrar Daño</h3>
                            <button type="button" class="damage-modal-close text-2xl text-gray-500 hover:text-gray-700" aria-label="Cerrar">
                                &times;
                            </button>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Descripción del daño*</label>
                                <textarea id="damage-note-input" 
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#372C97] focus:border-[#372C97]"
                                    rows="4" 
                                    placeholder="Describe el daño identificado en esta zona..."></textarea>
                            </div>
                            <div class="flex gap-3 justify-end">
                                <button type="button" class="damage-modal-cancel btn btn-secondary px-4 py-2 rounded-lg">
                                    Cancelar
                                </button>
                                <button type="button" class="damage-modal-save btn btn-primary px-4 py-2 rounded-lg">
                                    Guardar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', modalHTML);
            this.modal = document.getElementById('damage-register-modal');
            this.setupEvents();
        }

        setupEvents() {
            const closeBtn = this.modal.querySelector('.damage-modal-close');
            const cancelBtn = this.modal.querySelector('.damage-modal-cancel');
            const saveBtn = this.modal.querySelector('.damage-modal-save');
            const input = this.modal.querySelector('#damage-note-input');

            const close = () => {
                this.hide();
                if (this.resolve) {
                    this.resolve(null);
                }
            };

            closeBtn?.addEventListener('click', close);
            cancelBtn?.addEventListener('click', close);

            saveBtn?.addEventListener('click', () => {
                const note = input.value.trim();
                if (!note) {
                    input.focus();
                    return;
                }
                this.hide();
                if (this.resolve) {
                    this.resolve(note);
                }
            });

            this.modal.addEventListener('click', (e) => {
                if (e.target === this.modal) {
                    close();
                }
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !this.modal.classList.contains('hidden')) {
                    close();
                }
            });

            // Enter para guardar
            input?.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' && e.ctrlKey) {
                    saveBtn.click();
                }
            });
        }

        show() {
            this.modal.classList.remove('hidden');
            this.modal.classList.add('flex');
            const input = this.modal.querySelector('#damage-note-input');
            if (input) {
                input.value = '';
                setTimeout(() => input.focus(), 100);
            }
        }

        hide() {
            this.modal.classList.add('hidden');
            this.modal.classList.remove('flex');
        }

        async getNote() {
            return new Promise((resolve) => {
                this.resolve = resolve;
                this.show();
            });
        }
    }

    /**
     * Modal para ver/editar/eliminar un daño existente
     */
    class DamageDetailModal {
        constructor() {
            this.modal = null;
            this.currentPoint = null;
            this.resolve = null;
            this.createModal();
        }

        createModal() {
            const modalHTML = `
                <div id="damage-detail-modal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-[10000]">
                    <div class="bg-white rounded-[20px] p-6 w-[90%] md:max-w-[500px]">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-semibold text-[#372C97]">Detalle del Daño</h3>
                            <button type="button" class="damage-detail-close text-2xl text-gray-500 hover:text-gray-700" aria-label="Cerrar">
                                &times;
                            </button>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Descripción del daño*</label>
                                <textarea id="damage-edit-input" 
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#372C97] focus:border-[#372C97]"
                                    rows="4"
                                    placeholder="Describe el daño..."></textarea>
                            </div>
                            <div class="flex gap-3 justify-end">
                                <button type="button" class="damage-detail-delete btn btn-danger px-4 py-2 rounded-lg">
                                    Eliminar
                                </button>
                                <button type="button" class="damage-detail-cancel btn btn-secondary px-4 py-2 rounded-lg">
                                    Cancelar
                                </button>
                                <button type="button" class="damage-detail-save btn btn-primary px-4 py-2 rounded-lg">
                                    Guardar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', modalHTML);
            this.modal = document.getElementById('damage-detail-modal');
            this.setupEvents();
        }

        setupEvents() {
            const closeBtn = this.modal.querySelector('.damage-detail-close');
            const saveBtn = this.modal.querySelector('.damage-detail-save');
            const cancelBtn = this.modal.querySelector('.damage-detail-cancel');
            const deleteBtn = this.modal.querySelector('.damage-detail-delete');
            const editInput = this.modal.querySelector('#damage-edit-input');

            const close = () => {
                this.hide();
                if (this.resolve) {
                    this.resolve({ action: 'close' });
                }
            };

            closeBtn?.addEventListener('click', close);

            cancelBtn?.addEventListener('click', () => {
                close();
            });

            saveBtn?.addEventListener('click', () => {
                const newNote = editInput?.value.trim();
                if (!newNote) {
                    editInput?.focus();
                    return;
                }
                this.hide();
                if (this.resolve) {
                    this.resolve({ action: 'update', note: newNote });
                }
            });

            deleteBtn?.addEventListener('click', () => {
                this.hide();
                if (this.resolve) {
                    this.resolve({ action: 'delete' });
                }
            });

            this.modal.addEventListener('click', (e) => {
                if (e.target === this.modal) {
                    close();
                }
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !this.modal.classList.contains('hidden')) {
                    close();
                }
            });

            // Guardar con Ctrl+Enter
            editInput?.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' && e.ctrlKey) {
                    saveBtn.click();
                }
            });
        }

        show(point) {
            this.currentPoint = point;
            const editInput = this.modal.querySelector('#damage-edit-input');

            if (editInput) {
                editInput.value = point.note || '';
                // Enfocar el textarea al abrir el modal
                setTimeout(() => {
                    editInput.focus();
                    editInput.setSelectionRange(editInput.value.length, editInput.value.length);
                }, 100);
            }

            this.modal.classList.remove('hidden');
            this.modal.classList.add('flex');
        }

        hide() {
            this.modal.classList.add('hidden');
            this.modal.classList.remove('flex');
        }

        async getAction(point) {
            return new Promise((resolve) => {
                this.resolve = resolve;
                this.show(point);
            });
        }
    }

    class DamageSectionManager {
        constructor(sectionElement) {
            this.sectionElement = sectionElement;
            this.sectionKey = sectionElement.dataset.damageSection;
            this.input = sectionElement.querySelector(`input[name="damage_points_${this.sectionKey}"]`);
            this.canvas = sectionElement.querySelector('.damage-canvas');
            this.pinLayer = sectionElement.querySelector('.damage-pins');
            this.points = [];
            this.damageModal = new DamageModal();
            this.detailModal = new DamageDetailModal();

            if (!this.canvas || !this.pinLayer || !this.input) {
                return;
            }

            this.loadInitialPoints();
            this.canvas.addEventListener('click', (event) => this.handleCanvasClick(event));
        }

        async handleCanvasClick(event) {
            if (event.target.closest('.damage-pin')) {
                return;
            }

            const rect = this.canvas.getBoundingClientRect();
            const x = ((event.clientX - rect.left) / rect.width) * 100;
            const y = ((event.clientY - rect.top) / rect.height) * 100;

            // Usar modal en lugar de prompt
            const note = await this.damageModal.getNote();
            if (!note || !note.trim()) {
                return;
            }

            const point = {
                id: this.generateId(),
                x: Number(x.toFixed(2)),
                y: Number(y.toFixed(2)),
                note: note.trim(),
            };

            this.points.push(point);
            this.renderPoint(point);
            this.persist();
        }

        renderPoint(point) {
            const pin = document.createElement('button');
            pin.type = 'button';
            pin.className = 'damage-pin';
            pin.style.left = `${point.x}%`;
            pin.style.top = `${point.y}%`;
            pin.title = point.note;
            pin.setAttribute('aria-label', `Daño: ${point.note}`);
            pin.dataset.pointId = point.id;

            pin.addEventListener('click', async (event) => {
                event.stopPropagation();
                const action = await this.detailModal.getAction(point);
                
                if (action.action === 'delete') {
                    this.removePoint(point.id);
                } else if (action.action === 'update') {
                    point.note = action.note;
                    pin.title = point.note;
                    pin.setAttribute('aria-label', `Daño: ${point.note}`);
                    this.persist();
                }
            });

            this.pinLayer.appendChild(pin);
        }

        removePoint(id) {
            this.points = this.points.filter((point) => point.id !== id);
            const pin = this.pinLayer.querySelector(`[data-point-id="${id}"]`);
            if (pin) {
                pin.remove();
            }
            this.persist();
        }

        loadInitialPoints() {
            let initial = [];
            try {
                initial = JSON.parse(this.input.value || '[]');
                if (!Array.isArray(initial)) {
                    initial = [];
                }
            } catch (error) {
                initial = [];
            }

            this.points = initial;
            this.points.forEach((point) => this.renderPoint(point));
        }

        persist() {
            this.input.value = JSON.stringify(this.points);
        }

        generateId() {
            return `damage-${Math.random().toString(36).slice(2, 10)}-${Date.now()}`;
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.damage-section').forEach((section) => {
            new DamageSectionManager(section);
        });
    });
})();
