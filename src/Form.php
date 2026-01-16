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

    // Splade-compatible submission behavior options
    protected bool $stay = false;

    protected bool $preserveScroll = false;

    protected bool $resetOnSuccess = false;

    protected bool $restoreOnSuccess = false;

    // Confirmation dialog options
    protected bool $confirm = false;

    protected ?string $confirmText = null;

    protected ?string $confirmButton = null;

    protected ?string $cancelButton = null;

    protected bool $confirmDanger = false;

    // Password confirmation options
    protected bool $requirePassword = false;

    protected bool $requirePasswordOnce = false;

    // Auto-submit options
    protected bool $submitOnChange = false;

    protected ?int $debounce = null;

    // Background submission (keeps inputs enabled during submit)
    protected bool $background = false;

    // Blob download support
    protected bool $blob = false;

    // Unguarded model attribute access
    protected bool|array $unguarded = false;

    // Static default unguarded setting
    protected static bool|array|null $defaultUnguarded = null;

    // Static guard condition closure
    protected static ?Closure $guardWhenCallback = null;

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
     * Prevent navigation after successful submission (stay on same page).
     */
    public function stay(bool $stay = true): static
    {
        $this->stay = $stay;

        return $this;
    }

    /**
     * Check if form should stay on page after success.
     */
    public function shouldStay(): bool
    {
        return $this->stay;
    }

    /**
     * Preserve scroll position after form submission.
     */
    public function preserveScroll(bool $preserve = true): static
    {
        $this->preserveScroll = $preserve;

        return $this;
    }

    /**
     * Check if scroll position should be preserved.
     */
    public function shouldPreserveScroll(): bool
    {
        return $this->preserveScroll;
    }

    /**
     * Reset form data after successful submission.
     */
    public function resetOnSuccess(bool $reset = true): static
    {
        $this->resetOnSuccess = $reset;

        return $this;
    }

    /**
     * Check if form should reset on success.
     */
    public function shouldResetOnSuccess(): bool
    {
        return $this->resetOnSuccess;
    }

    /**
     * Restore form to default values after successful submission.
     */
    public function restoreOnSuccess(bool $restore = true): static
    {
        $this->restoreOnSuccess = $restore;

        return $this;
    }

    /**
     * Check if form should restore defaults on success.
     */
    public function shouldRestoreOnSuccess(): bool
    {
        return $this->restoreOnSuccess;
    }

    /**
     * Show confirmation dialog before submitting.
     */
    public function confirm(bool|string $confirm = true): static
    {
        if (is_string($confirm)) {
            $this->confirm = true;
            $this->confirmText = $confirm;
        } else {
            $this->confirm = $confirm;
        }

        return $this;
    }

    /**
     * Check if confirmation is required.
     */
    public function requiresConfirmation(): bool
    {
        return $this->confirm;
    }

    /**
     * Set the confirmation dialog text.
     */
    public function confirmText(string $text): static
    {
        $this->confirm = true;
        $this->confirmText = $text;

        return $this;
    }

    /**
     * Get the confirmation text.
     */
    public function getConfirmText(): ?string
    {
        return $this->confirmText;
    }

    /**
     * Set the confirmation button text.
     */
    public function confirmButton(string $text): static
    {
        $this->confirmButton = $text;

        return $this;
    }

    /**
     * Get the confirmation button text.
     */
    public function getConfirmButton(): ?string
    {
        return $this->confirmButton;
    }

    /**
     * Set the cancel button text.
     */
    public function cancelButton(string $text): static
    {
        $this->cancelButton = $text;

        return $this;
    }

    /**
     * Get the cancel button text.
     */
    public function getCancelButton(): ?string
    {
        return $this->cancelButton;
    }

    /**
     * Style the confirmation as a danger action.
     */
    public function confirmDanger(bool $danger = true): static
    {
        $this->confirm = true;
        $this->confirmDanger = $danger;

        return $this;
    }

    /**
     * Check if confirmation is styled as danger.
     */
    public function isConfirmDanger(): bool
    {
        return $this->confirmDanger;
    }

    /**
     * Require password confirmation before submitting.
     */
    public function requirePassword(bool $require = true): static
    {
        $this->requirePassword = $require;

        return $this;
    }

    /**
     * Check if password is required.
     */
    public function requiresPassword(): bool
    {
        return $this->requirePassword;
    }

    /**
     * Require password confirmation once per session.
     */
    public function requirePasswordOnce(bool $require = true): static
    {
        $this->requirePasswordOnce = $require;

        return $this;
    }

    /**
     * Check if password is required once per session.
     */
    public function requiresPasswordOnce(): bool
    {
        return $this->requirePasswordOnce;
    }

    /**
     * Submit form automatically when any field changes.
     */
    public function submitOnChange(bool $submit = true): static
    {
        $this->submitOnChange = $submit;

        return $this;
    }

    /**
     * Check if form submits on change.
     */
    public function submitsOnChange(): bool
    {
        return $this->submitOnChange;
    }

    /**
     * Set debounce time in milliseconds for auto-submit.
     */
    public function debounce(int $milliseconds): static
    {
        $this->debounce = $milliseconds;

        return $this;
    }

    /**
     * Get the debounce time.
     */
    public function getDebounce(): ?int
    {
        return $this->debounce;
    }

    /**
     * Submit in background (keep inputs enabled during submission).
     */
    public function background(bool $background = true): static
    {
        $this->background = $background;

        return $this;
    }

    /**
     * Check if form submits in background.
     */
    public function submitsInBackground(): bool
    {
        return $this->background;
    }

    /**
     * Enable blob/file download handling for form response.
     */
    public function blob(bool $blob = true): static
    {
        $this->blob = $blob;

        return $this;
    }

    /**
     * Check if form handles blob responses.
     */
    public function handlesBlob(): bool
    {
        return $this->blob;
    }

    /**
     * Allow unguarded model attribute access.
     * Can pass true for all fields, or an array of specific field names.
     *
     * @param  bool|array<string>  $unguarded
     */
    public function unguarded(bool|array $unguarded = true): static
    {
        $this->unguarded = $unguarded;

        return $this;
    }

    /**
     * Check if model attributes are unguarded (either globally or selectively).
     */
    public function isUnguarded(): bool
    {
        // Check instance-level unguarding first
        if ($this->unguarded === true || is_array($this->unguarded)) {
            return true;
        }

        // Check static default
        if (static::$defaultUnguarded === true || is_array(static::$defaultUnguarded)) {
            return true;
        }

        return false;
    }

    /**
     * Check if a specific field is unguarded.
     * Instance-level settings override static defaults.
     */
    public function isFieldUnguarded(string $fieldName): bool
    {
        // If instance-level unguarded is set, use it exclusively
        if ($this->unguarded !== false) {
            if ($this->unguarded === true) {
                return true;
            }

            return is_array($this->unguarded) && in_array($fieldName, $this->unguarded, true);
        }

        // Fall back to static default only if no instance-level setting
        if (static::$defaultUnguarded === true) {
            return true;
        }

        if (is_array(static::$defaultUnguarded)) {
            return in_array($fieldName, static::$defaultUnguarded, true);
        }

        return false;
    }

    /**
     * Get the unguarded fields (empty array if all fields are unguarded or none).
     *
     * @return array<string>
     */
    public function getUnguardedFields(): array
    {
        if (is_array($this->unguarded)) {
            return $this->unguarded;
        }

        if (is_array(static::$defaultUnguarded)) {
            return static::$defaultUnguarded;
        }

        return [];
    }

    /**
     * Set default unguarded fields globally.
     *
     * @param  bool|array<string>  $unguarded
     */
    public static function defaultUnguarded(bool|array $unguarded = true): void
    {
        static::$defaultUnguarded = $unguarded;
    }

    /**
     * Get the default unguarded setting.
     */
    public static function getDefaultUnguarded(): bool|array|null
    {
        return static::$defaultUnguarded;
    }

    /**
     * Set a callback to determine when the model should be guarded.
     * The callback receives the model and should return true if the model should be guarded.
     */
    public static function guardWhen(?Closure $callback): void
    {
        static::$guardWhenCallback = $callback;
    }

    /**
     * Get the guard condition callback.
     */
    public static function getGuardWhenCallback(): ?Closure
    {
        return static::$guardWhenCallback;
    }

    /**
     * Check if the bound model should be guarded based on the guardWhen callback.
     */
    public function shouldGuardModel(): bool
    {
        if (static::$guardWhenCallback === null) {
            return ! $this->isUnguarded();
        }

        if ($this->model === null) {
            return true;
        }

        return (bool) call_user_func(static::$guardWhenCallback, $this->model);
    }

    /**
     * Reset static configuration (useful for testing).
     */
    public static function flushState(): void
    {
        static::$defaultUnguarded = null;
        static::$guardWhenCallback = null;
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
