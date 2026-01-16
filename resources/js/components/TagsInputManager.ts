/**
 * Tags Input Component Manager
 *
 * Filament-style tags input with color support, prefix/suffix, and reorderable functionality.
 */

export class TagsInputManager {
    private static instances = new WeakMap<HTMLElement, TagsInputManager>();

    private wrapper: HTMLElement;
    private input: HTMLInputElement | null;
    private container: HTMLElement | null;
    private hiddenInput: HTMLInputElement | null;
    private separator: string;
    private maxTags: number;
    private tagPrefix: string;
    private tagSuffix: string;
    private isReorderable: boolean;
    private tags: string[] = [];

    constructor(wrapper: HTMLElement) {
        this.wrapper = wrapper;
        this.input = wrapper.querySelector('.tags-text-input');
        this.container = wrapper.querySelector('.tags-container');
        this.hiddenInput = wrapper.querySelector('.tags-value');
        this.separator = wrapper.dataset.separator || ',';
        this.maxTags = parseInt(wrapper.dataset.maxTags || '0', 10) || Infinity;
        this.tagPrefix = wrapper.dataset.tagPrefix || '';
        this.tagSuffix = wrapper.dataset.tagSuffix || '';
        this.isReorderable = wrapper.dataset.reorderable === 'true';

        this.init();
    }

    private init(): void {
        // Load existing tags from hidden input
        this.loadExistingTags();

        this.input?.addEventListener('keydown', (e) => this.handleKeydown(e));
        this.input?.addEventListener('blur', () => {
            const value = this.input?.value.trim();
            if (value) {
                this.addTag(value);
                if (this.input) this.input.value = '';
            }
        });

        // Click on wrapper focuses input
        this.wrapper.addEventListener('click', (e) => {
            if (e.target === this.wrapper || e.target === this.container) {
                this.input?.focus();
            }
        });
    }

    private loadExistingTags(): void {
        if (this.hiddenInput?.value) {
            try {
                const existingTags = JSON.parse(this.hiddenInput.value);
                if (Array.isArray(existingTags)) {
                    this.tags = existingTags;
                    this.render();
                }
            } catch {
                // If not JSON, try comma-separated
                const value = this.hiddenInput.value;
                if (value) {
                    this.tags = value.split(this.separator).map((t) => t.trim()).filter(Boolean);
                    this.render();
                }
            }
        }
    }

    private handleKeydown(e: KeyboardEvent): void {
        const input = e.target as HTMLInputElement;

        if (e.key === 'Enter' || e.key === this.separator) {
            e.preventDefault();
            const value = input.value.trim();
            if (value) {
                this.addTag(value);
                input.value = '';
            }
        }

        if (e.key === 'Backspace' && !input.value && this.tags.length > 0) {
            this.removeTag(this.tags.length - 1);
        }

        // Tab to add tag (common behavior)
        if (e.key === 'Tab' && input.value.trim()) {
            e.preventDefault();
            this.addTag(input.value.trim());
            input.value = '';
        }
    }

    public addTag(value: string): void {
        if (!value || this.tags.includes(value) || this.tags.length >= this.maxTags) {
            return;
        }

        this.tags.push(value);
        this.render();
        this.updateValue();
    }

    public removeTag(index: number): void {
        this.tags.splice(index, 1);
        this.render();
        this.updateValue();
    }

    private render(): void {
        if (!this.container) return;

        this.container.innerHTML = this.tags
            .map((tag, i) => {
                const displayText = this.getDisplayText(tag);
                return `
                    <span class="tag" data-index="${i}">
                        <span class="tag-text">${displayText}</span>
                        <button type="button" class="tag-remove" data-index="${i}" aria-label="Remove tag">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </span>
                `;
            })
            .join('');

        this.container.querySelectorAll<HTMLButtonElement>('.tag-remove').forEach((btn) => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const index = parseInt(btn.dataset.index || '0', 10);
                this.removeTag(index);
            });
        });

        // Setup drag and drop if reorderable
        if (this.isReorderable) {
            this.setupDragAndDrop();
        }
    }

    private getDisplayText(tag: string): string {
        const escapedTag = this.escapeHtml(tag);
        let display = '';

        if (this.tagPrefix) {
            display += `<span class="tag-prefix">${this.escapeHtml(this.tagPrefix)}</span>`;
        }

        display += escapedTag;

        if (this.tagSuffix) {
            display += `<span class="tag-suffix">${this.escapeHtml(this.tagSuffix)}</span>`;
        }

        return display;
    }

    private setupDragAndDrop(): void {
        const tagElements = this.container?.querySelectorAll<HTMLElement>('.tag');
        if (!tagElements) return;

        tagElements.forEach((tag) => {
            tag.draggable = true;

            tag.addEventListener('dragstart', (e) => {
                tag.classList.add('is-dragging');
                e.dataTransfer?.setData('text/plain', tag.dataset.index || '0');
            });

            tag.addEventListener('dragend', () => {
                tag.classList.remove('is-dragging');
            });

            tag.addEventListener('dragover', (e) => {
                e.preventDefault();
            });

            tag.addEventListener('drop', (e) => {
                e.preventDefault();
                const fromIndex = parseInt(e.dataTransfer?.getData('text/plain') || '0', 10);
                const toIndex = parseInt(tag.dataset.index || '0', 10);

                if (fromIndex !== toIndex) {
                    this.moveTag(fromIndex, toIndex);
                }
            });
        });
    }

    private moveTag(fromIndex: number, toIndex: number): void {
        const tag = this.tags.splice(fromIndex, 1)[0];
        this.tags.splice(toIndex, 0, tag);
        this.render();
        this.updateValue();
    }

    private updateValue(): void {
        if (this.hiddenInput) {
            this.hiddenInput.value = JSON.stringify(this.tags);
            this.hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
        }
    }

    private escapeHtml(text: string): string {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    public getTags(): string[] {
        return [...this.tags];
    }

    public setTags(tags: string[]): void {
        this.tags = tags.slice(0, this.maxTags);
        this.render();
        this.updateValue();
    }

    public clear(): void {
        this.tags = [];
        this.render();
        this.updateValue();
    }

    public static initAll(): void {
        document.querySelectorAll<HTMLElement>('.tags-input-wrapper').forEach((wrapper) => {
            if (!wrapper.dataset.initialized) {
                wrapper.dataset.initialized = 'true';
                TagsInputManager.instances.set(wrapper, new TagsInputManager(wrapper));
            }
        });
    }

    public static getInstance(wrapper: HTMLElement): TagsInputManager | undefined {
        return TagsInputManager.instances.get(wrapper);
    }
}
