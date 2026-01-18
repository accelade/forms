/**
 * Searchable Select Component Manager
 * Handles searchable select functionality including modals for create/edit options
 */

export interface SelectOptions {
    searchable: boolean;
    searchPlaceholder: string;
    noResultsText: string;
    noOptionsMessage: string;
    loadingMessage: string;
    searchingMessage: string;
    searchPrompt: string | null;
    searchMinLength: number;
    searchDebounce: number;
    allowClear: boolean;
    closeOnSelect: boolean;
    maxSelections: number | null;
    minSelections: number | null;
    taggable: boolean;
    createOptionText: string;
    multiple: boolean;
    disabled: boolean;
    optionsLimit: number;
    allowHtml: boolean;
    wrapOptionLabels: boolean;
    hasGroupedOptions: boolean;
    hasDescriptions: boolean;
    remoteUrl: string | null;
    remoteRoot: string | null;
    hasCreateOptionForm: boolean;
    createOptionModalHeading: string;
    createOptionModalSubmitButtonLabel: string;
    hasEditOptionForm: boolean;
    editOptionModalHeading: string;
    editOptionModalSubmitButtonLabel: string;
    recordUrl: string | null;
    createUrl: string | null;
    updateUrl: string | null;
    // Pagination options
    infiniteScroll: boolean;
    showAllOptions: boolean;
    perPage: number;
    hasModel: boolean;
    searchUrl: string | null;
    loadMoreMessage: string;
    // Notification options
    successNotification: boolean;
    createSuccessTitle: string;
    createSuccessBody: string;
    updateSuccessTitle: string;
    updateSuccessBody: string;
}

export class SelectManager {
    private static instances = new WeakMap<HTMLElement, SelectManager>();

    private wrapper: HTMLElement;
    private field: HTMLElement;
    private hiddenSelect: HTMLSelectElement | null;
    private trigger: HTMLButtonElement | null;
    private dropdown: HTMLElement | null;
    private searchInput: HTMLInputElement | null;
    private optionsList: HTMLElement | null;
    private display: HTMLElement | null;
    private clearBtn: HTMLElement | null;
    private createBtn: HTMLElement | null;
    private editBtn: HTMLElement | null;
    private createModal: HTMLElement | null;
    private editModal: HTMLElement | null;
    private tagsContainer: HTMLElement | null;
    private noResults: HTMLElement | null;
    private createOption: HTMLElement | null;

    private options: SelectOptions;
    private isOpen: boolean = false;
    private selectedValues: string[] = [];
    private highlightedIndex: number = -1;
    private searchTimeout: ReturnType<typeof setTimeout> | null = null;

    // Pagination state
    private currentPage: number = 1;
    private hasMorePages: boolean = false;
    private isLoadingMore: boolean = false;
    private currentSearchQuery: string = '';
    private loadMoreIndicator: HTMLElement | null;
    private scrollHint: HTMLElement | null;
    private loadingIndicator: HTMLElement | null;

    constructor(field: HTMLElement) {
        this.field = field;
        this.wrapper = field.querySelector('.searchable-select-wrapper') || field;
        this.hiddenSelect = field.querySelector('.searchable-select-hidden');
        this.trigger = field.querySelector('.searchable-select-trigger');
        this.dropdown = field.querySelector('.searchable-select-dropdown');
        this.searchInput = field.querySelector('.searchable-select-search');
        this.optionsList = field.querySelector('.searchable-select-options');
        this.display = field.querySelector('.searchable-select-display');
        this.clearBtn = field.querySelector('.searchable-select-clear');
        this.createBtn = field.querySelector('.searchable-select-create-btn');
        this.editBtn = field.querySelector('.searchable-select-edit-btn');
        this.createModal = field.querySelector('.searchable-select-create-modal');
        this.editModal = field.querySelector('.searchable-select-edit-modal');
        this.tagsContainer = field.querySelector('.searchable-select-tags');
        this.noResults = field.querySelector('.searchable-select-no-results');
        this.createOption = field.querySelector('.searchable-select-create');
        this.loadMoreIndicator = field.querySelector('.searchable-select-load-more');
        this.scrollHint = field.querySelector('.searchable-select-scroll-hint');
        this.loadingIndicator = field.querySelector('.searchable-select-loading');

        // Parse options from data attribute
        const optionsStr = field.dataset.searchableSelect;
        this.options = optionsStr ? JSON.parse(optionsStr) : this.getDefaultOptions();

        this.init();
    }

    private getDefaultOptions(): SelectOptions {
        return {
            searchable: true,
            searchPlaceholder: 'Search...',
            noResultsText: 'No results found',
            noOptionsMessage: 'No options available',
            loadingMessage: 'Loading...',
            searchingMessage: 'Searching...',
            searchPrompt: null,
            searchMinLength: 0,
            searchDebounce: 300,
            allowClear: false,
            closeOnSelect: true,
            maxSelections: null,
            minSelections: null,
            taggable: false,
            createOptionText: 'Create "{value}"',
            multiple: false,
            disabled: false,
            optionsLimit: 50,
            allowHtml: false,
            wrapOptionLabels: false,
            hasGroupedOptions: false,
            hasDescriptions: false,
            remoteUrl: null,
            remoteRoot: null,
            hasCreateOptionForm: false,
            createOptionModalHeading: 'Create option',
            createOptionModalSubmitButtonLabel: 'Create',
            hasEditOptionForm: false,
            editOptionModalHeading: 'Edit option',
            editOptionModalSubmitButtonLabel: 'Save',
            // Pagination defaults
            infiniteScroll: true,
            showAllOptions: false,
            perPage: 50,
            hasModel: false,
            searchUrl: null,
            loadMoreMessage: 'Loading more...',
            // Notification defaults
            successNotification: true,
            createSuccessTitle: 'Created',
            createSuccessBody: 'Record created successfully.',
            updateSuccessTitle: 'Updated',
            updateSuccessBody: 'Record updated successfully.',
        };
    }

