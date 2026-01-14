<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Field;
use Closure;

/**
 * File upload field component.
 */
class FileUpload extends Field
{
    protected array|string|Closure $acceptedFileTypes = [];

    protected int|Closure|null $maxSize = null;

    protected int|Closure|null $minSize = null;

    protected int|Closure|null $maxFiles = null;

    protected int|Closure|null $minFiles = null;

    protected bool $isMultiple = false;

    protected bool $isImage = false;

    protected ?string $directory = null;

    protected ?string $disk = null;

    protected bool $isPreviewable = true;

    protected bool $isDownloadable = true;

    protected bool $isDeletable = true;

    /**
     * Set up the field.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->maxSize = config('forms.file_upload.max_size', 10240);
    }

    /**
     * Set accepted file types.
     */
    public function acceptedFileTypes(array|string|Closure $types): static
    {
        $this->acceptedFileTypes = $types;

        return $this;
    }

    /**
     * Get accepted file types.
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
     * Set maximum file size in kilobytes.
     */
    public function maxSize(int|Closure $size): static
    {
        $this->maxSize = $size;

        return $this;
    }

    /**
     * Get maximum file size.
     */
    public function getMaxSize(): ?int
    {
        return $this->evaluate($this->maxSize);
    }

    /**
     * Set minimum file size in kilobytes.
     */
    public function minSize(int|Closure $size): static
    {
        $this->minSize = $size;

        return $this;
    }

    /**
     * Get minimum file size.
     */
    public function getMinSize(): ?int
    {
        return $this->evaluate($this->minSize);
    }

    /**
     * Allow multiple file uploads.
     */
    public function multiple(bool $condition = true): static
    {
        $this->isMultiple = $condition;

        return $this;
    }

    /**
     * Check if multiple uploads allowed.
     */
    public function isMultiple(): bool
    {
        return $this->isMultiple;
    }

    /**
     * Set maximum number of files.
     */
    public function maxFiles(int|Closure $count): static
    {
        $this->maxFiles = $count;
        $this->isMultiple = true;

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
    public function minFiles(int|Closure $count): static
    {
        $this->minFiles = $count;
        $this->isMultiple = true;

        return $this;
    }

    /**
     * Get minimum files count.
     */
    public function getMinFiles(): ?int
    {
        return $this->evaluate($this->minFiles);
    }

    /**
     * Restrict to image files only.
     */
    public function image(): static
    {
        $this->isImage = true;
        $this->acceptedFileTypes = ['image/*'];

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
     * Set the storage directory.
     */
    public function directory(string $directory): static
    {
        $this->directory = $directory;

        return $this;
    }

    /**
     * Get the storage directory.
     */
    public function getDirectory(): ?string
    {
        return $this->directory;
    }

    /**
     * Set the storage disk.
     */
    public function disk(string $disk): static
    {
        $this->disk = $disk;

        return $this;
    }

    /**
     * Get the storage disk.
     */
    public function getDisk(): ?string
    {
        return $this->disk;
    }

    /**
     * Enable/disable preview.
     */
    public function previewable(bool $condition = true): static
    {
        $this->isPreviewable = $condition;

        return $this;
    }

    /**
     * Check if previewable.
     */
    public function isPreviewable(): bool
    {
        return $this->isPreviewable;
    }

    /**
     * Enable/disable download.
     */
    public function downloadable(bool $condition = true): static
    {
        $this->isDownloadable = $condition;

        return $this;
    }

    /**
     * Check if downloadable.
     */
    public function isDownloadable(): bool
    {
        return $this->isDownloadable;
    }

    /**
     * Enable/disable deletion.
     */
    public function deletable(bool $condition = true): static
    {
        $this->isDeletable = $condition;

        return $this;
    }

    /**
     * Check if deletable.
     */
    public function isDeletable(): bool
    {
        return $this->isDeletable;
    }

    /**
     * Check if this field requires file upload capability.
     */
    public function hasFileUpload(): bool
    {
        return true;
    }

    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.file-upload';
    }
}
