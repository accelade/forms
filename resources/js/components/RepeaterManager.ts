/**
 * Repeater Component Manager
 *
 * Filament-compatible repeater with collapsible items, drag-and-drop reordering,
 * clone functionality, and proper index management.
 */

export class RepeaterManager {
    private static instances = new WeakMap<HTMLElement, RepeaterManager>();

    private _wrapper: HTMLElement;
    private itemsContainer: HTMLElement | null;
    private addButton: HTMLButtonElement | null;
    private expandAllButton: HTMLButtonElement | null;
    private collapseAllButton: HTMLButtonElement | null;
    private template: HTMLTemplateElement | null;
    private minItems: number;
    private maxItems: number;
    private _fieldName: string;
    private isSimple: boolean;
    private nextIndex: number = 0;

    constructor(wrapper: HTMLElement) {
        this._wrapper = wrapper;
        this.itemsContainer = wrapper.querySelector('.repeater-items');
        this.addButton = wrapper.querySelector('.repeater-add');
        this.expandAllButton = wrapper.querySelector('.repeater-expand-all');
        this.collapseAllButton = wrapper.querySelector('.repeater-collapse-all');

        const templateId = `${wrapper.id || 'repeater'}-template`;
        this.template = document.getElementById(templateId) as HTMLTemplateElement;

        this.minItems = parseInt(wrapper.dataset.minItems || '0', 10);
        this.maxItems = parseInt(wrapper.dataset.maxItems || '999', 10);
        this._fieldName = wrapper.dataset.name || 'items';
        this.isSimple = wrapper.dataset.simple === 'true';

        // Calculate next index from existing items
        this.calculateNextIndex();

        this.init();
    }

    private init(): void {
        this.addButton?.addEventListener('click', () => this.addItem());
        this.expandAllButton?.addEventListener('click', () => this.expandAll());
        this.collapseAllButton?.addEventListener('click', () => this.collapseAll());

        this.initExistingItems();
        this.initDragAndDrop();
        this.updateButtonState();
    }

    private calculateNextIndex(): void {
        const items = this.itemsContainer?.querySelectorAll('.repeater-item');
        if (items && items.length > 0) {
            const indices = Array.from(items).map((item) => {
                const index = parseInt((item as HTMLElement).dataset.index || '0', 10);
                return isNaN(index) ? 0 : index;
            });
            this.nextIndex = Math.max(...indices) + 1;
        }
    }

    private initExistingItems(): void {
        this.itemsContainer?.querySelectorAll('.repeater-item').forEach((item) => {
            this.bindItemEvents(item as HTMLElement);
        });
    }

    private bindItemEvents(item: HTMLElement): void {
        // Delete button
        item.querySelector('.repeater-delete')?.addEventListener('click', () => {
            if (this.getItemCount() > this.minItems) {
                item.classList.add('opacity-50', 'pointer-events-none');
                setTimeout(() => {
                    item.remove();
                    this.updateButtonState();
                    this.updateIndices();
                }, 150);
            }
        });

        // Clone button
        item.querySelector('.repeater-clone')?.addEventListener('click', () => {
            this.cloneItem(item);
        });

        // Collapse button
        item.querySelector('.repeater-collapse')?.addEventListener('click', () => {
            this.toggleCollapse(item);
        });
    }

    public addItem(): void {
        if (!this.template || !this.itemsContainer) return;
        if (this.getItemCount() >= this.maxItems) return;

        const clone = this.template.content.cloneNode(true) as DocumentFragment;
        const item = clone.querySelector('.repeater-item') as HTMLElement;

        if (item) {
            // Update index in the item
            item.dataset.index = String(this.nextIndex);

            // Update all input names and IDs with the correct index
            this.updateItemIndex(item, this.nextIndex);

            // Update item label
            const label = item.querySelector('.repeater-item-label');
            if (label) {
                label.textContent = `Item ${this.nextIndex + 1}`;
            }

            this.bindItemEvents(item);
            this.itemsContainer.appendChild(item);

            this.nextIndex++;
            this.updateButtonState();

            // Focus first input in the new item
            const firstInput = item.querySelector('input, textarea, select') as HTMLElement;
            firstInput?.focus();
        }
    }

    private cloneItem(item: HTMLElement): void {
        if (this.getItemCount() >= this.maxItems) return;

        const clone = item.cloneNode(true) as HTMLElement;

        // Update index
        clone.dataset.index = String(this.nextIndex);
        this.updateItemIndex(clone, this.nextIndex);

        // Update item label
        const label = clone.querySelector('.repeater-item-label');
        if (label) {
            label.textContent = `Item ${this.nextIndex + 1}`;
        }

        // Ensure it's expanded
        clone.classList.remove('is-collapsed');
        const content = clone.querySelector('.repeater-item-content') as HTMLElement;
        if (content) {
            content.hidden = false;
        }
        const icon = clone.querySelector('.collapse-icon');
        icon?.classList.remove('rotate-180');

        this.bindItemEvents(clone);
        item.parentNode?.insertBefore(clone, item.nextSibling);

        this.nextIndex++;
        this.updateButtonState();
    }

