/**
 * Media Browser Manager
 *
 * WordPress-style media browser/picker for selecting existing files from
 * the server. Supports Spatie Media Library collections organized by
 * Model -> Collection folders.
 *
 * Features:
 * - Modal dialog for browsing existing media
 * - Folder structure (Model -> Collection)
 * - Grid/List view toggle
 * - Search functionality
 * - File selection (single/multiple)
 * - Upload from browser
 * - Preview panel
 */

export interface MediaItem {
    id: number | string;
    name: string;
    file_name: string;
    mime_type: string;
    size: number;
    human_readable_size: string;
    url: string;
    preview_url?: string;
    thumbnail_url?: string;
    collection_name?: string;
    model_type?: string;
    model_id?: number;
    custom_properties?: Record<string, unknown>;
    created_at: string;
    updated_at: string;
}

export interface MediaCollection {
    name: string;
    count: number;
    model_type?: string;
    model_label?: string;
}

export interface MediaBrowserConfig {
    /** API endpoint for fetching media */
    endpoint: string;
    /** Upload token for security */
    token: string;
    /** Allow multiple selection */
    multiple?: boolean;
    /** Maximum selections allowed */
    maxSelection?: number;
    /** Accepted MIME types filter */
    acceptedTypes?: string[];
    /** Default collection to show */
    defaultCollection?: string;
    /** Default model type filter */
    defaultModelType?: string;
    /** Enable upload from browser */
    allowUpload?: boolean;
    /** Grid or list view */
    defaultView?: 'grid' | 'list';
    /** Items per page */
    perPage?: number;
    /** Callback when selection is confirmed */
    onSelect?: (items: MediaItem[]) => void;
    /** Callback when modal is closed */
    onClose?: () => void;
}

interface MediaBrowserState {
    isOpen: boolean;
    view: 'grid' | 'list';
    loading: boolean;
    items: MediaItem[];
    collections: MediaCollection[];
    selectedItems: MediaItem[];
    currentCollection: string | null;
    currentModelType: string | null;
    searchQuery: string;
    currentPage: number;
    totalPages: number;
    totalItems: number;
    previewItem: MediaItem | null;
}

export class MediaBrowserManager {
    /** Static method to initialize all media browser components */
    static initAll: () => void;

    private config: MediaBrowserConfig;
    private state: MediaBrowserState;
    private modal: HTMLElement | null = null;
    private abortController: AbortController | null = null;

    constructor(config: MediaBrowserConfig) {
        this.config = {
            multiple: false,
            maxSelection: 1,
            acceptedTypes: [],
            allowUpload: true,
            defaultView: 'grid',
            perPage: 24,
            ...config,
        };

        this.state = {
            isOpen: false,
            view: this.config.defaultView || 'grid',
            loading: false,
            items: [],
            collections: [],
            selectedItems: [],
            currentCollection: this.config.defaultCollection || null,
            currentModelType: this.config.defaultModelType || null,
            searchQuery: '',
            currentPage: 1,
            totalPages: 1,
            totalItems: 0,
            previewItem: null,
        };
    }

    /**
     * Open the media browser modal
     */
    public open(): void {
        if (this.state.isOpen) {
            return;
        }

        this.state.isOpen = true;
        this.createModal();
        this.loadCollections();
        this.loadMedia();
        document.body.classList.add('overflow-hidden');
    }

    /**
     * Close the media browser modal
     */
    public close(): void {
        if (!this.state.isOpen) {
            return;
        }

        this.state.isOpen = false;
        this.destroyModal();
        document.body.classList.remove('overflow-hidden');
        this.config.onClose?.();
    }

    /**
     * Get currently selected items
     */
    public getSelection(): MediaItem[] {
        return [...this.state.selectedItems];
    }

    /**
     * Clear selection
     */
    public clearSelection(): void {
        this.state.selectedItems = [];
        this.updateSelectionUI();
    }

