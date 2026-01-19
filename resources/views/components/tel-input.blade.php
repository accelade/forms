@props([
    'field' => null,
    'name' => null,
    'id' => null,
    'label' => null,
    'placeholder' => '(555) 000-0000',
    'value' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'autofocus' => false,
    'hint' => null,
    'defaultCountry' => 'US',
    'countries' => null,
    'preferredCountries' => [],
    'showFlags' => true,
    'showDialCode' => true,
    'searchable' => true,
    'separateDialCode' => false,
    'autocomplete' => 'tel',
    'minLength' => null,
    'maxLength' => null,
    'mask' => null,
])

@php
    use Accelade\Forms\Components\TelInput;

    // If field object is passed, use it; otherwise create one from props
    if ($field === null && $name !== null) {
        $field = TelInput::make($name)
            ->id($id ?? $name)
            ->placeholder($placeholder)
            ->defaultCountry($defaultCountry)
            ->showFlags($showFlags)
            ->showDialCode($showDialCode)
            ->searchable($searchable)
            ->separateDialCode($separateDialCode)
            ->autocomplete($autocomplete);

        if ($label !== null) {
            $field->label($label);
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
        if ($mask !== null) {
            $field->mask($mask);
        }
        if (is_array($countries) && count($countries) > 0) {
            $field->countries($countries);
        }
        if (is_array($preferredCountries) && count($preferredCountries) > 0) {
            $field->preferredCountries($preferredCountries);
        } elseif (is_string($preferredCountries) && !empty($preferredCountries)) {
            $field->preferredCountries($preferredCountries);
        }
    }

    // Abort if no field available
    if ($field === null) {
        throw new \InvalidArgumentException('TelInput requires either a "field" object or a "name" prop.');
    }

    $isDisabled = $field->isDisabled();
    $countriesList = $field->getCountries();
    $defaultCountryCode = $field->getDefaultCountry();
    $preferredCountriesList = $field->getPreferredCountries();
    $showFlagsEnabled = $field->shouldShowFlags();
    $showDialCodeEnabled = $field->shouldShowDialCode();
    $isSearchable = $field->isSearchable();
    $hasSeparateDialCode = $field->hasSeparateDialCode();

    // Get default country data
    $defaultCountryData = $countriesList[$defaultCountryCode] ?? $countriesList['US'] ?? reset($countriesList);

    // Container classes - use custom without overflow-hidden to allow dropdown to show
    // Using ring-inset so the ring appears inside the border, not behind the country selector
    $containerClasses = 'relative flex rounded-lg border border-gray-300 bg-white shadow-sm focus-within:border-primary-500 focus-within:ring-2 focus-within:ring-inset focus-within:ring-primary-500/20 dark:border-gray-600 dark:bg-gray-800';

    if ($isDisabled) {
        $containerClasses .= ' bg-gray-50 dark:bg-gray-900 cursor-not-allowed';
    }

    // Input classes
    $inputClasses = config('forms.styles.input', 'block w-full px-3 py-2 text-sm bg-transparent text-gray-900 placeholder-gray-400 border-0 focus:ring-0 focus:outline-none disabled:text-gray-500 disabled:cursor-not-allowed dark:text-gray-100 dark:placeholder-gray-500 dark:disabled:text-gray-600');

    // Build preferred and regular countries for ordering
    $orderedCountries = [];
    if (!empty($preferredCountriesList)) {
        foreach ($preferredCountriesList as $code) {
            if (isset($countriesList[$code])) {
                $orderedCountries[$code] = $countriesList[$code];
            }
        }
    }
    foreach ($countriesList as $code => $data) {
        if (!isset($orderedCountries[$code])) {
            $orderedCountries[$code] = $data;
        }
    }

    $fieldId = $field->getId();
    $fieldName = $field->getName();
@endphp

<div
    class="form-field tel-input-field {{ config('forms.styles.field', 'mb-4') }}"
    data-tel-input
    data-default-country="{{ $defaultCountryCode }}"
    data-searchable="{{ $isSearchable ? 'true' : 'false' }}"
    data-separate-dial-code="{{ $hasSeparateDialCode ? 'true' : 'false' }}"
>
    @if($field->getLabel())
        <label for="{{ $fieldId }}" class="{{ config('forms.styles.label', 'block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1.5') }}">
            {{ $field->getLabel() }}
            @if($field->isRequired())
                <span class="{{ config('forms.styles.required', 'text-red-500 dark:text-red-400 ms-0.5') }}">*</span>
            @endif
        </label>
    @endif

    <div class="tel-input-wrapper relative">
        <div class="{{ $containerClasses }}">
            {{-- Country Selector --}}
            <div class="tel-country-selector relative shrink-0">
                <button
                    type="button"
                    class="tel-country-trigger flex items-center gap-1.5 h-full px-3 py-2 border-e border-gray-300 bg-gray-50 hover:bg-gray-100 focus:bg-gray-100 focus:outline-none transition-colors rounded-s-lg dark:border-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:bg-gray-600 {{ $isDisabled ? 'cursor-not-allowed opacity-50' : '' }}"
                    aria-haspopup="listbox"
                    aria-expanded="false"
                    @if($isDisabled) disabled @endif
                >
                    @if($showFlagsEnabled)
                        <span class="tel-country-flag text-lg leading-none">{{ $defaultCountryData['flag'] ?? '' }}</span>
                    @endif
                    <span class="tel-country-code text-sm font-medium text-gray-700 dark:text-gray-200">{{ $defaultCountryData['dial_code'] ?? '' }}</span>
                    <svg class="tel-country-arrow w-4 h-4 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                {{-- Country Dropdown --}}
                <div class="tel-country-dropdown hidden absolute z-50 top-full left-0 mt-1 w-72 max-h-80 bg-white border border-gray-200 rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-700">
                    @if($isSearchable)
                        <div class="p-2 bg-white border-b border-gray-100 dark:bg-gray-800 dark:border-gray-700">
                            <input
                                type="text"
                                class="tel-country-search w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 dark:placeholder-gray-400"
                                placeholder="Search countries..."
                            >
                        </div>
                    @endif

                    <ul class="tel-country-list py-1 max-h-64 overflow-y-auto" role="listbox">
                        @if(!empty($preferredCountriesList))
                            @foreach($preferredCountriesList as $code)
                                @if(isset($countriesList[$code]))
                                    @php $country = $countriesList[$code]; @endphp
                                    <li
                                        class="tel-country-option flex items-center gap-3 px-3 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 {{ $code === $defaultCountryCode ? 'bg-primary-50 dark:bg-primary-900/20' : '' }}"
                                        data-code="{{ $code }}"
                                        data-dial-code="{{ $country['dial_code'] }}"
                                        data-flag="{{ $country['flag'] }}"
                                        data-name="{{ $country['name'] }}"
                                        role="option"
                                    >
                                        @if($showFlagsEnabled)
                                            <span class="text-lg">{{ $country['flag'] }}</span>
                                        @endif
                                        <span class="flex-1 text-sm text-gray-900 dark:text-gray-100">{{ $country['name'] }}</span>
                                        @if($showDialCodeEnabled)
                                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $country['dial_code'] }}</span>
                                        @endif
                                        <svg class="tel-country-check w-4 h-4 text-primary-600 {{ $code === $defaultCountryCode ? '' : 'hidden' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </li>
                                @endif
                            @endforeach
                            <li class="border-t border-gray-200 dark:border-gray-700 my-1"></li>
                        @endif

                        @foreach($orderedCountries as $code => $country)
                            @if(!in_array($code, $preferredCountriesList))
                                <li
                                    class="tel-country-option flex items-center gap-3 px-3 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 {{ $code === $defaultCountryCode ? 'bg-primary-50 dark:bg-primary-900/20' : '' }}"
                                    data-code="{{ $code }}"
                                    data-dial-code="{{ $country['dial_code'] }}"
                                    data-flag="{{ $country['flag'] }}"
                                    data-name="{{ $country['name'] }}"
                                    role="option"
                                >
                                    @if($showFlagsEnabled)
                                        <span class="text-lg">{{ $country['flag'] }}</span>
                                    @endif
                                    <span class="flex-1 text-sm text-gray-900 dark:text-gray-100">{{ $country['name'] }}</span>
                                    @if($showDialCodeEnabled)
                                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $country['dial_code'] }}</span>
                                    @endif
                                    <svg class="tel-country-check w-4 h-4 text-primary-600 {{ $code === $defaultCountryCode ? '' : 'hidden' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </li>
                            @endif
                        @endforeach
                    </ul>

                    <div class="tel-country-empty hidden px-3 py-4 text-sm text-center text-gray-500 dark:text-gray-400">
                        No countries found
                    </div>
                </div>
            </div>

            {{-- Phone Number Input --}}
            <input
                type="tel"
                name="{{ $fieldName }}"
                id="{{ $fieldId }}"
                class="tel-input {{ $inputClasses }}"
                inputmode="{{ $field->getInputMode() }}"
                @if($field->getPlaceholder()) placeholder="{{ $field->getPlaceholder() }}" @else placeholder="(555) 000-0000" @endif
                @if($field->getDefault() !== null) value="{{ $field->getDefault() }}" @endif
                @if($field->isRequired()) required @endif
                @if($isDisabled) disabled @endif
                @if($field->isReadonly()) readonly @endif
                @if($field->hasAutofocus()) autofocus @endif
                @if($field->getMinLength() !== null) minlength="{{ $field->getMinLength() }}" @endif
                @if($field->getMaxLength() !== null) maxlength="{{ $field->getMaxLength() }}" @endif
                @if($field->getAutocomplete()) autocomplete="{{ $field->getAutocomplete() }}" @endif
                @if($field->getMask()) data-mask="{{ $field->getMask() }}" @endif
                {!! $field->getExtraAttributesString() !!}
            >
        </div>

        {{-- Hidden inputs for separate dial code storage --}}
        @if($hasSeparateDialCode)
            <input type="hidden" name="{{ $fieldName }}_country" class="tel-country-input" value="{{ $defaultCountryCode }}">
            <input type="hidden" name="{{ $fieldName }}_dial_code" class="tel-dial-code-input" value="{{ $defaultCountryData['dial_code'] ?? '' }}">
        @endif
    </div>

    @if($field->getHelperText())
        <p class="{{ config('forms.styles.hint', 'text-sm text-gray-500 dark:text-gray-400 mt-1.5') }}">
            {{ $field->getHelperText() }}
        </p>
    @endif

    @error($fieldName)
        <p class="{{ config('forms.errors.classes', 'text-sm text-red-600 dark:text-red-400 mt-1') }}">
            {{ $message }}
        </p>
    @enderror
