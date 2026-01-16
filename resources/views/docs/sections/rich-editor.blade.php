@props(['framework' => 'vanilla', 'prefix' => 'a', 'documentation' => null, 'hasDemo' => true])

@php
    app('accelade')->setFramework($framework);
@endphp

<x-accelade::layouts.docs :framework="$framework" section="rich-editor" :documentation="$documentation" :hasDemo="$hasDemo">
    @include('forms::demo.partials._rich-editor', ['prefix' => $prefix])
</x-accelade::layouts.docs>
