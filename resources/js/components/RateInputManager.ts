/**
 * Rate Input Component Manager
 */

export class RateInputManager {
    private static instances = new WeakMap<HTMLElement, RateInputManager>();

    private wrapper: HTMLElement;
    private items: NodeListOf<HTMLElement>;
    private hiddenInput: HTMLInputElement | null;
    private valueDisplay: HTMLElement | null;
    private max: number;
    private allowHalf: boolean;
    private clearable: boolean;
    private currentValue: number = 0;

    constructor(wrapper: HTMLElement) {
        this.wrapper = wrapper;
        this.items = wrapper.querySelectorAll('.rate-item');
        this.hiddenInput = wrapper.querySelector('.rate-input-value');
        this.valueDisplay = wrapper.querySelector('.rate-value');
        this.max = parseInt(wrapper.dataset.max || '5', 10);
        this.allowHalf = wrapper.dataset.allowHalf === 'true';
        this.clearable = wrapper.dataset.clearable === 'true';

        this.init();
    }

    private init(): void {
        this.items.forEach((item, index) => {
            item.addEventListener('click', (e) => this.handleClick(e, index));
            item.addEventListener('mouseenter', () => this.handleHover(index));
        });

        this.wrapper.addEventListener('mouseleave', () => this.updateDisplay());
    }

    private handleClick(e: MouseEvent, index: number): void {
        let value = index + 1;

        if (this.allowHalf) {
            const rect = (e.target as HTMLElement).getBoundingClientRect();
            const isHalf = e.clientX < rect.left + rect.width / 2;
            value = isHalf ? index + 0.5 : index + 1;
        }

        if (this.clearable && this.currentValue === value) {
            this.currentValue = 0;
        } else {
            this.currentValue = value;
        }

        this.updateValue();
    }

    private handleHover(index: number): void {
        this.items.forEach((item, i) => {
            item.classList.toggle('is-hover', i <= index);
        });
    }

    private updateDisplay(): void {
        this.items.forEach((item, i) => {
            item.classList.remove('is-hover');
            item.classList.toggle('is-active', i < this.currentValue);
        });
    }

    private updateValue(): void {
        this.updateDisplay();

        if (this.hiddenInput) {
            this.hiddenInput.value = String(this.currentValue);
            this.hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
        }

        if (this.valueDisplay) {
            this.valueDisplay.textContent = String(this.currentValue);
        }
    }

    public getValue(): number {
        return this.currentValue;
    }

    public setValue(value: number): void {
        this.currentValue = Math.min(this.max, Math.max(0, value));
        this.updateValue();
    }

    public clear(): void {
        this.currentValue = 0;
        this.updateValue();
    }

    public static initAll(): void {
        document.querySelectorAll<HTMLElement>('.rate-input-wrapper').forEach((wrapper) => {
            if (!wrapper.dataset.initialized) {
                wrapper.dataset.initialized = 'true';
                RateInputManager.instances.set(wrapper, new RateInputManager(wrapper));
            }
        });
    }

    public static getInstance(wrapper: HTMLElement): RateInputManager | undefined {
        return RateInputManager.instances.get(wrapper);
    }
}
