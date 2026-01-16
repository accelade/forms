@props(['framework' => 'vanilla', 'prefix' => 'a', 'documentation' => null, 'hasDemo' => true])

@php
    app('accelade')->setFramework($framework);
@endphp

<x-accelade::layouts.docs :framework="$framework" section="group" :documentation="$documentation" :hasDemo="$hasDemo">
    @include('forms::demo.partials._group', ['prefix' => $prefix])
</x-accelade::layouts.docs>
