<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Field;
use Closure;

/**
 * TipTap rich text editor field component.
 *
 * A powerful rich text editor using TipTap with Filament-compatible API.
 *
 * @see https://tiptap.dev/docs
 * @see https://github.com/awcodes/filament-tiptap-editor
 */
class TipTapEditor extends Field
{
    /**
     * Toolbar profile presets.
     */
    public const PROFILE_DEFAULT = 'default';

    public const PROFILE_SIMPLE = 'simple';

    public const PROFILE_MINIMAL = 'minimal';

    public const PROFILE_NONE = 'none';

    /**
     * Output format options.
     */
    public const OUTPUT_HTML = 'html';

    public const OUTPUT_JSON = 'json';

    public const OUTPUT_TEXT = 'text';

    /**
     * Default toolbar profile.
     */
    protected string $profile = self::PROFILE_DEFAULT;

    /**
     * Custom tools (overrides profile).
     *
     * @var array<int, string>|null
     */
    protected ?array $tools = null;

    /**
     * Output format.
     */
    protected string $outputFormat = self::OUTPUT_HTML;

    /**
     * Maximum content width.
     */
    protected string $maxContentWidth = 'full';

    /**
     * Whether floating menus are enabled.
     */
    protected bool $floatingMenusEnabled = true;

    /**
     * Whether bubble menus are enabled.
     */
    protected bool $bubbleMenusEnabled = true;

    /**
     * Floating menu tools.
     *
     * @var array<int, string>|null
     */
    protected ?array $floatingMenuTools = null;

    /**
     * Placeholder text.
     */
    protected string|Closure|null $placeholder = null;

    /**
     * Node-specific placeholders.
     *
     * @var array<string, string>
     */
    protected array $nodePlaceholders = [];

    /**
     * Merge tags for mail merge functionality.
     *
     * @var array<int, string>
     */
    protected array $mergeTags = [];

    /**
     * Grid layouts for grid builder.
     *
     * @var array<int, string>
     */
    protected array $gridLayouts = [];

    /**
     * Maximum file size in KB.
     */
    protected int $maxFileSize = 2048;

    /**
     * Accepted file types for media.
     *
     * @var array<int, string>
     */
    protected array $acceptedFileTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

    /**
     * Storage disk for uploads.
     */
    protected string|Closure|null $disk = null;

    /**
     * Storage directory for uploads.
     */
    protected string|Closure|null $directory = null;

    /**
     * File visibility.
     */
    protected string|Closure|null $visibility = null;

    /**
     * Whether to preserve file names.
     */
    protected bool $preserveFilenames = false;

    /**
     * Image crop aspect ratio.
     */
    protected ?string $imageCropAspectRatio = null;

    /**
     * Image resize target width.
     */
    protected ?int $imageResizeTargetWidth = null;

    /**
     * Image resize target height.
     */
    protected ?int $imageResizeTargetHeight = null;

    /**
     * Maximum character count.
     */
    protected ?int $maxLength = null;

    /**
     * Whether to show character count.
     */
    protected bool $showCharacterCount = false;

    /**
     * Tippy placement for menus.
     */
    protected string $tippyPlacement = 'top';

    /**
     * Custom extensions to load.
     *
     * @var array<int, string>
     */
    protected array $extensions = [];

    /**
     * Whether to enable collaboration.
     */
    protected bool $collaborationEnabled = false;

    /**
     * Text direction (ltr or rtl).
     */
    protected string $direction = 'ltr';

    /**
     * Preset colors for color picker.
     *
     * @var array<string, string>
     */
    protected array $presetColors = [];

    /**
     * Extra input attributes.
     *
     * @var array<string, mixed>
     */
    protected array $extraInputAttributes = [];

