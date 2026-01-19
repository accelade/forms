@props([
    'field' => null,
    'name' => null,
    'id' => null,
    'label' => null,
    'placeholder' => null,
    'value' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'autofocus' => false,
    'hint' => null,
    'showToggle' => true,
    'bcrypt' => false,
    'requireUppercase' => false,
    'requireLowercase' => false,
    'requireNumbers' => false,
    'requireSymbols' => false,
    'requireConfirmation' => false,
    'confirmationField' => null,
    'autocomplete' => 'new-password',
    'showStrengthIndicator' => false,
    'generateButton' => false,
    'generatedLength' => 16,
    'minLength' => 8,
    'maxLength' => null,
])

@php
    use Accelade\Forms\Components\PasswordInput;

    // If field object is passed, use it; otherwise create one from props
    if ($field === null && $name !== null) {
        $field = PasswordInput::make($name)
            ->id($id ?? $name)
            ->showToggle($showToggle)
            ->bcrypt($bcrypt)
            ->requireUppercase($requireUppercase)
            ->requireLowercase($requireLowercase)
            ->requireNumbers($requireNumbers)
            ->requireSymbols($requireSymbols)
            ->autocomplete($autocomplete)
            ->showStrengthIndicator($showStrengthIndicator)
            ->generateButton($generateButton, $generatedLength);

        if ($label !== null) {
            $field->label($label);
        }
        if ($placeholder !== null) {
            $field->placeholder($placeholder);
        }
        if ($value !== null) {
            $field->default($value);
        }
        if ($required) {
            $field->required();
        }
        if ($disabled) {
            $field->disabled();
        }
        if ($readonly) {
            $field->readonly();
        }
        if ($autofocus) {
            $field->autofocus();
        }
        if ($hint !== null) {
            $field->hint($hint);
        }
        if ($minLength !== null) {
            $field->minLength($minLength);
        }
        if ($maxLength !== null) {
            $field->maxLength($maxLength);
        }
        if ($requireConfirmation) {
            $field->requireConfirmation(true, $confirmationField);
        }
    }

    // Abort if no field available
    if ($field === null) {
        throw new \InvalidArgumentException('PasswordInput requires either a "field" object or a "name" prop.');
    }

    $isDisabled = $field->isDisabled();
    $isRequired = $field->isRequired();
    $showToggleEnabled = $field->shouldShowToggle();
    $showStrengthEnabled = $field->shouldShowStrengthIndicator();
    $showGenerateBtn = $field->shouldShowGenerateButton();
    $genLength = $field->getGeneratedLength();
    $minLen = $field->getMinLength();
    $maxLen = $field->getMaxLength();
    $reqUpper = $field->isUppercaseRequired();
    $reqLower = $field->isLowercaseRequired();
    $reqNumbers = $field->areNumbersRequired();
    $reqSymbols = $field->areSymbolsRequired();
    $fieldId = $field->getId();
    $uniqueId = $fieldId . '-' . Str::random(6);

    // Container classes
    $containerClasses = 'relative flex rounded-lg border border-gray-300 bg-white shadow-sm focus-within:border-primary-500 focus-within:ring-2 focus-within:ring-inset focus-within:ring-primary-500/20 dark:border-gray-600 dark:bg-gray-800';

    if ($isDisabled) {
        $containerClasses .= ' bg-gray-50 dark:bg-gray-900 cursor-not-allowed';
    }

    // Input classes
    $inputClasses = config('forms.styles.input', 'block w-full px-3 py-2 text-sm bg-transparent text-gray-900 placeholder-gray-400 border-0 focus:ring-0 focus:outline-none disabled:text-gray-500 disabled:cursor-not-allowed dark:text-gray-100 dark:placeholder-gray-500 dark:disabled:text-gray-600');
@endphp

