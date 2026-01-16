/**
 * Toggle Switch Component Manager
 */

export class ToggleManager {
    private static instances = new WeakMap<HTMLElement, ToggleManager>();

    private wrapper: HTMLElement;
    private button: HTMLButtonElement | null;
    private hiddenInput: HTMLInputElement | null;
    private knob: HTMLElement | null;
    private onValue: string;
    private offValue: string;
    private onColor: string;
    private offColor: string;
    private isEnabled: boolean;

    constructor(wrapper: HTMLElement) {
        this.wrapper = wrapper;
        this.button = wrapper.querySelector('button[role="switch"]');
        this.hiddenInput = wrapper.querySelector('.toggle-hidden-input');
        this.knob = wrapper.querySelector('.toggle-knob');

        this.onValue = wrapper.dataset.onValue || '1';
        this.offValue = wrapper.dataset.offValue || '0';
        this.onColor = wrapper.dataset.onColor || '#7c3aed'; // primary/violet-600
        this.offColor = wrapper.dataset.offColor || '#d1d5db'; // gray-300
        this.isEnabled = this.button?.getAttribute('aria-checked') === 'true';

        this.init();
    }

    private init(): void {
        this.button?.addEventListener('click', () => this.toggle());

        // Handle keyboard navigation
        this.button?.addEventListener('keydown', (e) => {
            if (e.key === ' ' || e.key === 'Enter') {
                e.preventDefault();
                this.toggle();
            }
        });
    }

    public toggle(): void {
        if (this.button?.disabled) return;

        this.isEnabled = !this.isEnabled;
        this.updateState();
    }

    public enable(): void {
        this.isEnabled = true;
        this.updateState();
    }

    public disable(): void {
        this.isEnabled = false;
        this.updateState();
    }

    private updateState(): void {
        if (!this.button) return;

        // Update aria state
        this.button.setAttribute('aria-checked', String(this.isEnabled));

        // Update hidden input value
        if (this.hiddenInput) {
            this.hiddenInput.value = this.isEnabled ? this.onValue : this.offValue;
        }

        // Update button background color using inline style
        this.button.style.backgroundColor = this.isEnabled ? this.onColor : this.offColor;

        // Update knob position
        if (this.knob) {
            if (this.isEnabled) {
                this.knob.classList.remove('translate-x-0');
                this.knob.classList.add('translate-x-5');
            } else {
                this.knob.classList.remove('translate-x-5');
                this.knob.classList.add('translate-x-0');
            }
        }

        // Update icon visibility
        const onIcon = this.knob?.querySelector('.toggle-on-icon');
        const offIcon = this.knob?.querySelector('.toggle-off-icon');

        if (onIcon) {
            onIcon.classList.toggle('opacity-100', this.isEnabled);
            onIcon.classList.toggle('opacity-0', !this.isEnabled);
        }
        if (offIcon) {
            offIcon.classList.toggle('opacity-100', !this.isEnabled);
            offIcon.classList.toggle('opacity-0', this.isEnabled);
        }

        // Dispatch change event
        if (this.hiddenInput) {
            this.hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
        }

        // Dispatch custom event
        this.wrapper.dispatchEvent(new CustomEvent('toggle:change', {
            bubbles: true,
            detail: { enabled: this.isEnabled, value: this.isEnabled ? this.onValue : this.offValue }
        }));
    }

    public getValue(): string {
        return this.isEnabled ? this.onValue : this.offValue;
    }

    public isToggled(): boolean {
        return this.isEnabled;
    }

    public static initAll(): void {
        document.querySelectorAll<HTMLElement>('.toggle-wrapper').forEach((wrapper) => {
            if (!wrapper.dataset.initialized) {
                wrapper.dataset.initialized = 'true';
                ToggleManager.instances.set(wrapper, new ToggleManager(wrapper));
            }
        });
    }

    public static getInstance(wrapper: HTMLElement): ToggleManager | undefined {
        return ToggleManager.instances.get(wrapper);
    }
}
