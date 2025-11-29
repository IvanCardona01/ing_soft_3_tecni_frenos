(() => {
    class DamageSectionManager {
        constructor(sectionElement) {
            this.sectionElement = sectionElement;
            this.sectionKey = sectionElement.dataset.damageSection;
            this.input = sectionElement.querySelector(`input[name="damage_points_${this.sectionKey}"]`);
            this.canvas = sectionElement.querySelector('.damage-canvas');
            this.pinLayer = sectionElement.querySelector('.damage-pins');
            this.points = [];

            if (!this.canvas || !this.pinLayer || !this.input) {
                return;
            }

            this.loadInitialPoints();
            this.canvas.addEventListener('click', (event) => this.handleCanvasClick(event));
        }

        handleCanvasClick(event) {
            if (event.target.closest('.damage-pin')) {
                return;
            }

            const rect = this.canvas.getBoundingClientRect();
            const x = ((event.clientX - rect.left) / rect.width) * 100;
            const y = ((event.clientY - rect.top) / rect.height) * 100;

            const note = prompt('Describe el daño identificado en esta zona:');
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
            pin.setAttribute('aria-label', point.note);
            pin.dataset.pointId = point.id;

            pin.addEventListener('click', (event) => {
                event.stopPropagation();
                if (confirm('¿Eliminar este punto de daño?')) {
                    this.removePoint(point.id);
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

