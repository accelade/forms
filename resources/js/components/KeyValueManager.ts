/**
 * Key Value Component Manager
 */

export class KeyValueManager {
    private static instances = new WeakMap<HTMLElement, KeyValueManager>();

    private wrapper: HTMLElement;
    private rowsContainer: HTMLElement | null;
    private addButton: HTMLButtonElement | null;
    private template: HTMLTemplateElement | null;
    private hiddenInput: HTMLInputElement | null;

    constructor(wrapper: HTMLElement) {
        this.wrapper = wrapper;
        this.rowsContainer = wrapper.querySelector('.key-value-rows');
        this.addButton = wrapper.querySelector('.key-value-add');
        this.hiddenInput = wrapper.querySelector('.key-value-data');

        // Get template ID from wrapper (not rows container)
        const templateId = wrapper.dataset.templateId;
        this.template = templateId ? document.getElementById(templateId) as HTMLTemplateElement : null;

        this.init();
    }

    private init(): void {
        this.addButton?.addEventListener('click', () => this.addRow());
        this.initExistingRows();
    }

    private initExistingRows(): void {
        this.rowsContainer?.querySelectorAll('.key-value-row').forEach((row) => {
            this.bindRowEvents(row as HTMLElement);
        });
    }

    private bindRowEvents(row: HTMLElement): void {
        row.querySelector('.key-value-delete')?.addEventListener('click', () => {
            row.remove();
            this.updateValue();
        });

        row.querySelectorAll<HTMLInputElement>('input').forEach((input) => {
            input.addEventListener('input', () => this.updateValue());
        });
    }

    public addRow(): void {
        if (!this.template || !this.rowsContainer) return;

        const clone = this.template.content.cloneNode(true) as DocumentFragment;
        const row = clone.querySelector('.key-value-row') as HTMLElement;

        if (row) {
            this.bindRowEvents(row);
            this.rowsContainer.appendChild(clone);
            this.updateValue();

            // Focus the first input
            row.querySelector<HTMLInputElement>('.key-value-key')?.focus();
        }
    }

    private updateValue(): void {
        if (!this.hiddenInput || !this.rowsContainer) return;

        const data: Record<string, string> = {};

        this.rowsContainer.querySelectorAll('.key-value-row').forEach((row) => {
            const key = (row.querySelector('.key-value-key') as HTMLInputElement)?.value;
            const value = (row.querySelector('.key-value-value') as HTMLInputElement)?.value;

            if (key) {
                data[key] = value || '';
            }
        });

        this.hiddenInput.value = JSON.stringify(data);
        this.hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
    }

    public getValue(): Record<string, string> {
        try {
            return JSON.parse(this.hiddenInput?.value || '{}');
        } catch {
            return {};
        }
    }

    public setValue(data: Record<string, string>): void {
        if (!this.rowsContainer) return;

        // Clear existing rows
        this.rowsContainer.innerHTML = '';

        // Add rows for each entry
        Object.entries(data).forEach(([key, value]) => {
            if (this.template) {
                const clone = this.template.content.cloneNode(true) as DocumentFragment;
                const row = clone.querySelector('.key-value-row') as HTMLElement;

                if (row) {
                    const keyInput = row.querySelector('.key-value-key') as HTMLInputElement;
                    const valueInput = row.querySelector('.key-value-value') as HTMLInputElement;

                    if (keyInput) keyInput.value = key;
                    if (valueInput) valueInput.value = value;

                    this.bindRowEvents(row);
                    this.rowsContainer.appendChild(clone);
                }
            }
        });

        this.updateValue();
    }

    public static initAll(): void {
        document.querySelectorAll<HTMLElement>('.key-value-wrapper').forEach((wrapper) => {
            if (!wrapper.dataset.initialized) {
                wrapper.dataset.initialized = 'true';
                KeyValueManager.instances.set(wrapper, new KeyValueManager(wrapper));
            }
        });
    }

    public static getInstance(wrapper: HTMLElement): KeyValueManager | undefined {
        return KeyValueManager.instances.get(wrapper);
    }
}