    /**
     * Profile tool definitions.
     *
     * @var array<string, array<int, string>>
     */
    protected static array $profiles = [
        self::PROFILE_DEFAULT => [
            'heading',
            '|',
            'bold',
            'italic',
            'strike',
            'underline',
            '|',
            'bulletList',
            'orderedList',
            '|',
            'blockquote',
            'codeBlock',
            'horizontalRule',
            '|',
            'link',
            'media',
            'table',
            '|',
            'alignLeft',
            'alignCenter',
            'alignRight',
            'alignJustify',
            '|',
            'undo',
            'redo',
        ],
        self::PROFILE_SIMPLE => [
            'heading',
            '|',
            'bold',
            'italic',
            '|',
            'bulletList',
            'orderedList',
            '|',
            'link',
            'media',
            '|',
            'undo',
            'redo',
        ],
        self::PROFILE_MINIMAL => [
            'bold',
            'italic',
            'link',
            '|',
            'bulletList',
            'orderedList',
        ],
        self::PROFILE_NONE => [],
    ];

    /**
     * Set the toolbar profile.
     */
    public function profile(string $profile): static
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * Get the current profile.
     */
    public function getProfile(): string
    {
        return $this->profile;
    }

    /**
     * Set custom tools (overrides profile).
     *
     * @param  array<int, string>  $tools
     */
    public function tools(array $tools): static
    {
        $this->tools = $tools;

        return $this;
    }

    /**
     * Get the tools to display.
     *
     * @return array<int, string>
     */
    public function getTools(): array
    {
        if ($this->tools !== null) {
            return $this->tools;
        }

        return static::$profiles[$this->profile] ?? static::$profiles[self::PROFILE_DEFAULT];
    }

    /**
     * Get tools grouped by separator.
     *
     * @return array<int, array<int, string>>
     */
    public function getGroupedTools(): array
    {
        $tools = $this->getTools();
        $groups = [];
        $currentGroup = [];

        foreach ($tools as $tool) {
            if ($tool === '|') {
                if (! empty($currentGroup)) {
                    $groups[] = $currentGroup;
                    $currentGroup = [];
                }
            } else {
                $currentGroup[] = $tool;
            }
        }

        if (! empty($currentGroup)) {
            $groups[] = $currentGroup;
        }

        return $groups;
    }

    /**
     * Set output format.
     */
    public function output(string $format): static
    {
        $this->outputFormat = $format;

        return $this;
    }

    /**
     * Get output format.
     */
    public function getOutputFormat(): string
    {
        return $this->outputFormat;
    }

    /**
     * Set maximum content width.
     */
    public function maxContentWidth(string $width): static
    {
        $this->maxContentWidth = $width;

        return $this;
    }

    /**
     * Get maximum content width.
     */
    public function getMaxContentWidth(): string
    {
        return $this->maxContentWidth;
    }

    /**
     * Disable floating menus.
     */
    public function disableFloatingMenus(bool $condition = true): static
    {
        $this->floatingMenusEnabled = ! $condition;

        return $this;
    }

    /**
     * Check if floating menus are enabled.
     */
    public function hasFloatingMenus(): bool
    {
        return $this->floatingMenusEnabled;
    }

    /**
     * Disable bubble menus.
     */
    public function disableBubbleMenus(bool $condition = true): static
    {
        $this->bubbleMenusEnabled = ! $condition;

        return $this;
    }

    /**
     * Check if bubble menus are enabled.
     */
    public function hasBubbleMenus(): bool
    {
        return $this->bubbleMenusEnabled;
    }

    /**
     * Set floating menu tools.
     *
     * @param  array<int, string>  $tools
     */
    public function floatingMenuTools(array $tools): static
    {
        $this->floatingMenuTools = $tools;

        return $this;
    }

    /**
     * Get floating menu tools.
     *
     * @return array<int, string>
     */
    public function getFloatingMenuTools(): array
    {
        return $this->floatingMenuTools ?? ['media', 'table', 'horizontalRule'];
    }

    /**
     * Set placeholder text.
     */
    public function placeholder(string|Closure|null $placeholder): static
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    /**
     * Get placeholder text.
     */
    public function getPlaceholder(): ?string
    {
        return $this->evaluate($this->placeholder);
    }

    /**
     * Set node-specific placeholders.
     *
     * @param  array<string, string>  $placeholders
     */
    public function nodePlaceholders(array $placeholders): static
    {
        $this->nodePlaceholders = $placeholders;

        return $this;
    }

    /**
     * Get node placeholders.
     *
     * @return array<string, string>
     */
    public function getNodePlaceholders(): array
    {
        return $this->nodePlaceholders;
    }

