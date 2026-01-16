@props(['form'])

<form
    @if($form->getAction()) action="{{ $form->getAction() }}" @endif
    method="{{ $form->getFormMethod() }}"
    @if($form->getId()) id="{{ $form->getId() }}" @endif
    @if($form->getEnctype()) enctype="{{ $form->getEnctype() }}" @endif
    class="space-y-6"
    {{-- Splade-compatible submission behavior attributes --}}
    @if($form->shouldStay()) data-stay @endif
    @if($form->shouldPreserveScroll()) data-preserve-scroll @endif
    @if($form->shouldResetOnSuccess()) data-reset-on-success @endif
    @if($form->shouldRestoreOnSuccess()) data-restore-on-success @endif
    {{-- Confirmation dialog attributes --}}
    @if($form->requiresConfirmation()) data-confirm @endif
    @if($form->getConfirmText()) data-confirm-text="{{ $form->getConfirmText() }}" @endif
    @if($form->getConfirmButton()) data-confirm-button="{{ $form->getConfirmButton() }}" @endif
    @if($form->getCancelButton()) data-cancel-button="{{ $form->getCancelButton() }}" @endif
    @if($form->isConfirmDanger()) data-confirm-danger @endif
    {{-- Password confirmation attributes --}}
    @if($form->requiresPassword()) data-require-password @endif
    @if($form->requiresPasswordOnce()) data-require-password-once @endif
    {{-- Auto-submit attributes --}}
    @if($form->submitsOnChange()) data-submit-on-change @endif
    @if($form->getDebounce()) data-debounce="{{ $form->getDebounce() }}" @endif
    {{-- Background submission and blob attributes --}}
    @if($form->submitsInBackground()) data-background @endif
    @if($form->handlesBlob()) data-blob @endif
    {{-- Model handling --}}
    @if($form->isUnguarded()) data-unguarded @endif
    {{-- Extra attributes --}}
    @foreach($form->getExtraAttributes() as $key => $value)
        {{ $key }}="{{ $value }}"
    @endforeach
>
    @csrf

    @if($form->needsMethodSpoofing())
        @method($form->getMethod())
    @endif

    @foreach($form->getVisibleSchema() as $field)
        {!! $field->render() !!}
    @endforeach

    @if($form->shouldShowSubmit())
        <div class="flex justify-end">
            <button
                type="submit"
                class="inline-flex justify-center rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600"
            >
                {{ $form->getSubmitLabel() }}
            </button>
        </div>
    @endif
</form>
