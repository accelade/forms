<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Field;
use Accelade\Forms\Support\FileUploadToken;
use Closure;

/**
 * File upload field component with FilePond integration.
 *
 * Provides a comprehensive file upload experience with:
 * - FilePond-powered uploads with drag & drop
 * - Image preview, crop, resize, and edit capabilities
 * - Spatie Media Library integration
 * - Native HTML fallback mode
 * - Media browser for existing files
 */
class FileUpload extends Field
{
    // Storage configuration
    protected string|Closure|null $disk = null;

    protected string|Closure|null $directory = null;

    protected string|Closure $visibility = 'public';

    // File type restrictions
    protected array|string|Closure $acceptedFileTypes = [];

    // Size constraints (in KB)
    protected int|Closure|null $maxSize = null;

    protected int|Closure|null $minSize = null;

    // File count constraints
    protected int|Closure|null $maxFiles = null;

    protected int|Closure|null $minFiles = null;

    protected bool|Closure $isMultiple = false;

    // Image mode
    protected bool $isImage = false;

    protected bool|Closure $imagePreview = true;

    // Image cropping
    protected bool $imageCrop = false;

    protected string|float|Closure|null $imageCropAspectRatio = null;

    // Image resize
    protected int|Closure|null $imageResizeTargetWidth = null;

    protected int|Closure|null $imageResizeTargetHeight = null;

    protected string|Closure|null $imageResizeMode = null;

    // Image editor (Cropper.js)
    protected bool|Closure $imageEditor = false;

    protected array|Closure|null $imageEditorAspectRatios = null;

    protected int|Closure|null $imageEditorMode = null;

    protected string|Closure|null $imageEditorEmptyFillColor = null;

    protected string|Closure|null $imageEditorViewportWidth = null;

    protected string|Closure|null $imageEditorViewportHeight = null;

    protected bool|Closure $circleCropper = false;

    // Avatar mode
    protected bool|Closure $isAvatar = false;

    protected bool|Closure $alignCenter = false;

    // File actions
    protected bool|Closure $isDownloadable = false;

    protected bool|Closure $isOpenable = false;

    protected bool|Closure $isDeletable = true;

    protected bool|Closure $isReorderable = false;

    protected bool|Closure $isPreviewable = true;

    // FilePond UI options
    protected string|Closure|null $panelLayout = null;

    protected string|Closure|null $panelAspectRatio = null;

    protected string|Closure|null $imagePreviewHeight = null;

    protected string|Closure|null $loadingIndicatorPosition = null;

    protected string|Closure|null $removeUploadedFileButtonPosition = null;

    protected string|Closure|null $uploadButtonPosition = null;

    protected string|Closure|null $uploadProgressIndicatorPosition = null;

    // File handling
    protected bool|Closure $preserveFilenames = false;

    protected ?Closure $getUploadedFileNameForStorage = null;

    protected string|Closure|null $storeFileNamesIn = null;

    protected bool|Closure $appendFiles = false;

    protected bool|Closure $prependFiles = false;

    protected bool|Closure $moveFiles = true;

    protected bool|Closure $storeFiles = true;

    protected bool|Closure $orientImagesFromExif = true;

    protected bool|Closure $fetchFileInformation = true;

    protected bool|Closure $isPasteable = true;

    protected int|Closure|null $maxParallelUploads = null;

    protected string|Closure|null $uploadingMessage = null;

    protected array|Closure|null $mimeTypeMap = null;

    // Native mode (simple HTML file input)
    protected bool $isNative = false;

    // Spatie Media Library integration
    protected bool $useMediaLibrary = false;

    protected ?string $collection = null;

    protected array $customProperties = [];

    protected array $customHeaders = [];

    protected array $responsiveImages = [];

    protected array $conversions = [];

    // Media browser
    protected bool|Closure $hasMediaBrowser = false;

    // Image dimension constraints (validation)
    protected ?int $minWidth = null;

    protected ?int $maxWidth = null;

    protected ?int $minHeight = null;

    protected ?int $maxHeight = null;

    protected ?int $imageWidth = null;

    protected ?int $imageHeight = null;

    protected ?int $minResolution = null;

    protected ?int $maxResolution = null;

