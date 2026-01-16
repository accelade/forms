@props(['framework' => 'vanilla', 'prefix' => 'a', 'documentation' => null, 'hasDemo' => true])

@php
    app('accelade')->setFramework($framework);
@endphp

<x-accelade::layouts.docs :framework="$framework" section="number-field" :documentation="$documentation" :hasDemo="$hasDemo">
    @include('forms::demo.partials._number-field', ['prefix' => $prefix])
</x-accelade::layouts.docs>
