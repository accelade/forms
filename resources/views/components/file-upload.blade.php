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
/* FilePond Base Styles */
.filepond--root {
    font-family: inherit;
}

/* Avatar Mode Styles */
.filepond-avatar .filepond--root {
    width: 150px;
    margin: 0 auto;
}

.filepond-avatar .filepond--drop-label {
    min-height: 150px;
}

.filepond-avatar .filepond--image-preview-wrapper {
    border-radius: 9999px;
}

.filepond-avatar .filepond--panel-root {
    border-radius: 9999px;
}

.filepond-avatar .filepond--item-panel {
    border-radius: 9999px;
}

/* Circle Cropper */
.filepond--image-preview.circle-mask .filepond--image-preview-wrapper {
    border-radius: 9999px;
}

/* Dark Mode Support */
.dark .filepond--panel-root {
    background-color: rgb(31 41 55);
}

.dark .filepond--drop-label {
    color: rgb(156 163 175);
}

.dark .filepond--label-action {
    color: rgb(96 165 250);
}

.dark .filepond--label-action:hover {
    color: rgb(147 197 253);
}

.dark .filepond--drip-blob {
    background-color: rgb(55 65 81);
}

/* Native Upload Drag States */
.file-upload-dropzone.drag-over {
    border-color: rgb(59 130 246);
    background-color: rgb(239 246 255);
}

.dark .file-upload-dropzone.drag-over {
    background-color: rgb(30 58 138 / 0.2);
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
    background: rgb(31 41 55);
    border-color: rgb(55 65 81);
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
    background: rgb(55 65 81);
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
</style>
@endpush

{{-- FilePond and Cropper.js are now bundled via npm imports in FileUploadManager.ts --}}
{{-- No external scripts needed - everything is included in the forms.js bundle --}}
@endonce
