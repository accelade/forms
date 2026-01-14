<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Field;

/**
 * Rich text editor field component.
 */
class RichEditor extends Field
{
    protected array $toolbarButtons = [
        'bold',
        'italic',
        'underline',
        'strike',
        'link',
        'heading',
        'bulletList',
        'orderedList',
        'blockquote',
        'codeBlock',
        'undo',
        'redo',
    ];

    protected bool $withMedia = false;

    protected bool $withTables = false;

    protected ?int $maxLength = null;

    /**
     * Set the toolbar buttons.
     */
    public function toolbarButtons(array $buttons): static
    {
        $this->toolbarButtons = $buttons;

        return $this;
    }

    /**
     * Get the toolbar buttons.
     */
    public function getToolbarButtons(): array
    {
        return $this->toolbarButtons;
    }

    /**
     * Disable specific toolbar buttons.
     */
    public function disableToolbarButtons(array $buttons): static
    {
        $this->toolbarButtons = array_diff($this->toolbarButtons, $buttons);

        return $this;
    }

    /**
     * Enable only specific toolbar buttons.
     */
    public function enableToolbarButtons(array $buttons): static
    {
        $this->toolbarButtons = array_intersect($this->toolbarButtons, $buttons);

        return $this;
    }

    /**
     * Enable media uploads.
     */
    public function withMedia(bool $condition = true): static
    {
        $this->withMedia = $condition;

        return $this;
    }

    /**
     * Check if media is enabled.
     */
    public function hasMedia(): bool
    {
        return $this->withMedia;
    }

    /**
     * Enable table support.
     */
    public function withTables(bool $condition = true): static
    {
        $this->withTables = $condition;

        return $this;
    }

    /**
     * Check if tables are enabled.
     */
    public function hasTables(): bool
    {
        return $this->withTables;
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
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.rich-editor';
    }
}
