@props(['field'])

@php
    $isDisabled = $field->isDisabled();
    $isMultiple = $field->isMultiple();
    $isImage = $field->isImage();
    $isNative = $field->isNative();
    $isAvatar = $field->isAvatar();
    $hasMediaBrowser = $field->hasMediaBrowser();
    $config = $field->getJavaScriptConfig();
    $uniqueId = $field->getId() . '-' . uniqid();
@endphp

{{-- Native HTML File Upload --}}
@if($isNative)
<div class="form-field file-upload-field file-upload-native {{ config('forms.styles.field', 'mb-4') }}"
    data-file-upload-native
    data-multiple="{{ $isMultiple ? 'true' : 'false' }}"
    data-is-image="{{ $isImage ? 'true' : 'false' }}"
    data-max-size="{{ $field->getMaxSize() }}"
    data-accepted-types="{{ $field->getAcceptedFileTypes() }}"
>
    @if($field->getLabel())
        <label for="{{ $field->getId() }}" class="{{ config('forms.styles.label', 'block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1.5') }}">
            {{ $field->getLabel() }}
            @if($field->isRequired())
                <span class="{{ config('forms.styles.required', 'text-red-500 dark:text-red-400 ms-0.5') }}">*</span>
            @endif
        </label>
    @endif

    <div class="file-upload-dropzone relative flex justify-center rounded-lg border-2 border-dashed border-gray-300 bg-white px-6 py-10 transition-colors duration-150 hover:border-primary-400 dark:border-gray-600 dark:bg-gray-800 dark:hover:border-primary-500 {{ $isDisabled ? 'opacity-50 cursor-not-allowed' : '' }}"
        data-dropzone
    >
        <div class="text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500 file-upload-icon" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <div class="mt-4 flex justify-center text-sm leading-6 text-gray-600 dark:text-gray-400">
                <label for="{{ $field->getId() }}" class="relative cursor-pointer rounded-md font-semibold text-primary-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-primary-600 focus-within:ring-offset-2 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 dark:focus-within:ring-offset-gray-800">
                    <span>{{ __('Upload a file') }}</span>
                    <input
                        type="file"
                        name="{{ $field->getName() }}{{ $isMultiple ? '[]' : '' }}"
                        id="{{ $field->getId() }}"
                        @if($isMultiple) multiple @endif
                        @if($field->isRequired()) required @endif
                        @if($isDisabled) disabled @endif
                        @if($field->getAcceptedFileTypes()) accept="{{ $field->getAcceptedFileTypes() }}" @endif
                        class="sr-only file-upload-input"
                        {!! $field->getExtraAttributesString() !!}
                    >
                </label>
                <p class="ps-1">{{ __('or drag and drop') }}</p>
            </div>
            <p class="text-xs leading-5 text-gray-500 dark:text-gray-400 mt-1">
                @if($isImage)
                    PNG, JPG, GIF
                @endif
                @if($field->getMaxSize())
                    {{ __('up to') }} {{ round($field->getMaxSize() / 1024, 1) }}MB
                @endif
            </p>
        </div>
    </div>

    {{-- File list / Preview area for native --}}
    <div class="file-upload-list mt-3 space-y-2 hidden" data-file-list></div>

    @if($field->getHelperText())
        <p class="{{ config('forms.styles.hint', 'text-sm text-gray-500 dark:text-gray-400 mt-1.5') }}">
            {{ $field->getHelperText() }}
        </p>
    @endif

    @if(isset($errors) && $errors->has($field->getName()))
        <p class="{{ config('forms.errors.classes', 'text-sm text-red-600 dark:text-red-400 mt-1') }}">
            {{ $errors->first($field->getName()) }}
        </p>
    @endif
</div>

{{-- FilePond File Upload --}}
@else
<div class="form-field file-upload-field file-upload-filepond {{ config('forms.styles.field', 'mb-4') }} {{ $isAvatar ? 'file-upload-avatar' : '' }}"
    data-file-upload
    data-file-upload-config="{{ json_encode($config) }}"
    id="{{ $uniqueId }}"
