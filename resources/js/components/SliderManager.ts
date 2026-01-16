/**
 * Slider Component Manager
 */

export class SliderManager {
    private static instances = new WeakMap<HTMLElement, SliderManager>();

    private wrapper: HTMLElement;
    private slider: HTMLInputElement | null;
    private valueDisplay: HTMLElement | null;

    constructor(wrapper: HTMLElement) {
        this.wrapper = wrapper;
        this.slider = wrapper.querySelector('.slider-input');
        this.valueDisplay = wrapper.querySelector('.slider-value-current');

        this.init();
    }

    private init(): void {
        if (this.slider) {
            this.slider.addEventListener('input', () => this.updateDisplay());
            this.updateDisplay();
        }
    }

    private updateDisplay(): void {
        if (this.valueDisplay && this.slider) {
            this.valueDisplay.textContent = this.slider.value;
        }
    }

    public getValue(): number {
        return parseFloat(this.slider?.value || '0');
    }

    public setValue(value: number): void {
        if (this.slider) {
            this.slider.value = String(value);
            this.updateDisplay();
            this.slider.dispatchEvent(new Event('input', { bubbles: true }));
        }
    }

    public static initAll(): void {
        document.querySelectorAll<HTMLElement>('.slider-wrapper').forEach((wrapper) => {
            if (!wrapper.dataset.initialized) {
                wrapper.dataset.initialized = 'true';
                SliderManager.instances.set(wrapper, new SliderManager(wrapper));
            }
        });
    }

    public static getInstance(wrapper: HTMLElement): SliderManager | undefined {
        return SliderManager.instances.get(wrapper);
    }
}
