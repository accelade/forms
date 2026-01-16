/**
 * Checkbox List Component Manager
 */

export class CheckboxListManager {
    private static instances = new WeakMap<HTMLElement, CheckboxListManager>();

    private wrapper: HTMLElement;
    private searchInput: HTMLInputElement | null;
    private optionsContainer: HTMLElement | null;
    private options: HTMLElement[];
    private selectAllBtn: HTMLButtonElement | null;
    private deselectAllBtn: HTMLButtonElement | null;
    private noResultsEl: HTMLElement | null;
    private emptyEl: HTMLElement | null;
    private searchTimeout: ReturnType<typeof setTimeout> | null = null;
    private searchDebounce: number;

    constructor(wrapper: HTMLElement) {
        this.wrapper = wrapper;
        this.searchInput = wrapper.querySelector('.checkbox-list-search');
        this.optionsContainer = wrapper.querySelector('.checkbox-list-options');
        this.options = Array.from(wrapper.querySelectorAll('.checkbox-list-option'));
        this.selectAllBtn = wrapper.querySelector('.checkbox-list-select-all');
        this.deselectAllBtn = wrapper.querySelector('.checkbox-list-deselect-all');
        this.noResultsEl = wrapper.querySelector('.checkbox-list-no-results');
        this.emptyEl = wrapper.querySelector('.checkbox-list-empty');
        this.searchDebounce = parseInt(wrapper.dataset.searchDebounce || '300', 10);

        this.init();
    }

    private init(): void {
        // Search functionality
        if (this.searchInput) {
            this.searchInput.addEventListener('input', () => this.handleSearch());
        }

        // Bulk toggle functionality
        if (this.selectAllBtn) {
            this.selectAllBtn.addEventListener('click', () => this.selectAll());
        }

        if (this.deselectAllBtn) {
            this.deselectAllBtn.addEventListener('click', () => this.deselectAll());
        }

        // Dispatch change events on checkbox changes
        this.options.forEach((option) => {
            const checkbox = option.querySelector('input[type="checkbox"]') as HTMLInputElement;
            if (checkbox) {
                checkbox.addEventListener('change', () => this.dispatchChangeEvent());
            }
        });
    }

    private handleSearch(): void {
        if (!this.searchInput) return;

        if (this.searchTimeout) {
            clearTimeout(this.searchTimeout);
        }

        this.searchTimeout = setTimeout(() => {
            const query = this.searchInput!.value.trim().toLowerCase();
            this.filterOptions(query);
        }, this.searchDebounce);
    }

    private filterOptions(query: string): void {
        let visibleCount = 0;

        this.options.forEach((option) => {
            const label = option.querySelector('label:not(.checkbox-box)')?.textContent?.toLowerCase() || '';
            const description = option.querySelector('p')?.textContent?.toLowerCase() || '';
            const matches = query === '' || label.includes(query) || description.includes(query);

            if (matches) {
                option.classList.remove('hidden');
                visibleCount++;
            } else {
                option.classList.add('hidden');
            }
        });

        // Show/hide no results message
        if (this.noResultsEl) {
            if (visibleCount === 0 && query !== '') {
                this.noResultsEl.classList.remove('hidden');
                if (this.optionsContainer) {
                    this.optionsContainer.classList.add('hidden');
                }
            } else {
                this.noResultsEl.classList.add('hidden');
                if (this.optionsContainer) {
                    this.optionsContainer.classList.remove('hidden');
                }
            }
        }

        // Dispatch search event
        this.wrapper.dispatchEvent(new CustomEvent('checkbox-list:search', {
            bubbles: true,
            detail: { query, visibleCount }
        }));
    }

    public selectAll(): void {
        this.options.forEach((option) => {
            if (!option.classList.contains('hidden')) {
                const checkbox = option.querySelector('input[type="checkbox"]') as HTMLInputElement;
                if (checkbox && !checkbox.disabled) {
                    checkbox.checked = true;
                }
            }
        });
        this.dispatchChangeEvent();
    }

    public deselectAll(): void {
        this.options.forEach((option) => {
            if (!option.classList.contains('hidden')) {
                const checkbox = option.querySelector('input[type="checkbox"]') as HTMLInputElement;
                if (checkbox && !checkbox.disabled) {
                    checkbox.checked = false;
                }
            }
        });
        this.dispatchChangeEvent();
    }

    public getSelectedValues(): string[] {
        const values: string[] = [];
        this.options.forEach((option) => {
            const checkbox = option.querySelector('input[type="checkbox"]') as HTMLInputElement;
            if (checkbox && checkbox.checked) {
                values.push(checkbox.value);
            }
        });
        return values;
    }

    public setSelectedValues(values: string[]): void {
        this.options.forEach((option) => {
            const checkbox = option.querySelector('input[type="checkbox"]') as HTMLInputElement;
            if (checkbox && !checkbox.disabled) {
                checkbox.checked = values.includes(checkbox.value);
            }
        });
        this.dispatchChangeEvent();
    }

    private dispatchChangeEvent(): void {
        this.wrapper.dispatchEvent(new CustomEvent('checkbox-list:change', {
            bubbles: true,
            detail: { values: this.getSelectedValues() }
        }));
    }

    public static initAll(): void {
        document.querySelectorAll<HTMLElement>('[data-checkbox-list]').forEach((wrapper) => {
            if (!wrapper.dataset.initialized) {
                wrapper.dataset.initialized = 'true';
                CheckboxListManager.instances.set(wrapper, new CheckboxListManager(wrapper));
            }
        });
    }

    public static getInstance(wrapper: HTMLElement): CheckboxListManager | undefined {
        return CheckboxListManager.instances.get(wrapper);
    }
}
