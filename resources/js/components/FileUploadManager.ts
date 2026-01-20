/**
 * FileUploadManager - FilePond-based file upload component
 *
 * Features:
 * - FilePond integration with all plugins
 * - Image preview, crop, resize, and edit (Cropper.js)
 * - Avatar mode with circular preview
 * - Multiple file support with reordering
 * - Download and open buttons
 * - Native HTML fallback mode
 * - Spatie Media Library integration
 */

// Import FilePond and plugins
import * as FilePond from 'filepond';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginImageCrop from 'filepond-plugin-image-crop';
import FilePondPluginImageResize from 'filepond-plugin-image-resize';
import FilePondPluginImageTransform from 'filepond-plugin-image-transform';
import FilePondPluginImageExifOrientation from 'filepond-plugin-image-exif-orientation';
import FilePondPluginImageEdit from 'filepond-plugin-image-edit';

// Import Cropper.js v2
import Cropper from 'cropperjs';

// Import FilePond CSS - these will be injected into the JS bundle by vite-plugin-css-injected-by-js
import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css';
import 'filepond-plugin-image-edit/dist/filepond-plugin-image-edit.min.css';

// Note: Cropper.js v2 has no separate CSS - we style the cropper modal ourselves

// Inject FilamentPHP-style FilePond theme CSS - Orange primary color
const injectFilePondStyles = (): void => {
    if (document.getElementById('filepond-filament-theme')) return;

    const style = document.createElement('style');
    style.id = 'filepond-filament-theme';
    style.textContent = `
        /* FilePond Filament Theme - Orange Primary (like FilamentPHP) */
        .filepond--root {
            font-family: inherit !important;
            margin-bottom: 0 !important;
        }

        /* Drop zone - Clean styling without extra borders */
        .filepond--panel-root {
            background-color: transparent !important;
            border: none !important;
        }
        .filepond--root {
            background-color: transparent !important;
            border: 2px dashed rgb(75 85 99) !important;
            border-radius: 0.5rem !important;
            padding: 0.5rem !important;
        }
        .filepond--root:hover {
            border-color: var(--color-primary-400, #fb923c) !important;
        }
        .filepond--root[data-hopper="active"] {
            border-color: var(--color-primary-500, #f97316) !important;
            background-color: rgba(251, 146, 60, 0.1) !important;
        }

        /* Drop label */
        .filepond--drop-label {
            color: rgb(107 114 128) !important;
            font-size: 0.875rem !important;
            padding: 2.5rem 1.5rem !important;
            min-height: 6rem !important;
        }
        .filepond--drop-label label {
            cursor: pointer !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            gap: 1rem !important;
        }
        /* Upload icon in drop label - larger like native HTML */
        .filepond-upload-icon {
            width: 3rem !important;
            height: 3rem !important;
            color: rgb(156 163 175) !important;
        }
        .dark .filepond-upload-icon {
            color: rgb(107 114 128) !important;
        }
        .filepond-label-text {
            display: block !important;
            text-align: center !important;
            font-size: 0.875rem !important;
            line-height: 1.5rem !important;
            color: rgb(75 85 99) !important;
        }
        .dark .filepond-label-text {
            color: rgb(156 163 175) !important;
        }
        .filepond--label-action {
            color: var(--color-primary-600, #ea580c) !important;
            font-weight: 600 !important;
            text-decoration: none !important;
        }
        .filepond--label-action:hover {
            color: var(--color-primary-500, #f97316) !important;
        }
        .dark .filepond--label-action {
            color: var(--color-primary-400, #fb923c) !important;
        }
        .dark .filepond--label-action:hover {
            color: var(--color-primary-300, #fdba74) !important;
        }

        /* File item panel - Orange/Primary gradient like Filament */
        .filepond--item-panel {
            background: linear-gradient(180deg, #ea580c 0%, #c2410c 100%) !important;
            border-radius: 0.5rem !important;
        }

        /* File info - White text on colored background */
        .filepond--file-info {
            padding-left: 0.5rem !important;
        }
        .filepond--file-info-main {
            font-size: 0.75rem !important;
            font-weight: 500 !important;
            color: #fff !important;
        }
        .filepond--file-info-sub {
            font-size: 0.625rem !important;
            color: rgba(255, 255, 255, 0.85) !important;
            opacity: 1 !important;
        }

        /* File status - White text */
        .filepond--file-status {
            font-size: 0.625rem !important;
        }
        .filepond--file-status-main {
            color: #fff !important;
        }
        .filepond--file-status-sub {
            color: rgba(255, 255, 255, 0.85) !important;
            opacity: 1 !important;
        }

        /* Action buttons - White on colored background */
        .filepond--file-action-button {
            cursor: pointer !important;
            color: #fff !important;
            background-color: rgba(255, 255, 255, 0.15) !important;
            transition: background-color 0.15s ease-in-out !important;
        }
        .filepond--file-action-button:hover,
        .filepond--file-action-button:focus {
            background-color: rgba(255, 255, 255, 0.3) !important;
        }

        /* Progress indicator - White spinner */
        .filepond--progress-indicator {
            color: #fff !important;
        }
        .filepond--load-indicator {
            color: #fff !important;
        }
        .filepond--processing-complete-indicator {
            color: #fff !important;
        }

        /* Processing ring/arc color */
        .filepond--progress-indicator svg {
            color: #fff !important;
        }

        /* Error state - Red gradient */
        .filepond--item[data-filepond-item-state*="error"] .filepond--item-panel,
        .filepond--item[data-filepond-item-state*="invalid"] .filepond--item-panel {
            background: linear-gradient(180deg, #ef4444 0%, #dc2626 100%) !important;
        }

        /* Idle state (waiting) - Gray gradient */
        .filepond--item[data-filepond-item-state="idle"] .filepond--item-panel {
            background: linear-gradient(180deg, #6b7280 0%, #4b5563 100%) !important;
        }

        /* Processing state - Orange gradient */
        .filepond--item[data-filepond-item-state*="processing"] .filepond--item-panel,
        .filepond--item[data-filepond-item-state*="uploading"] .filepond--item-panel {
            background: linear-gradient(180deg, #ea580c 0%, #c2410c 100%) !important;
        }

        /* Success/Complete state - Green gradient like Filament */
        .filepond--item[data-filepond-item-state="processing-complete"] .filepond--item-panel {
            background: linear-gradient(180deg, #10b981 0%, #059669 100%) !important;
        }

        /* Image preview */
        .filepond--image-preview-wrapper {
            background-color: rgb(17 24 39) !important;
            border-radius: 0.375rem !important;
        }
        .filepond--image-preview {
            background-color: rgb(17 24 39) !important;
        }

        /* Hide credits */
        .filepond--credits {
            display: none !important;
        }

        /* Edit button (from filepond-plugin-image-edit) */
        .filepond--action-edit-item {
            width: 1.625rem !important;
            height: 1.625rem !important;
            padding: 0 !important;
            cursor: pointer !important;
        }
        .filepond--action-edit-item svg {
            width: 1.25rem !important;
            height: 1.25rem !important;
        }
        /* Make edit button visible */
        .filepond--file .filepond--action-edit-item {
            visibility: visible !important;
            opacity: 1 !important;
            color: #fff !important;
            background-color: rgba(255, 255, 255, 0.15) !important;
            transition: background-color 0.15s ease-in-out !important;
        }
        .filepond--file .filepond--action-edit-item:hover {
            background-color: rgba(255, 255, 255, 0.3) !important;
        }

        /* Drip blob - Orange */
        .filepond--drip-blob {
            background-color: rgba(234, 88, 12, 0.3) !important;
        }

        /* File wrapper */
        .filepond--file-wrapper {
            border: none !important;
            padding: 0 !important;
        }
        .filepond--file {
            padding: 0.5rem 0.625rem !important;
        }

        /* Avatar Mode */
        .filepond-avatar .filepond--root,
        .file-upload-avatar .filepond--root {
            width: 150px !important;
            margin: 0 auto !important;
        }
        .filepond-avatar .filepond--drop-label,
        .file-upload-avatar .filepond--drop-label {
            min-height: 150px !important;
            padding: 1rem !important;
        }
        .filepond-avatar .filepond--panel-root,
        .file-upload-avatar .filepond--panel-root {
            border-radius: 9999px !important;
        }
        .filepond-avatar .filepond--image-preview-wrapper,
        .file-upload-avatar .filepond--image-preview-wrapper {
            border-radius: 9999px !important;
        }
        .filepond-avatar .filepond--item-panel,
        .file-upload-avatar .filepond--item-panel {
            border-radius: 9999px !important;
        }

        /* Dark Mode */
        .dark .filepond--panel-root {
            background-color: transparent !important;
            border: none !important;
        }
        .dark .filepond--root {
            background-color: transparent !important;
            border: 2px dashed rgb(75 85 99) !important;
        }
        .dark .filepond--root:hover {
            border-color: var(--color-primary-500, #f97316) !important;
        }
        .dark .filepond--root[data-hopper="active"] {
            border-color: var(--color-primary-400, #fb923c) !important;
            background-color: rgba(251, 146, 60, 0.1) !important;
        }
        .dark .filepond--drop-label {
            color: rgb(156 163 175) !important;
        }
        /* File item panel keeps same colors in dark mode (colored gradient) */
        .dark .filepond--drip-blob {
            background-color: rgba(251, 146, 60, 0.3) !important;
        }
        .dark .filepond--image-preview-wrapper {
            background-color: rgb(17 24 39) !important;
        }
        .dark .filepond--image-preview {
            background-color: rgb(17 24 39) !important;
        }
    `;
    document.head.appendChild(style);
};