    /**
     * Set merge tags.
     *
     * @param  array<int, string>  $tags
     */
    public function mergeTags(array $tags): static
    {
        $this->mergeTags = $tags;

        return $this;
    }

    /**
     * Get merge tags.
     *
     * @return array<int, string>
     */
    public function getMergeTags(): array
    {
        return $this->mergeTags;
    }

    /**
     * Set grid layouts for grid builder.
     *
     * @param  array<int, string>  $layouts
     */
    public function gridLayouts(array $layouts): static
    {
        $this->gridLayouts = $layouts;

        return $this;
    }

    /**
     * Get grid layouts.
     *
     * @return array<int, string>
     */
    public function getGridLayouts(): array
    {
        return $this->gridLayouts !== [] ? $this->gridLayouts : [
            'two-columns',
            'three-columns',
            'four-columns',
            'five-columns',
            'fixed-two-columns',
            'fixed-three-columns',
            'fixed-four-columns',
            'asymmetric-left-thirds',
            'asymmetric-right-thirds',
            'asymmetric-left-fourths',
            'asymmetric-right-fourths',
        ];
    }

    /**
     * Set maximum file size in KB.
     */
    public function maxSize(int $size): static
    {
        $this->maxFileSize = $size;

        return $this;
    }

    /**
     * Get maximum file size.
     */
    public function getMaxSize(): int
    {
        return $this->maxFileSize;
    }

    /**
     * Set accepted file types.
     *
     * @param  array<int, string>  $types
     */
    public function acceptedFileTypes(array $types): static
    {
        $this->acceptedFileTypes = $types;

        return $this;
    }

    /**
     * Get accepted file types.
     *
     * @return array<int, string>
     */
    public function getAcceptedFileTypes(): array
    {
        return $this->acceptedFileTypes;
    }

    /**
     * Set storage disk.
     */
    public function disk(string|Closure|null $disk): static
    {
        $this->disk = $disk;

        return $this;
    }

    /**
     * Get storage disk.
     */
    public function getDisk(): ?string
    {
        return $this->evaluate($this->disk) ?? config('forms.file_upload.disk', 'public');
    }

    /**
     * Set storage directory.
     */
    public function directory(string|Closure|null $directory): static
    {
        $this->directory = $directory;

        return $this;
    }

    /**
     * Get storage directory.
     */
    public function getDirectory(): ?string
    {
        return $this->evaluate($this->directory) ?? config('forms.file_upload.directory', 'uploads');
    }

    /**
     * Set file visibility.
     */
    public function visibility(string|Closure|null $visibility): static
    {
        $this->visibility = $visibility;

        return $this;
    }

    /**
     * Get file visibility.
     */
    public function getVisibility(): string
    {
        return $this->evaluate($this->visibility) ?? 'public';
    }

    /**
     * Preserve original file names.
     */
    public function preserveFilenames(bool $condition = true): static
    {
        $this->preserveFilenames = $condition;

        return $this;
    }

    /**
     * Check if file names should be preserved.
     */
    public function shouldPreserveFilenames(): bool
    {
        return $this->preserveFilenames;
    }

    /**
     * Set image crop aspect ratio.
     */
    public function imageCropAspectRatio(?string $ratio): static
    {
        $this->imageCropAspectRatio = $ratio;

        return $this;
    }

    /**
     * Get image crop aspect ratio.
     */
    public function getImageCropAspectRatio(): ?string
    {
        return $this->imageCropAspectRatio;
    }

    /**
     * Set image resize target width.
     */
    public function imageResizeTargetWidth(?int $width): static
    {
        $this->imageResizeTargetWidth = $width;

        return $this;
    }

    /**
     * Get image resize target width.
     */
    public function getImageResizeTargetWidth(): ?int
    {
        return $this->imageResizeTargetWidth;
    }

    /**
     * Set image resize target height.
     */
    public function imageResizeTargetHeight(?int $height): static
    {
        $this->imageResizeTargetHeight = $height;

        return $this;
    }

    /**
     * Get image resize target height.
     */
    public function getImageResizeTargetHeight(): ?int
    {
        return $this->imageResizeTargetHeight;
    }

