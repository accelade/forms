<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Field;
use Closure;

/**
 * Pin Input Component
 *
 * A pin/OTP input field with individual character inputs.
 */
class PinInput extends Field
{
    protected int|Closure $length = 4;

    protected bool|Closure $mask = false;

    protected bool|Closure $otp = false;

    protected string|Closure $type = 'numeric';

    protected string|Closure $align = 'left';

    /**
     * Set the number of input fields.
     */
    public function length(int|Closure $length): static
    {
        $this->length = $length;

        return $this;
    }

    /**
     * Get the number of input fields.
     */
    public function getLength(): int
    {
        return $this->evaluate($this->length);
    }

    /**
     * Enable masking of input (for passwords).
     */
    public function mask(bool|Closure $mask = true): static
    {
        $this->mask = $mask;

        return $this;
    }

    /**
     * Get mask state.
     */
    public function getMask(): bool
    {
        return $this->evaluate($this->mask);
    }

    /**
     * Mark this as a one-time password input.
     */
    public function otp(bool|Closure $otp = true): static
    {
        $this->otp = $otp;

        return $this;
    }

    /**
     * Get OTP state.
     */
    public function getOtp(): bool
    {
        return $this->evaluate($this->otp);
    }

    /**
     * Set input type (numeric, alpha, alphanumeric).
     */
    public function type(string|Closure $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get input type.
     */
    public function getPinType(): string
    {
        return $this->evaluate($this->type);
    }

    /**
     * Set alignment (left, center, right).
     */
    public function align(string|Closure $align): static
    {
        $this->align = $align;

        return $this;
    }

    /**
     * Get alignment.
     */
    public function getAlign(): string
    {
        return $this->evaluate($this->align);
    }

    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.pin-input';
    }
}
