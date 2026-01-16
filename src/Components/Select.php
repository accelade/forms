<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Concerns\HasAffixes;
use Accelade\Forms\Concerns\HasOptions;
use Accelade\Forms\Field;
use Closure;
use Illuminate\Support\Collection;

/**
 * Select dropdown field component with optional searchable/Select2-style functionality.
 */
class Select extends Field
{
    use HasAffixes;
    use HasOptions;

    protected ?string $emptyOptionLabel = null;

    protected bool $isNative = false;

    protected ?Closure $getOptionLabelUsing = null;

    protected ?Closure $getOptionLabelsUsing = null;

    protected ?Closure $getOptionsUsing = null;

    protected ?Closure $getSearchResultsUsing = null;

    // Object collection option mapping
    protected ?string $optionLabel = null;

    protected ?string $optionValue = null;

    // Searchable select options
    protected ?string $searchPlaceholder = null;

    protected ?string $noResultsText = null;

    protected ?string $noOptionsMessage = null;

    protected ?string $loadingMessage = null;

    protected ?string $searchingMessage = null;

    protected ?string $searchPrompt = null;

    protected int $searchMinLength = 0;

    protected int $searchDebounce = 300;

    protected bool $allowClear = false;

    protected bool $closeOnSelect = true;

    protected ?int $maxSelections = null;

    protected ?int $minSelections = null;

    protected bool $taggable = false;

    protected ?string $createOptionText = null;

    protected ?Closure $createOptionUsing = null;

    // Choices.js integration (Splade compatibility)
    protected bool|array $choices = false;

    protected static ?array $defaultChoicesOptions = null;

    // Remote options (async loading)
    protected ?string $remoteUrl = null;

    protected ?string $remoteRoot = null;

    protected bool $selectFirstRemoteOption = false;

    protected bool $resetOnNewRemoteUrl = false;

    protected static bool $defaultSelectFirstRemoteOption = false;

    protected static bool $defaultResetOnNewRemoteUrl = false;

    // Eloquent relation support
    protected ?string $relation = null;

    protected ?string $relationTitleAttribute = null;

    protected ?Closure $getOptionLabelFromRecordUsing = null;

    protected ?Closure $modifyQueryUsing = null;

    protected array $pivotData = [];

    // Preload options
    protected bool $preload = false;

    // Options limit and pagination
    protected int $optionsLimit = 50;

    protected bool $showAllOptions = false;

    protected bool $infiniteScroll = true;

    protected int $perPage = 50;

    protected ?string $modelClass = null;

    protected ?string $modelLabelAttribute = null;

    protected ?string $modelValueAttribute = null;

    protected ?string $searchUrl = null;

    protected ?string $searchColumn = null;

    // Grouped options
    protected bool $hasGroupedOptions = false;

    // Option descriptions
    protected ?Closure $getOptionDescriptionUsing = null;

    // Disable specific options
    protected ?Closure $disableOptionWhen = null;

    // Allow HTML in options
    protected bool $allowHtml = false;

    // Wrap option labels
    protected bool $wrapOptionLabels = false;

    // Selectable placeholder
    protected bool $selectablePlaceholder = true;

    // Boolean select mode
    protected bool $isBoolean = false;

    protected string $trueLabel = 'Yes';

    protected string $falseLabel = 'No';

    // Create option modal form
    protected array|Closure|null $createOptionForm = null;

    protected ?Closure $createOptionAction = null;

    protected ?string $createOptionModalHeading = null;

    protected ?string $createOptionModalSubmitButtonLabel = null;

    // Edit option modal form
    protected array|Closure|null $editOptionForm = null;

    protected ?Closure $updateOptionAction = null;

    protected ?string $editOptionModalHeading = null;

    protected ?string $editOptionModalSubmitButtonLabel = null;

    /**
     * Set up the field.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->emptyOptionLabel = __('Select an option');
        $this->isSearchable = true; // Enable searchable by default for non-native mode
    }

    /**
     * Set the empty option label (placeholder).
     */
    public function emptyOptionLabel(?string $label): static
    {
        $this->emptyOptionLabel = $label;

        return $this;
    }

    /**
     * Override placeholder to set as empty option label for Select.
     */
    public function placeholder(string|\Closure|null $placeholder): static
    {
        // For selects, placeholder sets the empty option label
        if ($placeholder instanceof \Closure) {
            $placeholder = $placeholder();
        }

        return $this->emptyOptionLabel($placeholder);
    }