// Inject styles immediately
if (typeof document !== 'undefined') {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', injectFilePondStyles);
    } else {
        injectFilePondStyles();
    }
}

// Register FilePond plugins (order matters - image-edit should be after preview)
FilePond.registerPlugin(
    FilePondPluginFileValidateType,
    FilePondPluginFileValidateSize,
    FilePondPluginImageExifOrientation,
    FilePondPluginImagePreview,
    FilePondPluginImageCrop,
    FilePondPluginImageResize,
    FilePondPluginImageTransform,
    FilePondPluginImageEdit
);

// Use FilePond's own types
import type { FilePondFile } from 'filepond';

// Type for FilePond instance
type FilePondInstance = ReturnType<typeof FilePond.create>;

// Type for Cropper instance (v2)
type CropperInstance = InstanceType<typeof Cropper>;

interface FileUploadConfig {
    uploadUrl: string;
    uploadToken: string;
    disk: string;
    directory: string;
    visibility: string;
    maxSize: number | null;
    minSize: number | null;
    maxFiles: number | null;
    minFiles: number | null;
    multiple: boolean;
    acceptedFileTypes: string[];
    isImage: boolean;
    isAvatar: boolean;
    isNative: boolean;
    imagePreview: boolean;
    imageCrop: boolean;
    imageCropAspectRatio: string | number | null;
    imageResize: {
        width: number | null;
        height: number | null;
        mode: string | null;
    };
    imageEditor: boolean;
    imageEditorAspectRatios: string[] | null;
    imageEditorMode: number | null;
    imageEditorEmptyFillColor: string | null;
    imageEditorViewportWidth: string | null;
    imageEditorViewportHeight: string | null;
    circleCropper: boolean;
    downloadable: boolean;
    openable: boolean;
    deletable: boolean;
    reorderable: boolean;
    previewable: boolean;
    panelLayout: string | null;
    panelAspectRatio: string | null;
    imagePreviewHeight: string | null;
    loadingIndicatorPosition: string | null;
    removeUploadedFileButtonPosition: string | null;
    uploadButtonPosition: string | null;
    uploadProgressIndicatorPosition: string | null;
    preserveFilenames: boolean;
    appendFiles: boolean;
    prependFiles: boolean;
    orientImagesFromExif: boolean;
    fetchFileInformation: boolean;
    pasteable: boolean;
    maxParallelUploads: number | null;
    uploadingMessage: string | null;
    mimeTypeMap: Record<string, string> | null;
    alignCenter: boolean;
    useMediaLibrary: boolean;
    collection: string | null;
    mediaBrowser: boolean;
}

export class FileUploadManager {
    /** Static method to initialize all file upload components */
    static initAll: () => void;

    private container: HTMLElement;
    private input: HTMLInputElement;
    private config: FileUploadConfig;
    private pond: FilePondInstance | null = null;
    private previewUrlCache: Map<string, string> = new Map();
    private csrfToken: string;

    constructor(container: HTMLElement) {
        this.container = container;
        // Look for both native and filepond inputs
        this.input = (container.querySelector('.file-upload-input') ||
                     container.querySelector('.filepond-input') ||
                     container.querySelector('input[type="file"]')) as HTMLInputElement;
        this.config = this.parseConfig();
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        if (this.config.isNative) {
            this.initNativeUpload();
        } else {
            this.initFilePond();
        }
    }