    /**
     * Create the modal DOM structure
     */
    private createModal(): void {
        this.modal = document.createElement('div');
        this.modal.className = 'fixed inset-0 z-50 flex items-center justify-center';
        this.modal.innerHTML = this.renderModal();
        document.body.appendChild(this.modal);

        this.bindEvents();
    }

    /**
     * Destroy the modal
     */
    private destroyModal(): void {
        if (this.modal) {
            this.modal.remove();
            this.modal = null;
        }

        if (this.abortController) {
            this.abortController.abort();
            this.abortController = null;
        }
    }

    /**
     * Render the modal HTML
     */
    private renderModal(): string {
        return `
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" data-action="close"></div>

            <!-- Modal Content -->
            <div class="relative bg-white dark:bg-gray-900 rounded-xl shadow-2xl w-full max-w-6xl max-h-[90vh] mx-4 flex flex-col overflow-hidden">
                <!-- Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Media Browser</h2>
                    <div class="flex items-center gap-4">
                        <!-- Search -->
                        <div class="relative">
                            <input
                                type="text"
                                data-search-input
                                placeholder="Search files..."
                                class="pl-10 pr-4 py-2 w-64 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>

                        <!-- View Toggle -->
                        <div class="flex items-center gap-1 bg-gray-100 dark:bg-gray-800 rounded-lg p-1">
                            <button
                                data-view="grid"
                                class="p-2 rounded ${this.state.view === 'grid' ? 'bg-white dark:bg-gray-700 shadow' : 'hover:bg-gray-200 dark:hover:bg-gray-700'}"
                                title="Grid view"
                            >
                                <svg class="w-4 h-4 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                </svg>
                            </button>
                            <button
                                data-view="list"
                                class="p-2 rounded ${this.state.view === 'list' ? 'bg-white dark:bg-gray-700 shadow' : 'hover:bg-gray-200 dark:hover:bg-gray-700'}"
                                title="List view"
                            >
                                <svg class="w-4 h-4 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                            </button>
                        </div>

                        <!-- Close Button -->
                        <button data-action="close" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Body -->
                <div class="flex flex-1 overflow-hidden">
                    <!-- Sidebar - Collections -->
                    <div class="w-64 border-r border-gray-200 dark:border-gray-700 overflow-y-auto flex-shrink-0">
                        <div class="p-4">
                            <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Collections</h3>
                            <nav data-collections-list class="space-y-1">
                                <!-- Collections will be loaded here -->
                                <div class="animate-pulse space-y-2">
                                    <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded"></div>
                                    <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded"></div>
                                    <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded"></div>
                                </div>
                            </nav>
                        </div>
                    </div>

                    <!-- Main Content -->
                    <div class="flex-1 flex flex-col overflow-hidden">
                        <!-- Media Grid/List -->
                        <div class="flex-1 overflow-y-auto p-4" data-media-container>
                            <div data-media-grid class="${this.state.view === 'grid' ? 'grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4' : 'space-y-2'}">
                                <!-- Media items will be loaded here -->
                                <div class="col-span-full flex items-center justify-center py-12">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between" data-pagination>
                            <div class="text-sm text-gray-500 dark:text-gray-400" data-pagination-info>
                                Loading...
                            </div>
                            <div class="flex items-center gap-2" data-pagination-buttons>
                                <!-- Pagination buttons will be rendered here -->
                            </div>
                        </div>
                    </div>

                    <!-- Preview Panel -->
                    <div class="w-80 border-l border-gray-200 dark:border-gray-700 overflow-y-auto flex-shrink-0 hidden" data-preview-panel>
                        <!-- Preview content will be rendered here -->
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex items-center justify-between px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <div class="text-sm text-gray-500 dark:text-gray-400" data-selection-count>
                        No items selected
                    </div>
                    <div class="flex items-center gap-3">
                        ${this.config.allowUpload ? `
                            <button
                                data-action="upload"
                                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
                            >
                                Upload New
                            </button>
                        ` : ''}
                        <button
                            data-action="cancel"
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                        >
                            Cancel
                        </button>
                        <button
                            data-action="select"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            disabled
                        >
                            Select
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    /**
     * Bind event listeners
     */
    private bindEvents(): void {
        if (!this.modal) {
            return;
        }

        // Close actions
        this.modal.querySelectorAll('[data-action="close"], [data-action="cancel"]').forEach((el) => {
            el.addEventListener('click', () => this.close());
        });

        // Select action
        const selectBtn = this.modal.querySelector('[data-action="select"]');
        selectBtn?.addEventListener('click', () => {
            this.config.onSelect?.(this.state.selectedItems);
            this.close();
        });

        // Upload action
        const uploadBtn = this.modal.querySelector('[data-action="upload"]');
        uploadBtn?.addEventListener('click', () => this.openUploadDialog());

        // View toggle
        this.modal.querySelectorAll('[data-view]').forEach((el) => {
            el.addEventListener('click', () => {
                const view = el.getAttribute('data-view') as 'grid' | 'list';
                this.setView(view);
            });
        });

        // Search input
        const searchInput = this.modal.querySelector('[data-search-input]') as HTMLInputElement;
        let searchTimeout: ReturnType<typeof setTimeout>;
        searchInput?.addEventListener('input', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.state.searchQuery = searchInput.value;
                this.state.currentPage = 1;
                this.loadMedia();
            }, 300);
        });

        // Escape key to close
        const escHandler = (e: KeyboardEvent) => {
            if (e.key === 'Escape') {
                this.close();
                document.removeEventListener('keydown', escHandler);
            }
        };
        document.addEventListener('keydown', escHandler);
    }

    /**
     * Load collections from server
     */
    private async loadCollections(): Promise<void> {
        try {
            const response = await fetch(`${this.config.endpoint}/collections`, {
                headers: {
                    'X-Upload-Token': this.config.token,
                    Accept: 'application/json',
                },
            });

            if (!response.ok) {
                throw new Error('Failed to load collections');
            }

            const data = await response.json();
            this.state.collections = data.collections || [];
            this.renderCollections();
        } catch (error) {
            console.error('Error loading collections:', error);
            this.renderCollectionsError();
        }
    }

    /**
     * Load media items from server
     */
    private async loadMedia(): Promise<void> {
        // Cancel any pending request
        if (this.abortController) {
            this.abortController.abort();
        }
        this.abortController = new AbortController();

        this.state.loading = true;
        this.renderLoadingState();

        try {
            const params = new URLSearchParams({
                page: String(this.state.currentPage),
                per_page: String(this.config.perPage),
            });

            if (this.state.searchQuery) {
                params.append('search', this.state.searchQuery);
            }

            if (this.state.currentCollection) {
                params.append('collection', this.state.currentCollection);
            }

            if (this.state.currentModelType) {
                params.append('model_type', this.state.currentModelType);
            }

            if (this.config.acceptedTypes && this.config.acceptedTypes.length > 0) {
                params.append('mime_types', this.config.acceptedTypes.join(','));
            }

            const response = await fetch(`${this.config.endpoint}?${params}`, {
                headers: {
                    'X-Upload-Token': this.config.token,
                    Accept: 'application/json',
                },
                signal: this.abortController.signal,
            });

            if (!response.ok) {
                throw new Error('Failed to load media');
            }

            const data = await response.json();
            this.state.items = data.items || [];
            this.state.totalPages = data.total_pages || 1;
            this.state.totalItems = data.total || 0;
            this.state.loading = false;

            this.renderMediaItems();
            this.renderPagination();
        } catch (error) {
            if ((error as Error).name !== 'AbortError') {
                console.error('Error loading media:', error);
                this.state.loading = false;
                this.renderMediaError();
            }
        }
    }

    /**
     * Set view mode (grid/list)
     */
    private setView(view: 'grid' | 'list'): void {
        this.state.view = view;

        // Update view toggle buttons
        this.modal?.querySelectorAll('[data-view]').forEach((el) => {
            const btnView = el.getAttribute('data-view');
            if (btnView === view) {
                el.classList.add('bg-white', 'dark:bg-gray-700', 'shadow');
                el.classList.remove('hover:bg-gray-200', 'dark:hover:bg-gray-700');
            } else {
                el.classList.remove('bg-white', 'dark:bg-gray-700', 'shadow');
                el.classList.add('hover:bg-gray-200', 'dark:hover:bg-gray-700');
            }
        });

        // Re-render media items
        this.renderMediaItems();
    }

    /**
     * Select a collection
     */
    private selectCollection(collection: string | null, modelType: string | null = null): void {
        this.state.currentCollection = collection;
        this.state.currentModelType = modelType;
        this.state.currentPage = 1;
        this.updateCollectionUI();
        this.loadMedia();
    }

    /**
     * Toggle item selection
     */
    private toggleItemSelection(item: MediaItem): void {
        const index = this.state.selectedItems.findIndex((i) => i.id === item.id);

        if (index > -1) {
            // Deselect
            this.state.selectedItems.splice(index, 1);
        } else {
            // Select
            if (!this.config.multiple) {
                this.state.selectedItems = [item];
            } else if (
                !this.config.maxSelection ||
                this.state.selectedItems.length < this.config.maxSelection
            ) {
                this.state.selectedItems.push(item);
            }
        }

        this.updateSelectionUI();
        this.showPreview(this.state.selectedItems[this.state.selectedItems.length - 1] || null);
    }

    /**
     * Update selection UI
     */
    private updateSelectionUI(): void {
        // Update selection count
        const countEl = this.modal?.querySelector('[data-selection-count]');
        if (countEl) {
            const count = this.state.selectedItems.length;
            countEl.textContent =
                count === 0
                    ? 'No items selected'
                    : `${count} item${count > 1 ? 's' : ''} selected`;
        }

        // Update select button state
        const selectBtn = this.modal?.querySelector('[data-action="select"]') as HTMLButtonElement;
        if (selectBtn) {
            selectBtn.disabled = this.state.selectedItems.length === 0;
        }

        // Update item selection states
        this.modal?.querySelectorAll('[data-media-item]').forEach((el) => {
            const itemId = el.getAttribute('data-media-item');
            const isSelected = this.state.selectedItems.some((i) => String(i.id) === itemId);

            if (isSelected) {
                el.classList.add('ring-2', 'ring-blue-500', 'ring-offset-2');
            } else {
                el.classList.remove('ring-2', 'ring-blue-500', 'ring-offset-2');
            }
        });
    }

    /**
     * Update collection UI
     */
    private updateCollectionUI(): void {
        this.modal?.querySelectorAll('[data-collection]').forEach((el) => {
            const collection = el.getAttribute('data-collection');
            const modelType = el.getAttribute('data-model-type');

            const isActive =
                collection === this.state.currentCollection &&
                modelType === this.state.currentModelType;

            if (isActive) {
                el.classList.add('bg-blue-50', 'dark:bg-blue-900/20', 'text-blue-600', 'dark:text-blue-400');
                el.classList.remove('text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-100', 'dark:hover:bg-gray-800');
            } else {
                el.classList.remove('bg-blue-50', 'dark:bg-blue-900/20', 'text-blue-600', 'dark:text-blue-400');
                el.classList.add('text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-100', 'dark:hover:bg-gray-800');
            }
        });
    }

    /**
     * Show preview panel
     */
    private showPreview(item: MediaItem | null): void {
        this.state.previewItem = item;
        const panel = this.modal?.querySelector('[data-preview-panel]');

        if (!panel) {
            return;
        }

        if (!item) {
            panel.classList.add('hidden');
            return;
        }

        panel.classList.remove('hidden');
        panel.innerHTML = this.renderPreviewPanel(item);
    }

    /**
     * Render collections sidebar
     */
    private renderCollections(): void {
        const container = this.modal?.querySelector('[data-collections-list]');
        if (!container) {
            return;
        }

        // Group by model type
        const grouped = new Map<string, MediaCollection[]>();

        this.state.collections.forEach((collection) => {
            const modelKey = collection.model_type || 'general';
            if (!grouped.has(modelKey)) {
                grouped.set(modelKey, []);
            }
            grouped.get(modelKey)!.push(collection);
        });

        let html = `
            <button
                data-collection=""
                data-model-type=""
                class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium rounded-lg transition-colors ${
                    !this.state.currentCollection
                        ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400'
                        : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800'
                }"
            >
                <span>All Files</span>
                <span class="text-xs text-gray-400">${this.state.totalItems}</span>
            </button>
        `;

        grouped.forEach((collections, modelType) => {
            if (modelType !== 'general') {
                const modelLabel = collections[0]?.model_label || this.formatModelName(modelType);
                html += `
                    <div class="mt-4 first:mt-0">
                        <h4 class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">${modelLabel}</h4>
                    </div>
                `;
            }

            collections.forEach((collection) => {
                const isActive =
                    collection.name === this.state.currentCollection &&
                    (collection.model_type || '') === (this.state.currentModelType || '');

                html += `
                    <button
                        data-collection="${collection.name}"
                        data-model-type="${collection.model_type || ''}"
                        class="flex items-center justify-between w-full px-3 py-2 text-sm rounded-lg transition-colors ${
                            isActive
                                ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400'
                                : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800'
                        }"
                    >
                        <span class="truncate">${this.formatCollectionName(collection.name)}</span>
                        <span class="text-xs text-gray-400 ml-2">${collection.count}</span>
                    </button>
                `;
            });
        });

        container.innerHTML = html;

        // Bind collection click events
        container.querySelectorAll('[data-collection]').forEach((el) => {
            el.addEventListener('click', () => {
                const collection = el.getAttribute('data-collection') || null;
                const modelType = el.getAttribute('data-model-type') || null;
                this.selectCollection(collection, modelType);
            });
        });
    }

    /**
     * Render collections error
     */
    private renderCollectionsError(): void {
        const container = this.modal?.querySelector('[data-collections-list]');
        if (!container) {
            return;
        }

        container.innerHTML = `
            <div class="px-3 py-4 text-sm text-red-600 dark:text-red-400">
                Failed to load collections
            </div>
        `;
    }

    /**
     * Render loading state
     */
    private renderLoadingState(): void {
        const grid = this.modal?.querySelector('[data-media-grid]');
        if (!grid) {
            return;
        }

        grid.innerHTML = `
            <div class="col-span-full flex items-center justify-center py-12">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            </div>
        `;
    }

    /**
     * Render media items
     */
    private renderMediaItems(): void {
        const grid = this.modal?.querySelector('[data-media-grid]');
        if (!grid) {
            return;
        }

        if (this.state.items.length === 0) {
            grid.innerHTML = `
                <div class="col-span-full flex flex-col items-center justify-center py-12 text-gray-500 dark:text-gray-400">
                    <svg class="w-12 h-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="text-sm">No media files found</p>
                </div>
            `;
            return;
        }

        // Update grid/list classes
        if (this.state.view === 'grid') {
            grid.className = 'grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4';
        } else {
            grid.className = 'space-y-2';
        }

        grid.innerHTML = this.state.items.map((item) => this.renderMediaItem(item)).join('');

        // Bind item click events
        grid.querySelectorAll('[data-media-item]').forEach((el) => {
            const itemId = el.getAttribute('data-media-item');
            const item = this.state.items.find((i) => String(i.id) === itemId);
            if (item) {
                el.addEventListener('click', () => this.toggleItemSelection(item));
            }
        });
    }

    /**
     * Render a single media item
     */
    private renderMediaItem(item: MediaItem): string {
        const isSelected = this.state.selectedItems.some((i) => i.id === item.id);
        const isImage = item.mime_type.startsWith('image/');
        const thumbnail = item.thumbnail_url || item.preview_url || item.url;

        if (this.state.view === 'grid') {
            return `
                <button
                    data-media-item="${item.id}"
                    class="group relative aspect-square bg-gray-100 dark:bg-gray-800 rounded-lg overflow-hidden cursor-pointer transition-all hover:ring-2 hover:ring-blue-400 ${
                        isSelected ? 'ring-2 ring-blue-500 ring-offset-2' : ''
                    }"
                >
                    ${
                        isImage
                            ? `<img src="${thumbnail}" alt="${item.name}" class="w-full h-full object-cover">`
                            : `
                                <div class="flex items-center justify-center h-full">
                                    ${this.getFileIcon(item.mime_type)}
                                </div>
                            `
                    }
                    <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/60 to-transparent p-2">
                        <p class="text-xs text-white truncate">${item.name}</p>
                    </div>
                    ${
                        isSelected
                            ? `
                        <div class="absolute top-2 right-2 w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    `
                            : ''
                    }
                </button>
            `;
        }

        // List view
        return `
            <button
                data-media-item="${item.id}"
                class="flex items-center w-full p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 cursor-pointer transition-all hover:border-blue-400 ${
                    isSelected ? 'ring-2 ring-blue-500 border-blue-500' : ''
                }"
            >
                <div class="w-12 h-12 flex-shrink-0 bg-gray-100 dark:bg-gray-700 rounded overflow-hidden">
                    ${
                        isImage
                            ? `<img src="${thumbnail}" alt="${item.name}" class="w-full h-full object-cover">`
                            : `<div class="flex items-center justify-center h-full">${this.getFileIcon(item.mime_type, 'w-6 h-6')}</div>`
                    }
                </div>
                <div class="flex-1 min-w-0 ml-4 text-left">
                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">${item.name}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">${item.human_readable_size} • ${this.formatDate(item.created_at)}</p>
                </div>
                ${
                    isSelected
                        ? `
                    <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center ml-4">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                `
                        : ''
                }
            </button>
        `;
    }

    /**
     * Render media error
     */
    private renderMediaError(): void {
        const grid = this.modal?.querySelector('[data-media-grid]');
        if (!grid) {
            return;
        }

        grid.innerHTML = `
            <div class="col-span-full flex flex-col items-center justify-center py-12 text-red-500">
                <svg class="w-12 h-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm">Failed to load media</p>
                <button class="mt-4 px-4 py-2 text-sm text-blue-600 hover:underline" onclick="this.closest('[data-media-grid]').dispatchEvent(new CustomEvent('retry'))">
                    Try again
                </button>
            </div>
        `;

        grid.addEventListener(
            'retry',
            () => {
                this.loadMedia();
            },
            { once: true }
        );
    }

    /**
     * Render pagination
     */
    private renderPagination(): void {
        const infoEl = this.modal?.querySelector('[data-pagination-info]');
        const buttonsEl = this.modal?.querySelector('[data-pagination-buttons]');

        if (infoEl) {
            const start = (this.state.currentPage - 1) * (this.config.perPage || 24) + 1;
            const end = Math.min(start + this.state.items.length - 1, this.state.totalItems);
            infoEl.textContent =
                this.state.totalItems > 0
                    ? `Showing ${start}-${end} of ${this.state.totalItems}`
                    : 'No items';
        }

        if (buttonsEl) {
            let html = '';

            if (this.state.currentPage > 1) {
                html += `
                    <button
                        data-page="${this.state.currentPage - 1}"
                        class="px-3 py-1 text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded"
                    >
                        Previous
                    </button>
                `;
            }

            // Page numbers
            const pages = this.getPageNumbers();
            pages.forEach((page) => {
                if (page === '...') {
                    html += `<span class="px-2 text-gray-400">...</span>`;
                } else {
                    html += `
                        <button
                            data-page="${page}"
                            class="px-3 py-1 text-sm rounded ${
                                page === this.state.currentPage
                                    ? 'bg-blue-600 text-white'
                                    : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'
                            }"
                        >
                            ${page}
                        </button>
                    `;
                }
            });

            if (this.state.currentPage < this.state.totalPages) {
                html += `
                    <button
                        data-page="${this.state.currentPage + 1}"
                        class="px-3 py-1 text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded"
                    >
                        Next
                    </button>
                `;
            }

            buttonsEl.innerHTML = html;

            // Bind pagination events
            buttonsEl.querySelectorAll('[data-page]').forEach((el) => {
                el.addEventListener('click', () => {
                    const page = parseInt(el.getAttribute('data-page') || '1', 10);
                    this.state.currentPage = page;
                    this.loadMedia();
                });
            });
        }
    }

    /**
     * Render preview panel
     */
    private renderPreviewPanel(item: MediaItem): string {
        const isImage = item.mime_type.startsWith('image/');

        return `
            <div class="p-4">
                <div class="aspect-square bg-gray-100 dark:bg-gray-800 rounded-lg overflow-hidden mb-4">
                    ${
                        isImage
                            ? `<img src="${item.preview_url || item.url}" alt="${item.name}" class="w-full h-full object-contain">`
                            : `<div class="flex items-center justify-center h-full">${this.getFileIcon(item.mime_type, 'w-16 h-16')}</div>`
                    }
                </div>

                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-4 break-words">${item.name}</h3>

                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">File name</dt>
                        <dd class="text-gray-900 dark:text-white break-all">${item.file_name}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Type</dt>
                        <dd class="text-gray-900 dark:text-white">${item.mime_type}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Size</dt>
                        <dd class="text-gray-900 dark:text-white">${item.human_readable_size}</dd>
                    </div>
                    ${
                        item.collection_name
                            ? `
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Collection</dt>
                            <dd class="text-gray-900 dark:text-white">${this.formatCollectionName(item.collection_name)}</dd>
                        </div>
                    `
                            : ''
                    }
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Uploaded</dt>
                        <dd class="text-gray-900 dark:text-white">${this.formatDate(item.created_at)}</dd>
                    </div>
                </dl>
            </div>
        `;
    }

    /**
     * Open upload dialog
     */
    private openUploadDialog(): void {
        const input = document.createElement('input');
        input.type = 'file';
        input.multiple = this.config.multiple || false;

        if (this.config.acceptedTypes && this.config.acceptedTypes.length > 0) {
            input.accept = this.config.acceptedTypes.join(',');
        }

        input.addEventListener('change', () => {
            if (input.files && input.files.length > 0) {
                this.uploadFiles(Array.from(input.files));
            }
        });

        input.click();
    }

    /**
     * Upload files
     */
    private async uploadFiles(files: File[]): Promise<void> {
        // Show upload progress (simplified for now)
        const grid = this.modal?.querySelector('[data-media-grid]');
        if (grid) {
            grid.innerHTML = `
                <div class="col-span-full flex flex-col items-center justify-center py-12">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mb-4"></div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Uploading ${files.length} file(s)...</p>
                </div>
            `;
        }

        try {
            for (const file of files) {
                const formData = new FormData();
                formData.append('file', file);

                await fetch(this.config.endpoint.replace('/media', '/upload'), {
                    method: 'POST',
                    headers: {
                        'X-Upload-Token': this.config.token,
                    },
                    body: formData,
                });
            }

            // Reload media after upload
            this.loadMedia();
        } catch (error) {
            console.error('Upload failed:', error);
            this.renderMediaError();
        }
    }

    /**
     * Get page numbers for pagination
     */
    private getPageNumbers(): (number | string)[] {
        const pages: (number | string)[] = [];
        const total = this.state.totalPages;
        const current = this.state.currentPage;

        if (total <= 7) {
            for (let i = 1; i <= total; i++) {
                pages.push(i);
            }
        } else {
            pages.push(1);

            if (current > 3) {
                pages.push('...');
            }

            for (let i = Math.max(2, current - 1); i <= Math.min(total - 1, current + 1); i++) {
                pages.push(i);
            }

            if (current < total - 2) {
                pages.push('...');
            }

            pages.push(total);
        }

        return pages;
    }

    /**
     * Get file icon SVG based on mime type
     */
    private getFileIcon(mimeType: string, sizeClass: string = 'w-8 h-8'): string {
        const iconClass = `${sizeClass} text-gray-400`;

        if (mimeType.startsWith('video/')) {
            return `<svg class="${iconClass}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>`;
        }

        if (mimeType.startsWith('audio/')) {
            return `<svg class="${iconClass}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" /></svg>`;
        }

        if (mimeType === 'application/pdf') {
            return `<svg class="${iconClass}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>`;
        }

        if (
            mimeType.includes('spreadsheet') ||
            mimeType.includes('excel') ||
            mimeType === 'text/csv'
        ) {
            return `<svg class="${iconClass}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>`;
        }

        if (mimeType.includes('word') || mimeType.includes('document')) {
            return `<svg class="${iconClass}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>`;
        }

        if (mimeType.includes('zip') || mimeType.includes('archive') || mimeType.includes('compressed')) {
            return `<svg class="${iconClass}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>`;
        }

        // Default file icon
        return `<svg class="${iconClass}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>`;
    }

    /**
     * Format model name for display
     */
    private formatModelName(modelType: string): string {
        // Extract class name from fully qualified name
        const parts = modelType.split('\\');
        const className = parts[parts.length - 1];

        // Add spaces before capitals and pluralize
        return (
            className
                .replace(/([A-Z])/g, ' $1')
                .trim()
                .replace(/\s+/g, ' ') + 's'
        );
    }

    /**
     * Format collection name for display
     */
    private formatCollectionName(name: string): string {
        return name
            .replace(/[-_]/g, ' ')
            .replace(/\b\w/g, (l) => l.toUpperCase());
    }

    /**
     * Format date for display
     */
    private formatDate(dateString: string): string {
        const date = new Date(dateString);
        return date.toLocaleDateString(undefined, {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
        });
    }
}