<div class="password-input-component" data-password-component="{{ $uniqueId }}">
    {{-- Label --}}
    @if($field->getLabel())
        <label for="{{ $fieldId }}" class="{{ config('forms.styles.label', 'block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1') }}">
            {{ $field->getLabel() }}
            @if($isRequired)
                <span class="{{ config('forms.styles.required', 'text-red-500 dark:text-red-400 ms-0.5') }}">*</span>
            @endif
        </label>
    @endif

    <div class="password-input-wrapper relative">
        <div class="{{ $containerClasses }}">
            {{-- Password Input --}}
            <input
                type="password"
                name="{{ $field->getName() }}"
                id="{{ $fieldId }}"
                class="{{ $inputClasses }} password-input pe-12"
                placeholder="{{ $field->getPlaceholder() }}"
                autocomplete="{{ $field->getAutocomplete() }}"
                @if($minLen) minlength="{{ $minLen }}" @endif
                @if($maxLen) maxlength="{{ $maxLen }}" @endif
                @if($isRequired) required @endif
                @if($isDisabled) disabled @endif
                @if($field->isReadonly()) readonly @endif
                @if($field->hasAutofocus()) autofocus @endif
                {{ $attributes->whereStartsWith('wire:') }}
            >

            {{-- Action Buttons --}}
            <div class="absolute inset-y-0 end-0 flex items-center pe-1 gap-1">
                {{-- Generate Button --}}
                @if($showGenerateBtn && !$isDisabled)
                    <button
                        type="button"
                        class="password-generate-btn p-1.5 text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 dark:focus:text-gray-300 transition-colors"
                        title="Generate password"
                        data-length="{{ $genLength }}"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </button>
                @endif

                {{-- Toggle Visibility Button --}}
                @if($showToggleEnabled && !$isDisabled)
                    <button
                        type="button"
                        class="password-toggle-btn p-1.5 text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 dark:focus:text-gray-300 transition-colors"
                        title="Toggle password visibility"
                    >
                        {{-- Eye icon (show password) --}}
                        <svg class="password-icon-show w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        {{-- Eye-off icon (hide password) --}}
                        <svg class="password-icon-hide w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                    </button>
                @endif
            </div>
        </div>

        {{-- Strength Indicator --}}
        @if($showStrengthEnabled)
            <div class="password-strength-container mt-2 hidden">
                <div class="flex items-center gap-2">
                    <div class="flex-1 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                        <div class="password-strength-bar h-full transition-all duration-300 rounded-full" style="width: 0%;"></div>
                    </div>
                    <span class="password-strength-label text-xs font-medium text-gray-500 dark:text-gray-400 min-w-[60px] text-end"></span>
                </div>
            </div>
        @endif

        {{-- Requirements Checklist --}}
        @if($reqUpper || $reqLower || $reqNumbers || $reqSymbols || $minLen)
            <div class="password-requirements mt-2 text-xs text-gray-500 dark:text-gray-400 hidden">
                <ul class="space-y-1">
                    @if($minLen)
                        <li class="password-req flex items-center gap-1.5" data-requirement="length" data-min="{{ $minLen }}">
                            <svg class="req-icon w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" stroke-width="2" />
                            </svg>
                            <span>At least {{ $minLen }} characters</span>
                        </li>
                    @endif
                    @if($reqUpper)
                        <li class="password-req flex items-center gap-1.5" data-requirement="uppercase">
                            <svg class="req-icon w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" stroke-width="2" />
                            </svg>
                            <span>One uppercase letter</span>
                        </li>
                    @endif
                    @if($reqLower)
                        <li class="password-req flex items-center gap-1.5" data-requirement="lowercase">
                            <svg class="req-icon w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" stroke-width="2" />
                            </svg>
                            <span>One lowercase letter</span>
                        </li>
                    @endif
                    @if($reqNumbers)
                        <li class="password-req flex items-center gap-1.5" data-requirement="number">
                            <svg class="req-icon w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" stroke-width="2" />
                            </svg>
                            <span>One number</span>
                        </li>
                    @endif
                    @if($reqSymbols)
                        <li class="password-req flex items-center gap-1.5" data-requirement="symbol">
                            <svg class="req-icon w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" stroke-width="2" />
                            </svg>
                            <span>One special character (!@#$%^&*)</span>
                        </li>
                    @endif
                </ul>
            </div>
        @endif
    </div>

    {{-- Hint Text --}}
    @if($field->getHint())
        <p class="{{ config('forms.styles.hint', 'mt-1.5 text-xs text-gray-500 dark:text-gray-400') }}">
            {{ $field->getHint() }}
        </p>
    @endif

    {{-- Confirmation Field --}}
    @if($field->isConfirmationRequired())
        <div class="mt-4">
            <label for="{{ $field->getConfirmationField() }}" class="{{ config('forms.styles.label', 'block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1') }}">
                Confirm {{ $field->getLabel() ?? 'Password' }}
                @if($isRequired)
                    <span class="{{ config('forms.styles.required', 'text-red-500 dark:text-red-400 ms-0.5') }}">*</span>
                @endif
            </label>
            <div class="{{ $containerClasses }}">
                <input
                    type="password"
                    name="{{ $field->getConfirmationField() }}"
                    id="{{ $field->getConfirmationField() }}"
                    class="{{ $inputClasses }} password-confirm-input pe-12"
                    placeholder="Confirm your password"
                    autocomplete="new-password"
                    @if($isRequired) required @endif
                    @if($isDisabled) disabled @endif
                >
                @if($showToggleEnabled && !$isDisabled)
                    <div class="absolute inset-y-0 end-0 flex items-center pe-1">
                        <button
                            type="button"
                            class="password-confirm-toggle-btn p-1.5 text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 dark:focus:text-gray-300 transition-colors"
                            title="Toggle password visibility"
                        >
                            <svg class="password-confirm-icon-show w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg class="password-confirm-icon-hide w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                @endif
            </div>
            <p class="password-match-message mt-1.5 text-xs hidden"></p>
        </div>
    @endif
</div>

<script>
(function() {
    const component = document.querySelector('[data-password-component="{{ $uniqueId }}"]');
    if (!component) return;

    const input = component.querySelector('.password-input');
    const toggleBtn = component.querySelector('.password-toggle-btn');
    const iconShow = component.querySelector('.password-icon-show');
    const iconHide = component.querySelector('.password-icon-hide');
    const generateBtn = component.querySelector('.password-generate-btn');
    const strengthContainer = component.querySelector('.password-strength-container');
    const strengthBar = component.querySelector('.password-strength-bar');
    const strengthLabel = component.querySelector('.password-strength-label');
    const requirementsContainer = component.querySelector('.password-requirements');
    const confirmInput = component.querySelector('.password-confirm-input');
    const confirmToggleBtn = component.querySelector('.password-confirm-toggle-btn');
    const confirmIconShow = component.querySelector('.password-confirm-icon-show');
    const confirmIconHide = component.querySelector('.password-confirm-icon-hide');
    const matchMessage = component.querySelector('.password-match-message');

    // Toggle password visibility
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            iconShow.classList.toggle('hidden', !isPassword);
            iconHide.classList.toggle('hidden', isPassword);
        });
    }

    // Toggle confirmation password visibility
    if (confirmToggleBtn && confirmInput) {
        confirmToggleBtn.addEventListener('click', function() {
            const isPassword = confirmInput.type === 'password';
            confirmInput.type = isPassword ? 'text' : 'password';
            confirmIconShow.classList.toggle('hidden', !isPassword);
            confirmIconHide.classList.toggle('hidden', isPassword);
        });
    }

    // Generate password
    if (generateBtn) {
        generateBtn.addEventListener('click', function() {
            const length = parseInt(this.dataset.length) || 16;
            const charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-=[]{}|;:,.<>?';
            let password = '';

            // Ensure at least one of each required type
            password += 'abcdefghijklmnopqrstuvwxyz'[Math.floor(Math.random() * 26)];
            password += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'[Math.floor(Math.random() * 26)];
            password += '0123456789'[Math.floor(Math.random() * 10)];
            password += '!@#$%^&*()_+-=[]{}|;:,.<>?'[Math.floor(Math.random() * 26)];

            // Fill the rest
            for (let i = password.length; i < length; i++) {
                password += charset[Math.floor(Math.random() * charset.length)];
            }

            // Shuffle
            password = password.split('').sort(() => Math.random() - 0.5).join('');

            input.value = password;
            input.type = 'text';
            if (iconShow) iconShow.classList.add('hidden');
            if (iconHide) iconHide.classList.remove('hidden');

            // Trigger input event for validation
            input.dispatchEvent(new Event('input', { bubbles: true }));
        });
    }

    // Calculate strength
    function calculateStrength(password) {
        let strength = 0;
        const length = password.length;

        strength += Math.min(30, length * 2);

        if (/[a-z]/.test(password)) strength += 10;
        if (/[A-Z]/.test(password)) strength += 10;
        if (/[0-9]/.test(password)) strength += 10;
        if (/[!@#$%^&*()_+\-=\[\]{};\'":\\|,.<>\/?`~]/.test(password)) strength += 10;

        if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 10;
        if (/[0-9]/.test(password) && /[!@#$%^&*()_+\-=\[\]{};\'":\\|,.<>\/?`~]/.test(password)) strength += 10;

        if (/^[a-zA-Z]+$/.test(password)) strength -= 10;
        if (/^[0-9]+$/.test(password)) strength -= 20;
        if (/(.)\1{2,}/.test(password)) strength -= 10;

        return Math.max(0, Math.min(100, strength));
    }

    function getStrengthColor(strength) {
        if (strength >= 80) return '#22c55e';
        if (strength >= 60) return '#84cc16';
        if (strength >= 40) return '#eab308';
        if (strength >= 20) return '#f97316';
        return '#ef4444';
    }

    function getStrengthLabel(strength) {
        if (strength >= 80) return 'Strong';
        if (strength >= 60) return 'Good';
        if (strength >= 40) return 'Fair';
        if (strength >= 20) return 'Weak';
        return 'Very Weak';
    }

    // Check requirements
    function checkRequirements(password) {
        const requirements = component.querySelectorAll('.password-req');
        requirements.forEach(req => {
            const type = req.dataset.requirement;
            let passed = false;

            switch (type) {
                case 'length':
                    const min = parseInt(req.dataset.min) || 8;
                    passed = password.length >= min;
                    break;
                case 'uppercase':
                    passed = /[A-Z]/.test(password);
                    break;
                case 'lowercase':
                    passed = /[a-z]/.test(password);
                    break;
                case 'number':
                    passed = /[0-9]/.test(password);
                    break;
                case 'symbol':
                    passed = /[!@#$%^&*()_+\-=\[\]{};\'":\\|,.<>\/?`~]/.test(password);
                    break;
            }

            const icon = req.querySelector('.req-icon');
            if (passed) {
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />';
                icon.classList.remove('text-gray-400');
                icon.classList.add('text-green-500');
                req.classList.add('text-green-600', 'dark:text-green-400');
                req.classList.remove('text-gray-500', 'dark:text-gray-400');
            } else {
                icon.innerHTML = '<circle cx="12" cy="12" r="10" stroke-width="2" />';
                icon.classList.add('text-gray-400');
                icon.classList.remove('text-green-500');
                req.classList.remove('text-green-600', 'dark:text-green-400');
                req.classList.add('text-gray-500', 'dark:text-gray-400');
            }
        });
    }

    // Check password match
    function checkMatch() {
        if (!confirmInput || !matchMessage) return;

        const password = input.value;
        const confirm = confirmInput.value;

        if (confirm.length === 0) {
            matchMessage.classList.add('hidden');
            return;
        }

        matchMessage.classList.remove('hidden');

        if (password === confirm) {
            matchMessage.textContent = 'Passwords match';
            matchMessage.classList.remove('text-red-500', 'dark:text-red-400');
            matchMessage.classList.add('text-green-500', 'dark:text-green-400');
        } else {
            matchMessage.textContent = 'Passwords do not match';
            matchMessage.classList.remove('text-green-500', 'dark:text-green-400');
            matchMessage.classList.add('text-red-500', 'dark:text-red-400');
        }
    }

    // Input event handler
    input.addEventListener('input', function() {
        const password = this.value;

        // Show/hide requirements
        if (requirementsContainer) {
            if (password.length > 0) {
                requirementsContainer.classList.remove('hidden');
            } else {
                requirementsContainer.classList.add('hidden');
            }
            checkRequirements(password);
        }

        // Update strength indicator
        if (strengthContainer && strengthBar && strengthLabel) {
            if (password.length > 0) {
                strengthContainer.classList.remove('hidden');
                const strength = calculateStrength(password);
                strengthBar.style.width = strength + '%';
                strengthBar.style.backgroundColor = getStrengthColor(strength);
                strengthLabel.textContent = getStrengthLabel(strength);
                strengthLabel.style.color = getStrengthColor(strength);
            } else {
                strengthContainer.classList.add('hidden');
            }
        }

        checkMatch();
    });

    if (confirmInput) {
        confirmInput.addEventListener('input', checkMatch);
    }
})();
</script>