</div>

@pushOnce('scripts')
<script>
(function() {
    function initTelInputs() {
        document.querySelectorAll('[data-tel-input]:not([data-initialized])').forEach(function(field) {
            field.dataset.initialized = 'true';

            var wrapper = field.querySelector('.tel-input-wrapper');
            var trigger = field.querySelector('.tel-country-trigger');
            var dropdown = field.querySelector('.tel-country-dropdown');
            var search = field.querySelector('.tel-country-search');
            var options = field.querySelectorAll('.tel-country-option');
            var countryList = field.querySelector('.tel-country-list');
            var emptyState = field.querySelector('.tel-country-empty');
            var flagDisplay = field.querySelector('.tel-country-flag');
            var codeDisplay = field.querySelector('.tel-country-code');
            var phoneInput = field.querySelector('.tel-input');
            var countryInput = field.querySelector('.tel-country-input');
            var dialCodeInput = field.querySelector('.tel-dial-code-input');
            var separateDialCode = field.dataset.separateDialCode === 'true';

            var isOpen = false;
            var selectedCode = field.dataset.defaultCountry || 'US';

            function open() {
                if (trigger.disabled) return;
                isOpen = true;
                dropdown.classList.remove('hidden');
                trigger.setAttribute('aria-expanded', 'true');
                field.querySelector('.tel-country-arrow').classList.add('rotate-180');
                if (search) {
                    search.value = '';
                    filterOptions('');
                    setTimeout(function() { search.focus(); }, 10);
                }
            }

            function close() {
                isOpen = false;
                dropdown.classList.add('hidden');
                trigger.setAttribute('aria-expanded', 'false');
                field.querySelector('.tel-country-arrow').classList.remove('rotate-180');
            }

            function toggle() {
                if (isOpen) {
                    close();
                } else {
                    open();
                }
            }

            function selectCountry(option) {
                var code = option.dataset.code;
                var dialCode = option.dataset.dialCode;
                var flag = option.dataset.flag;

                // Update display
                if (flagDisplay) flagDisplay.textContent = flag;
                if (codeDisplay) codeDisplay.textContent = dialCode;

                // Update hidden inputs
                if (countryInput) countryInput.value = code;
                if (dialCodeInput) dialCodeInput.value = dialCode;

                // Update selection state
                options.forEach(function(opt) {
                    var check = opt.querySelector('.tel-country-check');
                    if (opt.dataset.code === code) {
                        opt.classList.add('bg-primary-50', 'dark:bg-primary-900/20');
                        if (check) check.classList.remove('hidden');
                    } else {
                        opt.classList.remove('bg-primary-50', 'dark:bg-primary-900/20');
                        if (check) check.classList.add('hidden');
                    }
                });

                selectedCode = code;
                close();
                phoneInput.focus();
            }

            function filterOptions(query) {
                query = query.toLowerCase().trim();
                var visibleCount = 0;

                options.forEach(function(option) {
                    var name = (option.dataset.name || '').toLowerCase();
                    var code = (option.dataset.code || '').toLowerCase();
                    var dialCode = (option.dataset.dialCode || '').toLowerCase();
                    var matches = name.includes(query) || code.includes(query) || dialCode.includes(query);

                    if (matches) {
                        option.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        option.classList.add('hidden');
                    }
                });

                if (emptyState) {
                    emptyState.classList.toggle('hidden', visibleCount > 0);
                }
            }

            // Event listeners
            trigger.addEventListener('click', function(e) {
                e.preventDefault();
                toggle();
            });

            options.forEach(function(option) {
                option.addEventListener('click', function() {
                    selectCountry(option);
                });
            });

            if (search) {
                search.addEventListener('input', function() {
                    filterOptions(search.value);
                });

                search.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        close();
                        trigger.focus();
                    }
                });
            }

            // Close on outside click
            document.addEventListener('click', function(e) {
                if (isOpen && !wrapper.contains(e.target)) {
                    close();
                }
            });

            // Keyboard navigation on trigger
            trigger.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ' || e.key === 'ArrowDown') {
                    e.preventDefault();
                    open();
                }
            });
        });
    }

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initTelInputs);
    } else {
        initTelInputs();
    }

    // Re-initialize on navigation
    document.addEventListener('accelade:navigated', initTelInputs);
    document.addEventListener('accelade:updated', initTelInputs);
})();
</script>
@endPushOnce