    /**
     * Set up the field with defaults.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->maxSize = config('forms.file_upload.max_size', 10240);
        $this->disk = config('forms.file_upload.disk', 'public');
        $this->directory = config('forms.file_upload.directory', 'uploads');
        $this->visibility = config('forms.file_upload.visibility', 'public');
    }

    // =========================================================================
    // Storage Configuration
    // =========================================================================

    /**
     * Set the storage disk.
     */
    public function disk(string|Closure|null $disk): static
    {
        $this->disk = $disk;

        return $this;
    }

    /**
     * Get the storage disk.
     */
    public function getDisk(): string
    {
        return $this->evaluate($this->disk) ?? 'public';
    }

    /**
     * Set the storage directory.
     */
    public function directory(string|Closure|null $directory): static
    {
        $this->directory = $directory;

        return $this;
    }

    /**
     * Get the storage directory.
     */
    public function getDirectory(): ?string
    {
        return $this->evaluate($this->directory);
    }

    /**
     * Set file visibility (public or private).
     */
    public function visibility(string|Closure $visibility): static
    {
        $this->visibility = $visibility;

        return $this;
    }

    /**
     * Get the visibility.
     */
    public function getVisibility(): string
    {
        return $this->evaluate($this->visibility);
    }

    // =========================================================================
    // File Type Restrictions
    // =========================================================================

    /**
     * Set accepted file types (MIME types or extensions).
     */
    public function acceptedFileTypes(array|string|Closure $types): static
    {
        $this->acceptedFileTypes = $types;

        return $this;
    }

    /**
     * Alias for acceptedFileTypes.
     */
    public function accept(array|string|Closure $types): static
    {
        return $this->acceptedFileTypes($types);
    }

    /**
     * Get accepted file types as a string for HTML accept attribute.
     */
    public function getAcceptedFileTypes(): array|string
    {
        $types = $this->evaluate($this->acceptedFileTypes);

        if (is_array($types)) {
            return implode(',', $types);
        }

        return $types;
    }

    /**
     * Get accepted file types as array.
     */
    public function getAcceptedFileTypesArray(): array
    {
        $types = $this->evaluate($this->acceptedFileTypes);

        if (is_string($types)) {
            return array_map('trim', explode(',', $types));
        }

        return $types;
    }

    // =========================================================================
    // Size Constraints
    // =========================================================================

    /**
     * Set maximum file size in kilobytes.
     */
    public function maxSize(int|Closure|null $size): static
    {
        $this->maxSize = $size;

        return $this;
    }

    /**
     * Get maximum file size in KB.
     */
    public function getMaxSize(): ?int
    {
        return $this->evaluate($this->maxSize);
    }

    /**
     * Set minimum file size in kilobytes.
     */
    public function minSize(int|Closure|null $size): static
    {
        $this->minSize = $size;

        return $this;
    }

    /**
     * Get minimum file size in KB.
     */
    public function getMinSize(): ?int
    {
        return $this->evaluate($this->minSize);
    }

    // =========================================================================
    // File Count Constraints
    // =========================================================================

    /**
     * Allow multiple file uploads.
     */
    public function multiple(bool|Closure $condition = true): static
    {
        $this->isMultiple = $condition;

        if ($condition === true && $this->maxFiles === 1) {
            $this->maxFiles = null;
        }

        return $this;
    }

    /**
     * Check if multiple uploads allowed.
     */
    public function isMultiple(): bool
    {
        return (bool) $this->evaluate($this->isMultiple);
    }

    /**
     * Set maximum number of files.
     */
    public function maxFiles(int|Closure|null $count): static
    {
        $this->maxFiles = $count;

        if ($count !== 1) {
            $this->isMultiple = true;
        }

        return $this;
    }

    /**
     * Get maximum files count.
     */
    public function getMaxFiles(): ?int
    {
        return $this->evaluate($this->maxFiles);
    }

    /**
     * Set minimum number of files.
     */
    public function minFiles(int|Closure|null $count): static
    {
        $this->minFiles = $count;

        if ($count !== null && $count > 1) {
            $this->isMultiple = true;
        }

        return $this;
    }

    /**
     * Get minimum files count.
     */
    public function getMinFiles(): ?int
    {
        return $this->evaluate($this->minFiles);
    }

    // =========================================================================
    // Image Mode
    // =========================================================================

    /**
     * Configure for image uploads only.
     */
    public function image(bool $condition = true): static
    {
        $this->isImage = $condition;

        if ($condition) {
            $this->acceptedFileTypes = ['image/png', 'image/jpeg', 'image/gif', 'image/webp', 'image/svg+xml'];
        }

        return $this;
    }

