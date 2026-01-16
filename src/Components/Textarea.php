<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Concerns\HasMinMax;
use Accelade\Forms\Field;

/**
 * Textarea field component.
 */
class Textarea extends Field
{
    use HasMinMax;

    protected int $rows = 3;

    protected int $cols = 50;

    protected bool $autosize = false;

    protected static bool $defaultAutosize = false;

    /**
     * Set up the field with defaults.
     */
    protected function setUp(): void
    {
        $this->autosize = static::$defaultAutosize;
    }

    /**
     * Set the number of rows.
     */
    public function rows(int $rows): static
    {
        $this->rows = $rows;

        return $this;
    }

    /**
     * Get the number of rows.
     */
    public function getRows(): int
    {
        return $this->rows;
    }

    /**
     * Set the number of columns.
     */
    public function cols(int $cols): static
    {
        $this->cols = $cols;

        return $this;
    }

    /**
     * Get the number of columns.
     */
    public function getCols(): int
    {
        return $this->cols;
    }

    /**
     * Enable autosize behavior.
     */
    public function autosize(bool $condition = true): static
    {
        $this->autosize = $condition;

        return $this;
    }

    /**
     * Check if autosize is enabled.
     */
    public function hasAutosize(): bool
    {
        return $this->autosize;
    }

    /**
     * Set autosize as the default for all textarea instances.
     */
    public static function defaultAutosize(bool $autosize = true): void
    {
        static::$defaultAutosize = $autosize;
    }

    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.textarea';
    }
}
