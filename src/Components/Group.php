<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Concerns\CanBeInline;
use Accelade\Forms\Field;
use Closure;

/**
 * Group component for bundling related form elements (Splade compatibility).
 *
 * Groups checkboxes, radios, and other elements together with shared
 * label and error display.
 */
class Group extends Field
{
    use CanBeInline;

    /**
     * Child fields within the group.
     *
     * @var array<Field>
     */
    protected array $schema = [];

    /**
     * Whether to show errors on individual child elements.
     */
    protected bool $showChildErrors = false;

    /**
     * Whether to show the group-level error.
     */
    protected bool $showErrors = true;

    /**
     * Set the child fields/schema.
     *
     * @param  array<Field>|Closure  $schema
     */
    public function schema(array|Closure $schema): static
    {
        $this->schema = $this->evaluate($schema);

        return $this;
    }

    /**
     * Get the child fields.
     *
     * @return array<Field>
     */
    public function getSchema(): array
    {
        return $this->schema;
    }

    /**
     * Check if group has child fields.
     */
    public function hasSchema(): bool
    {
        return count($this->schema) > 0;
    }

    /**
     * Show errors on child elements.
     */
    public function showChildErrors(bool $condition = true): static
    {
        $this->showChildErrors = $condition;

        return $this;
    }

    /**
     * Check if child errors should be shown.
     */
    public function shouldShowChildErrors(): bool
    {
        return $this->showChildErrors;
    }

    /**
     * Show or hide group-level errors.
     */
    public function showErrors(bool $condition = true): static
    {
        $this->showErrors = $condition;

        return $this;
    }

    /**
     * Check if group errors should be shown.
     */
    public function shouldShowErrors(): bool
    {
        return $this->showErrors;
    }

    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.group';
    }
}
