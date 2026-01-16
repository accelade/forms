@props(['submit'])

@php
    $baseClasses = 'inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium rounded-lg border shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors';

    $variantClasses = match(true) {
        $submit->isDanger() => 'bg-red-600 text-white border-transparent hover:bg-red-700 focus:ring-red-500',
        $submit->isSecondary() => 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50 focus:ring-primary-500 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600',
        default => 'bg-primary-600 text-white border-transparent hover:bg-primary-700 focus:ring-primary-500',
    };

    $classes = config('forms.styles.submit', $baseClasses . ' ' . $variantClasses);
@endphp

<button
    type="{{ $submit->getType() }}"
    @if($submit->isDisabled()) disabled @endif
    class="{{ $classes }}"
    @if($submit->hasSpinner()) data-spinner @endif
    {!! $submit->getExtraAttributesString() !!}
>
    @if($submit->hasSpinner())
        {{-- Loading spinner (hidden by default, shown during submission) --}}
        <svg
            class="hidden animate-spin -ml-1 mr-2 h-4 w-4"
            data-loading-spinner
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
        >
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    @endif

    <span>{{ $submit->getLabel() }}</span>
</button>
