/**
 * PIN Input Component Manager
 */

import type { PinInputOptions } from '../types';

export class PinInputManager {
    private static instances = new WeakMap<HTMLElement, PinInputManager>();

    private wrapper: HTMLElement;
    private inputs: NodeListOf<HTMLInputElement>;
    private hiddenInput: HTMLInputElement | null;
    private length: number;

    constructor(wrapper: HTMLElement) {
        this.wrapper = wrapper;
        this.inputs = wrapper.querySelectorAll('.pin-input-digit');
        this.hiddenInput = wrapper.querySelector('.pin-input-value');
        this.length = parseInt(wrapper.dataset.length || '4', 10);

        this.init();
    }

    private init(): void {
        this.inputs.forEach((input, index) => {
            input.addEventListener('input', (e) => this.handleInput(e, index));
            input.addEventListener('keydown', (e) => this.handleKeydown(e, index));
            input.addEventListener('paste', (e) => this.handlePaste(e));
            input.addEventListener('focus', () => input.select());
        });
    }

    private handleInput(e: Event, index: number): void {
        const input = e.target as HTMLInputElement;
        const value = input.value;

        if (value.length === 1 && index < this.length - 1) {
            this.inputs[index + 1]?.focus();
        }

        this.updateValue();
    }

    private handleKeydown(e: KeyboardEvent, index: number): void {
        const input = e.target as HTMLInputElement;

        if (e.key === 'Backspace' && !input.value && index > 0) {
            this.inputs[index - 1]?.focus();
        }

        if (e.key === 'ArrowLeft' && index > 0) {
            e.preventDefault();
            this.inputs[index - 1]?.focus();
        }

        if (e.key === 'ArrowRight' && index < this.length - 1) {
            e.preventDefault();
            this.inputs[index + 1]?.focus();
        }
    }

    private handlePaste(e: ClipboardEvent): void {
        e.preventDefault();
        const pastedData = e.clipboardData?.getData('text').slice(0, this.length) || '';

        pastedData.split('').forEach((char, i) => {
            if (this.inputs[i]) {
                this.inputs[i].value = char;
            }
        });

        this.updateValue();

        const nextIndex = Math.min(pastedData.length, this.length - 1);
        this.inputs[nextIndex]?.focus();
    }

    private updateValue(): void {
        if (this.hiddenInput) {
            this.hiddenInput.value = Array.from(this.inputs)
                .map((i) => i.value)
                .join('');
            this.hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
        }
    }

    public getValue(): string {
        return this.hiddenInput?.value || '';
    }

    public setValue(value: string): void {
        const chars = value.slice(0, this.length).split('');
        this.inputs.forEach((input, i) => {
            input.value = chars[i] || '';
        });
        this.updateValue();
    }

    public clear(): void {
        this.inputs.forEach((input) => {
            input.value = '';
        });
        this.updateValue();
        this.inputs[0]?.focus();
    }

    public static initAll(): void {
        document.querySelectorAll<HTMLElement>('.pin-input-wrapper').forEach((wrapper) => {
            if (!wrapper.dataset.initialized) {
                wrapper.dataset.initialized = 'true';
                PinInputManager.instances.set(wrapper, new PinInputManager(wrapper));
            }
        });
    }

    public static getInstance(wrapper: HTMLElement): PinInputManager | undefined {
        return PinInputManager.instances.get(wrapper);
    }
}
