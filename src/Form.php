<?php

declare(strict_types=1);

namespace Accelade\Forms;

use Closure;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Traits\Conditionable;

/**
 * Form container that wraps form fields.
 */
class Form implements Htmlable
{
    use Conditionable;

    protected ?string $action = null;

    protected string $method = 'POST';

    protected ?string $id = null;

    protected array $schema = [];

    protected bool $hasFiles = false;

    protected mixed $model = null;

    protected array $extraAttributes = [];

    protected ?string $submitLabel = null;

    protected bool $showSubmit = true;

    protected ?Closure $onSubmit = null;

    /**
     * Create a new form instance.
     */
    public static function make(?string $id = null): static
    {
        $static = new static;
        $static->id = $id;
        $static->method = config('forms.default_method', 'POST');

        return $static;
    }

    /**
     * Set the form action URL.
     */
    public function action(string $action): static
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get the form action.
     */
    public function getAction(): ?string
    {
        return $this->action;
    }

    /**
     * Set the HTTP method.
     */
    public function method(string $method): static
    {
        $this->method = strtoupper($method);

        return $this;
    }

    /**
     * Get the HTTP method.
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Check if method requires spoofing.
     */
    public function needsMethodSpoofing(): bool
    {
        return ! in_array($this->method, ['GET', 'POST']);
    }

    /**
     * Get the actual form method (GET or POST).
     */
    public function getFormMethod(): string
    {
        return $this->needsMethodSpoofing() ? 'POST' : $this->method;
    }

    /**
     * Set the form ID.
     */
    public function id(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the form ID.
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Set the form fields schema.
     *
     * @param  array<Field>  $schema
     */
    public function schema(array $schema): static
    {
        $this->schema = $schema;

        // Check if any field requires file upload
        foreach ($schema as $field) {
            if (method_exists($field, 'hasFileUpload') && $field->hasFileUpload()) {
                $this->hasFiles = true;
                break;
            }
        }

        return $this;
    }

    /**
     * Get the form schema.
     */
    public function getSchema(): array
    {
        return $this->schema;
    }

    /**
     * Get visible fields (filter out hidden ones).
     */
    public function getVisibleSchema(): array
    {
        return array_values(array_filter($this->schema, function ($field): bool {
            if (method_exists($field, 'isHidden')) {
                return ! $field->isHidden();
            }

            return true;
        }));
    }

    /**
     * Bind a model to the form for default values.
     */
    public function model(mixed $model): static
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get the bound model.
     */
    public function getModel(): mixed
    {
        return $this->model;
    }

    /**
     * Check if form has a bound model.
     */
    public function hasModel(): bool
    {
        return $this->model !== null;
    }

    /**
     * Enable file uploads (multipart/form-data).
     */
    public function withFiles(bool $hasFiles = true): static
    {
        $this->hasFiles = $hasFiles;

        return $this;
    }

    /**
     * Check if form supports file uploads.
     */
    public function hasFiles(): bool
    {
        return $this->hasFiles;
    }

    /**
     * Get the form enctype.
     */
    public function getEnctype(): ?string
    {
        return $this->hasFiles ? 'multipart/form-data' : null;
    }

    /**
     * Set the submit button label.
     */
    public function submitLabel(string $label): static
    {
        $this->submitLabel = $label;

        return $this;
    }

    /**
     * Get the submit button label.
     */
    public function getSubmitLabel(): string
    {
        return $this->submitLabel ?? __('Submit');
    }

    /**
     * Hide the submit button.
     */
    public function withoutSubmit(): static
    {
        $this->showSubmit = false;

        return $this;
    }

    /**
     * Show the submit button.
     */
    public function withSubmit(bool $show = true): static
    {
        $this->showSubmit = $show;

        return $this;
    }

    /**
     * Check if submit button should be shown.
     */
    public function shouldShowSubmit(): bool
    {
        return $this->showSubmit;
    }

    /**
     * Set callback for form submission.
     */
    public function onSubmit(Closure $callback): static
    {
        $this->onSubmit = $callback;

        return $this;
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
     * Get all validation rules from fields.
     */
    public function getValidationRules(): array
    {
        $rules = [];

        foreach ($this->schema as $field) {
            if (method_exists($field, 'getRules') && method_exists($field, 'getName')) {
                $fieldRules = $field->getRules();
                if (! empty($fieldRules)) {
                    $rules[$field->getName()] = $fieldRules;
                }
            }
        }

        return $rules;
    }

    /**
     * Get the view name for this form.
     */
    protected function getView(): string
    {
        return 'forms::components.form';
    }

    /**
     * Get the view data for rendering.
     */
    protected function getViewData(): array
    {
        return [
            'form' => $this,
        ];
    }

    /**
     * Render the form to HTML.
     */
    public function render(): string
    {
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
     * Convert form to string.
     */
    public function __toString(): string
    {
        return $this->render();
    }
}