    /**
     * Check if image only.
     */
    public function isImage(): bool
    {
        return $this->isImage;
    }

    /**
     * Enable/disable image preview.
     */
    public function imagePreview(bool|Closure $condition = true): static
    {
        $this->imagePreview = $condition;

        return $this;
    }

    /**
     * Check if image preview is enabled.
     */
    public function hasImagePreview(): bool
    {
        return (bool) $this->evaluate($this->imagePreview);
    }

    // =========================================================================
    // Image Cropping
    // =========================================================================

    /**
     * Enable image cropping with optional aspect ratio.
     */
    public function imageCrop(bool|float|string|null $aspectRatio = true): static
    {
        if ($aspectRatio === false) {
            $this->imageCrop = false;
            $this->imageCropAspectRatio = null;
        } elseif (is_float($aspectRatio) || is_int($aspectRatio)) {
            $this->imageCrop = true;
            $this->imageCropAspectRatio = (float) $aspectRatio;
        } elseif (is_string($aspectRatio)) {
            $this->imageCrop = true;
            $this->imageCropAspectRatio = $aspectRatio;
        } else {
            $this->imageCrop = true;
        }

        return $this;
    }

    /**
     * Check if image crop is enabled.
     */
    public function hasImageCrop(): bool
    {
        return $this->imageCrop;
    }

    /**
     * Set image crop aspect ratio.
     */
    public function imageCropAspectRatio(string|float|Closure|null $ratio): static
    {
        $this->imageCropAspectRatio = $ratio;

        if ($ratio !== null) {
            $this->imageCrop = true;
        }

        return $this;
    }

    /**
     * Get image crop aspect ratio.
     */
    public function getImageCropAspectRatio(): string|float|null
    {
        return $this->evaluate($this->imageCropAspectRatio);
    }

    // =========================================================================
    // Image Resize
    // =========================================================================

    /**
     * Set image resize target width.
     */
    public function imageResizeTargetWidth(int|Closure|null $width): static
    {
        $this->imageResizeTargetWidth = $width;

        return $this;
    }

    /**
     * Get image resize target width.
     */
    public function getImageResizeTargetWidth(): ?int
    {
        return $this->evaluate($this->imageResizeTargetWidth);
    }

    /**
     * Set image resize target height.
     */
    public function imageResizeTargetHeight(int|Closure|null $height): static
    {
        $this->imageResizeTargetHeight = $height;

        return $this;
    }

    /**
     * Get image resize target height.
     */
    public function getImageResizeTargetHeight(): ?int
    {
        return $this->evaluate($this->imageResizeTargetHeight);
    }

    /**
     * Set image resize dimensions.
     */
    public function imageResize(?int $width = 1920, ?int $height = 1080, string $mode = 'contain'): static
    {
        $this->imageResizeTargetWidth = $width;
        $this->imageResizeTargetHeight = $height;
        $this->imageResizeMode = $mode;

        return $this;
    }

    /**
     * Set image resize mode (force/cover/contain).
     */
    public function imageResizeMode(string|Closure|null $mode): static
    {
        $this->imageResizeMode = $mode;

        return $this;
    }

    /**
     * Get image resize mode.
     */
    public function getImageResizeMode(): ?string
    {
        return $this->evaluate($this->imageResizeMode);
    }

    // =========================================================================
    // Image Editor (Cropper.js)
    // =========================================================================

    /**
     * Enable/disable image editor.
     */
    public function imageEditor(bool|Closure $condition = true): static
    {
        $this->imageEditor = $condition;

        return $this;
    }

    /**
     * Check if image editor is enabled.
     */
    public function hasImageEditor(): bool
    {
        return (bool) $this->evaluate($this->imageEditor);
    }

    /**
     * Set image editor aspect ratios.
     */
    public function imageEditorAspectRatios(array|Closure|null $ratios): static
    {
        $this->imageEditorAspectRatios = $ratios;

        return $this;
    }

    /**
     * Get image editor aspect ratios.
     */
    public function getImageEditorAspectRatios(): ?array
    {
        return $this->evaluate($this->imageEditorAspectRatios);
    }

    /**
     * Set image editor mode (Cropper.js viewMode: 0, 1, 2, 3).
     */
    public function imageEditorMode(int|Closure $mode): static
    {
        $this->imageEditorMode = $mode;

        return $this;
    }