    /**
     * Parse configuration from data attributes or JSON config
     */
    private parseConfig(): FileUploadConfig {
        const data = this.container.dataset;

        // Try to parse JSON config first (from data-file-upload-config)
        if (data.fileUploadConfig) {
            try {
                const jsonConfig = JSON.parse(data.fileUploadConfig);
                return this.normalizeConfig(jsonConfig);
            } catch (e) {
                console.warn('Failed to parse file upload config JSON:', e);
            }
        }

        // Fall back to individual data attributes
        return {
            uploadUrl: data.uploadUrl || '/api/forms/upload',
            uploadToken: data.uploadToken || '',
            disk: data.disk || 'public',
            directory: data.directory || 'uploads',
            visibility: data.visibility || 'public',
            maxSize: data.maxSize ? parseInt(data.maxSize) : null,
            minSize: data.minSize ? parseInt(data.minSize) : null,
            maxFiles: data.maxFiles ? parseInt(data.maxFiles) : null,
            minFiles: data.minFiles ? parseInt(data.minFiles) : null,
            multiple: data.multiple === 'true',
            acceptedFileTypes: data.acceptedFileTypes ? JSON.parse(data.acceptedFileTypes) : [],
            isImage: data.isImage === 'true',
            isAvatar: data.isAvatar === 'true',
            isNative: data.fileUploadNative !== undefined || data.isNative === 'true',
            imagePreview: data.imagePreview !== 'false',
            imageCrop: data.imageCrop === 'true',
            imageCropAspectRatio: data.imageCropAspectRatio || null,
            imageResize: {
                width: data.imageResizeWidth ? parseInt(data.imageResizeWidth) : null,
                height: data.imageResizeHeight ? parseInt(data.imageResizeHeight) : null,
                mode: data.imageResizeMode || null,
            },
            imageEditor: data.imageEditor === 'true',
            imageEditorAspectRatios: data.imageEditorAspectRatios ? JSON.parse(data.imageEditorAspectRatios) : null,
            imageEditorMode: data.imageEditorMode ? parseInt(data.imageEditorMode) : null,
            imageEditorEmptyFillColor: data.imageEditorEmptyFillColor || null,
            imageEditorViewportWidth: data.imageEditorViewportWidth || null,
            imageEditorViewportHeight: data.imageEditorViewportHeight || null,
            circleCropper: data.circleCropper === 'true',
            downloadable: data.downloadable === 'true',
            openable: data.openable === 'true',
            deletable: data.deletable !== 'false',
            reorderable: data.reorderable === 'true',
            previewable: data.previewable !== 'false',
            panelLayout: data.panelLayout || null,
            panelAspectRatio: data.panelAspectRatio || null,
            imagePreviewHeight: data.imagePreviewHeight || null,
            loadingIndicatorPosition: data.loadingIndicatorPosition || null,
            removeUploadedFileButtonPosition: data.removeUploadedFileButtonPosition || null,
            uploadButtonPosition: data.uploadButtonPosition || null,
            uploadProgressIndicatorPosition: data.uploadProgressIndicatorPosition || null,
            preserveFilenames: data.preserveFilenames === 'true',
            appendFiles: data.appendFiles === 'true',
            prependFiles: data.prependFiles === 'true',
            orientImagesFromExif: data.orientImagesFromExif !== 'false',
            fetchFileInformation: data.fetchFileInformation !== 'false',
            pasteable: data.pasteable !== 'false',
            maxParallelUploads: data.maxParallelUploads ? parseInt(data.maxParallelUploads) : null,
            uploadingMessage: data.uploadingMessage || null,
            mimeTypeMap: data.mimeTypeMap ? JSON.parse(data.mimeTypeMap) : null,
            alignCenter: data.alignCenter === 'true',
            useMediaLibrary: data.useMediaLibrary === 'true',
            collection: data.collection || null,
            mediaBrowser: data.mediaBrowser === 'true',
        };
    }

    /**
     * Normalize config from JSON (snake_case to camelCase, handle nested objects)
     */
    private normalizeConfig(json: Record<string, unknown>): FileUploadConfig {
        return {
            uploadUrl: (json.upload_url as string) || (json.uploadUrl as string) || '/api/forms/upload',
            uploadToken: (json.upload_token as string) || (json.uploadToken as string) || '',
            disk: (json.disk as string) || 'public',
            directory: (json.directory as string) || 'uploads',
            visibility: (json.visibility as string) || 'public',
            maxSize: json.max_size != null ? Number(json.max_size) : (json.maxSize != null ? Number(json.maxSize) : null),
            minSize: json.min_size != null ? Number(json.min_size) : (json.minSize != null ? Number(json.minSize) : null),
            maxFiles: json.max_files != null ? Number(json.max_files) : (json.maxFiles != null ? Number(json.maxFiles) : null),
            minFiles: json.min_files != null ? Number(json.min_files) : (json.minFiles != null ? Number(json.minFiles) : null),
            multiple: Boolean(json.multiple),
            acceptedFileTypes: (json.accepted_file_types as string[]) || (json.acceptedFileTypes as string[]) || [],
            isImage: Boolean(json.is_image ?? json.isImage),
            isAvatar: Boolean(json.is_avatar ?? json.isAvatar),
            isNative: Boolean(json.is_native ?? json.isNative),
            imagePreview: json.image_preview !== false && json.imagePreview !== false,
            imageCrop: Boolean(json.image_crop ?? json.imageCrop),
            imageCropAspectRatio: (json.image_crop_aspect_ratio as string) || (json.imageCropAspectRatio as string) || null,
            imageResize: {
                width: json.image_resize_target_width != null ? Number(json.image_resize_target_width) :
                       (json.imageResizeTargetWidth != null ? Number(json.imageResizeTargetWidth) : null),
                height: json.image_resize_target_height != null ? Number(json.image_resize_target_height) :
                        (json.imageResizeTargetHeight != null ? Number(json.imageResizeTargetHeight) : null),
                mode: (json.image_resize_mode as string) || (json.imageResizeMode as string) || null,
            },
            imageEditor: Boolean(json.image_editor ?? json.imageEditor),
            imageEditorAspectRatios: (json.image_editor_aspect_ratios as string[]) || (json.imageEditorAspectRatios as string[]) || null,
            imageEditorMode: json.image_editor_mode != null ? Number(json.image_editor_mode) :
                            (json.imageEditorMode != null ? Number(json.imageEditorMode) : null),
            imageEditorEmptyFillColor: (json.image_editor_empty_fill_color as string) || (json.imageEditorEmptyFillColor as string) || null,
            imageEditorViewportWidth: (json.image_editor_viewport_width as string) || (json.imageEditorViewportWidth as string) || null,
            imageEditorViewportHeight: (json.image_editor_viewport_height as string) || (json.imageEditorViewportHeight as string) || null,
            circleCropper: Boolean(json.circle_cropper ?? json.circleCropper),
            downloadable: Boolean(json.downloadable),
            openable: Boolean(json.openable),
            deletable: json.deletable !== false,
            reorderable: Boolean(json.reorderable),
            previewable: json.previewable !== false,
            panelLayout: (json.panel_layout as string) || (json.panelLayout as string) || null,
            panelAspectRatio: (json.panel_aspect_ratio as string) || (json.panelAspectRatio as string) || null,
            imagePreviewHeight: (json.image_preview_height as string) || (json.imagePreviewHeight as string) || null,
            loadingIndicatorPosition: (json.loading_indicator_position as string) || (json.loadingIndicatorPosition as string) || null,
            removeUploadedFileButtonPosition: (json.remove_uploaded_file_button_position as string) || (json.removeUploadedFileButtonPosition as string) || null,
            uploadButtonPosition: (json.upload_button_position as string) || (json.uploadButtonPosition as string) || null,
            uploadProgressIndicatorPosition: (json.upload_progress_indicator_position as string) || (json.uploadProgressIndicatorPosition as string) || null,
            preserveFilenames: Boolean(json.preserve_filenames ?? json.preserveFilenames),
            appendFiles: Boolean(json.append_files ?? json.appendFiles),
            prependFiles: Boolean(json.prepend_files ?? json.prependFiles),
            orientImagesFromExif: json.orient_images_from_exif !== false && json.orientImagesFromExif !== false,
            fetchFileInformation: json.fetch_file_information !== false && json.fetchFileInformation !== false,
            pasteable: json.pasteable !== false,
            maxParallelUploads: json.max_parallel_uploads != null ? Number(json.max_parallel_uploads) :
                               (json.maxParallelUploads != null ? Number(json.maxParallelUploads) : null),
            uploadingMessage: (json.uploading_message as string) || (json.uploadingMessage as string) || null,
            mimeTypeMap: (json.mime_type_map as Record<string, string>) || (json.mimeTypeMap as Record<string, string>) || null,
            alignCenter: Boolean(json.align_center ?? json.alignCenter),
            useMediaLibrary: Boolean(json.use_media_library ?? json.useMediaLibrary),
            collection: (json.collection as string) || null,
            mediaBrowser: Boolean(json.media_browser ?? json.mediaBrowser),
        };
    }

