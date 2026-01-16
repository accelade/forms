/**
 * Icon Picker Component Manager
 * Supports multiple icon sets: Emoji, Boxicons, Heroicons, Lucide
 */

export class IconPickerManager {
    private static instances = new WeakMap<HTMLElement, IconPickerManager>();

    private wrapper: HTMLElement;
    private trigger: HTMLButtonElement | null;
    private dropdown: HTMLElement | null;
    private searchInput: HTMLInputElement | null;
    private items: NodeListOf<HTMLElement>;
    private setTabs: NodeListOf<HTMLElement>;
    private setPanels: NodeListOf<HTMLElement>;
    private categoryTabs: NodeListOf<HTMLElement>;
    private categoryPanels: NodeListOf<HTMLElement>;
    private hiddenInput: HTMLInputElement | null;
    private selectedDisplay: HTMLElement | null;
    private selectedNameDisplay: HTMLElement | null;
    private placeholder: HTMLElement | null;
    private emptyState: HTMLElement | null;
    private isOpen: boolean = false;
    private activeSet: string = '';
    private activeCategories: Map<string, string> = new Map();

    constructor(wrapper: HTMLElement) {
        this.wrapper = wrapper;
        this.trigger = wrapper.querySelector('.icon-picker-trigger');
        this.dropdown = wrapper.querySelector('.icon-picker-dropdown');
        this.searchInput = wrapper.querySelector('.icon-picker-search-input');
        this.items = wrapper.querySelectorAll('.icon-picker-item');
        this.setTabs = wrapper.querySelectorAll('.icon-picker-set-tab');
        this.setPanels = wrapper.querySelectorAll('.icon-picker-set-panel');
        this.categoryTabs = wrapper.querySelectorAll('.icon-picker-category-tab');
        this.categoryPanels = wrapper.querySelectorAll('.icon-picker-category-panel');
        this.hiddenInput = wrapper.querySelector('.icon-picker-value');
        this.selectedDisplay = wrapper.querySelector('.icon-picker-selected');
        this.selectedNameDisplay = wrapper.querySelector('.icon-picker-selected-name');
        this.placeholder = wrapper.querySelector('.icon-picker-placeholder');
        this.emptyState = wrapper.querySelector('.icon-picker-empty');

        // Set initial active set
        this.activeSet = wrapper.dataset.defaultSet || 'emoji';

        // Initialize active categories for each set
        this.setPanels.forEach(panel => {
            const setName = panel.dataset.set || '';
            const firstCategoryTab = panel.querySelector('.icon-picker-category-tab');
            if (firstCategoryTab) {
                this.activeCategories.set(setName, (firstCategoryTab as HTMLElement).dataset.category || '');
            }
        });

        this.init();
    }

    private init(): void {
        this.trigger?.addEventListener('click', () => this.toggle());

        this.searchInput?.addEventListener('input', () => this.filterItems());

        // Set tab clicks
        this.setTabs.forEach((tab) => {
            tab.addEventListener('click', () => {
                const set = tab.dataset.set;
                if (set) {
                    this.switchSet(set);
                }
            });
        });

        // Category tab clicks
        this.categoryTabs.forEach((tab) => {
            tab.addEventListener('click', () => {
                const category = tab.dataset.category;
                const set = tab.dataset.set;
                if (category && set) {
                    this.switchCategory(set, category);
                }
            });
        });

        // Icon item clicks
        this.items.forEach((item) => {
            item.addEventListener('click', () => {
                const icon = item.dataset.icon;
                const name = item.dataset.name;
                const type = item.dataset.type;
                if (icon) {
                    this.selectIcon(icon, name || '', type || 'emoji');
                }
            });
        });

        // Close on outside click
        document.addEventListener('click', (e) => {
            if (!this.wrapper.contains(e.target as Node)) {
                this.close();
            }
        });

        // Keyboard navigation
        this.wrapper.addEventListener('keydown', (e) => this.handleKeydown(e));
    }

