/**
 * Icon Picker Component Manager
 * Supports multiple icon sets: Emoji, Boxicons, Heroicons, Lucide, and Blade Icons
 */

interface BladeIconSet {
    name: string;
    prefix: string;
    count: number;
}

interface BladeIcon {
    name: string;
    fullName: string;
    svg: string;
    set: string;
    prefix: string;
}

interface BladeIconsResponse {
    icons: BladeIcon[];
    total: number;
    hasMore: boolean;
}

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
    private selectionContainer: HTMLElement | null;
    private selectedDisplay: HTMLElement | null;
    private selectedNameDisplay: HTMLElement | null;
    private placeholder: HTMLElement | null;
    private emptyState: HTMLElement | null;
    private isOpen: boolean = false;
    private activeSet: string = '';
    private activeCategories: Map<string, string> = new Map();

    // Blade Icons specific properties
    private isBladeIconsMode: boolean = false;
    private iconsApiEndpoint: string = '';
    private searchApiEndpoint: string = '';
    private perPage: number = 48;
    private isLoading: boolean = false;
    private currentOffset: number = 0;
    private hasMoreIcons: boolean = true;
    private bladeIconSets: BladeIconSet[] = [];
    private iconsContainer: HTMLElement | null = null;
    private loadingIndicator: HTMLElement | null = null;
    private searchDebounceTimer: number | null = null;
    private currentSearchQuery: string = '';

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
        this.selectionContainer = wrapper.querySelector('.icon-picker-selection');
        this.selectedDisplay = wrapper.querySelector('.icon-picker-selected');
        this.selectedNameDisplay = wrapper.querySelector('.icon-picker-selected-name');
        this.placeholder = wrapper.querySelector('.icon-picker-placeholder');
        this.emptyState = wrapper.querySelector('.icon-picker-empty');
        this.iconsContainer = wrapper.querySelector('.icon-picker-icons-container');
        this.loadingIndicator = wrapper.querySelector('.icon-picker-loading');

        // Check if Blade Icons mode is enabled
        this.isBladeIconsMode = wrapper.dataset.bladeIcons === 'true';
        this.iconsApiEndpoint = wrapper.dataset.iconsApi || '';
        this.searchApiEndpoint = wrapper.dataset.searchApi || '';
        this.perPage = parseInt(wrapper.dataset.perPage || '48', 10);

        // Set initial active set
        this.activeSet = wrapper.dataset.defaultSet || (this.isBladeIconsMode ? '' : 'emoji');

        // Initialize active categories for each set (non-Blade Icons mode)
        if (!this.isBladeIconsMode) {
            this.setPanels.forEach(panel => {
                const setName = panel.dataset.set || '';
                const firstCategoryTab = panel.querySelector('.icon-picker-category-tab');
                if (firstCategoryTab) {
                    this.activeCategories.set(setName, (firstCategoryTab as HTMLElement).dataset.category || '');
                }
            });
        }

        // Store manager instance on wrapper for debugging and external access
        (wrapper as any)._iconPickerManager = this;

        this.init();
    }

    private init(): void {
        this.trigger?.addEventListener('click', () => this.toggle());

        this.searchInput?.addEventListener('input', () => {
            if (this.isBladeIconsMode) {
                this.handleBladeIconsSearch();
            } else {
                this.filterItems();
            }
        });

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

        // Icon item clicks (for non-Blade Icons mode)
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

        // Initialize Blade Icons mode
        if (this.isBladeIconsMode) {
            this.initBladeIconsMode();
        }
    }

    private scrollHandler: (() => void) | null = null;
    private intersectionObserver: IntersectionObserver | null = null;

    private async initBladeIconsMode(): Promise<void> {
        // Setup infinite scroll handler
        this.scrollHandler = () => this.handleInfiniteScroll();

        // Load icon sets (icons will be loaded when dropdown opens)
    }

    private attachScrollListener(): void {
        if (!this.iconsContainer) return;

        // Use both scroll event and intersection observer for reliability
        if (this.scrollHandler) {
            this.iconsContainer.removeEventListener('scroll', this.scrollHandler);
            this.iconsContainer.addEventListener('scroll', this.scrollHandler, { passive: true });
        }

        // Setup intersection observer on loading indicator for more reliable detection
        if (this.loadingIndicator && !this.intersectionObserver) {
            this.intersectionObserver = new IntersectionObserver(
                (entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting && !this.isLoading && this.hasMoreIcons) {
                            this.loadIcons(this.activeSet, this.currentOffset, true);
                        }
                    });
                },
                {
                    root: this.iconsContainer,
                    rootMargin: '100px',
                    threshold: 0
                }
            );
            this.intersectionObserver.observe(this.loadingIndicator);
        }
    }

    private async loadBladeIconSets(): Promise<void> {
        if (!this.iconsApiEndpoint) return;

        try {
            const response = await fetch(this.iconsApiEndpoint.replace(/\/[^/]+$/, '/sets'));
            if (!response.ok) throw new Error('Failed to load icon sets');

            const data = await response.json();
            this.bladeIconSets = data.sets || [];

            // Render set tabs
            this.renderSetTabs();

            // Load icons for first set
            if (this.bladeIconSets.length > 0) {
                this.activeSet = this.bladeIconSets[0].name;
                await this.loadIcons(this.activeSet, 0);
            }
        } catch (error) {
            console.error('Failed to load Blade Icon sets:', error);
        }
    }

    private renderSetTabs(): void {
        const tabsContainer = this.wrapper.querySelector('.icon-picker-set-tabs');
        if (!tabsContainer || this.bladeIconSets.length === 0) return;

        tabsContainer.innerHTML = this.bladeIconSets.map((set, index) => `
            <button type="button"
                class="icon-picker-set-tab px-3 py-2 text-sm font-medium whitespace-nowrap transition-colors
                    ${index === 0 ? 'text-neutral-900 bg-white border-b-2 border-neutral-900 -mb-px dark:text-white dark:bg-neutral-900 dark:border-white' : 'text-neutral-500 hover:text-neutral-700 dark:hover:text-neutral-300'}"
                data-set="${set.name}"
                title="${set.count} icons">
                ${this.formatSetName(set.name)}
            </button>
        `).join('');

        // Re-bind tab click events
        this.setTabs = this.wrapper.querySelectorAll('.icon-picker-set-tab');
        this.setTabs.forEach((tab) => {
            tab.addEventListener('click', () => {
                const set = (tab as HTMLElement).dataset.set;
                if (set) {
                    this.switchSet(set);
                }
            });
        });
    }

    private formatSetName(name: string): string {
        // Convert kebab-case to Title Case
        return name.split('-').map(word =>
            word.charAt(0).toUpperCase() + word.slice(1)
        ).join(' ');
    }

    private async loadIcons(set: string, offset: number = 0, append: boolean = false): Promise<void> {
        if (this.isLoading || !this.iconsApiEndpoint) return;

        this.isLoading = true;
        this.showLoading(true);

        try {
            const url = new URL(this.iconsApiEndpoint.replace(/\/[^/]+$/, `/${set}`), window.location.origin);
            url.searchParams.set('offset', offset.toString());
            url.searchParams.set('limit', this.perPage.toString());

            if (this.currentSearchQuery) {
                url.searchParams.set('search', this.currentSearchQuery);
            }

            const response = await fetch(url.toString());
            if (!response.ok) throw new Error('Failed to load icons');

            const data: BladeIconsResponse = await response.json();

            this.hasMoreIcons = data.hasMore;
            this.currentOffset = offset + data.icons.length;

            this.renderIcons(data.icons, append);

            // Hide loading sentinel when no more icons
            if (!this.hasMoreIcons && this.loadingIndicator) {
                this.loadingIndicator.classList.add('hidden');
            } else if (this.loadingIndicator) {
                this.loadingIndicator.classList.remove('hidden');
            }
        } catch (error) {
            console.error('Failed to load icons:', error);
        } finally {
            this.isLoading = false;
            this.showLoading(false);
        }
    }

    private async searchIcons(query: string): Promise<void> {
        if (this.isLoading || !this.searchApiEndpoint) return;

        this.isLoading = true;
        this.showLoading(true);

        try {
            const url = new URL(this.searchApiEndpoint, window.location.origin);
            url.searchParams.set('q', query);
            url.searchParams.set('limit', this.perPage.toString());

            if (this.activeSet) {
                url.searchParams.set('set', this.activeSet);
            }

            const response = await fetch(url.toString());
            if (!response.ok) throw new Error('Failed to search icons');

            const data: BladeIconsResponse = await response.json();

            this.hasMoreIcons = data.hasMore;
            this.currentOffset = data.icons.length;

            this.renderIcons(data.icons, false);
        } catch (error) {
            console.error('Failed to search icons:', error);
        } finally {
            this.isLoading = false;
            this.showLoading(false);
        }
    }

    private renderIcons(icons: BladeIcon[], append: boolean = false): void {
        if (!this.iconsContainer) return;

        // Find the grid inside the container
        const grid = this.iconsContainer.querySelector('.icon-picker-grid') || this.iconsContainer;

        const html = icons.map(icon => `
            <button type="button"
                class="icon-picker-item flex items-center justify-center p-2 rounded-md hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500"
                data-icon="${icon.fullName}"
                data-name="${icon.name}"
                data-type="blade"
                title="${icon.name}">
                <span class="w-6 h-6 flex items-center justify-center text-neutral-700 dark:text-neutral-300 [&>svg]:w-full [&>svg]:h-full">
                    ${icon.svg}
                </span>
            </button>
        `).join('');

        if (append) {
            grid.insertAdjacentHTML('beforeend', html);
        } else {
            grid.innerHTML = html;
        }

        // Re-bind click events for new icons
        const newItems = grid.querySelectorAll('.icon-picker-item');
        newItems.forEach((item) => {
            // Remove existing listener to prevent duplicates
            const el = item as HTMLElement;
            el.onclick = () => {
                const icon = el.dataset.icon;
                const name = el.dataset.name;
                const type = el.dataset.type;
                if (icon) {
                    this.selectIcon(icon, name || '', type || 'blade');
                }
            };
        });

        // Update empty state
        if (this.emptyState) {
            this.emptyState.classList.toggle('hidden', icons.length > 0);
        }
    }

    private handleInfiniteScroll(): void {
        if (!this.iconsContainer || this.isLoading || !this.hasMoreIcons) return;

        const scrollElement = this.iconsContainer;
        const scrollTop = scrollElement.scrollTop;
        const scrollHeight = scrollElement.scrollHeight;
        const clientHeight = scrollElement.clientHeight;
        const threshold = 150; // px from bottom

        // Check if we're near the bottom
        if (scrollTop + clientHeight >= scrollHeight - threshold) {
            this.loadIcons(this.activeSet, this.currentOffset, true);
        }
    }

    private handleBladeIconsSearch(): void {
        const query = this.searchInput?.value.trim() || '';

        // Debounce search
        if (this.searchDebounceTimer) {
            window.clearTimeout(this.searchDebounceTimer);
        }

        this.searchDebounceTimer = window.setTimeout(() => {
            this.currentSearchQuery = query;
            this.currentOffset = 0;
            this.hasMoreIcons = true;

            if (query) {
                this.searchIcons(query);
            } else {
                this.loadIcons(this.activeSet, 0);
            }
        }, 300);
    }

    private showLoading(show: boolean): void {
        if (this.loadingIndicator) {
            // Show/hide the spinner and text inside the loading element
            const spinner = this.loadingIndicator.querySelector('.icon-picker-loading-spinner');
            const text = this.loadingIndicator.querySelector('.icon-picker-loading-text');
            spinner?.classList.toggle('hidden', !show);
            text?.classList.toggle('hidden', !show);
        }
    }

    private switchSet(set: string): void {
        if (this.activeSet === set) return;
        this.activeSet = set;

        // Update set tab styles
        this.setTabs.forEach((tab) => {
            const isActive = (tab as HTMLElement).dataset.set === set;
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

        if (this.isBladeIconsMode) {
            // Reset and load icons for new set
            this.currentOffset = 0;
            this.hasMoreIcons = true;
            this.loadIcons(set, 0);
        } else {
            // Show/hide set panels
            this.setPanels.forEach((panel) => {
                panel.classList.toggle('hidden', panel.dataset.set !== set);
            });
        }

        // Clear search when switching sets
        if (this.searchInput) {
            this.searchInput.value = '';
            this.currentSearchQuery = '';
            if (!this.isBladeIconsMode) {
                this.filterItems();
            }
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
        const container = this.isBladeIconsMode ? this.iconsContainer : this.dropdown;
        if (!container) return;

        const visibleItems = Array.from(container.querySelectorAll('.icon-picker-item:not([hidden])')) as HTMLElement[];
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

            // If Blade Icons mode
            if (this.isBladeIconsMode) {
                // Attach scroll listener for infinite scroll
                this.attachScrollListener();

                // If no icons loaded yet, load them now
                if (this.currentOffset === 0 && !this.isLoading) {
                    const grid = this.iconsContainer?.querySelector('.icon-picker-grid');
                    if (grid && grid.children.length === 0) {
                        this.loadBladeIconSets();
                    }
                }
            }
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
                this.selectedDisplay.className = 'icon-picker-selected flex items-center justify-center w-6 h-6 text-xl leading-none';
            } else if (type === 'blade') {
                // For Blade Icons, fetch and display the SVG
                this.loadSelectedIconSvg(icon);
            } else {
                // For icon classes, render the icon element
                this.selectedDisplay.innerHTML = `<i class="${icon}"></i>`;
                this.selectedDisplay.className = 'icon-picker-selected flex items-center justify-center w-6 h-6 text-lg';
            }
        }

        if (this.selectedNameDisplay) {
            this.selectedNameDisplay.textContent = name;
        }

        // Show selection container and hide placeholder
        if (this.selectionContainer) {
            this.selectionContainer.classList.remove('hidden');
        }
        if (this.placeholder) {
            this.placeholder.classList.add('hidden');
        }

        // Highlight selected item
        const container = this.isBladeIconsMode ? this.iconsContainer : this.dropdown;
        container?.querySelectorAll('.icon-picker-item').forEach((item) => {
            const isSelected = (item as HTMLElement).dataset.icon === icon;
            item.classList.toggle('is-selected', isSelected);
            item.classList.toggle('ring-2', isSelected);
            item.classList.toggle('ring-primary-500', isSelected);
            item.classList.toggle('bg-primary-50', isSelected);
            item.classList.toggle('dark:bg-primary-900/20', isSelected);
        });

        this.close();
    }

    private async loadSelectedIconSvg(icon: string): Promise<void> {
        if (!this.selectedDisplay || !this.iconsApiEndpoint) return;

        try {
            // Build SVG URL: replace /:set or /{set} placeholder with /svg/{icon}
            const baseUrl = this.iconsApiEndpoint.replace(/\/:[^/]+$|\/\{[^}]+\}$/, '');
            const svgUrl = `${baseUrl}/svg/${encodeURIComponent(icon)}`;
            const response = await fetch(svgUrl);

            if (!response.ok) throw new Error('Failed to load icon SVG');

            const data = await response.json();
            this.selectedDisplay.innerHTML = data.svg;
            this.selectedDisplay.className = 'icon-picker-selected flex items-center justify-center w-6 h-6 [&>svg]:w-full [&>svg]:h-full';
        } catch (error) {
            console.error('Failed to load selected icon SVG:', error);
            // Fallback to icon name
            this.selectedDisplay.textContent = icon.split(':').pop() || icon;
            this.selectedDisplay.className = 'icon-picker-selected flex items-center justify-center w-6 h-6 text-xs';
        }
    }

    public getValue(): string {
        return this.hiddenInput?.value || '';
    }

    public setValue(icon: string): void {
        if (this.isBladeIconsMode) {
            // For Blade Icons, just set the value and load the SVG
            const name = icon.split(':').pop() || icon;
            this.selectIcon(icon, name, 'blade');
        } else {
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

        // Hide selection container and show placeholder
        if (this.selectionContainer) {
            this.selectionContainer.classList.add('hidden');
        }
        if (this.placeholder) {
            this.placeholder.classList.remove('hidden');
        }

        // Remove selection highlight
        const container = this.isBladeIconsMode ? this.iconsContainer : this.dropdown;
        container?.querySelectorAll('.icon-picker-item').forEach((item) => {
            item.classList.remove('is-selected', 'ring-2', 'ring-primary-500', 'bg-primary-50', 'dark:bg-primary-900/20');
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
