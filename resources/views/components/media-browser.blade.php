{{--
    Media Browser Modal Component

    A WordPress-style media browser for selecting existing files from
    the server. Supports Spatie Media Library collections organized
    by Model -> Collection folders.

    Usage:
    <x-forms::media-browser
        :endpoint="route('forms.media.index')"
        :token="$uploadToken"
        :multiple="false"
        :max-selection="1"
        :accepted-types="['image/*']"
        :default-collection="'avatars'"
    />
--}}

@props([
    'endpoint' => null,
    'token' => null,
    'multiple' => false,
    'maxSelection' => null,
    'acceptedTypes' => [],
    'defaultCollection' => null,
    'defaultModelType' => null,
    'allowUpload' => true,
    'defaultView' => 'grid',
    'triggerId' => null,
])

@php
    $uniqueId = 'media-browser-' . uniqid();
@endphp

<div
    id="{{ $uniqueId }}"
    data-media-browser
    data-media-browser-endpoint="{{ $endpoint ?? route('forms.media.index') }}"
    data-media-browser-token="{{ $token }}"
    data-media-browser-multiple="{{ $multiple ? 'true' : 'false' }}"
    @if($maxSelection) data-media-browser-max-selection="{{ $maxSelection }}" @endif
    @if(!empty($acceptedTypes)) data-media-browser-accepted-types="{{ is_array($acceptedTypes) ? implode(',', $acceptedTypes) : $acceptedTypes }}" @endif
    @if($defaultCollection) data-media-browser-default-collection="{{ $defaultCollection }}" @endif
    @if($defaultModelType) data-media-browser-default-model-type="{{ $defaultModelType }}" @endif
    data-media-browser-allow-upload="{{ $allowUpload ? 'true' : 'false' }}"
    data-media-browser-default-view="{{ $defaultView }}"
    @if($triggerId) data-media-browser-trigger="{{ $triggerId }}" @endif
    {{ $attributes->class(['media-browser-container hidden']) }}
>
    {{ $slot ?? '' }}
</div>

@once
@push('styles')
<style>
/* Media Browser Modal Styles */
.media-browser-modal {
    position: fixed;
    inset: 0;
    z-index: 50;
    display: flex;
    align-items: center;
    justify-content: center;
}

.media-browser-backdrop {
    position: absolute;
    inset: 0;
    background-color: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
}

.media-browser-content {
    position: relative;
    background: white;
    border-radius: 0.75rem;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    width: 100%;
    max-width: 72rem;
    max-height: 90vh;
    margin: 1rem;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.dark .media-browser-content {
    background: rgb(17 24 39);
}

/* Header */
.media-browser-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid rgb(229 231 235);
}

.dark .media-browser-header {
    border-color: rgb(55 65 81);
}

/* Body */
.media-browser-body {
    display: flex;
    flex: 1;
    overflow: hidden;
}

/* Sidebar */
.media-browser-sidebar {
    width: 16rem;
    border-right: 1px solid rgb(229 231 235);
    overflow-y: auto;
    flex-shrink: 0;
}

.dark .media-browser-sidebar {
    border-color: rgb(55 65 81);
}

/* Main Content */
.media-browser-main {
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

/* Media Grid */
.media-browser-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 1rem;
    padding: 1rem;
}

.media-browser-grid.list-view {
    grid-template-columns: 1fr;
}

/* Media Item */
.media-browser-item {
    position: relative;
    aspect-ratio: 1;
    background: rgb(243 244 246);
    border-radius: 0.5rem;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.15s;
}

.dark .media-browser-item {
    background: rgb(31 41 55);
}

.media-browser-item:hover {
    ring: 2px;
    ring-color: rgb(59 130 246);
}

.media-browser-item.selected {
    ring: 2px;
    ring-color: rgb(59 130 246);
    ring-offset: 2px;
}

.media-browser-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.media-browser-item .check-badge {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    width: 1.5rem;
    height: 1.5rem;
    background: rgb(59 130 246);
    border-radius: 9999px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Preview Panel */
.media-browser-preview {
    width: 20rem;
    border-left: 1px solid rgb(229 231 235);
    overflow-y: auto;
    flex-shrink: 0;
}

.dark .media-browser-preview {
    border-color: rgb(55 65 81);
}

/* Footer */
.media-browser-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.5rem;
    border-top: 1px solid rgb(229 231 235);
    background: rgb(249 250 251);
}

.dark .media-browser-footer {
    border-color: rgb(55 65 81);
    background: rgb(31 41 55);
}

/* Pagination */
.media-browser-pagination {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.media-browser-pagination button {
    padding: 0.25rem 0.75rem;
    font-size: 0.875rem;
    border-radius: 0.375rem;
    transition: all 0.15s;
}

.media-browser-pagination button:hover:not(:disabled) {
    background: rgb(243 244 246);
}

.dark .media-browser-pagination button:hover:not(:disabled) {
    background: rgb(55 65 81);
}

.media-browser-pagination button.active {
    background: rgb(59 130 246);
    color: white;
}

/* Collection Item */
.collection-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    border-radius: 0.5rem;
    transition: all 0.15s;
    color: rgb(55 65 81);
}

.dark .collection-item {
    color: rgb(209 213 219);
}

.collection-item:hover {
    background: rgb(243 244 246);
}

.dark .collection-item:hover {
    background: rgb(31 41 55);
}

.collection-item.active {
    background: rgb(239 246 255);
    color: rgb(37 99 235);
}

.dark .collection-item.active {
    background: rgb(30 58 138 / 0.2);
    color: rgb(96 165 250);
}
</style>
@endpush

@push('scripts')
<script>
// Media Browser is initialized via MediaBrowserManager.ts
// This script provides a simple trigger mechanism
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[data-media-browser-trigger]').forEach(function(trigger) {
        const targetId = trigger.dataset.mediaBrowserTrigger;
        const target = document.getElementById(targetId);

        if (target && target.mediaBrowser) {
            trigger.addEventListener('click', function() {
                target.mediaBrowser.open();
            });
        }
    });
});
</script>
@endpush
@endonce
