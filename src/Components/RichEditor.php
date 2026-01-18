<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Field;
use Closure;

/**
 * Rich text editor field component.
 *
 * Filament-compatible API for rich text editing using contenteditable.
 *
 * @see https://filamentphp.com/docs/4.x/forms/rich-editor
 */
class RichEditor extends Field
{
    /**
     * Default toolbar buttons - Filament uses grouped arrays.
     *
     * @var array<int, array<int, string>|string>
     */
    protected array $toolbarButtons = [
        ['bold', 'italic', 'underline', 'strike', 'link'],
        ['h2', 'h3'],
        ['bulletList', 'orderedList', 'blockquote', 'codeBlock'],
        ['attachFiles'],
        ['undo', 'redo'],
    ];

    protected array $disabledToolbarButtons = [];

    protected bool $allToolbarButtonsDisabled = false;

    protected ?int $maxLength = null;

    protected string $outputFormat = 'html';

    // File attachments configuration (Filament compatibility)
    protected string|Closure|null $fileAttachmentsDisk = null;

    protected string|Closure|null $fileAttachmentsDirectory = null;

    protected string|Closure|null $fileAttachmentsVisibility = null;

    /**
     * Set the toolbar buttons.
     *
     * Supports grouped arrays (Filament style) or flat arrays.
     *
     * @param  array<int, array<int, string>|string>  $buttons
     */
    public function toolbarButtons(array $buttons): static
    {
        $this->toolbarButtons = $buttons;

        return $this;
    }

    /**
     * Get the toolbar buttons.
     *
     * @return array<int, array<int, string>|string>
     */
    public function getToolbarButtons(): array
    {
        if ($this->allToolbarButtonsDisabled) {
            return [];
        }

        if (empty($this->disabledToolbarButtons)) {
            return $this->toolbarButtons;
        }

        // Filter out disabled buttons from groups
        return array_map(function ($group) {
            if (is_array($group)) {
                return array_values(array_diff($group, $this->disabledToolbarButtons));
            }

            return in_array($group, $this->disabledToolbarButtons, true) ? null : $group;
        }, $this->toolbarButtons);
    }

    /**
     * Get flattened toolbar buttons (for iteration).
     *
     * @return array<int, string>
     */
    public function getFlatToolbarButtons(): array
    {
        $buttons = $this->getToolbarButtons();
        $flat = [];

        foreach ($buttons as $group) {
            if (is_array($group)) {
                $flat = array_merge($flat, $group);
            } elseif ($group !== null) {
                $flat[] = $group;
            }
        }

        return $flat;
    }

    /**
     * Check if toolbar buttons are grouped.
     */
    public function hasGroupedToolbarButtons(): bool
    {
        foreach ($this->toolbarButtons as $item) {
            if (is_array($item)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Disable specific toolbar buttons.
     *
     * @param  array<int, string>  $buttons
     */
    public function disableToolbarButtons(array $buttons): static
    {
        $this->disabledToolbarButtons = array_merge($this->disabledToolbarButtons, $buttons);

        return $this;
    }

    /**
     * Disable all toolbar buttons.
     */
    public function disableAllToolbarButtons(bool $condition = true): static
    {
        $this->allToolbarButtonsDisabled = $condition;

        return $this;
    }

    /**
     * Check if all toolbar buttons are disabled.
     */
    public function areAllToolbarButtonsDisabled(): bool
    {
        return $this->allToolbarButtonsDisabled;
    }

    /**
     * Enable only specific toolbar buttons.
     *
     * @param  array<int, string>  $buttons
     */
    public function enableToolbarButtons(array $buttons): static
    {
        // Get all available buttons and disable those not in the list
        $allButtons = $this->getFlatToolbarButtons();
        $this->disabledToolbarButtons = array_diff($allButtons, $buttons);

        return $this;
    }

    /**
     * Set maximum content length.
     */
    public function maxLength(int $length): static
    {
        $this->maxLength = $length;

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
     * Set output format (html, json, text).
     */
    public function output(string $format): static
    {
        $this->outputFormat = $format;

        return $this;
    }

    /**
     * Get the output format.
     */
    public function getOutputFormat(): string
    {
        return $this->outputFormat;
    }

    /**
     * Set the disk for file attachments.
     */
    public function fileAttachmentsDisk(string|Closure|null $disk): static
    {
        $this->fileAttachmentsDisk = $disk;

        return $this;
    }

    /**
     * Get the file attachments disk.
     */
    public function getFileAttachmentsDisk(): ?string
    {
        return $this->evaluate($this->fileAttachmentsDisk);
    }

    /**
     * Set the directory for file attachments.
     */
    public function fileAttachmentsDirectory(string|Closure|null $directory): static
    {
        $this->fileAttachmentsDirectory = $directory;

        return $this;
    }

    /**
     * Get the file attachments directory.
     */
    public function getFileAttachmentsDirectory(): ?string
    {
        return $this->evaluate($this->fileAttachmentsDirectory);
    }

    /**
     * Set the visibility for file attachments.
     */
    public function fileAttachmentsVisibility(string|Closure|null $visibility): static
    {
        $this->fileAttachmentsVisibility = $visibility;

        return $this;
    }

    /**
     * Get the file attachments visibility.
     */
    public function getFileAttachmentsVisibility(): ?string
    {
        return $this->evaluate($this->fileAttachmentsVisibility);
    }

    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.rich-editor';
    }
}