    private init(): void {
        if (!this.trigger || !this.dropdown) return;

        // Initialize selected values from hidden select
        this.syncSelectedValues();

        // Trigger click handler
        this.trigger.addEventListener('click', (e) => {
            // Don't toggle if clicking on action buttons
            const target = e.target as HTMLElement;
            if (target.closest('.searchable-select-clear') ||
                target.closest('.searchable-select-create-btn') ||
                target.closest('.searchable-select-edit-btn')) {
                return;
            }
            this.toggle();
        });

        // Search input handler
        if (this.searchInput) {
            this.searchInput.addEventListener('input', () => this.handleSearch());
            this.searchInput.addEventListener('keydown', (e) => this.handleKeydown(e));
        }

        // Option click handlers
        this.optionsList?.addEventListener('click', (e) => {
            const option = (e.target as HTMLElement).closest('.searchable-select-option');
            if (option && !option.hasAttribute('data-disabled')) {
                this.selectOption(option as HTMLElement);
            }
        });

        // Clear button handler
        this.clearBtn?.addEventListener('click', (e) => {
            e.stopPropagation();
            this.clear();
        });

        // Create button handler
        this.createBtn?.addEventListener('click', (e) => {
            e.stopPropagation();
            this.openCreateModal();
        });

        // Edit button handler
        this.editBtn?.addEventListener('click', (e) => {
            e.stopPropagation();
            this.openEditModal();
        });

        // Modal handlers
        this.initModals();

        // Close on outside click
        document.addEventListener('click', (e) => {
            if (!this.field.contains(e.target as Node) && this.isOpen) {
                this.close();
            }
        });

        // Close on escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                if (this.isOpen) {
                    this.close();
                }
                this.closeAllModals();
            }
        });

        // Taggable create option
        if (this.createOption) {
            this.createOption.addEventListener('click', () => this.createTag());
        }

        // Infinite scroll handler
        if (this.options.infiniteScroll && this.optionsList) {
            this.optionsList.addEventListener('scroll', () => this.handleScroll());
            this.initPagination();
        }

        // Update display
        this.updateDisplay();

        // Mark as initialized
        this.field.dataset.initialized = 'true';
    }

    private initModals(): void {
        // Create modal
        if (this.createModal) {
            const closeBtn = this.createModal.querySelector('.searchable-select-modal-close');
            const cancelBtn = this.createModal.querySelector('.searchable-select-modal-cancel');
            const backdrop = this.createModal.querySelector('.searchable-select-modal-backdrop');
            const form = this.createModal.querySelector('.searchable-select-create-form');

            closeBtn?.addEventListener('click', () => this.closeCreateModal());
            cancelBtn?.addEventListener('click', () => this.closeCreateModal());
            backdrop?.addEventListener('click', () => this.closeCreateModal());

            // Handle form submit
            form?.addEventListener('submit', (e) => {
                e.preventDefault();
                this.submitCreateForm();
            });
        }

        // Edit modal
        if (this.editModal) {
            const closeBtn = this.editModal.querySelector('.searchable-select-modal-close');
            const cancelBtn = this.editModal.querySelector('.searchable-select-modal-cancel');
            const backdrop = this.editModal.querySelector('.searchable-select-modal-backdrop');
            const form = this.editModal.querySelector('.searchable-select-edit-form');

            closeBtn?.addEventListener('click', () => this.closeEditModal());
            cancelBtn?.addEventListener('click', () => this.closeEditModal());
            backdrop?.addEventListener('click', () => this.closeEditModal());

            // Handle form submit
            form?.addEventListener('submit', (e) => {
                e.preventDefault();
                this.submitEditForm();
            });
        }
    }

    private syncSelectedValues(): void {
        if (!this.hiddenSelect) return;

        this.selectedValues = [];
        const selectedOptions = this.hiddenSelect.selectedOptions;

        for (let i = 0; i < selectedOptions.length; i++) {
            const value = selectedOptions[i].value;
            if (value) {
                this.selectedValues.push(value);
            }
        }
    }

    public toggle(): void {
        if (this.options.disabled) return;
        this.isOpen ? this.close() : this.open();
    }

    public open(): void {
        if (this.options.disabled || !this.dropdown) return;

        this.isOpen = true;
        this.dropdown.classList.remove('hidden');
        this.trigger?.setAttribute('aria-expanded', 'true');

        // Focus search input
        if (this.searchInput) {
            setTimeout(() => this.searchInput?.focus(), 10);
        }

        // Reset highlight
        this.highlightedIndex = -1;
        this.updateHighlight();
    }

    public close(): void {
        if (!this.dropdown) return;

        this.isOpen = false;
        this.dropdown.classList.add('hidden');
        this.trigger?.setAttribute('aria-expanded', 'false');

        // Clear search and reset options
        if (this.searchInput) {
            const hadSearch = this.searchInput.value.trim() !== '';
            this.searchInput.value = '';

            // If we had a server search, reload initial options
            if (hadSearch && this.options.searchUrl && this.options.hasModel) {
                this.resetToInitialOptions();
            } else {
                this.filterOptions('');
            }
        }
    }

    private handleSearch(): void {
        if (!this.searchInput) return;

        const query = this.searchInput.value.trim();

        // Debounce search
        if (this.searchTimeout) {
            clearTimeout(this.searchTimeout);
        }

        this.searchTimeout = setTimeout(() => {
            // If we have a model with search URL, query the server
            if (this.options.searchUrl && this.options.hasModel) {
                this.searchWithServer(query);
            } else {
                // Otherwise, filter client-side
                this.filterOptions(query.toLowerCase());
            }
        }, this.options.searchDebounce);
    }

    private filterOptions(query: string): void {
        const options = this.optionsList?.querySelectorAll('.searchable-select-option');
        const groups = this.optionsList?.querySelectorAll('.searchable-select-group');
        let visibleCount = 0;

        // If using grouped options, handle groups
        if (this.options.hasGroupedOptions && groups) {
            groups.forEach(group => {
                const groupOptions = group.querySelectorAll('.searchable-select-option');
                let groupVisibleCount = 0;

                groupOptions.forEach(option => {
                    const label = option.querySelector('.searchable-select-option-label')?.textContent?.toLowerCase() || '';
                    const description = option.querySelector('.searchable-select-option-description')?.textContent?.toLowerCase() || '';
                    const matches = label.includes(query) || description.includes(query);

                    (option as HTMLElement).classList.toggle('hidden', !matches);
                    if (matches) {
                        visibleCount++;
                        groupVisibleCount++;
                    }
                });

                // Hide group if no visible options
                (group as HTMLElement).classList.toggle('hidden', groupVisibleCount === 0);
            });
        } else if (options) {
            options.forEach(option => {
                const label = option.querySelector('.searchable-select-option-label')?.textContent?.toLowerCase() || '';
                const description = option.querySelector('.searchable-select-option-description')?.textContent?.toLowerCase() || '';
                const matches = label.includes(query) || description.includes(query);

                (option as HTMLElement).classList.toggle('hidden', !matches);
                if (matches) visibleCount++;
            });
        }

        // Show/hide no results message
        if (this.noResults) {
            this.noResults.classList.toggle('hidden', visibleCount > 0);
        }

        // Show/hide create option for taggable
        if (this.createOption && this.options.taggable) {
            const createValueSpan = this.createOption.querySelector('.searchable-select-create-value');
            if (createValueSpan) {
                createValueSpan.textContent = query;
            }
            this.createOption.classList.toggle('hidden', !query || visibleCount > 0);
        }

        // Reset highlight
        this.highlightedIndex = -1;
        this.updateHighlight();
    }

    private handleKeydown(e: KeyboardEvent): void {
        const visibleOptions = this.getVisibleOptions();

        switch (e.key) {
            case 'ArrowDown':
                e.preventDefault();
                this.highlightedIndex = Math.min(this.highlightedIndex + 1, visibleOptions.length - 1);
                this.updateHighlight();
                break;

            case 'ArrowUp':
                e.preventDefault();
                this.highlightedIndex = Math.max(this.highlightedIndex - 1, 0);
                this.updateHighlight();
                break;

            case 'Enter':
                e.preventDefault();
                if (this.highlightedIndex >= 0 && visibleOptions[this.highlightedIndex]) {
                    this.selectOption(visibleOptions[this.highlightedIndex] as HTMLElement);
                } else if (this.options.taggable && this.searchInput?.value) {
                    this.createTag();
                }
                break;

            case 'Escape':
                this.close();
                break;
        }
    }

    private getVisibleOptions(): HTMLElement[] {
        if (!this.optionsList) return [];
        return Array.from(this.optionsList.querySelectorAll('.searchable-select-option:not(.hidden)'));
    }

    private updateHighlight(): void {
        const options = this.getVisibleOptions();

        options.forEach((option, index) => {
            option.classList.toggle('bg-gray-100', index === this.highlightedIndex);
            option.classList.toggle('dark:bg-gray-700', index === this.highlightedIndex);
        });

        // Scroll highlighted option into view
        if (this.highlightedIndex >= 0 && options[this.highlightedIndex]) {
            options[this.highlightedIndex].scrollIntoView({ block: 'nearest' });
        }
    }

    private selectOption(option: HTMLElement): void {
        const value = option.dataset.value;
        if (!value || !this.hiddenSelect) return;

        if (this.options.multiple) {
            // Toggle selection for multiple
            const index = this.selectedValues.indexOf(value);
            if (index > -1) {
                this.selectedValues.splice(index, 1);
            } else {
                // Check max selections
                if (this.options.maxSelections && this.selectedValues.length >= this.options.maxSelections) {
                    return;
                }
                this.selectedValues.push(value);
            }
        } else {
            // Single selection
            this.selectedValues = [value];
        }

        // Update hidden select
        this.updateHiddenSelect();

        // Update display
        this.updateDisplay();

        // Update option checkmarks
        this.updateOptionCheckmarks();

        // Close dropdown for single select
        if (!this.options.multiple || this.options.closeOnSelect) {
            this.close();
        }

        // Dispatch change event
        this.dispatchChangeEvent();
    }

    private updateHiddenSelect(): void {
        if (!this.hiddenSelect) return;

        // Clear all selections
        Array.from(this.hiddenSelect.options).forEach(opt => opt.selected = false);

        // Select the values
        this.selectedValues.forEach(value => {
            const option = this.hiddenSelect!.querySelector(`option[value="${value}"]`) as HTMLOptionElement;
            if (option) {
                option.selected = true;
            }
        });

        // Trigger change event on hidden select
        this.hiddenSelect.dispatchEvent(new Event('change', { bubbles: true }));
    }

    private updateDisplay(): void {
        if (!this.display || !this.hiddenSelect) return;

        if (this.selectedValues.length === 0) {
            // Show placeholder
            const emptyOption = this.hiddenSelect.querySelector('option[value=""]');
            this.display.textContent = emptyOption?.textContent || '';
            this.display.classList.add('text-gray-400');

            // Hide clear and edit buttons
            this.clearBtn?.classList.add('hidden');
            this.editBtn?.classList.add('hidden');
        } else if (this.options.multiple) {
            // Update tags for multiple select
            this.updateTags();
            this.display.textContent = `${this.selectedValues.length} selected`;
            this.display.classList.remove('text-gray-400');

            // Show clear button
            if (this.options.allowClear) {
                this.clearBtn?.classList.remove('hidden');
            }
        } else {
            // Show selected label for single select
            const selectedOption = this.hiddenSelect.querySelector(`option[value="${this.selectedValues[0]}"]`);
            this.display.textContent = selectedOption?.textContent || this.selectedValues[0];
            this.display.classList.remove('text-gray-400');

            // Show clear and edit buttons
            if (this.options.allowClear) {
                this.clearBtn?.classList.remove('hidden');
            }
            if (this.options.hasEditOptionForm) {
                this.editBtn?.classList.remove('hidden');
            }
        }
    }

    private updateTags(): void {
        if (!this.tagsContainer || !this.hiddenSelect) return;

        this.tagsContainer.innerHTML = '';
        this.tagsContainer.classList.remove('hidden');

        this.selectedValues.forEach(value => {
            const option = this.hiddenSelect!.querySelector(`option[value="${value}"]`);
            const label = option?.textContent || value;

            const tag = document.createElement('span');
            tag.className = 'inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded-md bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-200';
            tag.innerHTML = `
                <span>${label}</span>
                <button type="button" class="searchable-select-tag-remove hover:text-primary-600 dark:hover:text-primary-400" data-value="${value}">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            `;

            // Remove tag handler
            tag.querySelector('.searchable-select-tag-remove')?.addEventListener('click', (e) => {
                e.stopPropagation();
                this.removeValue(value);
            });

            this.tagsContainer!.appendChild(tag);
        });
    }

    private updateOptionCheckmarks(): void {
        const options = this.optionsList?.querySelectorAll('.searchable-select-option');
        options?.forEach(option => {
            const value = (option as HTMLElement).dataset.value;
            const check = option.querySelector('.searchable-select-option-check');
            if (check) {
                check.classList.toggle('hidden', !this.selectedValues.includes(value || ''));
            }
        });
    }

    private removeValue(value: string): void {
        const index = this.selectedValues.indexOf(value);
        if (index > -1) {
            this.selectedValues.splice(index, 1);
            this.updateHiddenSelect();
            this.updateDisplay();
            this.updateOptionCheckmarks();
            this.dispatchChangeEvent();
        }
    }

    public clear(): void {
        this.selectedValues = [];
        this.updateHiddenSelect();
        this.updateDisplay();
        this.updateOptionCheckmarks();
        this.dispatchChangeEvent();
    }

    private createTag(): void {
        if (!this.searchInput || !this.hiddenSelect) return;

        const value = this.searchInput.value.trim();
        if (!value) return;

        // Check if option already exists
        const existingOption = this.hiddenSelect.querySelector(`option[value="${value}"]`);
        if (!existingOption) {
            // Create new option
            const option = document.createElement('option');
            option.value = value;
            option.textContent = value;
            this.hiddenSelect.appendChild(option);

            // Add to dropdown list
            const li = document.createElement('li');
            li.className = 'searchable-select-option px-3 py-2 text-sm cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-100';
            li.dataset.value = value;
            li.setAttribute('role', 'option');
            li.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="flex-1 truncate">
                        <span class="searchable-select-option-label">${value}</span>
                    </div>
                    <span class="searchable-select-option-check hidden ms-2 text-primary-600 shrink-0">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </span>
                </div>
            `;
            li.addEventListener('click', () => this.selectOption(li));
            this.optionsList?.appendChild(li);
        }

        // Select the value
        if (this.options.multiple) {
            if (!this.selectedValues.includes(value)) {
                this.selectedValues.push(value);
            }
        } else {
            this.selectedValues = [value];
        }

        this.updateHiddenSelect();
        this.updateDisplay();
        this.updateOptionCheckmarks();
        this.dispatchChangeEvent();

        // Clear search and close
        this.searchInput.value = '';
        this.filterOptions('');

        if (!this.options.multiple || this.options.closeOnSelect) {
            this.close();
        }
    }

    private dispatchChangeEvent(): void {
        // Dispatch custom event for dependent selects
        this.field.dispatchEvent(new CustomEvent('searchable-select-change', {
            bubbles: true,
            detail: {
                value: this.options.multiple ? this.selectedValues : this.selectedValues[0] || null,
                values: this.selectedValues
            }
        }));
    }

    // Pagination and Infinite Scroll methods
    private initPagination(): void {
        if (!this.options.infiniteScroll) return;

        // Check if we have more options than the limit
        const allOptions = this.optionsList?.querySelectorAll('.searchable-select-option');
        const totalOptions = allOptions?.length || 0;

        if (totalOptions > this.options.perPage) {
            // Hide options beyond the limit initially (for static options)
            this.applyPagination();
            this.hasMorePages = true;
        } else if (this.options.searchUrl && this.options.hasModel) {
            // If we have a server endpoint, assume there are more pages
            // Server will tell us when there are no more pages
            this.hasMorePages = totalOptions >= this.options.perPage;
        } else {
            this.hasMorePages = false;
        }

        this.updateScrollHint();
    }

    private applyPagination(): void {
        if (!this.optionsList || this.options.showAllOptions) return;

        const allOptions = this.optionsList.querySelectorAll('.searchable-select-option');
        const limit = this.currentPage * this.options.perPage;

        allOptions.forEach((option, index) => {
            if (index < limit) {
                (option as HTMLElement).classList.remove('hidden-by-pagination');
            } else {
                (option as HTMLElement).classList.add('hidden-by-pagination');
            }
        });

        // Update has more pages
        this.hasMorePages = allOptions.length > limit;
        this.updateScrollHint();
    }

    private handleScroll(): void {
        if (!this.optionsList || !this.options.infiniteScroll || this.isLoadingMore || !this.hasMorePages) return;

        const scrollTop = this.optionsList.scrollTop;
        const scrollHeight = this.optionsList.scrollHeight;
        const clientHeight = this.optionsList.clientHeight;

        // Load more when scrolled to 80% of the list
        if (scrollTop + clientHeight >= scrollHeight * 0.8) {
            this.loadMoreOptions();
        }
    }

    private async loadMoreOptions(): Promise<void> {
        if (this.isLoadingMore || !this.hasMorePages) return;

        this.isLoadingMore = true;
        this.showLoadMoreIndicator();

        // If we have a search URL, fetch from server
        if (this.options.searchUrl) {
            await this.loadOptionsFromServer();
        } else {
            // Local pagination - just show more items
            this.currentPage++;
            this.applyPagination();
        }

        this.isLoadingMore = false;
        this.hideLoadMoreIndicator();
    }

    private async loadOptionsFromServer(): Promise<void> {
        if (!this.options.searchUrl) return;

        try {
            const nextPage = this.currentPage + 1;
            const url = new URL(this.options.searchUrl, window.location.origin);
            url.searchParams.set('page', String(nextPage));
            if (this.currentSearchQuery) {
                url.searchParams.set('search', this.currentSearchQuery);
            }

            const response = await fetch(url.toString(), {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                }
            });

            if (!response.ok) throw new Error('Failed to fetch options');

            const result = await response.json();

            if (result.data && Array.isArray(result.data)) {
                this.appendOptions(result.data);
                this.hasMorePages = result.hasMore ?? false;
                this.currentPage = result.currentPage ?? nextPage;
            }
        } catch (error) {
            console.error('Error loading options:', error);
        }
    }

    private appendOptions(options: Array<{ value: string; label: string; disabled?: boolean; description?: string }>): void {
        if (!this.optionsList || !this.hiddenSelect) return;

        options.forEach(opt => {
            // Skip if already exists
            if (this.hiddenSelect!.querySelector(`option[value="${opt.value}"]`)) return;

            // Add to hidden select
            const selectOption = document.createElement('option');
            selectOption.value = opt.value;
            selectOption.textContent = opt.label;
            if (opt.disabled) selectOption.disabled = true;
            this.hiddenSelect!.appendChild(selectOption);

            // Add to dropdown list
            const li = document.createElement('li');
            li.className = `searchable-select-option px-3 py-2 text-sm cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-100 ${opt.disabled ? 'opacity-50 cursor-not-allowed' : ''} ${!this.options.wrapOptionLabels ? 'truncate' : ''}`;
            li.dataset.value = opt.value;
            if (opt.disabled) li.dataset.disabled = 'true';
            li.setAttribute('role', 'option');
            li.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="flex-1 ${!this.options.wrapOptionLabels ? 'truncate' : ''}">
                        <span class="searchable-select-option-label">${this.options.allowHtml ? opt.label : this.escapeHtml(opt.label)}</span>
                        ${opt.description ? `<p class="searchable-select-option-description text-xs text-gray-500 dark:text-gray-400 mt-0.5">${this.escapeHtml(opt.description)}</p>` : ''}
                    </div>
                    <span class="searchable-select-option-check hidden ms-2 text-primary-600 shrink-0">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </span>
                </div>
            `;

            if (!opt.disabled) {
                li.addEventListener('click', () => this.selectOption(li));
            }

            this.optionsList!.appendChild(li);
        });

        // Update checkmarks for any selected values
        this.updateOptionCheckmarks();
    }

    private escapeHtml(text: string): string {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    private showLoadMoreIndicator(): void {
        this.loadMoreIndicator?.classList.remove('hidden');
    }

    private hideLoadMoreIndicator(): void {
        this.loadMoreIndicator?.classList.add('hidden');
    }

    private updateScrollHint(): void {
        if (this.scrollHint) {
            this.scrollHint.classList.toggle('hidden', !this.hasMorePages);
        }
    }

    public async searchWithServer(query: string): Promise<void> {
        if (!this.options.searchUrl) return;

        this.currentSearchQuery = query;
        this.currentPage = 1;
        this.showLoading();

        try {
            const url = new URL(this.options.searchUrl, window.location.origin);
            url.searchParams.set('page', '1');
            if (query) {
                url.searchParams.set('search', query);
            }

            const response = await fetch(url.toString(), {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                }
            });

            if (!response.ok) throw new Error('Search failed');

            const result = await response.json();

            // Clear existing options (except selected ones)
            this.clearOptionsExceptSelected();

            if (result.data && Array.isArray(result.data)) {
                this.appendOptions(result.data);
                this.hasMorePages = result.hasMore ?? false;
                this.currentPage = result.currentPage ?? 1;
            }

            // Show no results if empty
            if (this.noResults) {
                const visibleOptions = this.optionsList?.querySelectorAll('.searchable-select-option:not(.hidden)');
                this.noResults.classList.toggle('hidden', (visibleOptions?.length ?? 0) > 0);
            }
        } catch (error) {
            console.error('Search error:', error);
        } finally {
            this.hideLoading();
        }
    }

    private clearOptionsExceptSelected(): void {
        if (!this.optionsList) return;

        // Clear dropdown options
        const options = this.optionsList.querySelectorAll('.searchable-select-option');
        options.forEach(option => {
            const value = (option as HTMLElement).dataset.value;
            if (value && !this.selectedValues.includes(value)) {
                option.remove();
            }
        });

        // Clear hidden select options (except empty option and selected ones)
        if (this.hiddenSelect) {
            const hiddenOptions = this.hiddenSelect.querySelectorAll('option');
            hiddenOptions.forEach(option => {
                const value = option.value;
                // Keep empty option and selected options
                if (value !== '' && !this.selectedValues.includes(value)) {
                    option.remove();
                }
            });
        }
    }

    private showLoading(): void {
        this.loadingIndicator?.classList.remove('hidden');
    }

    private hideLoading(): void {
        this.loadingIndicator?.classList.add('hidden');
    }

    public resetPagination(): void {
        this.currentPage = 1;
        this.currentSearchQuery = '';
        this.hasMorePages = false;
        this.isLoadingMore = false;
        this.applyPagination();
    }

    private async resetToInitialOptions(): Promise<void> {
        if (!this.options.searchUrl) return;

        this.currentSearchQuery = '';
        this.currentPage = 1;

        try {
            const url = new URL(this.options.searchUrl, window.location.origin);
            url.searchParams.set('page', '1');
            // Don't add search param - we want all initial options

            const response = await fetch(url.toString(), {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                }
            });

            if (!response.ok) throw new Error('Failed to reset options');

            const result = await response.json();

            // Clear all options except selected ones
            this.clearOptionsExceptSelected();

            if (result.data && Array.isArray(result.data)) {
                this.appendOptions(result.data);
                this.hasMorePages = result.hasMore ?? false;
                this.currentPage = result.currentPage ?? 1;
            }

            // Hide no results
            if (this.noResults) {
                this.noResults.classList.add('hidden');
            }

            this.updateScrollHint();
        } catch (error) {
            console.error('Error resetting options:', error);
        }
    }

    // Modal methods
    public openCreateModal(): void {
        if (!this.createModal) return;
        this.createModal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');

        // Focus first input
        const firstInput = this.createModal.querySelector('input, textarea, select') as HTMLElement;
        firstInput?.focus();
    }

    public closeCreateModal(): void {
        if (!this.createModal) return;
        this.createModal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');

        // Clear errors
        this.clearModalErrors(this.createModal);

        // Clear form
        const form = this.createModal.querySelector('.searchable-select-create-form') as HTMLFormElement;
        form?.reset();
    }

    private async submitCreateForm(): Promise<void> {
        if (!this.createModal) return;

        // Collect form data
        const formContent = this.createModal.querySelector('.searchable-select-create-form-content');
        const inputs = formContent?.querySelectorAll('input, textarea, select') as NodeListOf<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>;
        const data: Record<string, string> = {};

        inputs?.forEach(input => {
            const name = input.getAttribute('name');
            if (name && (input instanceof HTMLInputElement || input instanceof HTMLTextAreaElement || input instanceof HTMLSelectElement)) {
                data[name] = input.value;
            }
        });

        // If we have a createUrl, call the API
        if (this.options.createUrl) {
            try {
                // Disable inputs during submission
                const submitBtn = this.createModal.querySelector('.searchable-select-modal-submit') as HTMLButtonElement;
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Creating...';
                }
                inputs?.forEach(input => input.disabled = true);

                // Get CSRF token from meta tag
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

                const response = await fetch(this.options.createUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify(data),
                });

                if (response.ok) {
                    const result = await response.json();
                    if (result.success && result.data) {
                        this.clearModalErrors(this.createModal);
                        this.handleCreateCallback(result.data);
                    }
                } else {
                    const error = await response.json();
                    console.error('Failed to create record:', error);
                    // Show error in modal
                    this.showModalError(this.createModal, error);
                }

                // Re-enable inputs
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = this.options.createOptionModalSubmitButtonLabel;
                }
                inputs?.forEach(input => input.disabled = false);
            } catch (error) {
                console.error('Error creating record:', error);
                // Show generic error
                this.showModalError(this.createModal, { message: 'An unexpected error occurred. Please try again.' });
                // Re-enable inputs
                inputs?.forEach(input => input.disabled = false);
            }
        } else {
            // Fallback to event dispatch for custom handling
            this.field.dispatchEvent(new CustomEvent('searchable-select-create', {
                bubbles: true,
                detail: { data, callback: this.handleCreateCallback.bind(this) }
            }));
        }
    }

    /**
     * Show a success notification using Accelade's notify system
     */
    private showSuccessNotification(title: string, body: string = ''): void {
        if (!this.options.successNotification) return;

        // Check if Accelade is available (from the accelade package)
        const Accelade = (window as unknown as { Accelade?: { notify?: { success: (title: string, body?: string) => void } } }).Accelade;
        if (Accelade?.notify?.success) {
            Accelade.notify.success(title, body);
        }
    }

    private handleCreateCallback(option: { value: string; label: string }): void {
        if (!this.hiddenSelect || !option.value) return;

        // Add option to select
        const newOption = document.createElement('option');
        newOption.value = option.value;
        newOption.textContent = option.label;
        this.hiddenSelect.appendChild(newOption);

        // Add to dropdown
        const li = document.createElement('li');
        li.className = 'searchable-select-option px-3 py-2 text-sm cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-100';
        li.dataset.value = option.value;
        li.setAttribute('role', 'option');
        li.innerHTML = `
            <div class="flex items-center justify-between">
                <div class="flex-1 truncate">
                    <span class="searchable-select-option-label">${option.label}</span>
                </div>
                <span class="searchable-select-option-check hidden ms-2 text-primary-600 shrink-0">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </span>
            </div>
        `;
        li.addEventListener('click', () => this.selectOption(li));
        this.optionsList?.appendChild(li);

        // Select the new option
        if (this.options.multiple) {
            this.selectedValues.push(option.value);
        } else {
            this.selectedValues = [option.value];
        }

        this.updateHiddenSelect();
        this.updateDisplay();
        this.updateOptionCheckmarks();
        this.dispatchChangeEvent();

        // Close modal
        this.closeCreateModal();

        // Show success notification
        this.showSuccessNotification(
            this.options.createSuccessTitle,
            this.options.createSuccessBody
        );
    }

    public async openEditModal(): Promise<void> {
        if (!this.editModal || this.selectedValues.length === 0) return;

        const selectedValue = this.selectedValues[0];

        // Show modal immediately with loading state
        this.editModal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');

        // If we have a recordUrl, fetch the data to pre-fill the form
        if (this.options.recordUrl) {
            try {
                // Show loading state on inputs
                const formContent = this.editModal.querySelector('.searchable-select-edit-form-content');
                const inputs = formContent?.querySelectorAll('input, textarea, select') as NodeListOf<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>;
                inputs?.forEach(input => {
                    input.disabled = true;
                });

                // Fetch record data
                const url = new URL(this.options.recordUrl, window.location.origin);
                url.searchParams.set('id', selectedValue);

                const response = await fetch(url.toString(), {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                if (response.ok) {
                    const result = await response.json();
                    const data = result.data || {};

                    // Populate form fields with record data
                    inputs?.forEach(input => {
                        const name = input.getAttribute('name');
                        if (name && data[name] !== undefined) {
                            input.value = data[name] ?? '';
                        }
                        input.disabled = false;
                    });
                } else {
                    // Re-enable inputs even on error
                    inputs?.forEach(input => {
                        input.disabled = false;
                    });
                    console.error('Failed to fetch record data:', response.statusText);
                }
            } catch (error) {
                console.error('Error fetching record data:', error);
                // Re-enable inputs on error
                const inputs = this.editModal.querySelectorAll('input, textarea, select') as NodeListOf<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>;
                inputs?.forEach(input => {
                    input.disabled = false;
                });
            }
        }

        // Focus first input
        const firstInput = this.editModal.querySelector('input, textarea, select') as HTMLElement;
        firstInput?.focus();
    }

    public closeEditModal(): void {
        if (!this.editModal) return;
        this.editModal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');

        // Clear errors
        this.clearModalErrors(this.editModal);

        // Reset form
        const form = this.editModal.querySelector('.searchable-select-edit-form') as HTMLFormElement;
        form?.reset();
    }

    private async submitEditForm(): Promise<void> {
        if (!this.editModal || this.selectedValues.length === 0) return;

        const selectedValue = this.selectedValues[0];

        // Collect form data
        const formContent = this.editModal.querySelector('.searchable-select-edit-form-content');
        const inputs = formContent?.querySelectorAll('input, textarea, select') as NodeListOf<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>;
        const data: Record<string, string> = {};

        inputs?.forEach(input => {
            const name = input.getAttribute('name');
            if (name && (input instanceof HTMLInputElement || input instanceof HTMLTextAreaElement || input instanceof HTMLSelectElement)) {
                data[name] = input.value;
            }
        });

        // If we have an updateUrl, call the API
        if (this.options.updateUrl) {
            try {
                // Disable inputs during submission
                const submitBtn = this.editModal.querySelector('.searchable-select-modal-submit') as HTMLButtonElement;
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Updating...';
                }
                inputs?.forEach(input => input.disabled = true);

                // Get CSRF token from meta tag
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

                const response = await fetch(this.options.updateUrl, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ ...data, id: selectedValue }),
                });

                if (response.ok) {
                    const result = await response.json();
                    if (result.success && result.data) {
                        this.clearModalErrors(this.editModal);
                        this.handleEditCallback(result.data);
                    }
                } else {
                    const error = await response.json();
                    console.error('Failed to update record:', error);
                    // Show error in modal
                    this.showModalError(this.editModal, error);
                }

                // Re-enable inputs
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = this.options.editOptionModalSubmitButtonLabel;
                }
                inputs?.forEach(input => input.disabled = false);
            } catch (error) {
                console.error('Error updating record:', error);
                // Show generic error
                this.showModalError(this.editModal, { message: 'An unexpected error occurred. Please try again.' });
                // Re-enable inputs
                inputs?.forEach(input => input.disabled = false);
            }
        } else {
            // Fallback to event dispatch for custom handling
            this.field.dispatchEvent(new CustomEvent('searchable-select-edit', {
                bubbles: true,
                detail: {
                    value: selectedValue,
                    data,
                    callback: this.handleEditCallback.bind(this)
                }
            }));
        }
    }

    private handleEditCallback(option: { value: string; label: string }): void {
        if (!this.hiddenSelect) return;

        // Update option in select
        const existingOption = this.hiddenSelect.querySelector(`option[value="${option.value}"]`) as HTMLOptionElement;
        if (existingOption) {
            existingOption.textContent = option.label;
        }

        // Update in dropdown
        const dropdownOption = this.optionsList?.querySelector(`[data-value="${option.value}"]`);
        const labelSpan = dropdownOption?.querySelector('.searchable-select-option-label');
        if (labelSpan) {
            labelSpan.textContent = option.label;
        }

        // Update display
        this.updateDisplay();

        // Close modal
        this.closeEditModal();

        // Show success notification
        this.showSuccessNotification(
            this.options.updateSuccessTitle,
            this.options.updateSuccessBody
        );
    }

    private closeAllModals(): void {
        this.closeCreateModal();
        this.closeEditModal();
    }

    /**
     * Show error message in modal with Laravel-style design
     */
    private showModalError(modal: HTMLElement | null, error: { message?: string; errors?: Record<string, string[]> }): void {
        if (!modal) return;

        // Clear any existing errors first
        this.clearModalErrors(modal);

        const formContent = modal.querySelector('.searchable-select-create-form-content, .searchable-select-edit-form-content');
        if (!formContent) return;

        // Create error container
        const errorContainer = document.createElement('div');
        errorContainer.className = 'searchable-select-modal-errors mb-4 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 p-4';

        // Check if we have field-specific errors
        if (error.errors && Object.keys(error.errors).length > 0) {
            // Show validation errors per field
            const errorList = document.createElement('ul');
            errorList.className = 'list-disc list-inside space-y-1 text-sm text-red-600 dark:text-red-400';

            for (const [field, messages] of Object.entries(error.errors)) {
                messages.forEach(msg => {
                    const li = document.createElement('li');
                    li.textContent = msg;
                    errorList.appendChild(li);
                });

                // Also highlight the specific field
                const input = formContent.querySelector(`[name="${field}"]`) as HTMLElement;
                if (input) {
                    input.classList.add('border-red-500', 'dark:border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                }
            }

            errorContainer.appendChild(errorList);
        } else if (error.message) {
            // Show general error message
            const errorText = document.createElement('p');
            errorText.className = 'text-sm text-red-600 dark:text-red-400';
            errorText.textContent = error.message;
            errorContainer.appendChild(errorText);
        }

        // Insert error at the top of the form content
        formContent.insertBefore(errorContainer, formContent.firstChild);

        // Scroll to show error
        errorContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    /**
     * Clear error messages from modal
     */
    private clearModalErrors(modal: HTMLElement | null): void {
        if (!modal) return;

        // Remove error container
        const errorContainer = modal.querySelector('.searchable-select-modal-errors');
        if (errorContainer) {
            errorContainer.remove();
        }

        // Remove error styling from inputs
        const inputs = modal.querySelectorAll('.border-red-500');
        inputs.forEach(input => {
            input.classList.remove('border-red-500', 'dark:border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
        });
    }

    // Public API
    public getValue(): string | string[] | null {
        return this.options.multiple ? this.selectedValues : this.selectedValues[0] || null;
    }

    public setValue(value: string | string[]): void {
        if (Array.isArray(value)) {
            this.selectedValues = value;
        } else {
            this.selectedValues = value ? [value] : [];
        }
        this.updateHiddenSelect();
        this.updateDisplay();
        this.updateOptionCheckmarks();
        this.dispatchChangeEvent();
    }

    public addOption(value: string, label: string): void {
        if (!this.hiddenSelect || !this.optionsList) return;

        // Add to hidden select
        const option = document.createElement('option');
        option.value = value;
        option.textContent = label;
        this.hiddenSelect.appendChild(option);

        // Add to dropdown
        const li = document.createElement('li');
        li.className = 'searchable-select-option px-3 py-2 text-sm cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-100';
        li.dataset.value = value;
        li.setAttribute('role', 'option');
        li.innerHTML = `
            <div class="flex items-center justify-between">
                <div class="flex-1 truncate">
                    <span class="searchable-select-option-label">${label}</span>
                </div>
                <span class="searchable-select-option-check hidden ms-2 text-primary-600 shrink-0">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </span>
            </div>
        `;
        li.addEventListener('click', () => this.selectOption(li));
        this.optionsList.appendChild(li);
    }

    public static initAll(): void {
        document.querySelectorAll<HTMLElement>('.select-field[data-searchable-select]').forEach((field) => {
            if (!field.dataset.initialized) {
                SelectManager.instances.set(field, new SelectManager(field));
            }
        });
    }

    public static getInstance(field: HTMLElement): SelectManager | undefined {
        return SelectManager.instances.get(field);
    }
}

// Expose globally for window.initSearchableSelects compatibility
if (typeof window !== 'undefined') {
    (window as any).initSearchableSelects = () => SelectManager.initAll();
}
