(() => {
    class WitnessManager {
        constructor(options = {}) {
            this.basePath = (options.basePath || '/icons/witness').replace(/\/$/, '');
            this.defaultStates = ['natural', 'active-1'];
            this.customStates = options.customStates || {
                'witness-8': ['natural', 'active-1', 'active-2'],
                'witness-12': ['natural', 'active-1', 'active-2'],
            };
            this.cache = new Map();
        }

        getStates(id) {
            if (!this.cache.has(id)) {
                const states = this.customStates[id] || this.defaultStates;
                this.cache.set(id, states);
            }
            return this.cache.get(id);
        }

        buildSrc(id, state) {
            return `${this.basePath}/${state}/${id}.svg`;
        }
    }

    class WitnessIndicator {
        constructor(element, manager) {
            this.element = element;
            this.manager = manager;
            this.id = element.dataset.witnessId;
            this.img = element.querySelector('img');
            this.states = manager.getStates(this.id);
            this.index = 0;

            if (!this.id || !this.img) {
                return;
            }

            this.element.addEventListener('click', () => this.advance());
            this.render();
        }

        advance() {
            this.index = (this.index + 1) % this.states.length;
            this.render();
        }

        render() {
            const state = this.states[this.index];
            const src = this.manager.buildSrc(this.id, state);

            if (this.img.getAttribute('src') !== src) {
                this.img.setAttribute('src', src);
            }

            this.element.setAttribute('data-witness-state', state);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const container = document.querySelector('[data-witness-base]');
        if (!container) {
            return;
        }

        const basePath = container.dataset.witnessBase || '/icons/witness';
        const manager = new WitnessManager({ basePath });

        container.querySelectorAll('.witness-indicator').forEach((indicator) => {
            new WitnessIndicator(indicator, manager);
        });
    });
})();