    /**
     * Initialize FilePond
     */
    private initFilePond(): void {
        const options = this.buildFilePondOptions();

        this.pond = FilePond.create(this.input, options);

        // Load existing files if any
        this.loadExistingFiles();

        // Apply avatar mode styling
        if (this.config.isAvatar) {
            this.applyAvatarStyles();
        }

        // Inject buttons after files are added
        if (this.pond) {
            this.pond.on('addfile', (_error, _file) => {
                setTimeout(() => {
                    if (this.config.downloadable) this.injectDownloadButtons();
                    if (this.config.openable) this.injectOpenButtons();
                    // Note: Edit button is now handled by FilePond's native imageEditEditor API
                }, 100);
            });
        }
    }

    /**
     * Build FilePond options
     */
    private buildFilePondOptions(): Record<string, unknown> {
        const options: Record<string, unknown> = {
            name: this.input.name || 'file',
            server: this.buildServerConfig(),
            allowMultiple: this.config.isAvatar ? false : this.config.multiple,
            maxFiles: this.config.isAvatar ? 1 : this.config.maxFiles,
            maxFileSize: this.config.maxSize ? `${this.config.maxSize}KB` : null,
            minFileSize: this.config.minSize ? `${this.config.minSize}KB` : null,
            acceptedFileTypes: this.config.acceptedFileTypes.length > 0 ? this.config.acceptedFileTypes : null,
            allowReorder: this.config.reorderable,
            allowRemove: this.config.deletable,
            allowImagePreview: this.config.imagePreview && this.config.previewable,
            allowPaste: this.config.pasteable,
            instantUpload: true,
            allowRevert: true,
            credits: false,
            maxParallelUploads: this.config.maxParallelUploads || 2,
            itemInsertLocationFreedom: this.config.appendFiles || this.config.prependFiles,
            allowImageExifOrientation: this.config.orientImagesFromExif,
            checkValidity: this.config.fetchFileInformation,
        };

        // Panel layout and aspect ratio
        if (this.config.panelLayout) {
            options.stylePanelLayout = this.config.panelLayout;
        }
        if (this.config.panelAspectRatio) {
            options.stylePanelAspectRatio = this.config.panelAspectRatio;
        }

        // Avatar mode styles
        if (this.config.isAvatar) {
            options.imagePreviewMaxHeight = 170;
            options.imagePreviewMinHeight = 170;
            options.stylePanelLayout = 'compact circle';
            options.stylePanelAspectRatio = '1:1';
            options.styleLoadIndicatorPosition = 'center bottom';
            options.styleProgressIndicatorPosition = 'right bottom';
            options.styleButtonRemoveItemPosition = 'left bottom';
            options.styleButtonProcessItemPosition = 'right bottom';
            // Enable image crop for avatar with 1:1 aspect ratio
            options.allowImageCrop = true;
            options.imageCropAspectRatio = '1:1';
            options.allowImageTransform = true;
            options.imageTransformOutputQuality = 90;
        }

        // Button positions
        if (this.config.loadingIndicatorPosition) {
            options.styleLoadIndicatorPosition = this.config.loadingIndicatorPosition;
        }
        if (this.config.uploadButtonPosition) {
            options.styleButtonProcessItemPosition = this.config.uploadButtonPosition;
        }
        if (this.config.removeUploadedFileButtonPosition) {
            options.styleButtonRemoveItemPosition = this.config.removeUploadedFileButtonPosition;
        }
        if (this.config.uploadProgressIndicatorPosition) {
            options.styleProgressIndicatorPosition = this.config.uploadProgressIndicatorPosition;
        }

        // Image preview height
        if (this.config.imagePreviewHeight) {
            options.imagePreviewMaxHeight = parseInt(this.config.imagePreviewHeight);
        }

        // Label idle text - matching native HTML file input style
        const uploadIcon = `<svg class="filepond-upload-icon" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>`;
        const uploadText = 'Upload a file';
        const dragDropText = 'or drag and drop';
        options.labelIdle = this.config.uploadingMessage || `${uploadIcon}<span class="filepond-label-text"><span class="filepond--label-action">${uploadText}</span> ${dragDropText}</span>`;

        // Image transformation options
        if (this.config.imageCrop || this.config.imageResize.width || this.config.imageResize.height) {
            options.allowImageTransform = true;
            options.imageTransformOutputQuality = 90;
            options.imageTransformOutputMimeType = 'image/jpeg';

            if (this.config.imageCrop) {
                options.allowImageCrop = true;
                if (this.config.imageCropAspectRatio) {
                    options.imageCropAspectRatio = this.config.imageCropAspectRatio;
                }
            }

            if (this.config.imageResize.width || this.config.imageResize.height) {
                options.allowImageResize = true;
                if (this.config.imageResize.width) {
                    options.imageResizeTargetWidth = this.config.imageResize.width;
                }
                if (this.config.imageResize.height) {
                    options.imageResizeTargetHeight = this.config.imageResize.height;
                }
                options.imageResizeMode = (this.config.imageResize.mode as any) || 'contain';
            }
        }

        // Enable image editing with Cropper.js using FilePond's native imageEditEditor API
        // Show edit button if imageEditor is enabled (regardless of crop/resize settings)
        if (this.config.imageEditor) {
            options.allowImageEdit = true;
            options.imageEditInstantEdit = false;
            options.imageEditEditor = this.createImageEditEditor();
        }

        // Circle cropper styling for avatar mode
        if (this.config.circleCropper || this.config.isAvatar) {
            options.stylePanelLayout = 'compact circle';
            options.styleLoadIndicatorPosition = 'center bottom';
            options.styleProgressIndicatorPosition = 'center bottom';
            options.styleButtonRemoveItemPosition = 'center bottom';
            options.styleButtonProcessItemPosition = 'center bottom';
        }

        return options;
    }