    /**
     * Get image editor mode.
     */
    public function getImageEditorMode(): ?int
    {
        return $this->evaluate($this->imageEditorMode);
    }

    /**
     * Set image editor empty fill color.
     */
    public function imageEditorEmptyFillColor(string|Closure $color): static
    {
        $this->imageEditorEmptyFillColor = $color;

        return $this;
    }

    /**
     * Get image editor empty fill color.
     */
    public function getImageEditorEmptyFillColor(): ?string
    {
        return $this->evaluate($this->imageEditorEmptyFillColor);
    }

    /**
     * Set image editor viewport width.
     */
    public function imageEditorViewportWidth(string|Closure $width): static
    {
        $this->imageEditorViewportWidth = $width;

        return $this;
    }

    /**
     * Get image editor viewport width.
     */
    public function getImageEditorViewportWidth(): ?string
    {
        return $this->evaluate($this->imageEditorViewportWidth);
    }

    /**
     * Set image editor viewport height.
     */
    public function imageEditorViewportHeight(string|Closure $height): static
    {
        $this->imageEditorViewportHeight = $height;

        return $this;
    }

    /**
     * Get image editor viewport height.
     */
    public function getImageEditorViewportHeight(): ?string
    {
        return $this->evaluate($this->imageEditorViewportHeight);
    }

    /**
     * Enable/disable circle cropper.
     */
    public function circleCropper(bool|Closure $condition = true): static
    {
        $this->circleCropper = $condition;

        return $this;
    }

    /**
     * Check if circle cropper is enabled.
     */
    public function hasCircleCropper(): bool
    {
        return (bool) $this->evaluate($this->circleCropper);
    }

    // =========================================================================
    // Avatar Mode
    // =========================================================================

    /**
     * Configure as avatar upload (circular with 1:1 aspect ratio).
     */
    public function avatar(bool|Closure $condition = true): static
    {
        $this->isAvatar = $condition;

        if ($condition === true) {
            $this->imageCropAspectRatio = '1:1';
            $this->circleCropper = true;
            $this->imagePreview = true;
            $this->maxFiles = 1;
            $this->isMultiple = false;
            $this->image();
        }

        return $this;
    }

    /**
     * Check if avatar mode is enabled.
     */
    public function isAvatar(): bool
    {
        return (bool) $this->evaluate($this->isAvatar);
    }

    /**
     * Enable/disable center alignment.
     */
    public function alignCenter(bool|Closure $condition = true): static
    {
        $this->alignCenter = $condition;

        return $this;
    }

    /**
     * Check if center aligned.
     */
    public function isAlignCenter(): bool
    {
        return (bool) $this->evaluate($this->alignCenter);
    }

    // =========================================================================
    // File Actions
    // =========================================================================

    /**
     * Enable/disable file download.
     */
    public function downloadable(bool|Closure $condition = true): static
    {
        $this->isDownloadable = $condition;

        return $this;
    }

    /**
     * Check if downloadable.
     */
    public function isDownloadable(): bool
    {
        return (bool) $this->evaluate($this->isDownloadable);
    }

    /**
     * Enable/disable file opening in new tab.
     */
    public function openable(bool|Closure $condition = true): static
    {
        $this->isOpenable = $condition;

        return $this;
    }

    /**
     * Check if openable.
     */
    public function isOpenable(): bool
    {
        return (bool) $this->evaluate($this->isOpenable);
    }

    /**
     * Enable/disable file deletion.
     */
    public function deletable(bool|Closure $condition = true): static
    {
        $this->isDeletable = $condition;

        return $this;
    }

    /**
     * Check if deletable.
     */
    public function isDeletable(): bool
    {
        return (bool) $this->evaluate($this->isDeletable);
    }

    /**
     * Enable/disable file reordering.
     */
    public function reorderable(bool|Closure $condition = true): static
    {
        $this->isReorderable = $condition;

        return $this;
    }

    /**
     * Check if reorderable.
     */
    public function isReorderable(): bool
    {
        return (bool) $this->evaluate($this->isReorderable);
    }

    /**
     * Enable/disable file preview.
     */
    public function previewable(bool|Closure $condition = true): static
    {
        $this->isPreviewable = $condition;

        return $this;
    }

    /**
     * Check if previewable.
     */
    public function isPreviewable(): bool
    {
        return (bool) $this->evaluate($this->isPreviewable);
    }

