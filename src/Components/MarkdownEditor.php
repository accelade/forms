<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Field;

/**
 * Markdown editor field component.
 */
class MarkdownEditor extends Field
{
    protected array $toolbarButtons = [
        'bold',
        'italic',
        'strike',
        'link',
        'heading',
        'bulletList',
        'orderedList',
        'blockquote',
        'codeBlock',
        'table',
        'undo',
        'redo',
    ];

    protected bool $withPreview = true;

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
     * Enable preview panel.
     */
    public function withPreview(bool $condition = true): static
    {
        $this->withPreview = $condition;

        return $this;
    }

    /**
     * Check if preview is enabled.
     */
    public function hasPreview(): bool
    {
        return $this->withPreview;
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
        return 'forms::components.markdown-editor';
    }
}
