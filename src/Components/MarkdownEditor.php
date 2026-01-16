<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Field;
use Closure;

/**
 * Markdown editor field component - Filament-compatible API.
 *
 * Provides a WYSIWYG markdown editor with toolbar, preview, and file upload support.
 */
class MarkdownEditor extends Field
{
    /**
     * Default toolbar button configuration (grouped).
     *
     * @var array<int, array<int, string>>
     */
    protected array $toolbarButtons = [
        ['bold', 'italic', 'strike', 'link'],
        ['heading'],
        ['blockquote', 'codeBlock', 'bulletList', 'orderedList'],
        ['table', 'attachFiles'],
        ['undo', 'redo'],
    ];

    protected bool $previewEnabled = true;

    protected ?int $maxLength = null;

    protected bool $showCharacterCount = false;

    protected string $direction = 'ltr';

    protected string $disk = 'public';

    protected string $directory = 'attachments';

    protected string $visibility = 'public';

    protected int $maxFileSize = 12288; // 12MB in KB

    protected array $acceptedFileTypes = ['image/png', 'image/jpeg', 'image/gif', 'image/webp'];

    protected bool $fileAttachmentsEnabled = true;

    protected array $disabledButtons = [];

    /**
     * Set the toolbar buttons (grouped by arrays).
     *
     * Each nested array represents a group of buttons in the toolbar.
     *
     * @param  array<int, array<int, string>>|Closure  $buttons
     */
    public function toolbarButtons(array|Closure $buttons): static
    {
        $this->toolbarButtons = $this->evaluate($buttons);

        return $this;
    }

    /**
     * Get the toolbar buttons.
     *
     * @return array<int, array<int, string>>
     */
    public function getToolbarButtons(): array
    {
        // Filter out disabled buttons
        if (! empty($this->disabledButtons)) {
            return array_map(function ($group) {
                return array_values(array_diff($group, $this->disabledButtons));
            }, $this->toolbarButtons);
        }

        return $this->toolbarButtons;
    }

    /**
     * Disable specific toolbar buttons.
     *
     * @param  array<int, string>  $buttons
     */
    public function disableToolbarButtons(array $buttons): static
    {
        $this->disabledButtons = array_merge($this->disabledButtons, $buttons);

        return $this;
    }

    /**
     * Disable all toolbar buttons.
     */
    public function disableAllToolbarButtons(): static
    {
        $this->toolbarButtons = [];

        return $this;
    }

    /**
     * Enable preview panel (default: true).
     */
    public function preview(bool $condition = true): static
    {
        $this->previewEnabled = $condition;

        return $this;
    }

    /**
     * Alias for preview() to match common naming.
     */
    public function withPreview(bool $condition = true): static
    {
        return $this->preview($condition);
    }

    /**
     * Check if preview is enabled.
     */
    public function hasPreview(): bool
    {
        return $this->previewEnabled;
    }

    /**
     * Set maximum content length.
     */
    public function maxLength(int $length): static
    {
        $this->maxLength = $length;
        $this->showCharacterCount = true;

        return $this;
    }

    /**
     * Get maximum length.
     */
    public function getMaxLength(): ?int
    {
        return $this->maxLength;
    }

    /**
     * Show/hide character count.
     */
    public function characterCount(bool $condition = true): static
    {
        $this->showCharacterCount = $condition;

        return $this;
    }

    /**
     * Check if character count should be shown.
     */
    public function shouldShowCharacterCount(): bool
    {
        return $this->showCharacterCount;
    }

    /**
     * Set RTL direction.
     */
    public function rtl(bool $condition = true): static
    {
        $this->direction = $condition ? 'rtl' : 'ltr';

        return $this;
    }

    /**
     * Set text direction.
     */
    public function direction(string $direction): static
    {
        $this->direction = $direction;

        return $this;
    }

    /**
     * Get text direction.
     */
    public function getDirection(): string
    {
        return $this->direction;
    }

    /**
     * Set the storage disk for file attachments.
     */
    public function fileAttachmentsDisk(string $disk): static
    {
        $this->disk = $disk;

        return $this;
    }

    /**
     * Get the storage disk.
     */
    public function getFileAttachmentsDisk(): string
    {
        return $this->disk;
    }

    /**
     * Set the directory for file attachments.
     */
    public function fileAttachmentsDirectory(string $directory): static
    {
        $this->directory = $directory;

        return $this;
    }

    /**
     * Get the file attachments directory.
     */
    public function getFileAttachmentsDirectory(): string
    {
        return $this->directory;
    }

    /**
     * Set the visibility for file attachments.
     */
    public function fileAttachmentsVisibility(string $visibility): static
    {
        $this->visibility = $visibility;

        return $this;
    }

    /**
     * Get the file attachments visibility.
     */
    public function getFileAttachmentsVisibility(): string
    {
        return $this->visibility;
    }

    /**
     * Set maximum file size in KB.
     */
    public function fileAttachmentsMaxSize(int $sizeInKb): static
    {
        $this->maxFileSize = $sizeInKb;

        return $this;
    }

    /**
     * Get maximum file size.
     */
    public function getFileAttachmentsMaxSize(): int
    {
        return $this->maxFileSize;
    }

    /**
     * Set accepted file types for attachments.
     *
     * @param  array<int, string>  $types
     */
    public function fileAttachmentsAcceptedFileTypes(array $types): static
    {
        $this->acceptedFileTypes = $types;

        return $this;
    }

    /**
     * Get accepted file types.
     *
     * @return array<int, string>
     */
    public function getFileAttachmentsAcceptedFileTypes(): array
    {
        return $this->acceptedFileTypes;
    }

    /**
     * Enable/disable file attachments.
     */
    public function fileAttachments(bool $condition = true): static
    {
        $this->fileAttachmentsEnabled = $condition;

        return $this;
    }

    /**
     * Check if file attachments are enabled.
     */
    public function hasFileAttachments(): bool
    {
        return $this->fileAttachmentsEnabled && in_array('attachFiles', $this->flattenToolbarButtons());
    }

    /**
     * Flatten the toolbar buttons into a single array.
     *
     * @return array<int, string>
     */
    protected function flattenToolbarButtons(): array
    {
        $buttons = [];
        foreach ($this->getToolbarButtons() as $group) {
            $buttons = array_merge($buttons, $group);
        }

        return $buttons;
    }

    /**
     * Get grouped tools for template rendering.
     *
     * @return array<int, array<int, string>>
     */
    public function getGroupedTools(): array
    {
        return $this->getToolbarButtons();
    }

    /**
     * Get editor configuration for JavaScript.
     *
     * @return array<string, mixed>
     */
    public function getConfig(): array
    {
        return [
            'preview' => $this->previewEnabled,
            'maxLength' => $this->maxLength,
            'showCharacterCount' => $this->showCharacterCount,
            'direction' => $this->direction,
            'fileAttachments' => [
                'enabled' => $this->fileAttachmentsEnabled,
                'disk' => $this->disk,
                'directory' => $this->directory,
                'visibility' => $this->visibility,
                'maxSize' => $this->maxFileSize,
                'acceptedTypes' => $this->acceptedFileTypes,
            ],
        ];
    }

    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.markdown-editor';
    }
}
