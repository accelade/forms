<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Data\IconEmojiData;
use Accelade\Forms\Enums\IconSet;
use Accelade\Forms\Field;
use Accelade\Icons\BladeIconsRegistry;
use Closure;

/**
 * Icon Picker Component
 *
 * A picker for selecting icons from multiple icon libraries.
 * Supports Emoji, Boxicons, Heroicons, Lucide icons, and any installed Blade Icons packages.
 */
class IconPicker extends Field
{
    /** @var array<string, string>|Closure */
    protected array|Closure $icons = [];

    protected bool|Closure $searchable = true;

    protected int|Closure $gridColumns = 8;

    protected bool|Closure $showIconName = false;

    protected bool|Closure $multiple = false;

    protected int|Closure|null $maxItems = null;

    protected int|Closure|null $minItems = null;

    /** @var array<int, string> Icon sets to include */
    protected array $sets = ['emoji'];

    /** @var array<int, string> */
    protected array $categories = [];

    /** @var string|null Default icon set */
    protected ?string $defaultSet = null;

    /** @var bool Use Blade Icons (lazy loading from API) */
    protected bool $useBladeIcons = false;

    /** @var int Icons per page for lazy loading */
    protected int $perPage = 50;

    /**
     * Set the available icons (custom icons).
     *
     * @param  array<string, string>|Closure  $icons  Array of icon_class => icon_name
     */
    public function icons(array|Closure $icons): static
    {
        $this->icons = $icons;

        return $this;
    }

    /**
     * Set which icon sets to include.
     * Accepts an array of IconSet enums or string values.
     *
     * @param  array<int, IconSet|string>  $sets
     */
    public function sets(array $sets): static
    {
        $this->sets = array_map(
            fn (IconSet|string $set) => $set instanceof IconSet ? $set->value : $set,
            $sets
        );

        return $this;
    }

    /**
     * Get the enabled icon sets.
     *
     * @return array<int, string>
     */
    public function getSets(): array
    {
        return $this->sets;
    }

    /**
     * Set the default icon set to display.
     * Accepts an IconSet enum or string value.
     */
    public function defaultSet(IconSet|string $set): static
    {
        $this->defaultSet = $set instanceof IconSet ? $set->value : $set;

        return $this;
    }

    /**
     * Get the default icon set.
     */
    public function getDefaultSet(): string
    {
        return $this->defaultSet ?? ($this->sets[0] ?? 'emoji');
    }

    /**
     * Enable Blade Icons mode (lazy loading from installed packages).
     * When enabled, icons are fetched via API with pagination and search.
     */
    public function bladeIcons(bool $enabled = true): static
    {
        $this->useBladeIcons = $enabled;

        return $this;
    }

    /**
     * Check if Blade Icons mode is enabled.
     */
    public function usesBladeIcons(): bool
    {
        return $this->useBladeIcons;
    }

    /**
     * Set icons per page for lazy loading.
     */
    public function perPage(int $count): static
    {
        $this->perPage = $count;

        return $this;
    }

    /**
     * Get icons per page.
     */
    public function getPerPage(): int
    {
        return $this->perPage;
    }

    /**
     * Get available Blade Icon sets from registry.
     *
     * @return array<array{name: string, prefix: string, count: int}>
     */
    public function getBladeIconSets(): array
    {
        if (! app()->bound(BladeIconsRegistry::class)) {
            return [];
        }

        /** @var BladeIconsRegistry $registry */
        $registry = app(BladeIconsRegistry::class);

        return $registry->getSetsSummary();
    }

    /**
     * Get the API endpoint for icons.
     */
    public function getIconsApiEndpoint(): string
    {
        return route('accelade.icons.list', ['set' => ':set']);
    }

    /**
     * Get the API endpoint for search.
     */
    public function getSearchApiEndpoint(): string
    {
        return route('accelade.icons.search');
    }

    /**
     * Set the icon categories to display.
     *
     * @param  array<int, string>  $categories
     */
    public function categories(array $categories): static
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * Get the icon categories.
     *
     * @return array<int, string>
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * Get the icons array.
     *
     * @return array<string, array<string, array<string, string>>>
     */
    public function getIcons(): array
    {
        $customIcons = $this->evaluate($this->icons);

        if (! empty($customIcons)) {
            return ['custom' => ['icons' => $customIcons]];
        }

        return $this->getIconsBySets();
    }