    /**
     * Build server configuration for FilePond
     */
    private buildServerConfig(): Record<string, unknown> {
        return {
            process: {
                url: this.config.uploadUrl,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'X-Upload-Token': this.config.uploadToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                ondata: (formData: FormData) => {
                    formData.append('disk', this.config.disk);
                    formData.append('directory', this.config.directory);
                    formData.append('visibility', this.config.visibility);
                    return formData;
                },
                onload: (response: string) => {
                    const path = response.trim();
                    if (path) {
                        this.resolveFileUrl(path);
                    }
                    return path || null;
                },
            },
            revert: async (uniqueFileId: string, load: () => void, error: (message?: string) => void) => {
                try {
                    const response = await fetch(`${this.config.uploadUrl}/revert`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'text/plain',
                            'X-CSRF-TOKEN': this.csrfToken,
                            'X-Upload-Token': this.config.uploadToken,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: uniqueFileId,
                    });

                    if (response.ok) {
                        this.previewUrlCache.delete(uniqueFileId);
                        load();
                    } else {
                        error('Failed to revert upload');
                    }
                } catch (err) {
                    error('Failed to revert upload');
                }
            },
            load: async (source: any, load: (file: Blob) => void, error: (message?: string) => void, progress: (computable: boolean, current: number, total: number) => void, abort: () => void) => {
                const controller = new AbortController();

                try {
                    const path = typeof source === 'string' ? source : null;
                    const url = await this.resolveFileUrl(path);

                    if (!url) {
                        throw new Error('File path missing');
                    }

                    progress(true, 0, 1);
                    const response = await fetch(url, {
                        signal: controller.signal,
                        credentials: 'include',
                    });

                    if (!response.ok) {
                        throw new Error('Failed to load file');
                    }

                    const blob = await response.blob();
                    progress(true, 1, 1);
                    load(blob);
                } catch (err: any) {
                    if (err.name === 'AbortError') return;
                    error(err?.message || 'Unable to load file');
                }

                return {
                    abort: () => {
                        controller.abort();
                        abort();
                    },
                };
            },
        };
    }

    /**
     * Create FilePond imageEditEditor configuration with Cropper.js
     * This uses FilePond's native image-edit plugin API
     */
    private createImageEditEditor(): Record<string, unknown> {
        const self = this;
        // Flag to prevent re-opening editor when adding an already-edited file
        let isAddingEditedFile = false;

        return {
            // Open editor - create Cropper.js instance
            open: (file: File, _instructions: any, onconfirm: (file: File) => void, oncancel: () => void) => {
                // Skip if we're programmatically adding an edited file
                if (isAddingEditedFile) {
                    return { onclose: () => {} };
                }

                // Store original file item to replace after edit (for already-loaded files)
                let originalFileItem: any = null;
                if (self.pond) {
                    const allFiles = self.pond.getFiles();
                    originalFileItem = allFiles.find((item: any) => {
                        return item.file === file || item.filename === file.name;
                    });
                }

                // Create editor overlay
                const overlay = self.createEditorOverlay();

                // Create editor window
                const editorWindow = self.createEditorWindow();

                // Create image container
                const imgContainer = self.createImageContainer();

                // Create image element
                const img = document.createElement('img');
                img.className = 'filepond-image-editor-image max-w-full max-h-full';

                // Add circular class if circleCropper or avatar mode is enabled
                if (self.config.circleCropper || self.config.isAvatar) {
                    img.classList.add('filepond-image-editor-image-circle');
                }

                imgContainer.appendChild(img);
                editorWindow.appendChild(imgContainer);

                // Create toolbar
                const toolbar = document.createElement('div');
                toolbar.className = 'filepond-image-editor-toolbar';

                // Create aspect ratio selector if aspect ratios are provided
                let aspectRatioGroup: HTMLDivElement | null = null;
                if (self.config.imageEditorAspectRatios && self.config.imageEditorAspectRatios.length > 0 && !self.config.circleCropper && !self.config.isAvatar) {
                    aspectRatioGroup = document.createElement('div');
                    aspectRatioGroup.className = 'filepond-image-editor-aspect-ratios';

                    const aspectLabel = document.createElement('span');
                    aspectLabel.className = 'filepond-image-editor-aspect-label';
                    aspectLabel.textContent = 'Aspect Ratio:';
                    aspectRatioGroup.appendChild(aspectLabel);
                }

                // Create action buttons
                const actionsGroup = document.createElement('div');
                actionsGroup.className = 'filepond-image-editor-actions';

                // Helper function to create editor buttons
                const createButton = (text: string, title: string, variant: string = 'default', className: string = '') => {
                    const btn = document.createElement('button');
                    btn.textContent = text;
                    btn.title = title;
                    btn.type = 'button';
                    btn.className = `filepond-image-editor-btn filepond-image-editor-btn-${variant}${className ? ' ' + className : ''}`;
                    return btn;
                };

                // Action buttons
                const rotateLeftBtn = createButton('↶', 'Rotate Left', 'default', 'rotate-left');
                const rotateRightBtn = createButton('↷', 'Rotate Right', 'default', 'rotate-right');
                const flipHBtn = createButton('⇄', 'Flip Horizontal', 'default', 'flip-h');
                const flipVBtn = createButton('⇅', 'Flip Vertical', 'default', 'flip-v');
                const zoomInBtn = createButton('+', 'Zoom In', 'default', 'zoom-in');
                const zoomOutBtn = createButton('−', 'Zoom Out', 'default', 'zoom-out');

                actionsGroup.appendChild(rotateLeftBtn);
                actionsGroup.appendChild(rotateRightBtn);
                actionsGroup.appendChild(flipHBtn);
                actionsGroup.appendChild(flipVBtn);
                actionsGroup.appendChild(zoomInBtn);
                actionsGroup.appendChild(zoomOutBtn);

                // Create control buttons
                const controlsGroup = document.createElement('div');
                controlsGroup.className = 'filepond-image-editor-controls';

                const cancelBtn = createButton('Cancel', 'Cancel', 'secondary');
                const resetBtn = createButton('Reset', 'Reset', 'destructive');
                const confirmBtn = createButton('Save', 'Save', 'primary');

                controlsGroup.appendChild(cancelBtn);
                controlsGroup.appendChild(resetBtn);
                controlsGroup.appendChild(confirmBtn);

                if (aspectRatioGroup) {
                    toolbar.appendChild(aspectRatioGroup);
                }
                toolbar.appendChild(actionsGroup);
                toolbar.appendChild(controlsGroup);

                editorWindow.appendChild(toolbar);
                overlay.appendChild(editorWindow);
                document.body.appendChild(overlay);

                let cropper: CropperInstance | null = null;

                // Load image
                const reader = new FileReader();
                reader.onload = (e) => {
                    img.src = e.target?.result as string;

                    // Parse aspect ratio from string format (e.g., '16:9', '4:3', '1:1')
                    const parseAspectRatio = (ratio: string | null): number => {
                        if (!ratio) return NaN;
                        const parts = ratio.split(':');
                        if (parts.length === 2) {
                            const width = parseFloat(parts[0]);
                            const height = parseFloat(parts[1]);
                            return width / height;
                        }
                        return NaN;
                    };

                    // Determine initial aspect ratio
                    let initialAspectRatio = NaN;

                    // Force 1:1 aspect ratio for circle cropper or avatar mode
                    if (self.config.circleCropper || self.config.isAvatar) {
                        initialAspectRatio = 1;
                    } else if (self.config.imageEditorAspectRatios && self.config.imageEditorAspectRatios.length > 0) {
                        // Use first aspect ratio as default
                        initialAspectRatio = parseAspectRatio(self.config.imageEditorAspectRatios[0]);
                    } else if (self.config.imageCropAspectRatio) {
                        initialAspectRatio = typeof self.config.imageCropAspectRatio === 'number'
                            ? self.config.imageCropAspectRatio
                            : parseAspectRatio(String(self.config.imageCropAspectRatio));
                    }

                    // Initialize Cropper.js v2
                    // v2 uses Web Components - options are minimal, config is done via selection element
                    cropper = new Cropper(img);

                    // Wait for cropper to be ready, then configure selection
                    setTimeout(() => {
                        const cropperSelection = cropper?.getCropperSelection();
                        if (cropperSelection && !isNaN(initialAspectRatio)) {
                            cropperSelection.aspectRatio = initialAspectRatio;
                        }
                    }, 100);

                    // Create aspect ratio buttons if aspect ratios are provided (not for circle cropper)
                    if (aspectRatioGroup && self.config.imageEditorAspectRatios && !self.config.circleCropper && !self.config.isAvatar) {
                        let activeAspectBtn: HTMLButtonElement | null = null;

                        self.config.imageEditorAspectRatios.forEach((ratio, index) => {
                            const ratioValue = parseAspectRatio(ratio);
                            const ratioBtn = createButton(
                                ratio || 'Free',
                                `Set aspect ratio to ${ratio || 'free'}`,
                                index === 0 ? 'active' : 'default'
                            );

                            if (index === 0) {
                                activeAspectBtn = ratioBtn;
                            }

                            ratioBtn.onclick = () => {
                                // In v2, aspectRatio is set on the CropperSelection element
                                const cropperSelection = cropper?.getCropperSelection();
                                if (cropperSelection) {
                                    cropperSelection.aspectRatio = ratioValue;
                                }

                                // Update active state
                                if (activeAspectBtn) {
                                    activeAspectBtn.classList.remove('filepond-image-editor-btn-active');
                                }
                                ratioBtn.classList.add('filepond-image-editor-btn-active');
                                activeAspectBtn = ratioBtn;
                            };

                            aspectRatioGroup?.appendChild(ratioBtn);
                        });
                    }

                    // Action button handlers - Cropper.js v2 API
                    // In v2, methods are on CropperImage and CropperSelection elements
                    let flipXState = 1;
                    let flipYState = 1;

                    rotateLeftBtn.onclick = () => {
                        const cropperImage = cropper?.getCropperImage();
                        cropperImage?.$rotate('-90deg');
                    };
                    rotateRightBtn.onclick = () => {
                        const cropperImage = cropper?.getCropperImage();
                        cropperImage?.$rotate('90deg');
                    };
                    flipHBtn.onclick = () => {
                        const cropperImage = cropper?.getCropperImage();
                        if (!cropperImage) return;
                        flipXState *= -1;
                        cropperImage.$scale(flipXState, flipYState);
                    };
                    flipVBtn.onclick = () => {
                        const cropperImage = cropper?.getCropperImage();
                        if (!cropperImage) return;
                        flipYState *= -1;
                        cropperImage.$scale(flipXState, flipYState);
                    };
                    zoomInBtn.onclick = () => {
                        const cropperImage = cropper?.getCropperImage();
                        cropperImage?.$zoom(0.1);
                    };
                    zoomOutBtn.onclick = () => {
                        const cropperImage = cropper?.getCropperImage();
                        cropperImage?.$zoom(-0.1);
                    };
                    resetBtn.onclick = () => {
                        const cropperImage = cropper?.getCropperImage();
                        const cropperSelection = cropper?.getCropperSelection();
                        cropperImage?.$resetTransform();
                        cropperSelection?.$reset();
                        flipXState = 1;
                        flipYState = 1;
                    };

                    // Handle confirm
                    confirmBtn.onclick = () => {
                        if (!cropper) {
                            cleanup();
                            if (typeof oncancel === 'function') {
                                oncancel();
                            }
                            return;
                        }

                        // Cropper.js v2: use getCropperSelection().$toCanvas() which returns a Promise
                        const cropperSelection = cropper.getCropperSelection();
                        if (!cropperSelection) {
                            console.error('[FileUpload] No cropper selection found');
                            cleanup();
                            if (typeof oncancel === 'function') {
                                oncancel();
                            }
                            return;
                        }

                        cropperSelection.$toCanvas({
                            width: self.config.imageResize.width || 4096,
                            height: self.config.imageResize.height || undefined,
                        }).then((canvas: HTMLCanvasElement) => {
                            canvas.toBlob((blob: Blob | null) => {
                                if (!blob) {
                                    console.error('[FileUpload] Failed to create blob from cropped image');
                                    cleanup();
                                    if (typeof oncancel === 'function') {
                                        oncancel();
                                    }
                                    return;
                                }

                                const editedFile = new File([blob], file.name, {
                                    type: file.type || 'image/jpeg',
                                    lastModified: Date.now(),
                                });

                                // Handle edited file - always remove old and add new
                                // FilePond's imageEditEditor API doesn't always provide onconfirm callback
                                // So we handle ALL cases by removing the old file and adding the edited one
                                if (originalFileItem && self.pond) {
                                    const allFiles = self.pond.getFiles();
                                    const originalIndex = allFiles.indexOf(originalFileItem);

                                    // Remove the old file without reverting on server
                                    self.pond.removeFile(originalFileItem.id, { revert: false });

                                    // Wait a tick for removal to complete, then add the edited file
                                    setTimeout(() => {
                                        if (!self.pond) {
                                            cleanup();
                                            return;
                                        }

                                        // Set flag to prevent editor from re-opening
                                        isAddingEditedFile = true;

                                        // Add the edited file back at the same position
                                        self.pond.addFile(editedFile, {
                                            index: originalIndex >= 0 ? originalIndex : undefined,
                                        }).then(() => {
                                            // Reset flag after file is added
                                            isAddingEditedFile = false;
                                        }).catch(() => {
                                            isAddingEditedFile = false;
                                        });

                                        cleanup();
                                    }, 50);
                                } else if (typeof onconfirm === 'function') {
                                    // Fallback to onconfirm if available
                                    cleanup();
                                    onconfirm(editedFile);
                                } else {
                                    // Last resort - just add the file
                                    if (self.pond) {
                                        self.pond.addFile(editedFile);
                                    }
                                    cleanup();
                                }
                            }, file.type || 'image/jpeg', 0.92);
                        }).catch((err: Error) => {
                            console.error('[FileUpload] Failed to get cropped canvas:', err);
                            cleanup();
                            if (typeof oncancel === 'function') {
                                oncancel();
                            }
                        });
                    };

                    // Handle cancel
                    cancelBtn.onclick = () => {
                        cleanup();
                        if (typeof oncancel === 'function') {
                            oncancel();
                        }
                    };
                };

                reader.readAsDataURL(file);

                // Cleanup function
                const cleanup = () => {
                    if (cropper) {
                        cropper.destroy();
                        cropper = null;
                    }
                    if (overlay.parentNode) {
                        document.body.removeChild(overlay);
                    }
                    document.removeEventListener('keydown', handleEscape);
                };

                // Close on overlay click
                overlay.onclick = (e) => {
                    if (e.target === overlay) {
                        cleanup();
                        if (typeof oncancel === 'function') {
                            oncancel();
                        }
                    }
                };

                // Close on Escape key
                const handleEscape = (e: KeyboardEvent) => {
                    if (e.key === 'Escape') {
                        cleanup();
                        if (typeof oncancel === 'function') {
                            oncancel();
                        }
                    }
                };
                document.addEventListener('keydown', handleEscape);

                // Return object with onclose callback for FilePond
                return {
                    onclose: () => {
                        cleanup();
                    },
                };
            },
        };
    }

    /**
     * Create editor overlay element
     */
    private createEditorOverlay(): HTMLElement {
        const overlay = document.createElement('div');
        overlay.className = 'filepond-image-editor-overlay fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-5';
        return overlay;
    }

    /**
     * Create editor window element
     */
    private createEditorWindow(): HTMLElement {
        const window = document.createElement('div');
        window.className = 'filepond-image-editor-window flex flex-col w-full max-w-6xl h-[85vh] bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden';

        if (this.config.imageEditorViewportWidth) {
            window.style.width = this.config.imageEditorViewportWidth;
        }
        if (this.config.imageEditorViewportHeight) {
            window.style.height = this.config.imageEditorViewportHeight;
        }

        return window;
    }

    /**
     * Create image container element
     */
    private createImageContainer(): HTMLElement {
        const container = document.createElement('div');
        container.className = 'flex-1 flex items-center justify-center p-4 bg-gray-100 dark:bg-gray-900 min-h-[500px] overflow-hidden';

        if (this.config.imageEditorEmptyFillColor) {
            container.style.backgroundColor = this.config.imageEditorEmptyFillColor;
        }

        return container;
    }

    /**
     * Load existing files into FilePond
     */
    private loadExistingFiles(): void {
        const existingValue = this.input.dataset.value;
        if (!existingValue) return;

        try {
            const files = JSON.parse(existingValue);
            const filePaths = Array.isArray(files) ? files : [files];

            filePaths.forEach((path: string) => {
                if (path) {
                    this.pond?.addFile(path, { type: 'local' });
                }
            });
        } catch (e) {
            // Single value
            if (existingValue) {
                this.pond?.addFile(existingValue, { type: 'local' });
            }
        }
    }

    /**
     * Resolve file URL (get temporary URL for private files)
     */
    private async resolveFileUrl(path: string | null): Promise<string | null> {
        if (!path) return null;

        // Check cache
        if (this.previewUrlCache.has(path)) {
            return this.previewUrlCache.get(path) || null;
        }

        // Already absolute URL
        if (/^https?:\/\//.test(path)) {
            this.previewUrlCache.set(path, path);
            return path;
        }

        // Already has /storage/ prefix
        if (path.startsWith('/storage/')) {
            this.previewUrlCache.set(path, path);
            return path;
        }

        // For private files, get temporary URL
        if (this.config.visibility === 'private' || this.config.disk === 'local') {
            try {
                const response = await fetch(`${this.config.uploadUrl}/temporary-url`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                        'X-Upload-Token': this.config.uploadToken,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({ path, disk: this.config.disk }),
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.url) {
                        this.previewUrlCache.set(path, data.url);
                        return data.url;
                    }
                }
            } catch (e) {
                // Fall through to public URL
            }
        }

        // Construct public URL
        const cleanPath = path.startsWith('/') ? path.substring(1) : path;
        const publicUrl = `/storage/${cleanPath}`;
        this.previewUrlCache.set(path, publicUrl);
        return publicUrl;
    }

    /**
     * Apply avatar mode styles
     */
    private applyAvatarStyles(): void {
        this.container.classList.add('filepond-avatar-mode');
    }

    /**
     * Inject download buttons into FilePond items
     */
    private injectDownloadButtons(): void {
        if (!this.pond) return;

        const fileItems = this.container.querySelectorAll('.filepond--item');
        fileItems.forEach((item) => {
            if (item.querySelector('.filepond-download-button')) return;

            const actionsContainer = item.querySelector('.filepond--action-remove-item')?.parentElement;
            if (!actionsContainer) return;

            const fileId = item.getAttribute('data-id');
            if (!fileId) return;

            const files = this.pond!.getFiles();
            const fileItem = files.find((f: FilePondFile) => f.id === fileId);
            if (!fileItem?.serverId) return;

            const downloadButton = document.createElement('button');
            downloadButton.type = 'button';
            downloadButton.className = 'filepond--file-action-button filepond-download-button';
            downloadButton.title = 'Download file';
            downloadButton.innerHTML = `
                <svg width="26" height="26" viewBox="0 0 26 26" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13 3v12m0 0l-4-4m4 4l4-4M5 17v4h14v-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            `;

            downloadButton.addEventListener('click', async (e) => {
                e.preventDefault();
                e.stopPropagation();

                const url = await this.resolveFileUrl(fileItem.serverId as string);
                if (url) {
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = fileItem.filename || 'download';
                    a.style.display = 'none';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                }
            });

            const removeButton = actionsContainer.querySelector('.filepond--action-remove-item');
            if (removeButton) {
                actionsContainer.insertBefore(downloadButton, removeButton);
            } else {
                actionsContainer.appendChild(downloadButton);
            }
        });
    }

    /**
     * Inject open buttons into FilePond items
     */
    private injectOpenButtons(): void {
        if (!this.pond) return;

        const fileItems = this.container.querySelectorAll('.filepond--item');
        fileItems.forEach((item) => {
            if (item.querySelector('.filepond-open-button')) return;

            const actionsContainer = item.querySelector('.filepond--action-remove-item')?.parentElement;
            if (!actionsContainer) return;

            const fileId = item.getAttribute('data-id');
            if (!fileId) return;

            const files = this.pond!.getFiles();
            const fileItem = files.find((f: FilePondFile) => f.id === fileId);
            if (!fileItem?.serverId) return;

            const openButton = document.createElement('button');
            openButton.type = 'button';
            openButton.className = 'filepond--file-action-button filepond-open-button';
            openButton.title = 'Open file in new tab';
            openButton.innerHTML = `
                <svg width="26" height="26" viewBox="0 0 26 26" xmlns="http://www.w3.org/2000/svg">
                    <path d="M14 3h7v7M21 3L10 14M18 13v8H5V8h8" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            `;

            openButton.addEventListener('click', async (e) => {
                e.preventDefault();
                e.stopPropagation();

                const url = await this.resolveFileUrl(fileItem.serverId as string);
                if (url) {
                    window.open(url, '_blank', 'noopener,noreferrer');
                }
            });

            const removeButton = actionsContainer.querySelector('.filepond--action-remove-item');
            if (removeButton) {
                actionsContainer.insertBefore(openButton, removeButton);
            } else {
                actionsContainer.appendChild(openButton);
            }
        });
    }

    /**
     * Initialize native file upload (fallback mode)
     */
    private initNativeUpload(): void {
        const dropzone = this.container.querySelector('.file-upload-dropzone') as HTMLElement;
        const fileList = this.container.querySelector('.file-upload-list') as HTMLElement;

        if (!dropzone || !fileList) return;

        // Drag and drop handlers
        dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzone.classList.add('border-primary-500', 'bg-primary-50', 'dark:bg-primary-900/20');
        });

        dropzone.addEventListener('dragleave', () => {
            dropzone.classList.remove('border-primary-500', 'bg-primary-50', 'dark:bg-primary-900/20');
        });

        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.classList.remove('border-primary-500', 'bg-primary-50', 'dark:bg-primary-900/20');

            const files = e.dataTransfer?.files;
            if (files) {
                this.input.files = files;
                this.updateNativeFileList(files, fileList);
            }
        });

        // File input change handler
        this.input.addEventListener('change', () => {
            if (this.input.files) {
                this.updateNativeFileList(this.input.files, fileList);
            }
        });
    }

    /**
     * Update native file list display
     */
    private updateNativeFileList(files: FileList, listElement: HTMLElement): void {
        listElement.innerHTML = '';

        if (files.length === 0) {
            listElement.classList.add('hidden');
            return;
        }

        listElement.classList.remove('hidden');

        Array.from(files).forEach((file, index) => {
            const item = document.createElement('div');
            item.className = 'flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg';

            const isImage = file.type.startsWith('image/');
            const preview = isImage ? this.createImagePreview(file) : this.createFileIcon();

            const info = document.createElement('div');
            info.className = 'flex-1 min-w-0';
            info.innerHTML = `
                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">${file.name}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">${this.formatFileSize(file.size)}</p>
            `;

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'p-1 text-gray-400 hover:text-red-500 transition-colors';
            removeBtn.innerHTML = `
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            `;
            removeBtn.addEventListener('click', () => {
                // Remove file from input (requires creating new FileList)
                const dt = new DataTransfer();
                Array.from(this.input.files || []).forEach((f, i) => {
                    if (i !== index) dt.items.add(f);
                });
                this.input.files = dt.files;
                this.updateNativeFileList(dt.files, listElement);
            });

            item.appendChild(preview);
            item.appendChild(info);
            item.appendChild(removeBtn);
            listElement.appendChild(item);
        });
    }

    /**
     * Create image preview element
     */
    private createImagePreview(file: File): HTMLElement {
        const container = document.createElement('div');
        container.className = 'w-12 h-12 rounded-lg overflow-hidden bg-gray-200 dark:bg-gray-600 shrink-0';

        const img = document.createElement('img');
        img.className = 'w-full h-full object-cover';

        const reader = new FileReader();
        reader.onload = (e) => {
            img.src = e.target?.result as string;
        };
        reader.readAsDataURL(file);

        container.appendChild(img);
        return container;
    }

    /**
     * Create file icon element
     */
    private createFileIcon(): HTMLElement {
        const container = document.createElement('div');
        container.className = 'w-12 h-12 rounded-lg bg-gray-200 dark:bg-gray-600 flex items-center justify-center shrink-0';
        container.innerHTML = `
            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        `;
        return container;
    }

    /**
     * Format file size to human readable format
     */
    private formatFileSize(bytes: number): string {
        const units = ['B', 'KB', 'MB', 'GB'];
        let size = bytes;
        let unitIndex = 0;

        while (size >= 1024 && unitIndex < units.length - 1) {
            size /= 1024;
            unitIndex++;
        }

        return `${size.toFixed(1)} ${units[unitIndex]}`;
    }

    /**
     * Get current file values
     */
    public getValues(): string[] {
        if (this.config.isNative) {
            return [];
        }

        return this.pond?.getFiles()
            .map((file: FilePondFile) => file.serverId as string)
            .filter((id: string | null) => id !== null) || [];
    }

    /**
     * Destroy the instance
     */
    public destroy(): void {
        this.pond?.destroy();
        this.previewUrlCache.clear();
    }
}

/**
 * Initialize all file upload components
 */
export function initFileUploads(): void {
    const fields = document.querySelectorAll<HTMLElement>('.file-upload-field:not([data-initialized])');

    fields.forEach((container) => {
        try {
            new FileUploadManager(container);
            container.dataset.initialized = 'true';
        } catch (error) {
            // Silent fail - errors can be debugged by checking the DOM
        }
    });
}

// Add static initAll method for consistency with other managers
FileUploadManager.initAll = initFileUploads;

// Export for global access
if (typeof window !== 'undefined') {
    (window as any).FileUploadManager = FileUploadManager;
    (window as any).initFileUploads = initFileUploads;
}