    // =========================================================================
    // FilePond UI Options
    // =========================================================================

    /**
     * Set panel layout (compact, circle, integrated).
     */
    public function panelLayout(string|Closure|null $layout): static
    {
        $this->panelLayout = $layout;

        return $this;
    }

    /**
     * Get panel layout.
     */
    public function getPanelLayout(): ?string
    {
        return $this->evaluate($this->panelLayout);
    }

    /**
     * Set panel aspect ratio.
     */
    public function panelAspectRatio(string|Closure|null $ratio): static
    {
        $this->panelAspectRatio = $ratio;

        return $this;
    }

    /**
     * Get panel aspect ratio.
     */
    public function getPanelAspectRatio(): ?string
    {
        return $this->evaluate($this->panelAspectRatio);
    }

    /**
     * Set image preview height.
     */
    public function imagePreviewHeight(string|Closure $height): static
    {
        $this->imagePreviewHeight = $height;

        return $this;
    }

    /**
     * Get image preview height.
     */
    public function getImagePreviewHeight(): ?string
    {
        return $this->evaluate($this->imagePreviewHeight);
    }

    /**
     * Set loading indicator position.
     */
    public function loadingIndicatorPosition(string|Closure $position): static
    {
        $this->loadingIndicatorPosition = $position;

        return $this;
    }

    /**
     * Get loading indicator position.
     */
    public function getLoadingIndicatorPosition(): ?string
    {
        return $this->evaluate($this->loadingIndicatorPosition);
    }

    /**
     * Set remove uploaded file button position.
     */
    public function removeUploadedFileButtonPosition(string|Closure $position): static
    {
        $this->removeUploadedFileButtonPosition = $position;

        return $this;
    }

    /**
     * Get remove uploaded file button position.
     */
    public function getRemoveUploadedFileButtonPosition(): ?string
    {
        return $this->evaluate($this->removeUploadedFileButtonPosition);
    }

    /**
     * Set upload button position.
     */
    public function uploadButtonPosition(string|Closure $position): static
    {
        $this->uploadButtonPosition = $position;

        return $this;
    }

    /**
     * Get upload button position.
     */
    public function getUploadButtonPosition(): ?string
    {
        return $this->evaluate($this->uploadButtonPosition);
    }

    /**
     * Set upload progress indicator position.
     */
    public function uploadProgressIndicatorPosition(string|Closure $position): static
    {
        $this->uploadProgressIndicatorPosition = $position;

        return $this;
    }

    /**
     * Get upload progress indicator position.
     */
    public function getUploadProgressIndicatorPosition(): ?string
    {
        return $this->evaluate($this->uploadProgressIndicatorPosition);
    }

    // =========================================================================
    // File Handling Options
    // =========================================================================

    /**
     * Preserve original filenames when storing.
     */
    public function preserveFilenames(bool|Closure $condition = true): static
    {
        $this->preserveFilenames = $condition;

        return $this;
    }

    /**
     * Check if filenames should be preserved.
     */
    public function shouldPreserveFilenames(): bool
    {
        return (bool) $this->evaluate($this->preserveFilenames);
    }

    /**
     * Set a custom callback for generating uploaded file names.
     */
    public function getUploadedFileNameForStorageUsing(?Closure $callback): static
    {
        $this->getUploadedFileNameForStorage = $callback;

        return $this;
    }

    /**
     * Get the filename callback.
     */
    public function getUploadedFileNameForStorage(): ?Closure
    {
        return $this->getUploadedFileNameForStorage;
    }

    /**
     * Store original filenames in a separate field.
     */
    public function storeFileNamesIn(string|Closure|null $statePath): static
    {
        $this->storeFileNamesIn = $statePath;

        return $this;
    }

    /**
     * Get the state path for filenames.
     */
    public function getStoreFileNamesIn(): ?string
    {
        return $this->evaluate($this->storeFileNamesIn);
    }

    /**
     * Enable/disable appending files to existing ones.
     */
    public function appendFiles(bool|Closure $condition = true): static
    {
        $this->appendFiles = $condition;

        return $this;
    }

    /**
     * Check if files should be appended.
     */
    public function shouldAppendFiles(): bool
    {
        return (bool) $this->evaluate($this->appendFiles);
    }

