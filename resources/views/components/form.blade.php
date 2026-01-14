@props(['form'])

<form
    @if($form->getAction()) action="{{ $form->getAction() }}" @endif
    method="{{ $form->getFormMethod() }}"
    @if($form->getId()) id="{{ $form->getId() }}" @endif
    @if($form->getEnctype()) enctype="{{ $form->getEnctype() }}" @endif
    class="space-y-6"
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
