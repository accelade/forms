/**
 * Emoji Input Component Manager
 */

export class EmojiInputManager {
    private static instances = new WeakMap<HTMLElement, EmojiInputManager>();

    private wrapper: HTMLElement;
    private trigger: HTMLButtonElement | null;
    private dropdown: HTMLElement | null;
    private searchInput: HTMLInputElement | null;
    private items: NodeListOf<HTMLElement>;
    private tabs: NodeListOf<HTMLElement>;
    private panels: NodeListOf<HTMLElement>;
    private hiddenInput: HTMLInputElement | null;
    private selectedDisplay: HTMLElement | null;
    private selectedNameDisplay: HTMLElement | null;
    private placeholder: HTMLElement | null;
    private emptyState: HTMLElement | null;
    private previewArea: HTMLElement | null;
    private previewIcon: HTMLElement | null;
    private previewName: HTMLElement | null;
    private isOpen: boolean = false;
    private activeCategory: string = '';

    constructor(wrapper: HTMLElement) {
        this.wrapper = wrapper;
        this.trigger = wrapper.querySelector('.emoji-input-trigger');
        this.dropdown = wrapper.querySelector('.emoji-input-dropdown');
        this.searchInput = wrapper.querySelector('.emoji-input-search-input');
        this.items = wrapper.querySelectorAll('.emoji-input-item');
        this.tabs = wrapper.querySelectorAll('.emoji-input-tab');
        this.panels = wrapper.querySelectorAll('.emoji-input-panel');
        this.hiddenInput = wrapper.querySelector('.emoji-input-value');
        this.selectedDisplay = wrapper.querySelector('.emoji-input-selected');
        this.selectedNameDisplay = wrapper.querySelector('.emoji-input-selected-name');
        this.placeholder = wrapper.querySelector('.emoji-input-placeholder');
        this.emptyState = wrapper.querySelector('.emoji-input-empty');
        this.previewArea = wrapper.querySelector('.emoji-input-preview');
        this.previewIcon = wrapper.querySelector('.emoji-preview-icon');
        this.previewName = wrapper.querySelector('.emoji-preview-name');

        // Set initial active category
        const firstTab = this.tabs[0];
        if (firstTab) {
            this.activeCategory = firstTab.dataset.category || '';
        }

        this.init();
    }

