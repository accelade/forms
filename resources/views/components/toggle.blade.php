@props(['field'])

@php
    $toggleId = $field->getId();
@endphp

<div class="form-field">
    <div class="flex items-{{ $field->isInline() ? 'center' : 'start' }} gap-3">
        @if(!$field->isInline() && $field->getLabel())
            <label for="{{ $toggleId }}" class="{{ config('forms.styles.label', 'block text-sm font-medium text-gray-700 dark:text-gray-300') }}">
                {{ $field->getLabel() }}
                @if($field->isRequired())
                    <span class="text-red-500">*</span>
                @endif
            </label>
        @endif

        <x-accelade::data :data="['enabled' => (bool) $field->getDefault()]">
            <input
                type="hidden"
                name="{{ $field->getName() }}"
                a-bind:value="enabled ? '{{ $field->getOnValue() }}' : '{{ $field->getOffValue() }}'"
            >

            <button
                type="button"
                id="{{ $toggleId }}"
                role="switch"
                a-bind:aria-checked="enabled.toString()"
                @click="enabled = !enabled"
                @if($field->isDisabled()) disabled @endif
                a-class="enabled ? 'bg-{{ $field->getOnColor() }}-600' : 'bg-{{ $field->getOffColor() }}-200 dark:bg-{{ $field->getOffColor() }}-700'"
                class="{{ config('forms.styles.toggle', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2') }} {{ $field->isDisabled() ? 'opacity-50 cursor-not-allowed' : '' }}"
            >
                <span
                    aria-hidden="true"
                    a-class="enabled ? 'translate-x-5' : 'translate-x-0'"
                    class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                >
                    @if($field->getOnIcon() || $field->getOffIcon())
                        <span
                            a-class="enabled ? 'opacity-0 duration-100 ease-out' : 'opacity-100 duration-200 ease-in'"
                            class="absolute inset-0 flex h-full w-full items-center justify-center transition-opacity"
                        >
                            @if($field->getOffIcon())
                                {!! $field->getOffIcon() !!}
                            @endif
                        </span>
                        <span
                            a-class="enabled ? 'opacity-100 duration-200 ease-in' : 'opacity-0 duration-100 ease-out'"
                            class="absolute inset-0 flex h-full w-full items-center justify-center transition-opacity"
                        >
                            @if($field->getOnIcon())
                                {!! $field->getOnIcon() !!}
                            @endif
                        </span>
                    @endif
                </span>
            </button>
        </x-accelade::data>

        @if($field->isInline() && $field->getLabel())
            <label for="{{ $toggleId }}" class="text-sm text-gray-700 dark:text-gray-300">
                {{ $field->getLabel() }}
                @if($field->isRequired())
                    <span class="text-red-500">*</span>
                @endif
            </label>
        @endif
    </div>

    @if($field->getHelperText())
        <p class="{{ config('forms.styles.hint', 'text-sm text-gray-500 dark:text-gray-400 mt-1') }}">
            {{ $field->getHelperText() }}
        </p>
    @endif

    @error($field->getName())
        <p class="{{ config('forms.errors.classes', 'text-sm text-red-600 dark:text-red-400 mt-1') }}">
            {{ $message }}
        </p>
    @enderror
</div>