/**
 * Initialize media browsers from DOM elements
 */
export function initMediaBrowsers(): void {
    document.querySelectorAll<HTMLElement>('[data-media-browser]').forEach((element) => {
        const config = element.dataset;

        const browser = new MediaBrowserManager({
            endpoint: config.mediaBrowserEndpoint || '/api/forms/media',
            token: config.mediaBrowserToken || '',
            multiple: config.mediaBrowserMultiple === 'true',
            maxSelection: config.mediaBrowserMaxSelection
                ? parseInt(config.mediaBrowserMaxSelection, 10)
                : undefined,
            acceptedTypes: config.mediaBrowserAcceptedTypes
                ? config.mediaBrowserAcceptedTypes.split(',')
                : undefined,
            defaultCollection: config.mediaBrowserDefaultCollection || undefined,
            allowUpload: config.mediaBrowserAllowUpload !== 'false',
            defaultView: (config.mediaBrowserDefaultView as 'grid' | 'list') || 'grid',
            onSelect: (items) => {
                // Dispatch custom event with selected items
                element.dispatchEvent(
                    new CustomEvent('media-selected', {
                        detail: { items },
                        bubbles: true,
                    })
                );
            },
        });

        // Store instance on element
        (element as HTMLElement & { mediaBrowser?: MediaBrowserManager }).mediaBrowser = browser;

        // Listen for trigger clicks
        const triggerId = element.dataset.mediaBrowserTrigger;
        if (triggerId) {
            const trigger = document.getElementById(triggerId);
            trigger?.addEventListener('click', () => browser.open());
        }
    });
}

// Add static initAll method for consistency with other managers
MediaBrowserManager.initAll = initMediaBrowsers;

// Export for use in other modules
export default MediaBrowserManager;
