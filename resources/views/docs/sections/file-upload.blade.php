@props(['framework' => 'vanilla', 'prefix' => 'a', 'documentation' => null, 'hasDemo' => true])

@php
    app('accelade')->setFramework($framework);
@endphp

<x-accelade::layouts.docs :framework="$framework" section="file-upload" :documentation="$documentation" :hasDemo="$hasDemo">
    @include('forms::demo.partials._file-upload', ['prefix' => $prefix])
</x-accelade::layouts.docs>