    /**
     * Get the empty option label.
     */
    public function getEmptyOptionLabel(): ?string
    {
        return $this->emptyOptionLabel;
    }

    /**
     * Disable the empty option.
     */
    public function disableEmptyOption(): static
    {
        $this->emptyOptionLabel = null;

        return $this;
    }

    /**
     * Control whether placeholder can be selected.
     */
    public function selectablePlaceholder(bool $condition = true): static
    {
        $this->selectablePlaceholder = $condition;

        return $this;
    }

    /**
     * Check if placeholder is selectable.
     */
    public function isSelectablePlaceholder(): bool
    {
        return $this->selectablePlaceholder;
    }

    /**
     * Use native select element.
     */
    public function native(bool $condition = true): static
    {
        $this->isNative = $condition;

        return $this;
    }

    /**
     * Check if using native select.
     */
    public function isNative(): bool
    {
        return $this->isNative;
    }

    /**
     * Check if searchable is enabled (overrides trait to consider native mode).
     */
    public function isSearchable(): bool
    {
        return $this->isSearchable && ! $this->isNative;
    }

    /**
     * Set the search input placeholder.
     */
    public function searchPlaceholder(string $placeholder): static
    {
        $this->searchPlaceholder = $placeholder;

        return $this;
    }

    /**
     * Get the search placeholder.
     */
    public function getSearchPlaceholder(): string
    {
        return $this->searchPlaceholder ?? __('Search...');
    }

    /**
     * Set the no results text.
     */
    public function noResultsText(string $text): static
    {
        $this->noResultsText = $text;

        return $this;
    }

    /**
     * Alias for noResultsText - Filament compatibility.
     */
    public function noSearchResultsMessage(string $message): static
    {
        return $this->noResultsText($message);
    }

    /**
     * Get the no results text.
     */
    public function getNoResultsText(): string
    {
        return $this->noResultsText ?? __('No results found');
    }

    /**
     * Set the no options message (when no options available via preload).
     */
    public function noOptionsMessage(string $message): static
    {
        $this->noOptionsMessage = $message;

        return $this;
    }

    /**
     * Get the no options message.
     */
    public function getNoOptionsMessage(): string
    {
        return $this->noOptionsMessage ?? __('No options available');
    }

    /**
     * Set the loading message.
     */
    public function loadingMessage(string $message): static
    {
        $this->loadingMessage = $message;

        return $this;
    }

    /**
     * Get the loading message.
     */
    public function getLoadingMessage(): string
    {
        return $this->loadingMessage ?? __('Loading...');
    }

    /**
     * Set the searching message.
     */
    public function searchingMessage(string $message): static
    {
        $this->searchingMessage = $message;

        return $this;
    }

    /**
     * Get the searching message.
     */
    public function getSearchingMessage(): string
    {
        return $this->searchingMessage ?? __('Searching...');
    }

    /**
     * Set the search prompt (helper text before user starts searching).
     */
    public function searchPrompt(string $prompt): static
    {
        $this->searchPrompt = $prompt;

        return $this;
    }

    /**
     * Get the search prompt.
     */
    public function getSearchPrompt(): ?string
    {
        return $this->searchPrompt;
    }

    /**
     * Set the minimum search length before filtering.
     */
    public function searchMinLength(int $length): static
    {
        $this->searchMinLength = $length;

        return $this;
    }