    /**
     * Set maximum character count.
     */
    public function maxLength(?int $length): static
    {
        $this->maxLength = $length;

        return $this;
    }

    /**
     * Get maximum character count.
     */
    public function getMaxLength(): ?int
    {
        return $this->maxLength;
    }

    /**
     * Show character count.
     */
    public function showCharacterCount(bool $condition = true): static
    {
        $this->showCharacterCount = $condition;

        return $this;
    }

    /**
     * Check if character count should be shown.
     */
    public function shouldShowCharacterCount(): bool
    {
        return $this->showCharacterCount || $this->maxLength !== null;
    }

    /**
     * Set tippy placement.
     */
    public function tippyPlacement(string $placement): static
    {
        $this->tippyPlacement = $placement;

        return $this;
    }

    /**
     * Get tippy placement.
     */
    public function getTippyPlacement(): string
    {
        return $this->tippyPlacement;
    }

    /**
     * Set custom extensions.
     *
     * @param  array<int, string>  $extensions
     */
    public function extensions(array $extensions): static
    {
        $this->extensions = $extensions;

        return $this;
    }

    /**
     * Get custom extensions.
     *
     * @return array<int, string>
     */
    public function getExtensions(): array
    {
        return $this->extensions;
    }

    /**
     * Enable collaboration mode.
     */
    public function collaboration(bool $condition = true): static
    {
        $this->collaborationEnabled = $condition;

        return $this;
    }

    /**
     * Check if collaboration is enabled.
     */
    public function hasCollaboration(): bool
    {
        return $this->collaborationEnabled;
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
     * Set to RTL direction.
     */
    public function rtl(bool $condition = true): static
    {
        $this->direction = $condition ? 'rtl' : 'ltr';

        return $this;
    }

    /**
     * Set preset colors for color picker.
     *
     * @param  array<string, string>  $colors
     */
    public function presetColors(array $colors): static
    {
        $this->presetColors = $colors;

        return $this;
    }

    /**
     * Get preset colors.
     *
     * @return array<string, string>
     */
    public function getPresetColors(): array
    {
        return $this->presetColors;
    }

    /**
     * Set extra input attributes.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function extraInputAttributes(array $attributes): static
    {
        $this->extraInputAttributes = $attributes;

        return $this;
    }

    /**
     * Get extra input attributes.
     *
     * @return array<string, mixed>
     */
    public function getExtraInputAttributes(): array
    {
        return $this->extraInputAttributes;
    }

    /**
     * Get the configuration for JavaScript.
     *
     * @return array<string, mixed>
     */
    public function getConfig(): array
    {
        return [
            'profile' => $this->profile,
            'tools' => $this->getTools(),
            'output' => $this->outputFormat,
            'maxContentWidth' => $this->maxContentWidth,
            'floatingMenus' => $this->floatingMenusEnabled,
            'bubbleMenus' => $this->bubbleMenusEnabled,
            'floatingMenuTools' => $this->getFloatingMenuTools(),
            'placeholder' => $this->getPlaceholder(),
            'nodePlaceholders' => $this->nodePlaceholders,
            'mergeTags' => $this->mergeTags,
            'gridLayouts' => $this->getGridLayouts(),
            'maxSize' => $this->maxFileSize,
            'acceptedFileTypes' => $this->acceptedFileTypes,
            'disk' => $this->getDisk(),
            'directory' => $this->getDirectory(),
            'visibility' => $this->getVisibility(),
            'preserveFilenames' => $this->preserveFilenames,
            'imageCropAspectRatio' => $this->imageCropAspectRatio,
            'imageResizeTargetWidth' => $this->imageResizeTargetWidth,
            'imageResizeTargetHeight' => $this->imageResizeTargetHeight,
            'maxLength' => $this->maxLength,
            'showCharacterCount' => $this->shouldShowCharacterCount(),
            'tippyPlacement' => $this->tippyPlacement,
            'extensions' => $this->extensions,
            'collaboration' => $this->collaborationEnabled,
            'direction' => $this->direction,
            'presetColors' => $this->presetColors,
        ];
    }

    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.tiptap-editor';
    }
}
