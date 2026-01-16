/**
 * Color Picker Component Manager
 */

export class ColorPickerManager {
    private static instances = new WeakMap<HTMLElement, ColorPickerManager>();

    private wrapper: HTMLElement;
    private input: HTMLInputElement | null;
    private swatches: NodeListOf<HTMLElement>;

    constructor(wrapper: HTMLElement) {
        this.wrapper = wrapper;
        this.input = wrapper.querySelector('.color-input');
        this.swatches = wrapper.querySelectorAll('.color-swatch');

        this.init();
    }

    private init(): void {
        this.swatches.forEach((swatch) => {
            swatch.addEventListener('click', () => {
                const color = swatch.dataset.color;
                if (color) this.setColor(color);
            });
        });
    }

    private setColor(color: string): void {
        if (this.input) {
            this.input.value = color;
            this.input.dispatchEvent(new Event('input', { bubbles: true }));
        }
    }

    public getValue(): string {
        return this.input?.value || '';
    }

    public setValue(color: string): void {
        this.setColor(color);
    }

    public static initAll(): void {
        document.querySelectorAll<HTMLElement>('.color-picker-wrapper').forEach((wrapper) => {
            if (!wrapper.dataset.initialized) {
                wrapper.dataset.initialized = 'true';
                ColorPickerManager.instances.set(wrapper, new ColorPickerManager(wrapper));
            }
        });
    }

    public static getInstance(wrapper: HTMLElement): ColorPickerManager | undefined {
        return ColorPickerManager.instances.get(wrapper);
    }
}