    private switchSet(set: string): void {
        if (this.activeSet === set) return;
        this.activeSet = set;

        // Update set tab styles
        this.setTabs.forEach((tab) => {
            const isActive = tab.dataset.set === set;
            tab.classList.toggle('text-neutral-900', isActive);
            tab.classList.toggle('bg-white', isActive);
            tab.classList.toggle('border-b-2', isActive);
            tab.classList.toggle('border-neutral-900', isActive);
            tab.classList.toggle('-mb-px', isActive);
            tab.classList.toggle('dark:text-white', isActive);
            tab.classList.toggle('dark:bg-neutral-900', isActive);
            tab.classList.toggle('dark:border-white', isActive);
            tab.classList.toggle('text-neutral-500', !isActive);
        });

        // Show/hide set panels
        this.setPanels.forEach((panel) => {
            panel.classList.toggle('hidden', panel.dataset.set !== set);
        });

        // Clear search when switching sets
        if (this.searchInput) {
            this.searchInput.value = '';
            this.filterItems();
        }
    }

    private switchCategory(set: string, category: string): void {
        this.activeCategories.set(set, category);

        // Update category tab styles for this set
        this.categoryTabs.forEach((tab) => {
            if (tab.dataset.set !== set) return;
            const isActive = tab.dataset.category === category;
            tab.classList.toggle('text-neutral-900', isActive);
            tab.classList.toggle('border-b-2', isActive);
            tab.classList.toggle('border-neutral-400', isActive);
            tab.classList.toggle('dark:text-white', isActive);
            tab.classList.toggle('dark:border-neutral-500', isActive);
            tab.classList.toggle('text-neutral-500', !isActive);
        });

        // Show/hide category panels for this set
        this.categoryPanels.forEach((panel) => {
            if (panel.dataset.set !== set) return;
            panel.classList.toggle('hidden', panel.dataset.category !== category);
        });
    }