    /**
     * Enable/disable prepending files to existing ones.
     */
    public function prependFiles(bool|Closure $condition = true): static
    {
        $this->prependFiles = $condition;

        return $this;
    }

    /**
     * Check if files should be prepended.
     */
    public function shouldPrependFiles(): bool
    {
        return (bool) $this->evaluate($this->prependFiles);
    }

    /**
     * Enable/disable moving files on the server.
     */
    public function moveFiles(bool|Closure $condition = true): static
    {
        $this->moveFiles = $condition;

        return $this;
    }

    /**
     * Check if files should be moved.
     */
    public function shouldMoveFiles(): bool
    {
        return (bool) $this->evaluate($this->moveFiles);
    }

    /**
     * Enable/disable storing files.
     */
    public function storeFiles(bool|Closure $condition = true): static
    {
        $this->storeFiles = $condition;

        return $this;
    }

    /**
     * Check if files should be stored.
     */
    public function shouldStoreFiles(): bool
    {
        return (bool) $this->evaluate($this->storeFiles);
    }

    /**
     * Enable/disable orienting images from EXIF data.
     */
    public function orientImagesFromExif(bool|Closure $condition = true): static
    {
        $this->orientImagesFromExif = $condition;

        return $this;
    }

    /**
     * Check if images should be oriented from EXIF.
     */
    public function shouldOrientImagesFromExif(): bool
    {
        return (bool) $this->evaluate($this->orientImagesFromExif);
    }

    /**
     * Enable/disable fetching file information.
     */
    public function fetchFileInformation(bool|Closure $condition = true): static
    {
        $this->fetchFileInformation = $condition;

        return $this;
    }

    /**
     * Check if file information should be fetched.
     */
    public function shouldFetchFileInformation(): bool
    {
        return (bool) $this->evaluate($this->fetchFileInformation);
    }

    /**
     * Enable/disable pasting files.
     */
    public function pasteable(bool|Closure $condition = true): static
    {
        $this->isPasteable = $condition;

        return $this;
    }

    /**
     * Check if pasteable.
     */
    public function isPasteable(): bool
    {
        return (bool) $this->evaluate($this->isPasteable);
    }

    /**
     * Set maximum parallel uploads.
     */
    public function maxParallelUploads(int|Closure $count): static
    {
        $this->maxParallelUploads = $count;

        return $this;
    }

    /**
     * Get maximum parallel uploads.
     */
    public function getMaxParallelUploads(): ?int
    {
        return $this->evaluate($this->maxParallelUploads);
    }

    /**
     * Set uploading message.
     */
    public function uploadingMessage(string|Closure $message): static
    {
        $this->uploadingMessage = $message;

        return $this;
    }

    /**
     * Get uploading message.
     */
    public function getUploadingMessage(): ?string
    {
        return $this->evaluate($this->uploadingMessage);
    }

    /**
     * Set MIME type map.
     */
    public function mimeTypeMap(array|Closure $map): static
    {
        $this->mimeTypeMap = $map;

        return $this;
    }

    /**
     * Get MIME type map.
     */
    public function getMimeTypeMap(): ?array
    {
        return $this->evaluate($this->mimeTypeMap);
    }

    // =========================================================================
    // Native Mode
    // =========================================================================

    /**
     * Use native HTML file input instead of FilePond.
     */
    public function native(bool $condition = true): static
    {
        $this->isNative = $condition;

        return $this;
    }

    /**
     * Check if native mode is enabled.
     */
    public function isNative(): bool
    {
        return $this->isNative;
    }

    // =========================================================================
    // Spatie Media Library Integration
    // =========================================================================

    /**
     * Enable media library storage with optional collection name.
     */
    public function collection(?string $collection = 'default'): static
    {
        $this->useMediaLibrary = true;
        $this->collection = $collection;

        return $this;
    }

    /**
     * Check if media library is enabled.
     */
    public function usesMediaLibrary(): bool
    {
        return $this->useMediaLibrary;
    }

    /**
     * Get the media library collection name.
     */
    public function getCollection(): ?string
    {
        return $this->collection;
    }

    /**
     * Set custom properties for media library.
     */
    public function customProperties(array $properties): static
    {
        $this->customProperties = $properties;

        return $this;
    }

    /**
     * Get custom properties.
     */
    public function getCustomProperties(): array
    {
        return $this->customProperties;
    }

    /**
     * Set custom headers for media library.
     */
    public function customHeaders(array $headers): static
    {
        $this->customHeaders = $headers;

        return $this;
    }

