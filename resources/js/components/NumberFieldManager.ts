/**
 * Number Field Component Manager
 */

export class NumberFieldManager {
    private static instances = new WeakMap<HTMLElement, NumberFieldManager>();

    private _wrapper: HTMLElement;
    private input: HTMLInputElement | null;
    private decrementBtn: HTMLButtonElement | null;
    private incrementBtn: HTMLButtonElement | null;
    private step: number;
    private min: number;
    private max: number;

    constructor(wrapper: HTMLElement) {
        this._wrapper = wrapper;
        this.input = wrapper.querySelector('.number-input');
        this.decrementBtn = wrapper.querySelector('.number-decrement');
        this.incrementBtn = wrapper.querySelector('.number-increment');

        this.step = parseFloat(this.input?.step || '1');
        this.min = this.input?.min !== '' ? parseFloat(this.input?.min || '') : -Infinity;
        this.max = this.input?.max !== '' ? parseFloat(this.input?.max || '') : Infinity;

        this.init();
    }

    private init(): void {
        this.decrementBtn?.addEventListener('click', () => this.decrement());
        this.incrementBtn?.addEventListener('click', () => this.increment());

        // Handle keyboard shortcuts
        this.input?.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowUp') {
                e.preventDefault();
                this.increment();
            }
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                this.decrement();
            }
        });
    }

    public increment(): void {
        if (!this.input) return;

        const current = parseFloat(this.input.value) || 0;
        const newValue = Math.min(this.max, current + this.step);
        this.input.value = String(newValue);
        this.input.dispatchEvent(new Event('input', { bubbles: true }));
    }

    public decrement(): void {
        if (!this.input) return;

        const current = parseFloat(this.input.value) || 0;
        const newValue = Math.max(this.min, current - this.step);
        this.input.value = String(newValue);
        this.input.dispatchEvent(new Event('input', { bubbles: true }));
    }

    public getValue(): number {
        return parseFloat(this.input?.value || '0');
    }

    public setValue(value: number): void {
        if (this.input) {
            const clampedValue = Math.min(this.max, Math.max(this.min, value));
            this.input.value = String(clampedValue);
            this.input.dispatchEvent(new Event('input', { bubbles: true }));
        }
    }

    public static initAll(): void {
        document.querySelectorAll<HTMLElement>('.number-input-wrapper').forEach((wrapper) => {
            if (!wrapper.dataset.initialized) {
                wrapper.dataset.initialized = 'true';
                NumberFieldManager.instances.set(wrapper, new NumberFieldManager(wrapper));
            }
        });
    }

    public static getInstance(wrapper: HTMLElement): NumberFieldManager | undefined {
        return NumberFieldManager.instances.get(wrapper);
    }
}