    private updateItemIndex(item: HTMLElement, newIndex: number): void {
        // Update all inputs with __INDEX__ placeholder or existing index
        item.querySelectorAll('input, textarea, select').forEach((input) => {
            const el = input as HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement;

            // Update name attribute
            if (el.name) {
                el.name = el.name.replace(/__INDEX__|(\[\d+\])/, `[${newIndex}]`);
            }

            // Update id attribute
            if (el.id) {
                el.id = el.id.replace(/__INDEX__|_\d+_/, `_${newIndex}_`);
            }
        });

        // Update labels
        item.querySelectorAll('label').forEach((label) => {
            if (label.htmlFor) {
                label.htmlFor = label.htmlFor.replace(/__INDEX__|_\d+_/, `_${newIndex}_`);
            }
        });
    }

    private updateIndices(): void {
        // Re-index all items after deletion/reorder for proper form submission
        this.itemsContainer?.querySelectorAll('.repeater-item').forEach((item, idx) => {
            const el = item as HTMLElement;
            el.dataset.index = String(idx);
            this.updateItemIndex(el, idx);

            // Update label
            const label = el.querySelector('.repeater-item-label');
            if (label && !this.isSimple) {
                label.textContent = `Item ${idx + 1}`;
            }
        });

        // Reset nextIndex
        this.nextIndex = this.getItemCount();
    }

    private toggleCollapse(item: HTMLElement): void {
        const content = item.querySelector('.repeater-item-content') as HTMLElement;
        const icon = item.querySelector('.collapse-icon');

        if (content) {
            const isCollapsed = content.hidden;
            content.hidden = !isCollapsed;
            item.classList.toggle('is-collapsed', !isCollapsed);
            icon?.classList.toggle('rotate-180', !isCollapsed);
        }
    }

    public expandAll(): void {
        this.itemsContainer?.querySelectorAll('.repeater-item').forEach((item) => {
            const content = item.querySelector('.repeater-item-content') as HTMLElement;
            const icon = item.querySelector('.collapse-icon');
            if (content) {
                content.hidden = false;
                item.classList.remove('is-collapsed');
                icon?.classList.remove('rotate-180');
            }
        });
    }

    public collapseAll(): void {
        this.itemsContainer?.querySelectorAll('.repeater-item').forEach((item) => {
            const content = item.querySelector('.repeater-item-content') as HTMLElement;
            const icon = item.querySelector('.collapse-icon');
            if (content) {
                content.hidden = true;
                item.classList.add('is-collapsed');
                icon?.classList.add('rotate-180');
            }
        });
    }

    private initDragAndDrop(): void {
        if (!this.itemsContainer) return;

        let draggedItem: HTMLElement | null = null;

        this.itemsContainer.addEventListener('dragstart', (e) => {
            const target = (e.target as HTMLElement).closest('.repeater-item') as HTMLElement;
            if (!target) return;

            draggedItem = target;
            draggedItem.classList.add('opacity-50', 'border-dashed');
            if (e.dataTransfer) {
                e.dataTransfer.effectAllowed = 'move';
            }
        });

        this.itemsContainer.addEventListener('dragend', () => {
            if (draggedItem) {
                draggedItem.classList.remove('opacity-50', 'border-dashed');
                draggedItem = null;
            }
            // Update indices after reorder
            this.updateIndices();
        });

        this.itemsContainer.addEventListener('dragover', (e) => {
            e.preventDefault();
            e.dataTransfer!.dropEffect = 'move';

            const target = (e.target as HTMLElement).closest('.repeater-item') as HTMLElement;
            if (!target || target === draggedItem) return;

            const rect = target.getBoundingClientRect();
            const midpoint = rect.top + rect.height / 2;

            if (e.clientY < midpoint) {
                target.parentNode?.insertBefore(draggedItem!, target);
            } else {
                target.parentNode?.insertBefore(draggedItem!, target.nextSibling);
            }
        });
    }

    private getItemCount(): number {
        return this.itemsContainer?.querySelectorAll('.repeater-item').length || 0;
    }

    private updateButtonState(): void {
        if (this.addButton) {
            this.addButton.disabled = this.getItemCount() >= this.maxItems;
        }
    }

    public getItems(): HTMLElement[] {
        return Array.from(this.itemsContainer?.querySelectorAll('.repeater-item') || []) as HTMLElement[];
    }

    public static initAll(): void {
        document.querySelectorAll<HTMLElement>('.repeater-field').forEach((wrapper) => {
            if (!wrapper.dataset.initialized) {
                wrapper.dataset.initialized = 'true';
                RepeaterManager.instances.set(wrapper, new RepeaterManager(wrapper));
            }
        });
    }

    public static getInstance(wrapper: HTMLElement): RepeaterManager | undefined {
        return RepeaterManager.instances.get(wrapper);
    }
}