    /**
     * Get custom headers.
     */
    public function getCustomHeaders(): array
    {
        return $this->customHeaders;
    }

    /**
     * Set responsive images configuration.
     */
    public function responsiveImages(array $config): static
    {
        $this->responsiveImages = $config;

        return $this;
    }

    /**
     * Get responsive images config.
     */
    public function getResponsiveImages(): array
    {
        return $this->responsiveImages;
    }

    /**
     * Set media conversions.
     */
    public function conversion(string $name): static
    {
        $this->conversions[] = $name;

        return $this;
    }

    /**
     * Set multiple media conversions.
     */
    public function conversions(array $names): static
    {
        $this->conversions = $names;

        return $this;
    }

    /**
     * Get media conversions.
     */
    public function getConversions(): array
    {
        return $this->conversions;
    }

    // =========================================================================
    // Media Browser
    // =========================================================================

    /**
     * Enable/disable media browser modal.
     */
    public function mediaBrowser(bool|Closure $condition = true): static
    {
        $this->hasMediaBrowser = $condition;

        return $this;
    }

    /**
     * Check if media browser is enabled.
     */
    public function hasMediaBrowser(): bool
    {
        return (bool) $this->evaluate($this->hasMediaBrowser);
    }

    // =========================================================================
    // Image Dimension Constraints (Validation)
    // =========================================================================

    /**
     * Set minimum image width.
     */
    public function minWidth(int $width): static
    {
        $this->minWidth = $width;

        return $this;
    }

    /**
     * Get minimum width.
     */
    public function getMinWidth(): ?int
    {
        return $this->minWidth;
    }

    /**
     * Set maximum image width.
     */
    public function maxWidth(int $width): static
    {
        $this->maxWidth = $width;

        return $this;
    }

    /**
     * Get maximum width.
     */
    public function getMaxWidth(): ?int
    {
        return $this->maxWidth;
    }

    /**
     * Set minimum image height.
     */
    public function minHeight(int $height): static
    {
        $this->minHeight = $height;

        return $this;
    }

    /**
     * Get minimum height.
     */
    public function getMinHeight(): ?int
    {
        return $this->minHeight;
    }

    /**
     * Set maximum image height.
     */
    public function maxHeight(int $height): static
    {
        $this->maxHeight = $height;

        return $this;
    }

    /**
     * Get maximum height.
     */
    public function getMaxHeight(): ?int
    {
        return $this->maxHeight;
    }

    /**
     * Set exact image width.
     */
    public function width(int $width): static
    {
        $this->imageWidth = $width;

        return $this;
    }

    /**
     * Get exact width.
     */
    public function getWidth(): ?int
    {
        return $this->imageWidth;
    }

    /**
     * Set exact image height.
     */
    public function height(int $height): static
    {
        $this->imageHeight = $height;

        return $this;
    }

    /**
     * Get exact height.
     */
    public function getHeight(): ?int
    {
        return $this->imageHeight;
    }

    /**
     * Set image dimensions (width and height).
     */
    public function dimensions(int $width, int $height): static
    {
        $this->imageWidth = $width;
        $this->imageHeight = $height;

        return $this;
    }

    /**
     * Set image size constraints (min/max width and height).
     */
    public function imageDimensions(?int $minWidth = null, ?int $maxWidth = null, ?int $minHeight = null, ?int $maxHeight = null): static
    {
        $this->minWidth = $minWidth;
        $this->maxWidth = $maxWidth;
        $this->minHeight = $minHeight;
        $this->maxHeight = $maxHeight;

        return $this;
    }

    /**
     * Set minimum resolution in pixels.
     */
    public function minResolution(int $pixels): static
    {
        $this->minResolution = $pixels;

        return $this;
    }

    /**
     * Get minimum resolution.
     */
    public function getMinResolution(): ?int
    {
        return $this->minResolution;
    }

    /**
     * Set maximum resolution in pixels.
     */
    public function maxResolution(int $pixels): static
    {
        $this->maxResolution = $pixels;

        return $this;
    }

    /**
     * Get maximum resolution.
     */
    public function getMaxResolution(): ?int
    {
        return $this->maxResolution;
    }

    // =========================================================================
    // Token Generation
    // =========================================================================