    private init(): void {
        this.trigger?.addEventListener('click', () => this.toggle());

        this.searchInput?.addEventListener('input', () => this.filterItems());

        // Category tab clicks
        this.tabs.forEach((tab) => {
            tab.addEventListener('click', () => {
                const category = tab.dataset.category;
                if (category) {
                    this.switchCategory(category);
                }
            });
        });

        // Emoji item clicks and hover
        this.items.forEach((item) => {
            item.addEventListener('click', () => {
                const emoji = item.dataset.emoji;
                const name = item.dataset.name;
                if (emoji) {
                    this.selectEmoji(emoji, name || '');
                }
            });

            // Preview on hover
            item.addEventListener('mouseenter', () => {
                const emoji = item.dataset.emoji;
                const name = item.dataset.name;
                if (emoji) {
                    this.showPreview(emoji, name || '');
                }
            });

            item.addEventListener('mouseleave', () => {
                this.hidePreview();
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

    private showPreview(emoji: string, name: string): void {
        if (this.previewArea && this.previewIcon && this.previewName) {
            this.previewArea.classList.remove('hidden');
            this.previewIcon.textContent = emoji;
            this.previewName.textContent = name;
        }
    }

    private hidePreview(): void {
        if (this.previewArea) {
            this.previewArea.classList.add('hidden');
        }
    }

    private switchCategory(category: string): void {
        this.activeCategory = category;

        // Update tab styles
        this.tabs.forEach((tab) => {
            const isActive = tab.dataset.category === category;
            tab.classList.toggle('bg-white', isActive);
            tab.classList.toggle('border-b-2', isActive);
            tab.classList.toggle('border-primary-600', isActive);
            tab.classList.toggle('dark:bg-gray-800', isActive);
            tab.classList.toggle('dark:border-primary-400', isActive);
            tab.classList.toggle('text-gray-500', !isActive);
        });

        // Show/hide panels
        this.panels.forEach((panel) => {
            panel.classList.toggle('hidden', panel.dataset.category !== category);
        });

        // Clear search when switching categories
        if (this.searchInput) {
            this.searchInput.value = '';
            this.filterItems();
        }
    }

    private handleKeydown(e: KeyboardEvent): void {
        if (e.key === 'Escape') {
            this.close();
        }

        if (e.key === 'Enter' && this.isOpen) {
            const focusedItem = this.dropdown?.querySelector('.emoji-input-item:focus') as HTMLElement;
            if (focusedItem) {
                const emoji = focusedItem.dataset.emoji;
                const name = focusedItem.dataset.name;
                if (emoji) {
                    this.selectEmoji(emoji, name || '');
                }
            }
        }
    }

    private filterItems(): void {
        const query = this.searchInput?.value.toLowerCase() || '';
        let visibleCount = 0;

        this.items.forEach((item) => {
            const name = item.dataset.name?.toLowerCase() || '';
            const emoji = item.dataset.emoji || '';
            const matches = name.includes(query) || emoji.includes(query);
            item.hidden = !matches;
            if (matches) visibleCount++;
        });

        // Show/hide empty state
        if (this.emptyState) {
            this.emptyState.classList.toggle('hidden', visibleCount > 0);
        }

        // When searching, show all panels to search across categories
        if (query) {
            this.panels.forEach((panel) => {
                panel.classList.remove('hidden');
            });
        } else {
            // When not searching, show only active category
            this.panels.forEach((panel) => {
                panel.classList.toggle('hidden', panel.dataset.category !== this.activeCategory);
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
        }
    }

    public close(): void {
        if (this.dropdown) {
            this.dropdown.hidden = true;
            this.isOpen = false;
            this.hidePreview();
        }
    }

    public selectEmoji(emoji: string, name: string = ''): void {
        if (this.hiddenInput) {
            this.hiddenInput.value = emoji;
            this.hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
        }

        if (this.selectedDisplay) {
            this.selectedDisplay.textContent = emoji;
        }

        if (this.selectedNameDisplay) {
            this.selectedNameDisplay.textContent = name;
        }

        if (this.placeholder) {
            this.placeholder.hidden = true;
        }

        // Highlight selected item
        this.items.forEach((item) => {
            const isSelected = item.dataset.emoji === emoji;
            item.classList.toggle('bg-primary-100', isSelected);
            item.classList.toggle('dark:bg-primary-900/30', isSelected);
        });

        this.close();
    }

    public getValue(): string {
        return this.hiddenInput?.value || '';
    }

    public setValue(emoji: string): void {
        // Find the item to get the name
        let name = '';
        this.items.forEach((item) => {
            if (item.dataset.emoji === emoji) {
                name = item.dataset.name || '';
            }
        });
        this.selectEmoji(emoji, name);
    }

    public clear(): void {
        if (this.hiddenInput) {
            this.hiddenInput.value = '';
            this.hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
        }

        if (this.selectedDisplay) {
            this.selectedDisplay.textContent = '';
        }

        if (this.selectedNameDisplay) {
            this.selectedNameDisplay.textContent = '';
        }

        if (this.placeholder) {
            this.placeholder.hidden = false;
        }

        // Remove selection highlight
        this.items.forEach((item) => {
            item.classList.remove('bg-primary-100', 'dark:bg-primary-900/30');
        });
    }

    public static initAll(): void {
        document.querySelectorAll<HTMLElement>('.emoji-input-wrapper').forEach((wrapper) => {
            if (!wrapper.dataset.initialized) {
                wrapper.dataset.initialized = 'true';
                EmojiInputManager.instances.set(wrapper, new EmojiInputManager(wrapper));
            }
        });
    }

    public static getInstance(wrapper: HTMLElement): EmojiInputManager | undefined {
        return EmojiInputManager.instances.get(wrapper);
    }
}