>
    @if($field->getLabel())
        <label for="{{ $field->getId() }}" class="{{ config('forms.styles.label', 'block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1.5') }}">
            {{ $field->getLabel() }}
            @if($field->isRequired())
                <span class="{{ config('forms.styles.required', 'text-red-500 dark:text-red-400 ms-0.5') }}">*</span>
            @endif
        </label>
    @endif

    {{-- FilePond Container --}}
    <div class="filepond-wrapper {{ $isAvatar ? 'filepond-avatar flex justify-center' : '' }}"
        @if($isAvatar)
        style="--filepond-panel-root-max-height: 200px;"
        @endif
    >
        {{-- Hidden input that FilePond will enhance --}}
        <input
            type="file"
            name="{{ $field->getName() }}{{ $isMultiple ? '[]' : '' }}"
            id="{{ $field->getId() }}"
            @if($isMultiple) multiple @endif
            @if($field->isRequired()) required @endif
            @if($isDisabled) disabled @endif
            @if($field->getAcceptedFileTypes()) accept="{{ $field->getAcceptedFileTypes() }}" @endif
            class="filepond-input"
            {!! $field->getExtraAttributesString() !!}
        >
    </div>

    {{-- Media Browser Button --}}
    @if($hasMediaBrowser)
        <div class="mt-2">
            <button
                type="button"
                class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors"
                data-media-browser-trigger="{{ $uniqueId }}"
                data-media-browser
                data-media-browser-endpoint="{{ route('forms.media.index') }}"
                data-media-browser-token="{{ $field->getUploadToken() }}"
                data-media-browser-multiple="{{ $isMultiple ? 'true' : 'false' }}"
                data-media-browser-max-selection="{{ $field->getMaxFiles() }}"
                data-media-browser-accepted-types="{{ $field->getAcceptedFileTypes() }}"
                data-media-browser-default-collection="{{ $field->getCollection() }}"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                {{ __('Browse Media Library') }}
            </button>
        </div>
    @endif

    @if($field->getHelperText())
        <p class="{{ config('forms.styles.hint', 'text-sm text-gray-500 dark:text-gray-400 mt-1.5') }}">
            {{ $field->getHelperText() }}
        </p>
    @endif

    @if(isset($errors) && $errors->has($field->getName()))
        <p class="{{ config('forms.errors.classes', 'text-sm text-red-600 dark:text-red-400 mt-1') }}">
            {{ $errors->first($field->getName()) }}
        </p>
    @endif
</div>
@endif

@once
@push('styles')
{{-- FilePond and Cropper.js CSS are now bundled via npm imports in FileUploadManager.ts --}}
<style>
/* ================================================
   FilePond Filament Theme - Orange Primary (like FilamentPHP)
   ================================================ */

/* Base FilePond Root */
.filepond--root {
    font-family: inherit;
    margin-bottom: 0;
}

/* Panel Root - Remove extra borders */
.filepond--panel-root {
    background-color: transparent !important;
    border: none !important;
}

/* Root - Single dashed border */
.filepond--root {
    background-color: transparent !important;
    border: 2px dashed rgb(75 85 99) !important;
    border-radius: 0.5rem !important;
    padding: 0.5rem !important;
}

