<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Concerns\HasOptions;
use Accelade\Forms\Field;
use Closure;

/**
 * Checkbox list field component.
 *
 * Allows users to select multiple items from a predefined list.
 */
class CheckboxList extends Field
{
    use HasOptions;

    protected int|Closure $columns = 1;

    protected string $gridDirection = 'column';

    protected bool $isBulkToggleable = false;

    protected ?string $selectAllActionLabel = null;

    protected ?string $deselectAllActionLabel = null;

    protected ?string $searchPrompt = null;

    protected int $searchDebounce = 300;

    protected ?string $noSearchResultsMessage = null;

    protected array|Closure $descriptions = [];

    protected ?Closure $disableOptionWhen = null;

    protected bool $allowHtml = false;

    protected ?string $relationship = null;

    protected array $pivotData = [];

    /**
     * Set the number of columns for the grid layout.
     */
    public function columns(int|Closure $columns): static
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * Get the number of columns.
     */
    public function getColumns(): int
    {
        return $this->evaluate($this->columns);
    }

    /**
     * Set the grid direction.
     *
     * @param  string  $direction  Either 'column' or 'row'
     */
    public function gridDirection(string $direction): static
    {
        $this->gridDirection = $direction;

        return $this;
    }

    /**
     * Get the grid direction.
     */
    public function getGridDirection(): string
    {
        return $this->gridDirection;
    }

    /**
     * Enable bulk toggle (select all / deselect all).
     */
    public function bulkToggleable(bool $condition = true): static
    {
        $this->isBulkToggleable = $condition;

        return $this;
    }

    /**
     * Check if bulk toggle is enabled.
     */
    public function isBulkToggleable(): bool
    {
        return $this->isBulkToggleable;
    }

    /**
     * Set the "Select All" action label.
     */
    public function selectAllActionLabel(string $label): static
    {
        $this->selectAllActionLabel = $label;

        return $this;
    }

    /**
     * Get the "Select All" action label.
     */
    public function getSelectAllActionLabel(): string
    {
        return $this->selectAllActionLabel ?? __('Select all');
    }

    /**
     * Set the "Deselect All" action label.
     */
    public function deselectAllActionLabel(string $label): static
    {
        $this->deselectAllActionLabel = $label;

        return $this;
    }

    /**
     * Get the "Deselect All" action label.
     */
    public function getDeselectAllActionLabel(): string
    {
        return $this->deselectAllActionLabel ?? __('Deselect all');
    }

    /**
     * Set the search prompt placeholder text.
     */
    public function searchPrompt(?string $prompt): static
    {
        $this->searchPrompt = $prompt;

        return $this;
    }

    /**
     * Get the search prompt.
     */
    public function getSearchPrompt(): string
    {
        return $this->searchPrompt ?? __('Search options...');
    }

    /**
     * Set the search debounce time in milliseconds.
     */
    public function searchDebounce(int $milliseconds): static
    {
        $this->searchDebounce = $milliseconds;

        return $this;
    }

    /**
     * Get the search debounce time.
     */
    public function getSearchDebounce(): int
    {
        return $this->searchDebounce;
    }

    /**
     * Set the message shown when no search results are found.
     */
    public function noSearchResultsMessage(?string $message): static
    {
        $this->noSearchResultsMessage = $message;

        return $this;
    }

    /**
     * Get the no search results message.
     */
    public function getNoSearchResultsMessage(): string
    {
        return $this->noSearchResultsMessage ?? __('No results found');
    }

    /**
     * Set descriptions for options.
     */
    public function descriptions(array|Closure $descriptions): static
    {
        $this->descriptions = $descriptions;

        return $this;
    }

    /**
     * Get the descriptions.
     */
    public function getDescriptions(): array
    {
        return $this->evaluate($this->descriptions);
    }

    /**
     * Get description for a specific option.
     */
    public function getDescription(string|int $value): ?string
    {
        return $this->getDescriptions()[$value] ?? null;
    }

    /**
     * Set a callback to disable specific options.
     */
    public function disableOptionWhen(?Closure $callback): static
    {
        $this->disableOptionWhen = $callback;

        return $this;
    }

    /**
     * Check if a specific option is disabled.
     */
    public function isOptionDisabled(string|int $value, string $label): bool
    {
        if ($this->disableOptionWhen === null) {
            return false;
        }

        return (bool) ($this->disableOptionWhen)($value, $label);
    }

    /**
     * Allow HTML in option labels.
     */
    public function allowHtml(bool $condition = true): static
    {
        $this->allowHtml = $condition;

        return $this;
    }

    /**
     * Check if HTML is allowed in labels.
     */
    public function isHtmlAllowed(): bool
    {
        return $this->allowHtml;
    }

    /**
     * Set the relationship for model binding.
     */
    public function relationship(string $name, string $titleAttribute, ?Closure $modifyQueryUsing = null): static
    {
        $this->relationship = $name;

        return $this;
    }

    /**
     * Get the relationship name.
     */
    public function getRelationship(): ?string
    {
        return $this->relationship;
    }

    /**
     * Set additional pivot data for relationship.
     */
    public function pivotData(array $data): static
    {
        $this->pivotData = $data;

        return $this;
    }

    /**
     * Get the pivot data.
     */
    public function getPivotData(): array
    {
        return $this->pivotData;
    }

    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.checkbox-list';
    }
}
