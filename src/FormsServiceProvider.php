<?php

declare(strict_types=1);

namespace Accelade\Forms;

use Accelade\Docs\DocsRegistry;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class FormsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/forms.php', 'forms');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'forms');
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'forms');

        $this->configurePublishing();
        $this->configureComponents();
        $this->configureRoutes();
        $this->registerDocs();
        $this->registerAssets();
    }

    /**
     * Register scripts and styles into Accelade.
     */
    protected function registerAssets(): void
    {
        if (! $this->app->bound('accelade')) {
            return;
        }

        $accelade = $this->app->make('accelade');

        // Register CSS styles (including FilePond and Cropper.js styles bundled via npm)
        $accelade->registerStyle('forms', function () {
            $css = '';

            // First try dist CSS (includes FilePond and Cropper.js styles)
            $distCssPath = __DIR__.'/../dist/accelade-forms.css';
            if (file_exists($distCssPath)) {
                $css .= file_get_contents($distCssPath);
            }

            // Also include custom forms.css if exists
            $customCssPath = __DIR__.'/../resources/css/forms.css';
            if (file_exists($customCssPath)) {
                $css .= "\n".file_get_contents($customCssPath);
            }

            if ($css) {
                return "<style data-forms-styles>\n{$css}\n</style>";
            }

            return '';
        });

        // Register JavaScript
        $accelade->registerScript('forms', function () {
            // First try the built dist file
            $distPath = __DIR__.'/../dist/forms.iife.js';
            if (file_exists($distPath)) {
                $js = file_get_contents($distPath);

                return "<script data-forms-scripts>\n{$js}\n</script>";
            }

            // Fallback to minimal inline initialization
            return $this->getInlineFormScripts();
        });
    }

    /**
     * Get inline form initialization scripts.
     */
    protected function getInlineFormScripts(): string
    {
        return <<<'HTML'
<script data-forms-scripts>
(function() {
    'use strict';

    // Forms initialization
    function initForms() {
        initToggles();
        initPinInputs();
        initTagsInputs();
        initRateInputs();
        initSliders();
        initNumberFields();
        initIconPickers();
        initEmojiInputs();
        initColorPickers();
        initKeyValue();
        initRepeaters();
        initRichEditors();
        initMarkdownEditors();
        initFileUploads();
        initDateTimePickers();
        initAutosizeTextareas();
        initSearchableSelects();
    }

    // Toggle
    function initToggles() {
        document.querySelectorAll('.toggle-wrapper:not([data-initialized])').forEach(function(wrapper) {
            wrapper.dataset.initialized = 'true';
            var button = wrapper.querySelector('button[role="switch"]');
            var knob = wrapper.querySelector('.toggle-knob');
            var hiddenInput = wrapper.querySelector('.toggle-hidden-input');
            var onIcon = wrapper.querySelector('.toggle-on-icon');
            var offIcon = wrapper.querySelector('.toggle-off-icon');
            var onValue = wrapper.dataset.onValue || '1';
            var offValue = wrapper.dataset.offValue || '0';

            if (!button) return;

            var onClass = button.dataset.onClass || 'bg-primary-600';
            var offClass = button.dataset.offClass || 'bg-gray-200';

            function isEnabled() {
                return button.getAttribute('aria-checked') === 'true';
            }

            function toggle() {
                var enabled = !isEnabled();
                button.setAttribute('aria-checked', enabled.toString());

                // Update knob position
                if (knob) {
                    if (enabled) {
                        knob.classList.add('translate-x-5');
                        knob.classList.remove('translate-x-0');
                    } else {
                        knob.classList.remove('translate-x-5');
                        knob.classList.add('translate-x-0');
                    }
                }

                // Update button background
                onClass.split(' ').forEach(function(c) {
                    if (c) button.classList.toggle(c, enabled);
                });
                offClass.split(' ').forEach(function(c) {
                    if (c) button.classList.toggle(c, !enabled);
                });

                // Update icons
                if (onIcon) {
                    onIcon.classList.toggle('opacity-100', enabled);
                    onIcon.classList.toggle('opacity-0', !enabled);
                }
                if (offIcon) {
                    offIcon.classList.toggle('opacity-100', !enabled);
                    offIcon.classList.toggle('opacity-0', enabled);
                }

                // Update hidden input
                if (hiddenInput) {
                    hiddenInput.value = enabled ? onValue : offValue;
                }
            }

            if (!button.disabled) {
                button.addEventListener('click', toggle);
            }
        });
    }

    // PIN Input
    function initPinInputs() {
        document.querySelectorAll('.pin-input-wrapper:not([data-initialized])').forEach(function(wrapper) {
            wrapper.dataset.initialized = 'true';
            var inputs = wrapper.querySelectorAll('.pin-input-digit');
            var hiddenInput = wrapper.querySelector('.pin-input-hidden');

            inputs.forEach(function(input, index) {
                input.addEventListener('input', function(e) {
                    if (e.target.value.length >= 1) {
                        e.target.value = e.target.value.slice(-1);
                        if (inputs[index + 1]) inputs[index + 1].focus();
                    }
                    updateHiddenValue();
                });

                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && !e.target.value && inputs[index - 1]) {
                        inputs[index - 1].focus();
                    }
                });

                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    var paste = (e.clipboardData || window.clipboardData).getData('text');
                    paste.split('').slice(0, inputs.length).forEach(function(char, i) {
                        if (inputs[index + i]) inputs[index + i].value = char;
                    });
                    updateHiddenValue();
                });
            });

            function updateHiddenValue() {
                if (hiddenInput) {
                    hiddenInput.value = Array.from(inputs).map(function(i) { return i.value; }).join('');
                }
            }
        });
    }

    // Tags Input
    function initTagsInputs() {
        document.querySelectorAll('.tags-input-wrapper:not([data-initialized])').forEach(function(wrapper) {
            wrapper.dataset.initialized = 'true';
            var container = wrapper.querySelector('.tags-container');
            var input = wrapper.querySelector('.tags-input');
            var hiddenInput = wrapper.querySelector('.tags-hidden-input');
            var maxTags = parseInt(wrapper.dataset.maxTags || '999', 10);

            if (!input) return;

            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ',') {
                    e.preventDefault();
                    addTag(input.value.trim());
                    input.value = '';
                }
            });

            wrapper.addEventListener('click', function(e) {
                if (e.target.classList.contains('tag-remove')) {
                    e.target.closest('.tag').remove();
                    updateHiddenValue();
                }
            });

            function addTag(value) {
                if (!value || container.querySelectorAll('.tag').length >= maxTags) return;
                var tag = document.createElement('span');
                tag.className = 'tag';
                tag.innerHTML = '<span class="tag-text">' + value + '</span><button type="button" class="tag-remove">&times;</button>';
                container.appendChild(tag);
                updateHiddenValue();
            }

            function updateHiddenValue() {
                if (hiddenInput) {
                    hiddenInput.value = Array.from(container.querySelectorAll('.tag-text')).map(function(t) { return t.textContent; }).join(',');
                }
            }
        });
    }

    // Rate Input
    function initRateInputs() {
        document.querySelectorAll('.rate-input-wrapper:not([data-initialized])').forEach(function(wrapper) {
            wrapper.dataset.initialized = 'true';
            var items = wrapper.querySelectorAll('.rate-item');
            var hiddenInput = wrapper.querySelector('.rate-hidden-input');
            var valueDisplay = wrapper.querySelector('.rate-value');

            items.forEach(function(item, index) {
                item.addEventListener('click', function() {
                    var value = index + 1;
                    if (hiddenInput) hiddenInput.value = value;
                    if (valueDisplay) valueDisplay.textContent = value;
                    items.forEach(function(i, idx) {
                        i.classList.toggle('is-active', idx < value);
                    });
                });
            });
        });
    }

    // Slider
    function initSliders() {
        document.querySelectorAll('.slider-wrapper:not([data-initialized])').forEach(function(wrapper) {
            wrapper.dataset.initialized = 'true';
            var input = wrapper.querySelector('.slider-input');
            var valueDisplay = wrapper.querySelector('.slider-value');

            if (input && valueDisplay) {
                input.addEventListener('input', function() {
                    valueDisplay.textContent = input.value;
                });
            }
        });
    }

    // Number Field
    function initNumberFields() {
        document.querySelectorAll('.number-input-wrapper:not([data-initialized])').forEach(function(wrapper) {
            wrapper.dataset.initialized = 'true';
            var input = wrapper.querySelector('.number-input');
            var decrement = wrapper.querySelector('.number-decrement');
            var increment = wrapper.querySelector('.number-increment');

            if (!input) return;
            var min = parseFloat(input.min) || -Infinity;
            var max = parseFloat(input.max) || Infinity;
            var step = parseFloat(input.step) || 1;

            if (decrement) {
                decrement.addEventListener('click', function() {
                    var val = parseFloat(input.value) || 0;
                    input.value = Math.max(min, val - step);
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                });
            }

            if (increment) {
                increment.addEventListener('click', function() {
                    var val = parseFloat(input.value) || 0;
                    input.value = Math.min(max, val + step);
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                });
            }
        });
    }

    // Icon Picker
    function initIconPickers() {
        document.querySelectorAll('.icon-picker-wrapper:not([data-initialized])').forEach(function(wrapper) {
            wrapper.dataset.initialized = 'true';
            var trigger = wrapper.querySelector('.icon-picker-trigger');
            var dropdown = wrapper.querySelector('.icon-picker-dropdown');
            var searchInput = wrapper.querySelector('.icon-picker-search-input');
            var items = wrapper.querySelectorAll('.icon-picker-item');
            var hiddenInput = wrapper.querySelector('.icon-picker-value');
            var selectedDisplay = wrapper.querySelector('.icon-picker-selected');
            var selectedNameDisplay = wrapper.querySelector('.icon-picker-selected-name');
            var placeholder = wrapper.querySelector('.icon-picker-placeholder');
            var emptyState = wrapper.querySelector('.icon-picker-empty');
            var tabs = wrapper.querySelectorAll('.icon-picker-tab');
            var panels = wrapper.querySelectorAll('.icon-picker-panel');

            if (trigger && dropdown) {
                trigger.addEventListener('click', function() {
                    dropdown.hidden = !dropdown.hidden;
                });

                document.addEventListener('click', function(e) {
                    if (!wrapper.contains(e.target)) dropdown.hidden = true;
                });
            }

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    var query = searchInput.value.toLowerCase();
                    var visibleCount = 0;
                    items.forEach(function(item) {
                        var name = (item.dataset.name || '').toLowerCase();
                        var icon = item.dataset.icon || '';
                        var matches = name.includes(query) || icon.includes(query);
                        item.hidden = !matches;
                        if (matches) visibleCount++;
                    });
                    if (emptyState) emptyState.classList.toggle('hidden', visibleCount > 0);
                    // Show all panels when searching
                    if (query) {
                        panels.forEach(function(p) { p.classList.remove('hidden'); });
                    }
                });
            }

            // Category tabs
            tabs.forEach(function(tab) {
                tab.addEventListener('click', function() {
                    var category = tab.dataset.category;
                    tabs.forEach(function(t) {
                        var isActive = t.dataset.category === category;
                        t.classList.toggle('text-primary-600', isActive);
                        t.classList.toggle('border-b-2', isActive);
                        t.classList.toggle('border-primary-600', isActive);
                        t.classList.toggle('text-gray-500', !isActive);
                    });
                    panels.forEach(function(p) {
                        p.classList.toggle('hidden', p.dataset.category !== category);
                    });
                    if (searchInput) searchInput.value = '';
                });
            });

            items.forEach(function(item) {
                item.addEventListener('click', function() {
                    var icon = item.dataset.icon;
                    var name = item.dataset.name || '';
                    if (hiddenInput) hiddenInput.value = icon;
                    if (selectedDisplay) selectedDisplay.textContent = icon;
                    if (selectedNameDisplay) selectedNameDisplay.textContent = name;
                    if (placeholder) placeholder.hidden = true;
                    if (dropdown) dropdown.hidden = true;
                    // Highlight selected
                    items.forEach(function(i) {
                        i.classList.toggle('bg-primary-100', i.dataset.icon === icon);
                    });
                });
            });
        });
    }

    // Emoji Input
    function initEmojiInputs() {
        document.querySelectorAll('.emoji-input-wrapper:not([data-initialized])').forEach(function(wrapper) {
            wrapper.dataset.initialized = 'true';
            var trigger = wrapper.querySelector('.emoji-input-trigger');
            var dropdown = wrapper.querySelector('.emoji-input-dropdown');
            var searchInput = wrapper.querySelector('.emoji-input-search-input');
            var items = wrapper.querySelectorAll('.emoji-input-item');
            var hiddenInput = wrapper.querySelector('.emoji-input-value');
            var selectedDisplay = wrapper.querySelector('.emoji-input-selected');
            var selectedNameDisplay = wrapper.querySelector('.emoji-input-selected-name');
            var placeholder = wrapper.querySelector('.emoji-input-placeholder');
            var emptyState = wrapper.querySelector('.emoji-input-empty');
            var tabs = wrapper.querySelectorAll('.emoji-input-tab');
            var panels = wrapper.querySelectorAll('.emoji-input-panel');
            var previewArea = wrapper.querySelector('.emoji-input-preview');
            var previewIcon = wrapper.querySelector('.emoji-preview-icon');
            var previewName = wrapper.querySelector('.emoji-preview-name');

            if (trigger && dropdown) {
                trigger.addEventListener('click', function() {
                    dropdown.hidden = !dropdown.hidden;
                    if (!dropdown.hidden && searchInput) searchInput.focus();
                });

                document.addEventListener('click', function(e) {
                    if (!wrapper.contains(e.target)) dropdown.hidden = true;
                });
            }

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    var query = searchInput.value.toLowerCase();
                    var visibleCount = 0;
                    items.forEach(function(item) {
                        var name = (item.dataset.name || '').toLowerCase();
                        var emoji = item.dataset.emoji || '';
                        var matches = name.includes(query) || emoji.includes(query);
                        item.hidden = !matches;
                        if (matches) visibleCount++;
                    });
                    if (emptyState) emptyState.classList.toggle('hidden', visibleCount > 0);
                    if (query) {
                        panels.forEach(function(p) { p.classList.remove('hidden'); });
                    }
                });
            }

            // Category tabs
            tabs.forEach(function(tab) {
                tab.addEventListener('click', function() {
                    var category = tab.dataset.category;
                    tabs.forEach(function(t) {
                        var isActive = t.dataset.category === category;
                        t.classList.toggle('bg-white', isActive);
                        t.classList.toggle('border-b-2', isActive);
                        t.classList.toggle('border-primary-600', isActive);
                        t.classList.toggle('text-gray-500', !isActive);
                    });
                    panels.forEach(function(p) {
                        p.classList.toggle('hidden', p.dataset.category !== category);
                    });
                    if (searchInput) searchInput.value = '';
                    items.forEach(function(item) { item.hidden = false; });
                    if (emptyState) emptyState.classList.add('hidden');
                });
            });

            items.forEach(function(item) {
                item.addEventListener('click', function() {
                    var emoji = item.dataset.emoji;
                    var name = item.dataset.name || '';
                    if (hiddenInput) {
                        hiddenInput.value = emoji;
                        hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                    if (selectedDisplay) selectedDisplay.textContent = emoji;
                    if (selectedNameDisplay) selectedNameDisplay.textContent = name;
                    if (placeholder) placeholder.hidden = true;
                    if (dropdown) dropdown.hidden = true;
                    items.forEach(function(i) {
                        i.classList.toggle('bg-primary-100', i.dataset.emoji === emoji);
                    });
                });

                // Preview on hover
                item.addEventListener('mouseenter', function() {
                    if (previewArea && previewIcon && previewName) {
                        previewArea.classList.remove('hidden');
                        previewIcon.textContent = item.dataset.emoji;
                        previewName.textContent = item.dataset.name || '';
                    }
                });
                item.addEventListener('mouseleave', function() {
                    if (previewArea) previewArea.classList.add('hidden');
                });
            });

            // Escape key
            wrapper.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && dropdown) dropdown.hidden = true;
            });
        });
    }

    // Color Picker
    function initColorPickers() {
        document.querySelectorAll('.color-picker-wrapper:not([data-initialized])').forEach(function(wrapper) {
            wrapper.dataset.initialized = 'true';
            var colorInput = wrapper.querySelector('.color-input');
            var swatches = wrapper.querySelectorAll('.color-swatch');
            var hiddenInput = wrapper.querySelector('.color-picker-hidden');

            swatches.forEach(function(swatch) {
                swatch.addEventListener('click', function() {
                    var color = swatch.dataset.color;
                    if (colorInput) colorInput.value = color;
                    if (hiddenInput) hiddenInput.value = color;
                });
            });

            if (colorInput) {
                colorInput.addEventListener('input', function() {
                    if (hiddenInput) hiddenInput.value = colorInput.value;
                });
            }
        });
    }

    // Key Value
    function initKeyValue() {
        document.querySelectorAll('.key-value-wrapper:not([data-initialized])').forEach(function(wrapper) {
            wrapper.dataset.initialized = 'true';
            var addBtn = wrapper.querySelector('.key-value-add');
            var rows = wrapper.querySelector('.key-value-rows');
            var templateId = wrapper.dataset.templateId;
            var template = templateId ? document.getElementById(templateId) : null;
            var dataInput = wrapper.querySelector('.key-value-data');

            function updateHiddenValue() {
                if (!dataInput) return;
                var data = {};
                rows.querySelectorAll('.key-value-row').forEach(function(row) {
                    var keyInput = row.querySelector('.key-value-key');
                    var valueInput = row.querySelector('.key-value-value');
                    if (keyInput && valueInput && keyInput.value) {
                        data[keyInput.value] = valueInput.value;
                    }
                });
                dataInput.value = JSON.stringify(data);
            }

            if (addBtn && template && rows) {
                addBtn.addEventListener('click', function() {
                    var clone = template.content.cloneNode(true);
                    rows.appendChild(clone);
                    updateHiddenValue();
                });
            }

            // Update hidden value when inputs change
            wrapper.addEventListener('input', function(e) {
                if (e.target.classList.contains('key-value-key') || e.target.classList.contains('key-value-value')) {
                    updateHiddenValue();
                }
            });

            wrapper.addEventListener('click', function(e) {
                var deleteBtn = e.target.closest('.key-value-delete');
                if (deleteBtn) {
                    deleteBtn.closest('.key-value-row').remove();
                    updateHiddenValue();
                }
            });
        });
    }

    // Repeater
    function initRepeaters() {
        document.querySelectorAll('.repeater-field:not([data-initialized])').forEach(function(field) {
            field.dataset.initialized = 'true';
            var wrapper = field.querySelector('.repeater-wrapper');
            if (!wrapper) return;

            var addBtn = field.querySelector('.repeater-add');
            var items = field.querySelector('.repeater-items');
            var fieldId = field.id;
            var fieldName = field.dataset.name || fieldId;
            var templateId = fieldId ? fieldId + '-template' : null;
            var template = templateId ? document.getElementById(templateId) : null;
            var minItems = parseInt(field.dataset.minItems || '0', 10);
            var maxItems = parseInt(field.dataset.maxItems || '999', 10);
            var itemCounter = 0;

            // Count existing items to set initial counter
            if (items) {
                itemCounter = items.querySelectorAll('.repeater-item').length;
            }

            function getItemCount() {
                return items ? items.querySelectorAll('.repeater-item').length : 0;
            }

            function updateButtonState() {
                if (addBtn) addBtn.disabled = getItemCount() >= maxItems;
            }

            function updateItemLabels() {
                items.querySelectorAll('.repeater-item').forEach(function(item, index) {
                    var label = item.querySelector('.repeater-item-label');
                    if (label) label.textContent = 'Item ' + (index + 1);
                    item.dataset.index = index;
                });
            }

            function updateFieldNames() {
                items.querySelectorAll('.repeater-item').forEach(function(item, index) {
                    item.querySelectorAll('input, select, textarea').forEach(function(input) {
                        var name = input.getAttribute('name');
                        var id = input.getAttribute('id');
                        if (name) {
                            // Replace [__INDEX__] or existing [N] with new index
                            input.name = name.replace(/\[__INDEX__\]|\[\d+\]/g, '[' + index + ']');
                        }
                        if (id) {
                            // Replace __INDEX__ or _N_ with new index
                            input.id = id.replace(/__INDEX__|_\d+_/g, '_' + index + '_');
                        }
                    });
                    // Update labels
                    item.querySelectorAll('label').forEach(function(label) {
                        var forAttr = label.getAttribute('for');
                        if (forAttr) {
                            label.setAttribute('for', forAttr.replace(/__INDEX__|_\d+_/g, '_' + index + '_'));
                        }
                    });
                });
            }

            function addItem() {
                if (getItemCount() >= maxItems) return;
                if (!template) return;
                var clone = template.content.cloneNode(true);
                var newIndex = itemCounter++;
                // Replace __INDEX__ placeholders in the clone
                var container = document.createElement('div');
                container.appendChild(clone);
                var html = container.innerHTML;
                html = html.replace(/__INDEX__/g, newIndex);
                container.innerHTML = html;
                while (container.firstChild) {
                    items.appendChild(container.firstChild);
                }
                updateButtonState();
                updateItemLabels();
                updateFieldNames();
            }

            function cloneItem(item) {
                if (getItemCount() >= maxItems) return;
                var clone = item.cloneNode(true);
                item.parentNode.insertBefore(clone, item.nextSibling);
                itemCounter++;
                updateButtonState();
                updateItemLabels();
                updateFieldNames();
            }

            if (addBtn && template && items) {
                addBtn.addEventListener('click', addItem);
            }

            field.addEventListener('click', function(e) {
                if (e.target.classList.contains('repeater-delete') || e.target.closest('.repeater-delete')) {
                    if (getItemCount() > minItems) {
                        e.target.closest('.repeater-item').remove();
                        updateButtonState();
                        updateItemLabels();
                        updateFieldNames();
                    }
                }

                if (e.target.classList.contains('repeater-collapse') || e.target.closest('.repeater-collapse')) {
                    var item = e.target.closest('.repeater-item');
                    var content = item.querySelector('.repeater-item-content');
                    var icon = item.querySelector('.collapse-icon');
                    if (content) {
                        content.hidden = !content.hidden;
                        if (icon) icon.style.transform = content.hidden ? 'rotate(180deg)' : '';
                    }
                }

                if (e.target.classList.contains('repeater-clone') || e.target.closest('.repeater-clone')) {
                    var item = e.target.closest('.repeater-item');
                    cloneItem(item);
                }
            });

            updateButtonState();
            // Ensure existing items have correct field names
            updateFieldNames();
        });
    }

    // Rich Editor
    function initRichEditors() {
        document.querySelectorAll('.rich-editor-wrapper:not([data-initialized])').forEach(function(wrapper) {
            wrapper.dataset.initialized = 'true';
            var content = wrapper.querySelector('.rich-editor-content');
            var hiddenInput = wrapper.querySelector('.rich-editor-value');
            var toolbar = wrapper.querySelector('.rich-editor-toolbar');
            var counter = wrapper.closest('.rich-editor-field')?.querySelector('.current-length');
            var maxLength = content ? parseInt(content.dataset.maxLength || '0', 10) : 0;
            var placeholder = content ? content.dataset.placeholder : '';

            if (!content || !hiddenInput) return;

            // Set initial content
            if (hiddenInput.value) {
                content.innerHTML = hiddenInput.value;
            } else if (placeholder) {
                content.innerHTML = '<span class="placeholder text-gray-400">' + placeholder + '</span>';
            }

            // Update hidden input and counter
            function updateValue() {
                var text = content.innerText || '';
                var html = content.innerHTML || '';

                // Remove placeholder if present
                if (html.includes('class="placeholder"')) {
                    html = '';
                    text = '';
                }

                hiddenInput.value = html;
                if (counter) counter.textContent = text.length;
            }

            // Clear placeholder on focus
            content.addEventListener('focus', function() {
                if (content.innerHTML.includes('class="placeholder"')) {
                    content.innerHTML = '';
                }
            });

            // Restore placeholder on blur if empty
            content.addEventListener('blur', function() {
                if (!content.innerText.trim() && placeholder) {
                    content.innerHTML = '<span class="placeholder text-gray-400">' + placeholder + '</span>';
                }
                updateValue();
            });

            content.addEventListener('input', updateValue);

            // Toolbar actions
            if (toolbar) {
                toolbar.addEventListener('click', function(e) {
                    var btn = e.target.closest('.toolbar-button');
                    if (!btn) return;

                    var action = btn.dataset.action;
                    content.focus();

                    switch (action) {
                        case 'bold': document.execCommand('bold', false, null); break;
                        case 'italic': document.execCommand('italic', false, null); break;
                        case 'underline': document.execCommand('underline', false, null); break;
                        case 'strike': document.execCommand('strikeThrough', false, null); break;
                        case 'h1': document.execCommand('formatBlock', false, '<h1>'); break;
                        case 'h2': document.execCommand('formatBlock', false, '<h2>'); break;
                        case 'h3': document.execCommand('formatBlock', false, '<h3>'); break;
                        case 'ul':
                        case 'bulletList': document.execCommand('insertUnorderedList', false, null); break;
                        case 'ol':
                        case 'orderedList': document.execCommand('insertOrderedList', false, null); break;
                        case 'blockquote': document.execCommand('formatBlock', false, '<blockquote>'); break;
                        case 'code': document.execCommand('formatBlock', false, '<pre>'); break;
                        case 'link':
                            var url = prompt('Enter URL:');
                            if (url) document.execCommand('createLink', false, url);
                            break;
                        case 'hr': document.execCommand('insertHorizontalRule', false, null); break;
                        case 'clear': document.execCommand('removeFormat', false, null); break;
                        case 'undo': document.execCommand('undo', false, null); break;
                        case 'redo': document.execCommand('redo', false, null); break;
                    }

                    updateValue();
                });
            }
        });
    }

    // Markdown Editor
    function initMarkdownEditors() {
        document.querySelectorAll('.markdown-editor-wrapper:not([data-initialized])').forEach(function(wrapper) {
            wrapper.dataset.initialized = 'true';
            var textarea = wrapper.querySelector('.markdown-editor-input');
            var preview = wrapper.querySelector('.markdown-editor-preview');
            var toolbar = wrapper.querySelector('.markdown-editor-toolbar');
            var previewToggle = wrapper.querySelector('.toolbar-preview-toggle');
            var counter = wrapper.closest('.markdown-editor-field')?.querySelector('.current-length');

            if (!textarea) return;

            // Update counter
            function updateCounter() {
                if (counter) counter.textContent = textarea.value.length;
            }

            textarea.addEventListener('input', updateCounter);
            updateCounter();

            // Preview toggle
            if (previewToggle && preview) {
                previewToggle.addEventListener('click', function() {
                    var isPreviewHidden = preview.classList.contains('hidden');
                    textarea.classList.toggle('hidden', isPreviewHidden);
                    preview.classList.toggle('hidden', !isPreviewHidden);
                    previewToggle.classList.toggle('bg-gray-200', isPreviewHidden);

                    if (isPreviewHidden) {
                        // Simple markdown rendering
                        preview.innerHTML = renderMarkdown(textarea.value);
                    }
                });
            }

            // Simple markdown to HTML conversion
            function renderMarkdown(md) {
                return md
                    .replace(/^### (.*$)/gim, '<h3>$1</h3>')
                    .replace(/^## (.*$)/gim, '<h2>$1</h2>')
                    .replace(/^# (.*$)/gim, '<h1>$1</h1>')
                    .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
                    .replace(/\*(.+?)\*/g, '<em>$1</em>')
                    .replace(/~~(.+?)~~/g, '<del>$1</del>')
                    .replace(/`([^`]+)`/g, '<code>$1</code>')
                    .replace(/^- (.+)$/gim, '<li>$1</li>')
                    .replace(/^> (.+)$/gim, '<blockquote>$1</blockquote>')
                    .replace(/---/g, '<hr>')
                    .replace(/\n/g, '<br>');
            }

            // Toolbar actions
            if (toolbar) {
                toolbar.addEventListener('click', function(e) {
                    var btn = e.target.closest('.toolbar-button');
                    if (!btn || btn.classList.contains('toolbar-preview-toggle')) return;

                    var action = btn.dataset.action;
                    var start = textarea.selectionStart;
                    var end = textarea.selectionEnd;
                    var text = textarea.value;
                    var selected = text.substring(start, end);
                    var before = text.substring(0, start);
                    var after = text.substring(end);
                    var insert = '';

                    switch (action) {
                        case 'bold': insert = '**' + (selected || 'bold text') + '**'; break;
                        case 'italic': insert = '*' + (selected || 'italic text') + '*'; break;
                        case 'strike': insert = '~~' + (selected || 'strikethrough') + '~~'; break;
                        case 'h1': insert = '# ' + (selected || 'Heading 1'); break;
                        case 'h2': insert = '## ' + (selected || 'Heading 2'); break;
                        case 'h3': insert = '### ' + (selected || 'Heading 3'); break;
                        case 'ul': insert = '- ' + (selected || 'List item'); break;
                        case 'ol': insert = '1. ' + (selected || 'List item'); break;
                        case 'blockquote': insert = '> ' + (selected || 'Quote'); break;
                        case 'code': insert = '`' + (selected || 'code') + '`'; break;
                        case 'codeblock': insert = '```\n' + (selected || 'code') + '\n```'; break;
                        case 'link': insert = '[' + (selected || 'link text') + '](url)'; break;
                        case 'image': insert = '![' + (selected || 'alt text') + '](image-url)'; break;
                        case 'hr': insert = '\n---\n'; break;
                        case 'task': insert = '- [ ] ' + (selected || 'Task item'); break;
                        case 'table': insert = '| Column 1 | Column 2 |\n|----------|----------|\n| Cell 1   | Cell 2   |'; break;
                        default: insert = selected;
                    }

                    textarea.value = before + insert + after;
                    textarea.focus();
                    textarea.setSelectionRange(start + insert.length, start + insert.length);
                    updateCounter();
                });
            }
        });
    }

    // File Upload
    function initFileUploads() {
        document.querySelectorAll('.file-upload-field:not([data-initialized])').forEach(function(field) {
            field.dataset.initialized = 'true';
            var dropzone = field.querySelector('.file-upload-dropzone');
            var input = field.querySelector('.file-upload-input');
            var fileList = field.querySelector('.file-upload-list');
            var isImage = field.dataset.isImage === 'true';

            if (!input || !fileList) return;

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                var k = 1024;
                var sizes = ['Bytes', 'KB', 'MB', 'GB'];
                var i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            function renderFileList(files) {
                fileList.innerHTML = '';
                if (files.length === 0) {
                    fileList.classList.add('hidden');
                    return;
                }
                fileList.classList.remove('hidden');

                Array.from(files).forEach(function(file, index) {
                    var item = document.createElement('div');
                    item.className = 'file-upload-item flex items-center gap-3 p-3 rounded-lg border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800';

                    var isImageFile = file.type.startsWith('image/');
                    var preview = '';
                    if (isImageFile) {
                        preview = '<div class="file-preview w-12 h-12 rounded overflow-hidden flex-shrink-0 bg-gray-100 dark:bg-gray-700"></div>';
                    } else {
                        preview = '<div class="w-12 h-12 rounded flex items-center justify-center flex-shrink-0 bg-gray-100 dark:bg-gray-700"><svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg></div>';
                    }

                    item.innerHTML = preview +
                        '<div class="flex-1 min-w-0">' +
                            '<p class="text-sm font-medium text-gray-900 truncate dark:text-gray-100">' + file.name + '</p>' +
                            '<p class="text-xs text-gray-500 dark:text-gray-400">' + formatFileSize(file.size) + '</p>' +
                        '</div>' +
                        '<button type="button" class="file-remove p-1 text-gray-400 hover:text-red-500 rounded transition-colors" data-index="' + index + '">' +
                            '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>' +
                        '</button>';

                    fileList.appendChild(item);

                    // Load image preview
                    if (isImageFile) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            var previewEl = item.querySelector('.file-preview');
                            if (previewEl) {
                                previewEl.innerHTML = '<img src="' + e.target.result + '" class="w-full h-full object-cover" alt="' + file.name + '">';
                            }
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Handle file selection
            input.addEventListener('change', function() {
                renderFileList(this.files);
            });

            // Drag and drop support
            if (dropzone) {
                dropzone.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    dropzone.classList.add('border-primary-500', 'bg-primary-50', 'dark:bg-primary-900/20');
                });

                dropzone.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    dropzone.classList.remove('border-primary-500', 'bg-primary-50', 'dark:bg-primary-900/20');
                });

                dropzone.addEventListener('drop', function(e) {
                    e.preventDefault();
                    dropzone.classList.remove('border-primary-500', 'bg-primary-50', 'dark:bg-primary-900/20');

                    if (e.dataTransfer.files.length > 0) {
                        input.files = e.dataTransfer.files;
                        renderFileList(input.files);
                    }
                });
            }

            // Handle remove (note: can't modify FileList, so we just clear all)
            fileList.addEventListener('click', function(e) {
                var removeBtn = e.target.closest('.file-remove');
                if (removeBtn) {
                    // Clear the input - can't selectively remove from FileList
                    input.value = '';
                    fileList.innerHTML = '';
                    fileList.classList.add('hidden');
                }
            });
        });
    }

    // DateTime/Date/Time Pickers with Flatpickr
    function initDateTimePickers() {
        // Check if Flatpickr is available
        if (typeof flatpickr === 'undefined') {
            // Load Flatpickr CSS dynamically
            if (!document.querySelector('link[href*="flatpickr"]')) {
                var link = document.createElement('link');
                link.rel = 'stylesheet';
                link.href = 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css';
                document.head.appendChild(link);

                // Also add dark theme
                var darkLink = document.createElement('link');
                darkLink.rel = 'stylesheet';
                darkLink.href = 'https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css';
                darkLink.media = '(prefers-color-scheme: dark)';
                document.head.appendChild(darkLink);
            }

            // Load Flatpickr JS dynamically
            if (!document.querySelector('script[src*="flatpickr"]')) {
                var script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/flatpickr';
                script.onload = function() {
                    initDateTimePickersWithFlatpickr();
                };
                document.head.appendChild(script);
                return;
            }
        }

        initDateTimePickersWithFlatpickr();
    }

    function initDateTimePickersWithFlatpickr() {
        if (typeof flatpickr === 'undefined') return;

        // Select all picker field types that have data-flatpickr attribute
        var selectors = [
            '.datetime-picker-field[data-flatpickr]:not([data-initialized])',
            '.date-picker-field[data-flatpickr]:not([data-initialized])',
            '.time-picker-field[data-flatpickr]:not([data-initialized])',
            '.date-range-picker-field[data-flatpickr]:not([data-initialized])'
        ];

        document.querySelectorAll(selectors.join(',')).forEach(function(field) {
            field.dataset.initialized = 'true';

            // Find the input - try different class names
            var input = field.querySelector('.datetime-picker-input') ||
                        field.querySelector('.date-picker-input') ||
                        field.querySelector('.time-picker-input') ||
                        field.querySelector('.date-range-picker-input') ||
                        field.querySelector('.flatpickr-input');

            // Find the toggle button
            var toggle = field.querySelector('.datetime-picker-toggle') ||
                         field.querySelector('.date-picker-toggle') ||
                         field.querySelector('.time-picker-toggle') ||
                         field.querySelector('.date-range-picker-toggle');

            var optionsStr = field.dataset.flatpickr;

            if (!input) return;

            var options = {};
            try {
                options = JSON.parse(optionsStr);
            } catch (e) {
                console.warn('Invalid Flatpickr options:', e);
            }

            // Apply dark theme based on system preference
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                options.theme = 'dark';
            }

            // Handle wrap mode for toggle button
            if (toggle) {
                options.wrap = false;
                options.clickOpens = true;
            }

            // Create Flatpickr instance
            var fp = flatpickr(input, options);

            // Toggle button click handler
            if (toggle) {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    fp.toggle();
                });
            }

            // Store reference for cleanup
            input._flatpickr = fp;
        });
    }

    // Autosize Textareas
    function initAutosizeTextareas() {
        document.querySelectorAll('textarea[data-autosize]:not([data-autosize-initialized])').forEach(function(textarea) {
            textarea.dataset.autosizeInitialized = 'true';

            // Store original styles
            var minHeight = textarea.offsetHeight;
            var computedStyle = window.getComputedStyle(textarea);
            var borderTop = parseInt(computedStyle.borderTopWidth, 10) || 0;
            var borderBottom = parseInt(computedStyle.borderBottomWidth, 10) || 0;
            var paddingTop = parseInt(computedStyle.paddingTop, 10) || 0;
            var paddingBottom = parseInt(computedStyle.paddingBottom, 10) || 0;

            // Set initial styles for autosize
            textarea.style.overflow = 'hidden';
            textarea.style.resize = 'none';
            textarea.style.boxSizing = 'border-box';

            function resize() {
                // Reset height to auto to get the correct scrollHeight
                textarea.style.height = 'auto';
                // Calculate new height
                var newHeight = textarea.scrollHeight + borderTop + borderBottom;
                // Apply minimum height
                textarea.style.height = Math.max(minHeight, newHeight) + 'px';
            }

            // Initial resize
            resize();

            // Resize on input
            textarea.addEventListener('input', resize);

            // Resize on window resize (in case of responsive layouts)
            var resizeTimeout;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(resize, 100);
            });

            // Handle programmatic value changes
            var descriptor = Object.getOwnPropertyDescriptor(HTMLTextAreaElement.prototype, 'value');
            if (descriptor && descriptor.set) {
                Object.defineProperty(textarea, 'value', {
                    get: function() {
                        return descriptor.get.call(this);
                    },
                    set: function(val) {
                        descriptor.set.call(this, val);
                        resize();
                    }
                });
            }

            // Store resize function for external use
            textarea._autosizeResize = resize;
        });
    }

    // Searchable Select (Select2-style)
    function initSearchableSelects() {
        document.querySelectorAll('.select-field[data-searchable-select]:not([data-initialized])').forEach(function(field) {
            field.dataset.initialized = 'true';

            var optionsStr = field.dataset.searchableSelect;
            var options = {};
            try {
                options = JSON.parse(optionsStr);
            } catch (e) {
                console.error('Invalid searchable select options:', e);
                return;
            }

            var wrapper = field.querySelector('.searchable-select-wrapper');
            if (!wrapper) return;

            var hiddenSelect = wrapper.querySelector('.searchable-select-hidden');
            var trigger = wrapper.querySelector('.searchable-select-trigger');
            var dropdown = wrapper.querySelector('.searchable-select-dropdown');
            var searchInput = wrapper.querySelector('.searchable-select-search');
            var optionsList = wrapper.querySelector('.searchable-select-options');
            var noResults = wrapper.querySelector('.searchable-select-no-results');
            var createOption = wrapper.querySelector('.searchable-select-create');
            var display = wrapper.querySelector('.searchable-select-display');
            var clearBtn = wrapper.querySelector('.searchable-select-clear');
            var arrow = wrapper.querySelector('.searchable-select-arrow');
            var tagsContainer = field.querySelector('.searchable-select-tags');

            if (!hiddenSelect || !trigger || !dropdown) return;

            var isOpen = false;
            var selectedValues = [];
            var highlightedIndex = -1;
            var allOptions = Array.from(optionsList.querySelectorAll('.searchable-select-option'));

            // Initialize selected values from hidden select
            function initSelectedValues() {
                selectedValues = [];
                Array.from(hiddenSelect.selectedOptions).forEach(function(opt) {
                    if (opt.value) {
                        selectedValues.push(opt.value);
                    }
                });
                updateDisplay();
                updateOptionStates();
                updateTags();
            }

            // Update display text
            function updateDisplay() {
                if (options.multiple) {
                    if (selectedValues.length === 0) {
                        display.textContent = hiddenSelect.querySelector('option[value=""]')?.textContent || 'Select...';
                        display.classList.add('text-gray-400');
                    } else {
                        display.textContent = selectedValues.length + ' selected';
                        display.classList.remove('text-gray-400');
                    }
                } else {
                    var selectedOpt = hiddenSelect.querySelector('option:checked');
                    if (selectedOpt && selectedOpt.value) {
                        display.textContent = selectedOpt.textContent;
                        display.classList.remove('text-gray-400');
                        if (clearBtn && options.allowClear) {
                            clearBtn.classList.remove('hidden');
                        }
                    } else {
                        display.textContent = hiddenSelect.querySelector('option[value=""]')?.textContent || 'Select...';
                        display.classList.add('text-gray-400');
                        if (clearBtn) {
                            clearBtn.classList.add('hidden');
                        }
                    }
                }
            }

            // Update option visual states
            function updateOptionStates() {
                allOptions.forEach(function(opt) {
                    var val = opt.dataset.value;
                    var check = opt.querySelector('.searchable-select-option-check');
                    if (selectedValues.includes(val)) {
                        opt.classList.add('bg-primary-50', 'dark:bg-primary-900/20');
                        if (check) check.classList.remove('hidden');
                    } else {
                        opt.classList.remove('bg-primary-50', 'dark:bg-primary-900/20');
                        if (check) check.classList.add('hidden');
                    }
                });
            }

            // Update tags for multiple select
            function updateTags() {
                if (!tagsContainer || !options.multiple) return;

                tagsContainer.innerHTML = '';
                if (selectedValues.length === 0) {
                    tagsContainer.classList.add('hidden');
                    return;
                }

                tagsContainer.classList.remove('hidden');
                selectedValues.forEach(function(val) {
                    var opt = hiddenSelect.querySelector('option[value="' + val + '"]');
                    if (opt) {
                        var tag = document.createElement('span');
                        tag.className = 'inline-flex items-center gap-1 px-2 py-1 text-xs rounded-md bg-primary-100 text-primary-800 dark:bg-primary-900/30 dark:text-primary-300';
                        tag.innerHTML = '<span>' + opt.textContent + '</span><button type="button" class="hover:text-primary-600" data-remove="' + val + '">&times;</button>';
                        tagsContainer.appendChild(tag);
                    }
                });

                // Add remove handlers
                tagsContainer.querySelectorAll('[data-remove]').forEach(function(btn) {
                    btn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        var val = btn.dataset.remove;
                        deselectValue(val);
                    });
                });
            }

            // Select a value
            function selectValue(val) {
                if (options.multiple) {
                    if (!selectedValues.includes(val)) {
                        if (options.maxSelections && selectedValues.length >= options.maxSelections) {
                            return;
                        }
                        selectedValues.push(val);
                    }
                } else {
                    selectedValues = [val];
                }
                syncToHiddenSelect();
                updateDisplay();
                updateOptionStates();
                updateTags();

                if (!options.multiple || options.closeOnSelect) {
                    close();
                }

                // Dispatch change event
                hiddenSelect.dispatchEvent(new Event('change', { bubbles: true }));
            }

            // Deselect a value
            function deselectValue(val) {
                var idx = selectedValues.indexOf(val);
                if (idx > -1) {
                    selectedValues.splice(idx, 1);
                }
                syncToHiddenSelect();
                updateDisplay();
                updateOptionStates();
                updateTags();
                hiddenSelect.dispatchEvent(new Event('change', { bubbles: true }));
            }

            // Sync selected values to hidden select
            function syncToHiddenSelect() {
                Array.from(hiddenSelect.options).forEach(function(opt) {
                    opt.selected = selectedValues.includes(opt.value);
                });
            }

            // Clear selection
            function clearSelection() {
                selectedValues = [];
                syncToHiddenSelect();
                updateDisplay();
                updateOptionStates();
                updateTags();
                hiddenSelect.dispatchEvent(new Event('change', { bubbles: true }));
            }

            // Open dropdown
            function open() {
                if (options.disabled) return;
                isOpen = true;
                dropdown.classList.remove('hidden');
                trigger.setAttribute('aria-expanded', 'true');
                if (arrow) arrow.classList.add('rotate-180');
                if (searchInput) {
                    searchInput.value = '';
                    filterOptions('');
                    setTimeout(function() { searchInput.focus(); }, 10);
                }
                highlightedIndex = -1;
                positionDropdown();
            }

            // Close dropdown
            function close() {
                isOpen = false;
                dropdown.classList.add('hidden');
                trigger.setAttribute('aria-expanded', 'false');
                if (arrow) arrow.classList.remove('rotate-180');
                highlightedIndex = -1;
            }

            // Toggle dropdown
            function toggle() {
                if (isOpen) {
                    close();
                } else {
                    open();
                }
            }

            // Position dropdown (handle viewport overflow)
            function positionDropdown() {
                var rect = wrapper.getBoundingClientRect();
                var dropdownHeight = dropdown.offsetHeight;
                var spaceBelow = window.innerHeight - rect.bottom;

                if (spaceBelow < dropdownHeight && rect.top > dropdownHeight) {
                    dropdown.style.bottom = '100%';
                    dropdown.style.top = 'auto';
                    dropdown.style.marginTop = '0';
                    dropdown.style.marginBottom = '0.25rem';
                } else {
                    dropdown.style.top = '100%';
                    dropdown.style.bottom = 'auto';
                    dropdown.style.marginBottom = '0';
                    dropdown.style.marginTop = '0.25rem';
                }
            }

            // Filter options based on search
            function filterOptions(query) {
                query = query.toLowerCase().trim();
                var visibleCount = 0;

                allOptions.forEach(function(opt) {
                    var label = opt.querySelector('.searchable-select-option-label').textContent.toLowerCase();
                    if (query.length < options.searchMinLength || label.includes(query)) {
                        opt.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        opt.classList.add('hidden');
                    }
                });

                // Show/hide no results
                if (noResults) {
                    if (visibleCount === 0 && query.length >= options.searchMinLength) {
                        noResults.classList.remove('hidden');
                    } else {
                        noResults.classList.add('hidden');
                    }
                }

                // Show/hide create option for taggable
                if (createOption && options.taggable) {
                    var exactMatch = allOptions.some(function(opt) {
                        return opt.querySelector('.searchable-select-option-label').textContent.toLowerCase() === query;
                    });
                    if (query && !exactMatch) {
                        createOption.classList.remove('hidden');
                        var createValueSpan = createOption.querySelector('.searchable-select-create-value');
                        if (createValueSpan) {
                            createValueSpan.textContent = query;
                        }
                    } else {
                        createOption.classList.add('hidden');
                    }
                }

                highlightedIndex = -1;
            }

            // Keyboard navigation
            function navigateOptions(direction) {
                var visibleOptions = allOptions.filter(function(opt) {
                    return !opt.classList.contains('hidden');
                });

                if (visibleOptions.length === 0) return;

                // Remove highlight from current
                if (highlightedIndex >= 0 && visibleOptions[highlightedIndex]) {
                    visibleOptions[highlightedIndex].classList.remove('bg-gray-100', 'dark:bg-gray-700');
                }

                // Calculate new index
                highlightedIndex += direction;
                if (highlightedIndex < 0) highlightedIndex = visibleOptions.length - 1;
                if (highlightedIndex >= visibleOptions.length) highlightedIndex = 0;

                // Add highlight to new
                if (visibleOptions[highlightedIndex]) {
                    visibleOptions[highlightedIndex].classList.add('bg-gray-100', 'dark:bg-gray-700');
                    visibleOptions[highlightedIndex].scrollIntoView({ block: 'nearest' });
                }
            }

            // Select highlighted option
            function selectHighlighted() {
                var visibleOptions = allOptions.filter(function(opt) {
                    return !opt.classList.contains('hidden');
                });

                if (highlightedIndex >= 0 && visibleOptions[highlightedIndex]) {
                    var val = visibleOptions[highlightedIndex].dataset.value;
                    if (options.multiple && selectedValues.includes(val)) {
                        deselectValue(val);
                    } else {
                        selectValue(val);
                    }
                }
            }

            // Create new option (taggable)
            function createNewOption(value) {
                var opt = document.createElement('option');
                opt.value = value;
                opt.textContent = value;
                hiddenSelect.appendChild(opt);

                var li = document.createElement('li');
                li.className = 'searchable-select-option px-3 py-2 text-sm cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-100';
                li.dataset.value = value;
                li.setAttribute('role', 'option');
                li.innerHTML = '<span class="searchable-select-option-label">' + value + '</span><span class="searchable-select-option-check hidden ms-2 text-primary-600"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg></span>';
                optionsList.appendChild(li);

                li.addEventListener('click', function() {
                    if (options.multiple && selectedValues.includes(value)) {
                        deselectValue(value);
                    } else {
                        selectValue(value);
                    }
                });

                allOptions.push(li);
                selectValue(value);
            }

            // Event listeners
            trigger.addEventListener('click', function(e) {
                e.preventDefault();
                toggle();
            });

            if (clearBtn) {
                clearBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    clearSelection();
                });
            }

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    filterOptions(searchInput.value);
                });

                searchInput.addEventListener('keydown', function(e) {
                    if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        navigateOptions(1);
                    } else if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        navigateOptions(-1);
                    } else if (e.key === 'Enter') {
                        e.preventDefault();
                        if (highlightedIndex >= 0) {
                            selectHighlighted();
                        }
                    } else if (e.key === 'Escape') {
                        close();
                        trigger.focus();
                    }
                });
            }

            // Option click handlers
            allOptions.forEach(function(opt) {
                opt.addEventListener('click', function() {
                    var val = opt.dataset.value;
                    if (options.multiple && selectedValues.includes(val)) {
                        deselectValue(val);
                    } else {
                        selectValue(val);
                    }
                });
            });

            // Create option click handler
            if (createOption) {
                createOption.addEventListener('click', function() {
                    var value = searchInput ? searchInput.value.trim() : '';
                    if (value) {
                        createNewOption(value);
                    }
                });
            }

            // Close on outside click
            document.addEventListener('click', function(e) {
                if (isOpen && !wrapper.contains(e.target)) {
                    close();
                }
            });

            // Keyboard on trigger
            trigger.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ' || e.key === 'ArrowDown') {
                    e.preventDefault();
                    open();
                }
            });

            // Initialize
            initSelectedValues();
        });
    }

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initForms);
    } else {
        initForms();
    }

    // Re-initialize on Accelade navigation
    document.addEventListener('accelade:navigated', initForms);
    document.addEventListener('accelade:updated', initForms);

    // Expose globally
    window.AcceladeForms = { init: initForms };
})();
</script>
HTML;
    }

    /**
     * Configure publishing.
     */
    protected function configurePublishing(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../config/forms.php' => config_path('forms.php'),
        ], 'forms-config');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/forms'),
        ], 'forms-views');
    }

    /**
     * Configure Blade components.
     */
    protected function configureComponents(): void
    {
        // Register anonymous Blade components
        Blade::anonymousComponentPath(__DIR__.'/../resources/views/components', 'forms');
    }

    /**
     * Configure demo routes.
     */
    protected function configureRoutes(): void
    {
        // Always load API routes (for select options endpoint)
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');

        if (! config('forms.demo.enabled', false)) {
            return;
        }

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }

    /**
     * Register documentation into Accelade's docs registry.
     */
    protected function registerDocs(): void
    {
        if (! config('forms.docs.enabled', true)) {
            return;
        }

        // Check if accelade.docs is available
        if (! $this->app->bound('accelade.docs')) {
            return;
        }

        /** @var DocsRegistry $docs */
        $docs = $this->app->make('accelade.docs');

        // Register forms package documentation path
        $docs->registerPackage('forms', __DIR__.'/../docs');

        // Register forms navigation group
        $docs->registerGroup('forms', 'Forms', 'form', 25);

        // Register form component sections
        $this->registerFormInputDocs($docs);
        $this->registerFormAdvancedDocs($docs);
    }

    /**
     * Register basic form input component docs.
     */
    protected function registerFormInputDocs(DocsRegistry $docs): void
    {
        $docs->section('forms-overview')
            ->label('Forms Overview')
            ->markdown('getting-started.md')
            ->demo()
            ->icon('📋')
            ->package('forms')
            ->inGroup('forms')
            ->register();

        $docs->section('text-input')
            ->label('Text Input')
            ->markdown('text-input.md')
            ->demo()
            ->icon('🔤')
            ->package('forms')
            ->inGroup('forms')
            ->register();

        $docs->section('textarea')
            ->label('Textarea')
            ->markdown('textarea.md')
            ->demo()
            ->icon('📝')
            ->package('forms')
            ->inGroup('forms')
            ->register();

        $docs->section('select')
            ->label('Select')
            ->markdown('select.md')
            ->demo()
            ->icon('📜')
            ->package('forms')
            ->inGroup('forms')
            ->register();

        $docs->section('checkbox')
            ->label('Checkbox')
            ->markdown('checkbox.md')
            ->demo()
            ->icon('☑️')
            ->package('forms')
            ->inGroup('forms')
            ->register();

        $docs->section('checkbox-list')
            ->label('Checkbox List')
            ->markdown('checkbox-list.md')
            ->demo()
            ->icon('☑️')
            ->package('forms')
            ->inGroup('forms')
            ->register();

        $docs->section('radio')
            ->label('Radio')
            ->markdown('radio.md')
            ->demo()
            ->icon('🔘')
            ->package('forms')
            ->inGroup('forms')
            ->register();

        $docs->section('toggle-input')
            ->label('Toggle Input')
            ->markdown('toggle.md')
            ->demo()
            ->icon('🔄')
            ->package('forms')
            ->inGroup('forms')
            ->register();

        $docs->section('number-field')
            ->label('Number Field')
            ->markdown('number-field.md')
            ->demo()
            ->icon('🔢')
            ->package('forms')
            ->inGroup('forms')
            ->register();

        $docs->section('slider')
            ->label('Slider')
            ->markdown('slider.md')
            ->demo()
            ->icon('📏')
            ->package('forms')
            ->inGroup('forms')
            ->register();

        $docs->section('date-picker')
            ->label('Date Picker')
            ->markdown('date-picker.md')
            ->demo()
            ->icon('📅')
            ->package('forms')
            ->inGroup('forms')
            ->register();

        $docs->section('time-picker')
            ->label('Time Picker')
            ->markdown('time-picker.md')
            ->demo()
            ->icon('⏰')
            ->package('forms')
            ->inGroup('forms')
            ->register();

        $docs->section('color-picker')
            ->label('Color Picker')
            ->markdown('color-picker.md')
            ->demo()
            ->icon('🎨')
            ->package('forms')
            ->inGroup('forms')
            ->register();

        $docs->section('file-upload')
            ->label('File Upload')
            ->markdown('file-upload.md')
            ->demo()
            ->icon('📤')
            ->package('forms')
            ->inGroup('forms')
            ->register();

        $docs->section('toggle-buttons')
            ->label('Toggle Buttons')
            ->markdown('toggle-buttons.md')
            ->demo()
            ->icon('🔲')
            ->package('forms')
            ->inGroup('forms')
            ->register();

        $docs->section('datetime-picker')
            ->label('DateTime Picker')
            ->markdown('datetime-picker.md')
            ->demo()
            ->icon('📆')
            ->package('forms')
            ->inGroup('forms')
            ->register();

        $docs->section('date-range-picker')
            ->label('Date Range Picker')
            ->markdown('date-range-picker.md')
            ->demo()
            ->icon('📅')
            ->package('forms')
            ->inGroup('forms')
            ->register();
    }

    /**
     * Register advanced form component docs.
     */
    protected function registerFormAdvancedDocs(DocsRegistry $docs): void
    {
        $docs->section('group')
            ->label('Group')
            ->markdown('group.md')
            ->demo()
            ->icon('📦')
            ->package('forms')
            ->inGroup('forms')
            ->register();

        $docs->section('submit')
            ->label('Submit')
            ->markdown('submit.md')
            ->demo()
            ->icon('🚀')
            ->package('forms')
            ->inGroup('forms')
            ->register();

        $docs->section('tags-input')
            ->label('Tags Input')
            ->markdown('tags-input.md')
            ->demo()
            ->icon('🏷️')
            ->package('forms')
            ->inGroup('forms')
            ->register();

        $docs->section('key-value')
            ->label('Key Value')
            ->markdown('key-value.md')
            ->demo()
            ->icon('🔑')
            ->package('forms')
            ->inGroup('forms')
            ->register();

        $docs->section('repeater')
            ->label('Repeater')
            ->markdown('repeater.md')
            ->demo()
            ->icon('🔁')
            ->package('forms')
            ->inGroup('forms')
            ->register();

        $docs->section('rich-editor')
            ->label('Rich Editor')
            ->markdown('rich-editor.md')
            ->demo()
            ->icon('✏️')
            ->package('forms')
            ->inGroup('forms')
            ->register();

        $docs->section('tiptap-editor')
            ->label('TipTap Editor')
            ->markdown('tiptap-editor.md')
            ->demo()
            ->icon('📝')
            ->package('forms')
            ->inGroup('forms')
            ->register();

        $docs->section('markdown-editor')
            ->label('Markdown Editor')
            ->markdown('markdown-editor.md')
            ->demo()
            ->icon('📄')
            ->package('forms')
            ->inGroup('forms')
            ->register();

        $docs->section('pin-input')
            ->label('PIN Input')
            ->markdown('pin-input.md')
            ->demo()
            ->icon('🔐')
            ->package('forms')
            ->inGroup('forms')
            ->register();

        $docs->section('rate-input')
            ->label('Rate Input')
            ->markdown('rate-input.md')
            ->demo()
            ->icon('⭐')
            ->package('forms')
            ->inGroup('forms')
            ->register();

        $docs->section('icon-picker')
            ->label('Icon Picker')
            ->markdown('icon-picker.md')
            ->demo()
            ->icon('😊')
            ->package('forms')
            ->inGroup('forms')
            ->register();

        $docs->section('forms-api')
            ->label('API Reference')
            ->markdown('api-reference.md')
            ->icon('📚')
            ->package('forms')
            ->inGroup('forms')
            ->register();
    }
}