    /**
     * Get icons organized by sets.
     *
     * When using Blade Icons mode (bladeIcons()), icons are fetched via API.
     * For inline usage, provide custom icons via the icons() method.
     * Emoji icons are always available as predefined data.
     *
     * @return array<string, array<string, array<string, string>>>
     */
    protected function getIconsBySets(): array
    {
        $result = [];

        foreach ($this->sets as $set) {
            $icons = match ($set) {
                'emoji' => $this->getEmojiIcons(),
                // Other icon sets (boxicons, heroicons, lucide) are loaded via Blade Icons API
                default => [],
            };

            if (! empty($icons)) {
                $result[$set] = $icons;
            }
        }

        return $result;
    }

    /**
     * Get Emoji icons organized by category.
     *
     * @return array<string, array<string, string>>
     */
    protected function getEmojiIcons(): array
    {
        return IconEmojiData::all();
    }

    /**
     * Get set labels for display.
     *
     * @return array<string, string>
     */
    public function getSetLabels(): array
    {
        return [
            'emoji' => 'Emoji',
            'boxicons' => 'Boxicons',
            'heroicons' => 'Heroicons',
            'lucide' => 'Lucide',
            'custom' => 'Custom',
        ];
    }

    /**
     * Get category labels.
     *
     * @return array<string, string>
     */
    public function getCategoryLabels(): array
    {
        return [
            // Emoji categories
            'smileys' => 'Smileys & Emotion',
            'people' => 'People & Body',
            'animals' => 'Animals & Nature',
            'food' => 'Food & Drink',
            'travel' => 'Travel & Places',
            'activities' => 'Activities',
            'objects' => 'Objects',
            'symbols' => 'Symbols',
            'flags' => 'Flags',
            // Icon library categories
            'arrows' => 'Arrows',
            'brands' => 'Brands',
            'buildings' => 'Buildings',
            'business' => 'Business',
            'charts' => 'Charts',
            'communication' => 'Communication',
            'design' => 'Design',
            'development' => 'Development',
            'devices' => 'Devices',
            'editor' => 'Editor',
            'education' => 'Education',
            'files' => 'Files',
            'finance' => 'Finance',
            'general' => 'General',
            'health' => 'Health',
            'interface' => 'Interface',
            'logos' => 'Logos',
            'maps' => 'Maps',
            'media' => 'Media',
            'nature' => 'Nature',
            'navigation' => 'Navigation',
            'notifications' => 'Notifications',
            'security' => 'Security',
            'shapes' => 'Shapes',
            'shopping' => 'Shopping',
            'social' => 'Social',
            'text' => 'Text',
            'time' => 'Time',
            'toggle' => 'Toggle',
            'users' => 'Users',
            'weather' => 'Weather',
        ];
    }

    /**
     * Enable/disable search functionality.
     */
    public function searchable(bool|Closure $condition = true): static
    {
        $this->searchable = $condition;

        return $this;
    }

    /**
     * Get searchable state.
     */
    public function isSearchable(): bool
    {
        return $this->evaluate($this->searchable);
    }

    /**
     * Set the number of grid columns.
     */
    public function gridColumns(int|Closure $columns): static
    {
        $this->gridColumns = $columns;

        return $this;
    }

    /**
     * Get grid columns.
     */
    public function getGridColumns(): int
    {
        return $this->evaluate($this->gridColumns);
    }

    /**
     * Show/hide icon name below each icon.
     */
    public function showIconName(bool|Closure $condition = true): static
    {
        $this->showIconName = $condition;

        return $this;
    }

    /**
     * Get show icon name state.
     */
    public function getShowIconName(): bool
    {
        return $this->evaluate($this->showIconName);
    }

    /**
     * Enable multiple icon selection.
     */
    public function multiple(bool|Closure $condition = true): static
    {
        $this->multiple = $condition;

        return $this;
    }

    /**
     * Check if multiple selection is enabled.
     */
    public function isMultiple(): bool
    {
        return $this->evaluate($this->multiple);
    }

    /**
     * Set the maximum number of icons.
     */
    public function maxItems(int|Closure|null $count): static
    {
        $this->maxItems = $count;

        return $this;
    }

    /**
     * Get the maximum number of icons.
     */
    public function getMaxItems(): ?int
    {
        return $this->evaluate($this->maxItems);
    }

    /**
     * Set the minimum number of icons.
     */
    public function minItems(int|Closure|null $count): static
    {
        $this->minItems = $count;

        return $this;
    }

    /**
     * Get the minimum number of icons.
     */
    public function getMinItems(): ?int
    {
        return $this->evaluate($this->minItems);
    }

    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.icon-picker';
    }
}