    /**
     * Generate a secure upload token containing all configuration.
     */
    public function getUploadToken(): string
    {
        return FileUploadToken::generate([
            'disk' => $this->getDisk(),
            'directory' => $this->getDirectory(),
            'visibility' => $this->getVisibility(),
            'max_size' => $this->getMaxSize(),
            'min_size' => $this->getMinSize(),
            'accepted_types' => $this->getAcceptedFileTypesArray(),
            'max_files' => $this->getMaxFiles(),
            'min_files' => $this->getMinFiles(),
            'preserve_filenames' => $this->shouldPreserveFilenames(),
            'use_media_library' => $this->usesMediaLibrary(),
            'collection' => $this->getCollection(),
        ]);
    }

    /**
     * Get the upload URL with token.
     */
    public function getUploadUrl(): string
    {
        return FileUploadToken::url($this->getUploadToken());
    }

    // =========================================================================
    // Helper Methods
    // =========================================================================

    /**
     * Check if this field requires file upload capability.
     */
    public function hasFileUpload(): bool
    {
        return true;
    }

    /**
     * Get all configuration as array for JavaScript.
     */
    public function getJavaScriptConfig(): array
    {
        return [
            'uploadUrl' => route('forms.upload'),
            'uploadToken' => $this->getUploadToken(),
            'disk' => $this->getDisk(),
            'directory' => $this->getDirectory(),
            'visibility' => $this->getVisibility(),
            'maxSize' => $this->getMaxSize(),
            'minSize' => $this->getMinSize(),
            'maxFiles' => $this->getMaxFiles(),
            'minFiles' => $this->getMinFiles(),
            'multiple' => $this->isMultiple(),
            'acceptedFileTypes' => $this->getAcceptedFileTypesArray(),
            'isImage' => $this->isImage(),
            'isAvatar' => $this->isAvatar(),
            'isNative' => $this->isNative(),
            'imagePreview' => $this->hasImagePreview(),
            'imageCrop' => $this->hasImageCrop(),
            'imageCropAspectRatio' => $this->getImageCropAspectRatio(),
            'imageResize' => [
                'width' => $this->getImageResizeTargetWidth(),
                'height' => $this->getImageResizeTargetHeight(),
                'mode' => $this->getImageResizeMode(),
            ],
            'imageEditor' => $this->hasImageEditor(),
            'imageEditorAspectRatios' => $this->getImageEditorAspectRatios(),
            'imageEditorMode' => $this->getImageEditorMode(),
            'imageEditorEmptyFillColor' => $this->getImageEditorEmptyFillColor(),
            'imageEditorViewportWidth' => $this->getImageEditorViewportWidth(),
            'imageEditorViewportHeight' => $this->getImageEditorViewportHeight(),
            'circleCropper' => $this->hasCircleCropper(),
            'downloadable' => $this->isDownloadable(),
            'openable' => $this->isOpenable(),
            'deletable' => $this->isDeletable(),
            'reorderable' => $this->isReorderable(),
            'previewable' => $this->isPreviewable(),
            'panelLayout' => $this->getPanelLayout(),
            'panelAspectRatio' => $this->getPanelAspectRatio(),
            'imagePreviewHeight' => $this->getImagePreviewHeight(),
            'loadingIndicatorPosition' => $this->getLoadingIndicatorPosition(),
            'removeUploadedFileButtonPosition' => $this->getRemoveUploadedFileButtonPosition(),
            'uploadButtonPosition' => $this->getUploadButtonPosition(),
            'uploadProgressIndicatorPosition' => $this->getUploadProgressIndicatorPosition(),
            'preserveFilenames' => $this->shouldPreserveFilenames(),
            'appendFiles' => $this->shouldAppendFiles(),
            'prependFiles' => $this->shouldPrependFiles(),
            'orientImagesFromExif' => $this->shouldOrientImagesFromExif(),
            'fetchFileInformation' => $this->shouldFetchFileInformation(),
            'pasteable' => $this->isPasteable(),
            'maxParallelUploads' => $this->getMaxParallelUploads(),
            'uploadingMessage' => $this->getUploadingMessage(),
            'mimeTypeMap' => $this->getMimeTypeMap(),
            'alignCenter' => $this->isAlignCenter(),
            'useMediaLibrary' => $this->usesMediaLibrary(),
            'collection' => $this->getCollection(),
            'mediaBrowser' => $this->hasMediaBrowser(),
        ];
    }

    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.file-upload';
    }
}