    /**
     * Get the minimum search length.
     */
    public function getSearchMinLength(): int
    {
        return $this->searchMinLength;
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
     * Allow clearing the selection.
     */
    public function allowClear(bool $condition = true): static
    {
        $this->allowClear = $condition;

        return $this;
    }

    /**
     * Check if clear is allowed.
     */
    public function hasAllowClear(): bool
    {
        return $this->allowClear;
    }

    /**
     * Close dropdown on select.
     */
    public function closeOnSelect(bool $condition = true): static
    {
        $this->closeOnSelect = $condition;

        return $this;
    }

    /**
     * Check if should close on select.
     */
    public function shouldCloseOnSelect(): bool
    {
        return $this->closeOnSelect;
    }

    /**
     * Set maximum number of selections (for multiple).
     */
    public function maxSelections(int $max): static
    {
        $this->maxSelections = $max;

        return $this;
    }

    /**
     * Alias for maxSelections - Filament compatibility.
     */
    public function maxItems(int $max): static
    {
        return $this->maxSelections($max);
    }

    /**
     * Get maximum selections.
     */
    public function getMaxSelections(): ?int
    {
        return $this->maxSelections;
    }

    /**
     * Set minimum number of selections (for multiple).
     */
    public function minSelections(int $min): static
    {
        $this->minSelections = $min;

        return $this;
    }

    /**
     * Alias for minSelections - Filament compatibility.
     */
    public function minItems(int $min): static
    {
        return $this->minSelections($min);
    }

    /**
     * Get minimum selections.
     */
    public function getMinSelections(): ?int
    {
        return $this->minSelections;
    }

    /**
     * Allow creating new options (tagging).
     */
    public function taggable(bool $condition = true): static
    {
        $this->taggable = $condition;

        return $this;
    }

    /**
     * Check if taggable is enabled.
     */
    public function isTaggable(): bool
    {
        return $this->taggable;
    }

    /**
     * Set the create option text template.
     */
    public function createOptionText(string $text): static
    {
        $this->createOptionText = $text;

        return $this;
    }

    /**
     * Get the create option text.
     */
    public function getCreateOptionText(): string
    {
        return $this->createOptionText ?? __('Create "{value}"');
    }

    /**
     * Set a callback for creating new options.
     */
    public function createOptionUsing(Closure $callback): static
    {
        $this->createOptionUsing = $callback;
        $this->taggable = true;

        return $this;
    }

    /**
     * Get the create option callback.
     */
    public function getCreateOptionUsing(): ?Closure
    {
        return $this->createOptionUsing;
    }

    /**
     * Set maximum options to display.
     */
    public function optionsLimit(int $limit): static
    {
        $this->optionsLimit = $limit;

        return $this;
    }

    /**
     * Get options limit.
     */
    public function getOptionsLimit(): int
    {
        return $this->optionsLimit;
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
     * Check if HTML is allowed.
     */
    public function hasAllowHtml(): bool
    {
        return $this->allowHtml;
    }

    /**
     * Control whether option labels should wrap.
     */
    public function wrapOptionLabels(bool $condition = true): static
    {
        $this->wrapOptionLabels = $condition;

        return $this;
    }

    /**
     * Check if option labels should wrap.
     */
    public function shouldWrapOptionLabels(): bool
    {
        return $this->wrapOptionLabels;
    }

    /**
     * Set a closure to disable specific options.
     */
    public function disableOptionWhen(Closure $callback): static
    {
        $this->disableOptionWhen = $callback;

        return $this;
    }

    /**
     * Get the disable option callback.
     */
    public function getDisableOptionWhen(): ?Closure
    {
        return $this->disableOptionWhen;
    }

    /**
     * Check if an option is disabled.
     */
    public function isOptionDisabled(mixed $value, string $label): bool
    {
        if ($this->disableOptionWhen === null) {
            return false;
        }

        return (bool) ($this->disableOptionWhen)($value, $label);
    }

    /**
     * Set a closure to get option descriptions.
     */
    public function getOptionDescriptionUsing(Closure $callback): static
    {
        $this->getOptionDescriptionUsing = $callback;

        return $this;
    }

    /**
     * Get the option description for a value.
     */
    public function getOptionDescription(mixed $value): ?string
    {
        if ($this->getOptionDescriptionUsing === null) {
            return null;
        }

        return ($this->getOptionDescriptionUsing)($value);
    }

    /**
     * Check if options have descriptions.
     */
    public function hasOptionDescriptions(): bool
    {
        return $this->getOptionDescriptionUsing !== null;
    }

    /**
     * Create a boolean Yes/No select.
     */
    public function boolean(string $trueLabel = 'Yes', string $falseLabel = 'No', ?string $placeholder = null): static
    {
        $this->isBoolean = true;
        $this->trueLabel = $trueLabel;
        $this->falseLabel = $falseLabel;

        $this->options([
            '1' => __($trueLabel),
            '0' => __($falseLabel),
        ]);

        if ($placeholder !== null) {
            $this->emptyOptionLabel($placeholder);
        }

        return $this;
    }

    /**
     * Check if this is a boolean select.
     */
    public function isBoolean(): bool
    {
        return $this->isBoolean;
    }

    /**
     * Get all searchable select options as array for JavaScript.
     */
    public function getSearchableOptions(): array
    {
        return [
            'searchable' => $this->isSearchable(),
            'searchPlaceholder' => $this->getSearchPlaceholder(),
            'noResultsText' => $this->getNoResultsText(),
            'noOptionsMessage' => $this->getNoOptionsMessage(),
            'loadingMessage' => $this->getLoadingMessage(),
            'searchingMessage' => $this->getSearchingMessage(),
            'searchPrompt' => $this->getSearchPrompt(),
            'searchMinLength' => $this->searchMinLength,
            'searchDebounce' => $this->searchDebounce,
            'allowClear' => $this->allowClear,
            'closeOnSelect' => $this->closeOnSelect,
            'maxSelections' => $this->maxSelections,
            'minSelections' => $this->minSelections,
            'taggable' => $this->taggable,
            'createOptionText' => $this->getCreateOptionText(),
            'multiple' => $this->isMultiple(),
            'disabled' => $this->isDisabled(),
            'optionsLimit' => $this->optionsLimit,
            'allowHtml' => $this->allowHtml,
            'wrapOptionLabels' => $this->wrapOptionLabels,
            'hasGroupedOptions' => $this->hasGroupedOptions,
            'hasDescriptions' => $this->hasOptionDescriptions(),
            'remoteUrl' => $this->remoteUrl,
            'remoteRoot' => $this->remoteRoot,
            'hasCreateOptionForm' => $this->hasCreateOptionForm(),
            'createOptionModalHeading' => $this->getCreateOptionModalHeading(),
            'createOptionModalSubmitButtonLabel' => $this->getCreateOptionModalSubmitButtonLabel(),
            'hasEditOptionForm' => $this->hasEditOptionForm(),
            'editOptionModalHeading' => $this->getEditOptionModalHeading(),
            'editOptionModalSubmitButtonLabel' => $this->getEditOptionModalSubmitButtonLabel(),
            // Pagination options
            'infiniteScroll' => $this->hasInfiniteScroll(),
            'showAllOptions' => $this->showAllOptions,
            'perPage' => $this->perPage,
            'hasModel' => $this->hasModel(),
            'searchUrl' => $this->getSearchUrl(),
            'loadMoreMessage' => __('Loading more...'),
        ];
    }

    /**
     * Set a callback for getting the option label.
     */
    public function getOptionLabelUsing(Closure $callback): static
    {
        $this->getOptionLabelUsing = $callback;

        return $this;
    }

    /**
     * Set a callback for getting multiple option labels.
     */
    public function getOptionLabelsUsing(Closure $callback): static
    {
        $this->getOptionLabelsUsing = $callback;

        return $this;
    }

    /**
     * Get the option label for a value.
     */
    public function getOptionLabel(mixed $value): ?string
    {
        if ($this->getOptionLabelUsing !== null) {
            return ($this->getOptionLabelUsing)($value);
        }

        $options = $this->getOptions();

        // Handle grouped options
        if ($this->hasGroupedOptions) {
            foreach ($options as $group) {
                if (is_array($group) && isset($group[$value])) {
                    return $group[$value];
                }
            }

            return null;
        }

        return $options[$value] ?? null;
    }

    /**
     * Get multiple option labels.
     */
    public function getOptionLabels(array $values): array
    {
        if ($this->getOptionLabelsUsing !== null) {
            return ($this->getOptionLabelsUsing)($values);
        }

        $labels = [];
        foreach ($values as $value) {
            $label = $this->getOptionLabel($value);
            if ($label !== null) {
                $labels[$value] = $label;
            }
        }

        return $labels;
    }

    /**
     * Set a callback for dynamically loading options.
     */
    public function getOptionsUsing(Closure $callback): static
    {
        $this->getOptionsUsing = $callback;

        return $this;
    }

    /**
     * Set a callback for async search results.
     */
    public function getSearchResultsUsing(Closure $callback): static
    {
        $this->getSearchResultsUsing = $callback;

        return $this;
    }

    /**
     * Get the search results callback.
     */
    public function getSearchResultsCallback(): ?Closure
    {
        return $this->getSearchResultsUsing;
    }

    /**
     * Check if async search is enabled.
     */
    public function hasSearchResultsUsing(): bool
    {
        return $this->getSearchResultsUsing !== null;
    }

    /**
     * Set the property name for option labels (for object collections).
     */
    public function optionLabel(string $property): static
    {
        $this->optionLabel = $property;

        return $this;
    }

    /**
     * Get the option label property.
     */
    public function getOptionLabelProperty(): ?string
    {
        return $this->optionLabel;
    }

    /**
     * Set the property name for option values (for object collections).
     */
    public function optionValue(string $property): static
    {
        $this->optionValue = $property;

        return $this;
    }

    /**
     * Get the option value property.
     */
    public function getOptionValueProperty(): ?string
    {
        return $this->optionValue;
    }

    /**
     * Set grouped options (options within groups).
     */
    public function groupedOptions(array|Collection|Closure $options): static
    {
        $this->hasGroupedOptions = true;

        return $this->options($options);
    }

    /**
     * Check if options are grouped.
     */
    public function hasGroupedOptions(): bool
    {
        return $this->hasGroupedOptions;
    }

    /**
     * Enable Choices.js integration (Splade compatibility).
     */
    public function choices(bool|array $options = true): static
    {
        $this->choices = $options;
        $this->isNative = false;

        return $this;
    }

    /**
     * Check if Choices.js is enabled.
     */
    public function hasChoices(): bool
    {
        return $this->choices !== false;
    }

    /**
     * Get Choices.js options.
     */
    public function getChoicesOptions(): array
    {
        if ($this->choices === true) {
            return static::$defaultChoicesOptions ?? [];
        }

        return is_array($this->choices) ? $this->choices : [];
    }

    /**
     * Set default Choices.js options globally.
     */
    public static function defaultChoices(array $options = []): void
    {
        static::$defaultChoicesOptions = $options;
    }

    /**
     * Set the remote URL for async option loading.
     */
    public function remoteUrl(string $url): static
    {
        $this->remoteUrl = $url;

        return $this;
    }

    /**
     * Get the remote URL.
     */
    public function getRemoteUrl(): ?string
    {
        return $this->remoteUrl;
    }

    /**
     * Check if remote options are enabled.
     */
    public function hasRemoteUrl(): bool
    {
        return $this->remoteUrl !== null;
    }

    /**
     * Set the remote root path for nested data extraction.
     */
    public function remoteRoot(string $path): static
    {
        $this->remoteRoot = $path;

        return $this;
    }

    /**
     * Get the remote root path.
     */
    public function getRemoteRoot(): ?string
    {
        return $this->remoteRoot;
    }

    /**
     * Auto-select the first remote option.
     */
    public function selectFirstRemoteOption(bool $condition = true): static
    {
        $this->selectFirstRemoteOption = $condition;

        return $this;
    }

    /**
     * Check if first remote option should be selected.
     */
    public function shouldSelectFirstRemoteOption(): bool
    {
        return $this->selectFirstRemoteOption || static::$defaultSelectFirstRemoteOption;
    }

    /**
     * Set default select first remote option globally.
     */
    public static function defaultSelectFirstRemoteOption(bool $condition = true): void
    {
        static::$defaultSelectFirstRemoteOption = $condition;
    }

    /**
     * Reset selection when remote URL changes.
     */
    public function resetOnNewRemoteUrl(bool $condition = true): static
    {
        $this->resetOnNewRemoteUrl = $condition;

        return $this;
    }

    /**
     * Check if should reset on new remote URL.
     */
    public function shouldResetOnNewRemoteUrl(): bool
    {
        return $this->resetOnNewRemoteUrl || static::$defaultResetOnNewRemoteUrl;
    }

    /**
     * Set default reset on new remote URL globally.
     */
    public static function defaultResetOnNewRemoteUrl(bool $condition = true): void
    {
        static::$defaultResetOnNewRemoteUrl = $condition;
    }

    /**
     * Set the Eloquent relationship.
     */
    public function relationship(string $name, ?string $titleAttribute = null, ?Closure $modifyQueryUsing = null): static
    {
        $this->relation = $name;
        $this->relationTitleAttribute = $titleAttribute;

        if ($modifyQueryUsing !== null) {
            $this->modifyQueryUsing = $modifyQueryUsing;
        }

        return $this;
    }

    /**
     * Set the Eloquent relation name (for BelongsToMany, MorphToMany) - Splade compatibility.
     */
    public function relation(string $name): static
    {
        $this->relation = $name;
        $this->isMultiple = true;

        return $this;
    }

    /**
     * Get the relation name.
     */
    public function getRelation(): ?string
    {
        return $this->relation;
    }

    /**
     * Get the relation title attribute.
     */
    public function getRelationTitleAttribute(): ?string
    {
        return $this->relationTitleAttribute;
    }

    /**
     * Check if using relation.
     */
    public function hasRelation(): bool
    {
        return $this->relation !== null;
    }

    /**
     * Set a callback for getting option label from Eloquent record.
     */
    public function getOptionLabelFromRecordUsing(Closure $callback): static
    {
        $this->getOptionLabelFromRecordUsing = $callback;

        return $this;
    }

    /**
     * Get the option label from record callback.
     */
    public function getOptionLabelFromRecordCallback(): ?Closure
    {
        return $this->getOptionLabelFromRecordUsing;
    }

    /**
     * Modify the relationship query.
     */
    public function modifyQueryUsing(Closure $callback): static
    {
        $this->modifyQueryUsing = $callback;

        return $this;
    }

    /**
     * Get the query modifier.
     */
    public function getModifyQueryUsing(): ?Closure
    {
        return $this->modifyQueryUsing;
    }

    /**
     * Set pivot data for BelongsToMany relationships.
     */
    public function pivotData(array $data): static
    {
        $this->pivotData = $data;

        return $this;
    }

    /**
     * Get pivot data.
     */
    public function getPivotData(): array
    {
        return $this->pivotData;
    }

    /**
     * Preload all options.
     */
    public function preload(bool $condition = true): static
    {
        $this->preload = $condition;

        return $this;
    }

    /**
     * Check if preload is enabled.
     */
    public function shouldPreload(): bool
    {
        return $this->preload;
    }

    /**
     * Set the options limit (number of items to show by default).
     */
    public function limit(int $limit): static
    {
        $this->optionsLimit = $limit;
        $this->perPage = $limit;
        $this->showAllOptions = false;

        // Reload options if model is configured
        if ($this->hasModel()) {
            $this->loadInitialModelOptions();
        }

        return $this;
    }

    /**
     * Show all options without pagination.
     */
    public function all(): static
    {
        $this->showAllOptions = true;
        $this->infiniteScroll = false;

        // Reload options if model is configured
        if ($this->hasModel()) {
            $this->loadInitialModelOptions();
        }

        return $this;
    }

    /**
     * Check if showing all options.
     */
    public function isShowingAllOptions(): bool
    {
        return $this->showAllOptions;
    }

    /**
     * Enable or disable infinite scroll.
     */
    public function infiniteScroll(bool $condition = true): static
    {
        $this->infiniteScroll = $condition;

        return $this;
    }

    /**
     * Check if infinite scroll is enabled.
     */
    public function hasInfiniteScroll(): bool
    {
        return $this->infiniteScroll && ! $this->showAllOptions;
    }

    /**
     * Set the number of items per page for infinite scroll.
     */
    public function perPage(int $perPage): static
    {
        $this->perPage = $perPage;

        return $this;
    }

    /**
     * Get the per page value.
     */
    public function getPerPage(): int
    {
        return $this->perPage;
    }

    /**
     * Set the Eloquent model for searching options.
     * This will automatically load the first page of options.
     */
    public function model(string $modelClass, string $labelAttribute = 'name', string $valueAttribute = 'id'): static
    {
        $this->modelClass = $modelClass;
        $this->modelLabelAttribute = $labelAttribute;
        $this->modelValueAttribute = $valueAttribute;

        // Load initial options from model
        $this->loadInitialModelOptions();

        return $this;
    }

    /**
     * Override default to ensure selected value is included in options.
     */
    public function default(mixed $value): static
    {
        parent::default($value);

        // If model is configured and we have a default value, ensure it's in the options
        if ($this->hasModel() && $value !== null) {
            $this->ensureSelectedValueInOptions($value);
        }

        return $this;
    }

    /**
     * Load the initial page of options from the model.
     */
    protected function loadInitialModelOptions(): void
    {
        if (! $this->modelClass) {
            return;
        }

        $query = $this->modelClass::query();

        // Apply any custom query modifications
        if ($this->modifyQueryUsing !== null) {
            $query = ($this->modifyQueryUsing)($query);
        }

        $limit = $this->showAllOptions ? null : $this->perPage;

        if ($limit) {
            $query->limit($limit);
        }

        $results = $query->pluck($this->modelLabelAttribute, $this->modelValueAttribute)->toArray();

        $this->options = $results;

        // Ensure any default value is included in options
        $defaultValue = $this->getDefault();
        if ($defaultValue !== null) {
            $this->ensureSelectedValueInOptions($defaultValue);
        }
    }

    /**
     * Ensure selected value(s) are included in options.
     * This queries the model to fetch the label for selected values not in the current options.
     */
    protected function ensureSelectedValueInOptions(mixed $value): void
    {
        if (! $this->modelClass || $value === null) {
            return;
        }

        $values = is_array($value) ? $value : [$value];
        $missingValues = [];

        // Find values not in current options
        foreach ($values as $v) {
            if (! isset($this->options[$v])) {
                $missingValues[] = $v;
            }
        }

        if (empty($missingValues)) {
            return;
        }

        // Query the model for missing values
        $valueAttr = $this->getModelValueAttribute();
        $labelAttr = $this->getModelLabelAttribute();

        $missingOptions = $this->modelClass::query()
            ->whereIn($valueAttr, $missingValues)
            ->pluck($labelAttr, $valueAttr)
            ->toArray();

        // Prepend missing options to ensure they appear first (selected items should be visible)
        $this->options = $missingOptions + $this->options;
    }

    /**
     * Get the model class.
     */
    public function getModelClass(): ?string
    {
        return $this->modelClass;
    }

    /**
     * Get the model label attribute.
     */
    public function getModelLabelAttribute(): string
    {
        return $this->modelLabelAttribute ?? 'name';
    }

    /**
     * Get the model value attribute.
     */
    public function getModelValueAttribute(): string
    {
        return $this->modelValueAttribute ?? 'id';
    }

    /**
     * Check if a model is configured.
     */
    public function hasModel(): bool
    {
        return $this->modelClass !== null;
    }

    /**
     * Set the search URL for async options loading.
     */
    public function searchUrl(string $url): static
    {
        $this->searchUrl = $url;

        return $this;
    }

    /**
     * Get the search URL.
     * If a model is configured, automatically generates a secure token URL.
     */
    public function getSearchUrl(): ?string
    {
        // If custom URL is set, use that
        if ($this->searchUrl !== null) {
            return $this->searchUrl;
        }

        // If model is configured, generate secure URL
        if ($this->hasModel()) {
            return $this->generateSecureSearchUrl();
        }

        return null;
    }

    /**
     * Generate a secure search URL with encrypted token.
     * The model class and all configuration is encrypted in the token.
     */
    protected function generateSecureSearchUrl(): ?string
    {
        if (! $this->modelClass) {
            return null;
        }

        return \Accelade\Forms\Support\SelectToken::url(
            $this->modelClass,
            [
                'label_attribute' => $this->getModelLabelAttribute(),
                'value_attribute' => $this->getModelValueAttribute(),
                'search_columns' => $this->searchColumn,
                'per_page' => $this->perPage,
            ]
        );
    }

    /**
     * Set the column to search on.
     */
    public function searchColumn(string $column): static
    {
        $this->searchColumn = $column;

        return $this;
    }

    /**
     * Get the search column.
     */
    public function getSearchColumn(): string
    {
        return $this->searchColumn ?? $this->getModelLabelAttribute();
    }

    /**
     * Get paginated options from model.
     */
    public function getPaginatedOptions(int $page = 1, ?string $search = null): array
    {
        if (! $this->hasModel()) {
            $options = $this->getOptions();

            if ($this->showAllOptions) {
                return [
                    'data' => $this->formatOptionsForJs($options),
                    'hasMore' => false,
                    'total' => count($options),
                ];
            }

            $offset = ($page - 1) * $this->perPage;
            $sliced = array_slice($options, $offset, $this->perPage, true);

            return [
                'data' => $this->formatOptionsForJs($sliced),
                'hasMore' => $offset + $this->perPage < count($options),
                'total' => count($options),
            ];
        }

        $query = $this->modelClass::query();

        // Apply search filter
        if ($search !== null && $search !== '') {
            $searchColumn = $this->getSearchColumn();
            $query->where($searchColumn, 'like', "%{$search}%");
        }

        // Apply any custom query modifications
        if ($this->modifyQueryUsing !== null) {
            $query = ($this->modifyQueryUsing)($query);
        }

        if ($this->showAllOptions) {
            $results = $query->get();

            return [
                'data' => $this->formatModelResultsForJs($results),
                'hasMore' => false,
                'total' => $results->count(),
            ];
        }

        $paginator = $query->paginate($this->perPage, ['*'], 'page', $page);

        return [
            'data' => $this->formatModelResultsForJs($paginator->items()),
            'hasMore' => $paginator->hasMorePages(),
            'total' => $paginator->total(),
            'currentPage' => $paginator->currentPage(),
            'lastPage' => $paginator->lastPage(),
        ];
    }

    /**
     * Format static options array for JavaScript.
     */
    protected function formatOptionsForJs(array $options): array
    {
        $formatted = [];

        foreach ($options as $value => $label) {
            $formatted[] = [
                'value' => (string) $value,
                'label' => $label,
                'disabled' => $this->isOptionDisabled($value, $label),
                'description' => $this->getOptionDescription($value),
            ];
        }

        return $formatted;
    }

    /**
     * Format model results for JavaScript.
     */
    protected function formatModelResultsForJs($results): array
    {
        $formatted = [];
        $labelAttr = $this->getModelLabelAttribute();
        $valueAttr = $this->getModelValueAttribute();

        foreach ($results as $model) {
            $value = $model->{$valueAttr};
            $label = $model->{$labelAttr};

            $formatted[] = [
                'value' => (string) $value,
                'label' => $label,
                'disabled' => $this->isOptionDisabled($value, $label),
                'description' => $this->getOptionDescription($value),
            ];
        }

        return $formatted;
    }

    /**
     * Set the form schema for creating new options in a modal.
     */
    public function createOptionForm(array|Closure $form): static
    {
        $this->createOptionForm = $form;

        return $this;
    }

    /**
     * Get the create option form schema.
     */
    public function getCreateOptionForm(): ?array
    {
        if ($this->createOptionForm === null) {
            return null;
        }

        if ($this->createOptionForm instanceof Closure) {
            return ($this->createOptionForm)();
        }

        return $this->createOptionForm;
    }

    /**
     * Check if create option form is enabled.
     */
    public function hasCreateOptionForm(): bool
    {
        return $this->createOptionForm !== null;
    }

    /**
     * Set the action to execute when creating a new option.
     */
    public function createOptionAction(Closure $action): static
    {
        $this->createOptionAction = $action;

        return $this;
    }

    /**
     * Get the create option action.
     */
    public function getCreateOptionAction(): ?Closure
    {
        return $this->createOptionAction;
    }

    /**
     * Set the create option modal heading.
     */
    public function createOptionModalHeading(string $heading): static
    {
        $this->createOptionModalHeading = $heading;

        return $this;
    }

    /**
     * Get the create option modal heading.
     */
    public function getCreateOptionModalHeading(): string
    {
        return $this->createOptionModalHeading ?? __('Create option');
    }

    /**
     * Set the create option modal submit button label.
     */
    public function createOptionModalSubmitButtonLabel(string $label): static
    {
        $this->createOptionModalSubmitButtonLabel = $label;

        return $this;
    }

    /**
     * Get the create option modal submit button label.
     */
    public function getCreateOptionModalSubmitButtonLabel(): string
    {
        return $this->createOptionModalSubmitButtonLabel ?? __('Create');
    }

    /**
     * Set the form schema for editing selected options in a modal.
     */
    public function editOptionForm(array|Closure $form): static
    {
        $this->editOptionForm = $form;

        return $this;
    }

    /**
     * Get the edit option form schema.
     */
    public function getEditOptionForm(): ?array
    {
        if ($this->editOptionForm === null) {
            return null;
        }

        if ($this->editOptionForm instanceof Closure) {
            return ($this->editOptionForm)();
        }

        return $this->editOptionForm;
    }

    /**
     * Check if edit option form is enabled.
     */
    public function hasEditOptionForm(): bool
    {
        return $this->editOptionForm !== null;
    }

    /**
     * Set the action to execute when updating an option.
     */
    public function updateOptionUsing(Closure $action): static
    {
        $this->updateOptionAction = $action;

        return $this;
    }

    /**
     * Get the update option action.
     */
    public function getUpdateOptionAction(): ?Closure
    {
        return $this->updateOptionAction;
    }

    /**
     * Set the edit option modal heading.
     */
    public function editOptionModalHeading(string $heading): static
    {
        $this->editOptionModalHeading = $heading;

        return $this;
    }

    /**
     * Get the edit option modal heading.
     */
    public function getEditOptionModalHeading(): string
    {
        return $this->editOptionModalHeading ?? __('Edit option');
    }

    /**
     * Set the edit option modal submit button label.
     */
    public function editOptionModalSubmitButtonLabel(string $label): static
    {
        $this->editOptionModalSubmitButtonLabel = $label;

        return $this;
    }

    /**
     * Get the edit option modal submit button label.
     */
    public function getEditOptionModalSubmitButtonLabel(): string
    {
        return $this->editOptionModalSubmitButtonLabel ?? __('Save');
    }

    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.select';
    }
}