.filepond--root:hover {
    border-color: var(--color-primary-400, #fb923c) !important;
}

/* Dragging/Drop State */
.filepond--root[data-hopper="active"] {
    border-color: var(--color-primary-500, #f97316) !important;
    background-color: rgba(251, 146, 60, 0.1) !important;
}

/* Drop Label - The idle text */
.filepond--drop-label {
    color: rgb(107 114 128) !important;
    font-size: 0.875rem !important;
    padding: 2.5rem 1.5rem !important;
    min-height: 6rem !important;
}

.filepond--drop-label label {
    cursor: pointer;
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

/* Label Action - The "Upload a file" link - Primary color like native */
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

/* File Item Panel - Orange gradient like Filament's primary color */
.filepond--item-panel {
    background: linear-gradient(180deg, #ea580c 0%, #c2410c 100%) !important;
    border-radius: 0.5rem !important;
}

/* File Info - White text on colored background */
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

/* File Status - White text */
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

/* Action Buttons - White on colored background */
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

/* Progress Indicator - White spinner */
.filepond--progress-indicator {
    color: #fff !important;
}

.filepond--load-indicator {
    color: #fff !important;
}

/* Processing Ring - White */
.filepond--file-wrapper .filepond--file .filepond--progress-indicator svg {
    color: #fff !important;
}

/* Processing Complete Indicator - White checkmark */
.filepond--processing-complete-indicator {
    color: #fff !important;
}

/* Error State - Red gradient */
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

/* Success/Complete State - Green gradient like Filament */
.filepond--item[data-filepond-item-state="processing-complete"] .filepond--item-panel {
    background: linear-gradient(180deg, #10b981 0%, #059669 100%) !important;
}

/* Image Preview */
.filepond--image-preview-wrapper {
    background-color: rgb(17 24 39) !important;
    border-radius: 0.375rem !important;
}

.filepond--image-preview {
    background-color: rgb(17 24 39) !important;
}

/* Credits Hidden */
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

/* Drip Blob Animation - Orange */
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

/* ================================================
   Avatar Mode Styles
   ================================================ */
.filepond-avatar .filepond--root,
.file-upload-avatar .filepond--root {
    width: 150px;
    margin: 0 auto;
}

.filepond-avatar .filepond--drop-label,
.file-upload-avatar .filepond--drop-label {
    min-height: 150px;
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

/* Circle Cropper */
.filepond--image-preview.circle-mask .filepond--image-preview-wrapper {
    border-radius: 9999px !important;
}

/* ================================================
   Dark Mode Support - Filament Style
   ================================================ */
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

/* File item panel keeps colored gradient in dark mode */

.dark .filepond--drip-blob {
    background-color: rgba(251, 146, 60, 0.3) !important;
}

.dark .filepond--image-preview-wrapper {
    background-color: rgb(17 24 39) !important;
}

.dark .filepond--image-preview {
    background-color: rgb(17 24 39) !important;
}

/* ================================================
   Download/Open Button Styles
   ================================================ */
.filepond-download-button,
.filepond-open-button {
    background: transparent !important;
    border: none !important;
    padding: 0.25rem !important;
    margin: 0 0.125rem !important;
}

.filepond-download-button svg,
.filepond-open-button svg {
    width: 1.25rem;
    height: 1.25rem;
}

/* ================================================
   Native Upload Styles - Orange Accent
   ================================================ */
.file-upload-dropzone {
    transition: all 0.15s ease-in-out;
}

.file-upload-dropzone:focus-within {
    border-color: #ea580c !important;
    box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.1) !important;
}

.file-upload-dropzone.drag-over {
    border-color: #ea580c !important;
    background-color: #fff7ed !important;
    box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.1) !important;
}

.dark .file-upload-dropzone {
    background-color: rgb(55 65 81);
    border-color: rgb(75 85 99);
}

.dark .file-upload-dropzone:hover {
    border-color: rgb(107 114 128);
}

.dark .file-upload-dropzone:focus-within {
    border-color: #fb923c !important;
    box-shadow: 0 0 0 3px rgba(251, 146, 60, 0.15) !important;
}

.dark .file-upload-dropzone.drag-over {
    border-color: #fb923c !important;
    background-color: rgba(234, 88, 12, 0.15) !important;
    box-shadow: 0 0 0 3px rgba(251, 146, 60, 0.15) !important;
}

/* Native File List */
.file-upload-list .file-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: white;
    border: 1px solid rgb(229 231 235);
    border-radius: 0.5rem;
}

.dark .file-upload-list .file-item {
    background: rgb(55 65 81);
    border-color: rgb(75 85 99);
}

.file-upload-list .file-item .file-preview {
    width: 3rem;
    height: 3rem;
    flex-shrink: 0;
    border-radius: 0.375rem;
    overflow: hidden;
    background: rgb(243 244 246);
}

.dark .file-upload-list .file-item .file-preview {
    background: rgb(75 85 99);
}

.file-upload-list .file-item .file-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.file-upload-list .file-item .file-info {
    flex: 1;
    min-width: 0;
}

.file-upload-list .file-item .file-name {
    font-size: 0.875rem;
    font-weight: 500;
    color: rgb(17 24 39);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.dark .file-upload-list .file-item .file-name {
    color: rgb(243 244 246);
}

.file-upload-list .file-item .file-size {
    font-size: 0.75rem;
    color: rgb(107 114 128);
}

.file-upload-list .file-item .file-remove {
    padding: 0.25rem;
    color: rgb(107 114 128);
    cursor: pointer;
    transition: color 0.15s;
}

.file-upload-list .file-item .file-remove:hover {
    color: rgb(239 68 68);
}

/* ================================================
   Image Editor Modal Styles
   ================================================ */
.filepond-image-editor-overlay {
    z-index: 9999 !important;
}

.filepond-image-editor-toolbar {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    background: rgb(249 250 251);
    border-top: 1px solid rgb(229 231 235);
}

.dark .filepond-image-editor-toolbar {
    background: rgb(31 41 55);
    border-color: rgb(55 65 81);
}

.filepond-image-editor-actions,
.filepond-image-editor-controls {
    display: flex;
    align-items: center;
    gap: 0.375rem;
}

.filepond-image-editor-aspect-ratios {
    display: flex;
    align-items: center;
    gap: 0.375rem;
}

.filepond-image-editor-aspect-label {
    font-size: 0.75rem;
    font-weight: 500;
    color: rgb(107 114 128);
    margin-right: 0.25rem;
}

.dark .filepond-image-editor-aspect-label {
    color: rgb(156 163 175);
}

.filepond-image-editor-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 2rem;
    height: 2rem;
    padding: 0 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    border-radius: 0.375rem;
    border: 1px solid transparent;
    cursor: pointer;
    transition: all 0.15s ease-in-out;
}

.filepond-image-editor-btn-default {
    background: rgb(255 255 255);
    border-color: rgb(209 213 219);
    color: rgb(55 65 81);
}

.filepond-image-editor-btn-default:hover {
    background: rgb(243 244 246);
    border-color: rgb(156 163 175);
}

.dark .filepond-image-editor-btn-default {
    background: rgb(55 65 81);
    border-color: rgb(75 85 99);
    color: rgb(229 231 235);
}

.dark .filepond-image-editor-btn-default:hover {
    background: rgb(75 85 99);
    border-color: rgb(107 114 128);
}

.filepond-image-editor-btn-primary {
    background: #ea580c;
    border-color: #ea580c;
    color: rgb(255 255 255);
}

.filepond-image-editor-btn-primary:hover {
    background: #c2410c;
    border-color: #c2410c;
}

.filepond-image-editor-btn-secondary {
    background: rgb(255 255 255);
    border-color: rgb(209 213 219);
    color: rgb(55 65 81);
}

.filepond-image-editor-btn-secondary:hover {
    background: rgb(243 244 246);
}

.dark .filepond-image-editor-btn-secondary {
    background: rgb(55 65 81);
    border-color: rgb(75 85 99);
    color: rgb(229 231 235);
}

.dark .filepond-image-editor-btn-secondary:hover {
    background: rgb(75 85 99);
}

.filepond-image-editor-btn-destructive {
    background: rgb(255 255 255);
    border-color: rgb(252 165 165);
    color: rgb(185 28 28);
}

.filepond-image-editor-btn-destructive:hover {
    background: rgb(254 242 242);
    border-color: rgb(248 113 113);
}

.dark .filepond-image-editor-btn-destructive {
    background: rgb(127 29 29 / 0.3);
    border-color: rgb(185 28 28);
    color: rgb(248 113 113);
}

.dark .filepond-image-editor-btn-destructive:hover {
    background: rgb(127 29 29 / 0.5);
}

.filepond-image-editor-btn-active {
    background: #ea580c !important;
    border-color: #ea580c !important;
    color: rgb(255 255 255) !important;
}
</style>
@endpush

{{-- FilePond and Cropper.js are now bundled via npm imports in FileUploadManager.ts --}}
{{-- No external scripts needed - everything is included in the forms.js bundle --}}
@endonce