    private handleKeydown(e: KeyboardEvent): void {
        if (e.key === 'Escape') {
            this.close();
        }

        if (e.key === 'Enter' && this.isOpen) {
            const focusedItem = this.dropdown?.querySelector('.icon-picker-item:focus') as HTMLElement;
            if (focusedItem) {
                const icon = focusedItem.dataset.icon;
                const name = focusedItem.dataset.name;
                const type = focusedItem.dataset.type;
                if (icon) {
                    this.selectIcon(icon, name || '', type || 'emoji');
                }
            }
        }

        // Arrow key navigation
        if (this.isOpen && ['ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
            e.preventDefault();
            this.navigateWithArrows(e.key);
        }
    }

    private navigateWithArrows(direction: string): void {
        const visibleItems = Array.from(this.items).filter(item => !item.hidden && !item.closest('.hidden'));
        if (visibleItems.length === 0) return;

        const currentFocus = document.activeElement as HTMLElement;
        const currentIndex = visibleItems.indexOf(currentFocus);

        let nextIndex: number;
        const cols = 8; // Grid columns

        switch (direction) {
            case 'ArrowRight':
                nextIndex = currentIndex < visibleItems.length - 1 ? currentIndex + 1 : 0;
                break;
            case 'ArrowLeft':
                nextIndex = currentIndex > 0 ? currentIndex - 1 : visibleItems.length - 1;
                break;
            case 'ArrowDown':
                nextIndex = currentIndex + cols < visibleItems.length ? currentIndex + cols : currentIndex;
                break;
            case 'ArrowUp':
                nextIndex = currentIndex - cols >= 0 ? currentIndex - cols : currentIndex;
                break;
            default:
                return;
        }

        visibleItems[nextIndex]?.focus();
    }

    private filterItems(): void {
        const query = this.searchInput?.value.toLowerCase() || '';
        let visibleCount = 0;

        this.items.forEach((item) => {
            const name = item.dataset.name?.toLowerCase() || '';
            const icon = item.dataset.icon?.toLowerCase() || '';
            const matches = name.includes(query) || icon.includes(query);
            item.hidden = !matches;
            if (matches) visibleCount++;
        });

        // Show/hide empty state
        if (this.emptyState) {
            this.emptyState.classList.toggle('hidden', visibleCount > 0);
        }

        // When searching, show all panels to search across sets/categories
        if (query) {
            this.setPanels.forEach(panel => panel.classList.remove('hidden'));
            this.categoryPanels.forEach(panel => panel.classList.remove('hidden'));
        } else {
            // When not searching, show only active set and category
            this.setPanels.forEach(panel => {
                panel.classList.toggle('hidden', panel.dataset.set !== this.activeSet);
            });

            this.categoryPanels.forEach(panel => {
                const set = panel.dataset.set || '';
                const category = panel.dataset.category || '';
                const activeCategory = this.activeCategories.get(set) || '';
                const isActiveSet = set === this.activeSet;
                panel.classList.toggle('hidden', !isActiveSet || category !== activeCategory);
            });
        }
    }

    public toggle(): void {
        this.isOpen ? this.close() : this.open();
    }

    public open(): void {
        if (this.dropdown) {
            this.dropdown.hidden = false;
            this.isOpen = true;
            this.searchInput?.focus();

            // Rotate arrow
            const arrow = this.wrapper.querySelector('.icon-picker-arrow');
            arrow?.classList.add('rotate-180');
        }
    }

    public close(): void {
        if (this.dropdown) {
            this.dropdown.hidden = true;
            this.isOpen = false;

            // Reset arrow
            const arrow = this.wrapper.querySelector('.icon-picker-arrow');
            arrow?.classList.remove('rotate-180');
        }
    }

    public selectIcon(icon: string, name: string = '', type: string = 'emoji'): void {
        if (this.hiddenInput) {
            this.hiddenInput.value = icon;
            this.hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
        }

        if (this.selectedDisplay) {
            if (type === 'emoji') {
                this.selectedDisplay.textContent = icon;
                this.selectedDisplay.className = 'icon-picker-selected text-xl leading-none';
            } else {
                // For icon classes, render the icon element
                this.selectedDisplay.innerHTML = `<i class="${icon}"></i>`;
                this.selectedDisplay.className = 'icon-picker-selected text-lg';
            }
        }

        if (this.selectedNameDisplay) {
            this.selectedNameDisplay.textContent = name;
        }

        if (this.placeholder) {
            this.placeholder.hidden = true;
        }

        // Highlight selected item
        this.items.forEach((item) => {
            const isSelected = item.dataset.icon === icon;
            item.classList.toggle('is-selected', isSelected);
        });

        this.close();
    }

    public getValue(): string {
        return this.hiddenInput?.value || '';
    }

    public setValue(icon: string): void {
        // Find the item to get the name and type
        let name = '';
        let type = 'emoji';
        this.items.forEach((item) => {
            if (item.dataset.icon === icon) {
                name = item.dataset.name || '';
                type = item.dataset.type || 'emoji';
            }
        });
        this.selectIcon(icon, name, type);
    }

    public clear(): void {
        if (this.hiddenInput) {
            this.hiddenInput.value = '';
            this.hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
        }

        if (this.selectedDisplay) {
            this.selectedDisplay.textContent = '';
            this.selectedDisplay.innerHTML = '';
        }

        if (this.selectedNameDisplay) {
            this.selectedNameDisplay.textContent = '';
        }

        if (this.placeholder) {
            this.placeholder.hidden = false;
        }

        // Remove selection highlight
        this.items.forEach((item) => {
            item.classList.remove('is-selected');
        });
    }

    public getActiveSet(): string {
        return this.activeSet;
    }

    public setActiveSet(set: string): void {
        this.switchSet(set);
    }

    public static initAll(): void {
        document.querySelectorAll<HTMLElement>('.icon-picker-wrapper').forEach((wrapper) => {
            if (!wrapper.dataset.initialized) {
                wrapper.dataset.initialized = 'true';
                IconPickerManager.instances.set(wrapper, new IconPickerManager(wrapper));
            }
        });
    }

    public static getInstance(wrapper: HTMLElement): IconPickerManager | undefined {
        return IconPickerManager.instances.get(wrapper);
    }
}
