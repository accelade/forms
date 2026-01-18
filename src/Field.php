<?php

declare(strict_types=1);

namespace Accelade\Forms;

use Closure;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Conditionable;

/**
 * Base field class for form components.
 */
abstract class Field implements Htmlable
{
    use Conditionable;

    protected string $name = '';

    protected ?string $id = null;

    protected string|Closure|null $label = null;

    protected string|Closure|null $placeholder = null;

    protected string|Closure|null $hint = null;

    protected mixed $default = null;

    protected bool $isRequired = false;

    protected bool $isDisabled = false;

    protected bool $isReadonly = false;

    protected bool $isHidden = false;

    protected bool $isAutofocus = false;

    protected array $rules = [];

    protected array $extraAttributes = [];

    protected ?string $prefix = null;

    protected ?string $suffix = null;

    protected ?string $helperText = null;

    /**
     * Create a new field instance.
     */
    public static function make(string $name): static
    {
        $static = new static;
        $static->name = $name;
        $static->id = Str::slug($name);
        $static->setUp();

        return $static;
    }

    /**
     * Set up the field. Override in subclasses.
     */
    protected function setUp(): void
    {
        // Override in subclasses
    }

    /**
     * Set the field name.
     */
    public function name(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the field name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the wire:model attribute name (supports dot notation).
     */
    public function getWireModelName(): string
    {
        return $this->name;
    }

    /**
     * Get the state path for this field.
     */
    public function getStatePath(): string
    {
        return $this->name;
    }

    /**
     * Set the field ID.
     */
    public function id(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the field ID.
     */
    public function getId(): string
    {
        return $this->id ?? Str::slug($this->name);
    }

    /**
     * Set the label.
     */
    public function label(string|Closure $label): static
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get the label.
     */
    public function getLabel(): ?string
    {
        if ($this->label === null) {
            return Str::headline($this->name);
        }

        return $this->evaluate($this->label);
    }

    /**
     * Set the placeholder.
     */
    public function placeholder(string|Closure $placeholder): static
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    /**
     * Get the placeholder.
     */
    public function getPlaceholder(): ?string
    {
        return $this->evaluate($this->placeholder);
    }

    /**
     * Set the hint text.
     */
    public function hint(string|Closure $hint): static
    {
        $this->hint = $hint;

        return $this;
    }

    /**
     * Get the hint.
     */
    public function getHint(): ?string
    {
        return $this->evaluate($this->hint);
    }

    /**
     * Set the helper text (alias for hint).
     */
    public function helperText(string $text): static
    {
        $this->helperText = $text;

        return $this;
    }

    /**
     * Get the helper text.
     */
    public function getHelperText(): ?string
    {
        return $this->helperText ?? $this->getHint();
    }

    /**
     * Set the default value.
     */
    public function default(mixed $value): static
    {
        $this->default = $value;

        return $this;
    }

    /**
     * Get the default value.
     */
    public function getDefault(): mixed
    {
        return $this->evaluate($this->default);
    }

    /**
     * Get the default value with record context.
     * This is used when the default is a closure that needs the record.
     */
    public function getDefaultWithRecord(mixed $record = null): mixed
    {
        if ($this->default instanceof Closure) {
            return ($this->default)($record);
        }

        return $this->default;
    }

    /**
     * Get the current value for this field.
     *
     * Returns old input if available, otherwise the default value.
     */
    public function getValue(): mixed
    {
        return old($this->name, $this->getDefault());
    }

    /**
     * Mark field as required.
     */
    public function required(bool $condition = true): static
    {
        $this->isRequired = $condition;

        return $this;
    }

    /**
     * Check if required.
     */
    public function isRequired(): bool
    {
        return $this->isRequired;
    }

    /**
     * Mark field as disabled.
     */
    public function disabled(bool $condition = true): static
    {
        $this->isDisabled = $condition;

        return $this;
    }

    /**
     * Check if disabled.
     */
    public function isDisabled(): bool
    {
        return $this->isDisabled;
    }

    /**
     * Mark field as readonly.
     */
    public function readonly(bool $condition = true): static
    {
        $this->isReadonly = $condition;

        return $this;
    }

    /**
     * Check if readonly.
     */
    public function isReadonly(): bool
    {
        return $this->isReadonly;
    }

    /**
     * Mark field as hidden.
     */
    public function hidden(bool $condition = true): static
    {
        $this->isHidden = $condition;

        return $this;
    }

    /**
     * Check if hidden.
     */
    public function isHidden(): bool
    {
        return $this->isHidden;
    }

    /**
     * Set visible state.
     */
    public function visible(bool $condition = true): static
    {
        $this->isHidden = ! $condition;

        return $this;
    }

    /**
     * Mark field for autofocus.
     */
    public function autofocus(bool $condition = true): static
    {
        $this->isAutofocus = $condition;

        return $this;
    }

    /**
     * Check if autofocus.
     */
    public function hasAutofocus(): bool
    {
        return $this->isAutofocus;
    }

    /**
     * Set validation rules.
     */
    public function rules(array|string $rules): static
    {
        $this->rules = is_string($rules) ? explode('|', $rules) : $rules;

        return $this;
    }

    /**
     * Get validation rules.
     */
    public function getRules(): array
    {
        $rules = $this->rules;

        if ($this->isRequired && ! in_array('required', $rules, true)) {
            array_unshift($rules, 'required');
        }

        return $rules;
    }

    /**
     * Set a prefix for the field.
     */
    public function prefix(string $prefix): static
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * Alias for prefix (Splade compatibility).
     */
    public function prepend(string $text): static
    {
        return $this->prefix($text);
    }

    /**
     * Get the prefix.
     */
    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    /**
     * Set a suffix for the field.
     */
    public function suffix(string $suffix): static
    {
        $this->suffix = $suffix;

        return $this;
    }

    /**
     * Alias for suffix (Splade compatibility).
     */
    public function append(string $text): static
    {
        return $this->suffix($text);
    }

    /**
     * Get the suffix.
     */
    public function getSuffix(): ?string
    {
        return $this->suffix;
    }

    /**
     * Add extra HTML attributes.
     */
    public function extraAttributes(array $attributes): static
    {
        $this->extraAttributes = array_merge($this->extraAttributes, $attributes);

        return $this;
    }

    /**
     * Get extra attributes.
     */
    public function getExtraAttributes(): array
    {
        return $this->extraAttributes;
    }

    /**
     * Get extra attributes as HTML string.
     */
    public function getExtraAttributesString(): string
    {
        $html = [];

        foreach ($this->extraAttributes as $key => $value) {
            if (is_bool($value)) {
                if ($value) {
                    $html[] = e($key);
                }
            } else {
                $html[] = e($key).'="'.e($value).'"';
            }
        }

        return implode(' ', $html);
    }

    /**
     * Evaluate a value that may be a Closure.
     */
    protected function evaluate(mixed $value): mixed
    {
        if ($value instanceof Closure) {
            try {
                // Check if the closure requires parameters
                $reflection = new \ReflectionFunction($value);
                $requiredParams = $reflection->getNumberOfRequiredParameters();

                // If the closure requires parameters, return null (it needs record context)
                if ($requiredParams > 0) {
                    return null;
                }

                return $value();
            } catch (\Throwable) {
                // If reflection or invocation fails, return null
                return null;
            }
        }

        return $value;
    }

    /**
     * Get the view name for this field.
     */
    abstract protected function getView(): string;

    /**
     * Get the view data for rendering.
     */
    protected function getViewData(): array
    {
        return [
            'field' => $this,
        ];
    }

    /**
     * Render the field to HTML.
     */
    public function render(): string
    {
        if ($this->isHidden()) {
            return '';
        }

        return view($this->getView(), $this->getViewData())->render();
    }

    /**
     * Get content as a string of HTML.
     */
    public function toHtml(): string
    {
        return $this->render();
    }

    /**
     * Convert field to string.
     */
    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * Serialize to array for JSON output.
     */
    public function toArray(): array
    {
        return $this->toArrayWithRecord(null);
    }

    /**
     * Serialize to array for JSON output with record context.
     */
    public function toArrayWithRecord(mixed $record = null): array
    {
        return [
            'type' => class_basename(static::class),
            'name' => $this->name,
            'id' => $this->getId(),
            'label' => $this->getLabel(),
            'placeholder' => $this->getPlaceholder(),
            'hint' => $this->getHint(),
            'default' => $this->getDefaultWithRecord($record),
            'required' => $this->isRequired,
            'disabled' => $this->isDisabled,
            'readonly' => $this->isReadonly,
            'hidden' => $this->isHidden,
            'rules' => $this->getRules(),
        ];
    }
}
