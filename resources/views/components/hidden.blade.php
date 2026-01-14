@props(['field'])

<input
    type="hidden"
    name="{{ $field->getName() }}"
    id="{{ $field->getId() }}"
    value="{{ $field->getDefault() }}"
    {!! $field->getExtraAttributesString() !!}
>
