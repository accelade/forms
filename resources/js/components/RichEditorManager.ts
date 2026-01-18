/**
 * Rich Editor Component Manager
 * A simple contenteditable-based rich text editor with toolbar functionality
 * Compatible with Filament's RichEditor API
 */

export class RichEditorManager {
    private static instances = new WeakMap<HTMLElement, RichEditorManager>();

    private _wrapper: HTMLElement;
    private editor: HTMLElement | null;
    private toolbar: HTMLElement | null;
    private hiddenInput: HTMLInputElement | null;
    private maxLength: number | null;
    private counterElement: HTMLElement | null;
    private savedRange: Range | null = null;

    constructor(wrapper: HTMLElement) {
        this._wrapper = wrapper;
        this.editor = wrapper.querySelector('.rich-editor-content');
        this.toolbar = wrapper.querySelector('.rich-editor-toolbar');
        this.hiddenInput = wrapper.querySelector('.rich-editor-value');
        this.maxLength = this.editor?.dataset.maxLength ? parseInt(this.editor.dataset.maxLength, 10) : null;
        this.counterElement = wrapper.parentElement?.querySelector('.rich-editor-counter .current-length') || null;

        this.init();
    }

    private init(): void {
        if (!this.editor) return;

        this.bindToolbarEvents();
        this.bindEditorEvents();
        this.setupPlaceholder();
        this.updateValue();
    }

    private bindToolbarEvents(): void {
        this.toolbar?.querySelectorAll('.toolbar-button').forEach((button) => {
            // Prevent focus loss on mousedown
            button.addEventListener('mousedown', (e) => {
                e.preventDefault();
            });

            button.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                const action = (button as HTMLElement).dataset.action;
                if (action) {
                    this.executeAction(action);
                }
            });
        });
    }

    private bindEditorEvents(): void {
        if (!this.editor) return;

        // Save selection frequently
        this.editor.addEventListener('keyup', () => this.saveSelection());
        this.editor.addEventListener('mouseup', () => this.saveSelection());

        // Save selection on blur
        this.editor.addEventListener('blur', () => {
            this.saveSelection();
            this.updateValue();
        });

        // Update hidden input on content change
        this.editor.addEventListener('input', () => this.updateValue());

        // Handle paste
        this.editor.addEventListener('paste', (e) => this.handlePaste(e as ClipboardEvent));

        // Track selection changes
        document.addEventListener('selectionchange', () => {
            if (this.editor && document.activeElement === this.editor) {
                this.saveSelection();
            }
        });
    }

    private saveSelection(): void {
        const selection = window.getSelection();
        if (selection && selection.rangeCount > 0) {
            const range = selection.getRangeAt(0);
            if (this.editor?.contains(range.commonAncestorContainer)) {
                this.savedRange = range.cloneRange();
            }
        }
    }

    private restoreSelection(): void {
        if (this.savedRange && this.editor) {
            const selection = window.getSelection();
            if (selection) {
                selection.removeAllRanges();
                selection.addRange(this.savedRange);
            }
        }
    }

    private ensureFocusAndSelection(): void {
        if (!this.editor) return;

        // Focus the editor
        this.editor.focus();

        // Restore saved selection or create one
        if (this.savedRange) {
            this.restoreSelection();
        } else {
            // If editor is empty, ensure there's a paragraph to work with
            if (this.editor.innerHTML.trim() === '' || this.editor.innerHTML === '<br>') {
                this.editor.innerHTML = '<p><br></p>';
            }

            // Place cursor at the end
            const selection = window.getSelection();
            if (selection) {
                const range = document.createRange();
                range.selectNodeContents(this.editor);
                range.collapse(false);
                selection.removeAllRanges();
                selection.addRange(range);
                this.savedRange = range.cloneRange();
            }
        }
    }

    private setupPlaceholder(): void {
        if (!this.editor) return;

        const placeholder = this.editor.dataset.placeholder;
        if (!placeholder) return;

        const updatePlaceholder = () => {
            const isEmpty = !this.editor?.textContent?.trim();
            if (isEmpty) {
                this.editor?.classList.add('is-empty');
            } else {
                this.editor?.classList.remove('is-empty');
            }
        };

        this.editor.addEventListener('input', updatePlaceholder);
        this.editor.addEventListener('focus', updatePlaceholder);
        this.editor.addEventListener('blur', updatePlaceholder);
        updatePlaceholder();
    }

    private executeAction(action: string): void {
        this.ensureFocusAndSelection();

        switch (action) {
            // Text formatting
            case 'bold':
                document.execCommand('bold', false);
                break;
            case 'italic':
                document.execCommand('italic', false);
                break;
            case 'underline':
                document.execCommand('underline', false);
                break;
            case 'strike':
            case 'strikethrough':
                document.execCommand('strikeThrough', false);
                break;
            case 'subscript':
                document.execCommand('subscript', false);
                break;
            case 'superscript':
                document.execCommand('superscript', false);
                break;

            // Headings - use formatBlock with proper tag names
            case 'h1':
                document.execCommand('formatBlock', false, 'h1');
                break;
            case 'h2':
                document.execCommand('formatBlock', false, 'h2');
                break;
            case 'h3':
                document.execCommand('formatBlock', false, 'h3');
                break;
            case 'h4':
                document.execCommand('formatBlock', false, 'h4');
                break;
            case 'h5':
                document.execCommand('formatBlock', false, 'h5');
                break;
            case 'h6':
                document.execCommand('formatBlock', false, 'h6');
                break;
            case 'paragraph':
            case 'p':
                document.execCommand('formatBlock', false, 'p');
                break;

            // Lists
            case 'bulletList':
            case 'ul':
                document.execCommand('insertUnorderedList', false);
                break;
            case 'orderedList':
            case 'ol':
                document.execCommand('insertOrderedList', false);
                break;

            // Block elements
            case 'blockquote':
            case 'quote':
                document.execCommand('formatBlock', false, 'blockquote');
                break;
            case 'codeBlock':
            case 'code':
                document.execCommand('formatBlock', false, 'pre');
                break;
            case 'horizontalRule':
            case 'hr':
                document.execCommand('insertHorizontalRule', false);
                break;

            // Links
            case 'link':
                this.insertLink();
                break;
            case 'unlink':
                document.execCommand('unlink', false);
                break;

            // Media
            case 'image':
            case 'media':
            case 'attachFiles':
                this.insertImage();
                break;
            case 'table':
                this.insertTable();
                break;

            // Alignment
            case 'alignStart':
            case 'alignLeft':
                document.execCommand('justifyLeft', false);
                break;
            case 'alignCenter':
                document.execCommand('justifyCenter', false);
                break;
            case 'alignEnd':
            case 'alignRight':
                document.execCommand('justifyRight', false);
                break;
            case 'alignJustify':
                document.execCommand('justifyFull', false);
                break;

            // Indentation
            case 'indent':
                document.execCommand('indent', false);
                break;
            case 'outdent':
                document.execCommand('outdent', false);
                break;

            // History
            case 'undo':
                document.execCommand('undo', false);
                break;
            case 'redo':
                document.execCommand('redo', false);
                break;

            // Formatting
            case 'clearFormatting':
            case 'clear':
                document.execCommand('removeFormat', false);
                break;

            // Additional tools
            case 'highlight':
                this.toggleHighlight();
                break;

            default:
                console.warn(`RichEditor: Unknown action "${action}"`);
                break;
        }

        this.updateValue();
        this.saveSelection();
    }

    private toggleHighlight(): void {
        const selection = window.getSelection();
        if (!selection || selection.rangeCount === 0) return;

        const range = selection.getRangeAt(0);
        const selectedText = range.toString();

        if (selectedText) {
            const mark = document.createElement('mark');
            mark.className = 'bg-yellow-200 dark:bg-yellow-800';
            try {
                range.surroundContents(mark);
            } catch (e) {
                // If surroundContents fails (partial selection), use insertHTML
                document.execCommand('insertHTML', false, `<mark class="bg-yellow-200 dark:bg-yellow-800">${selectedText}</mark>`);
            }
        }
    }

    private insertLink(): void {
        const selection = window.getSelection();
        const selectedText = selection?.toString() || '';

        const url = prompt('Enter URL:', 'https://');
        if (url && url !== 'https://') {
            if (selectedText) {
                document.execCommand('createLink', false, url);
            } else {
                const linkText = prompt('Enter link text:', 'Link');
                if (linkText) {
                    document.execCommand('insertHTML', false, `<a href="${url}">${linkText}</a>`);
                }
            }
        }
    }

    private insertImage(): void {
        const url = prompt('Enter image URL:', 'https://');
        if (url && url !== 'https://') {
            document.execCommand('insertImage', false, url);
        }
    }

    private insertTable(): void {
        const rows = parseInt(prompt('Number of rows:', '3') || '3', 10);
        const cols = parseInt(prompt('Number of columns:', '3') || '3', 10);

        if (rows > 0 && cols > 0) {
            let table = '<table class="border-collapse border border-gray-300 dark:border-gray-600 w-full my-4">';
            for (let i = 0; i < rows; i++) {
                table += '<tr>';
                for (let j = 0; j < cols; j++) {
                    table += '<td class="border border-gray-300 dark:border-gray-600 p-2">&nbsp;</td>';
                }
                table += '</tr>';
            }
            table += '</table>';

            document.execCommand('insertHTML', false, table);
        }
    }

    private handlePaste(_e: ClipboardEvent): void {
        // Allow default paste behavior
        // Could add option to strip formatting here
    }

    private updateValue(): void {
        if (!this.hiddenInput || !this.editor) return;

        this.hiddenInput.value = this.editor.innerHTML;
        this.hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));

        // Update character counter
        if (this.counterElement) {
            const length = this.editor.textContent?.length || 0;
            this.counterElement.textContent = String(length);

            if (this.maxLength && length > this.maxLength) {
                this.counterElement.classList.add('text-red-500');
            } else {
                this.counterElement.classList.remove('text-red-500');
            }
        }
    }

    public getValue(): string {
        return this.hiddenInput?.value || '';
    }

    public setValue(html: string): void {
        if (this.editor) {
            this.editor.innerHTML = html;
            this.updateValue();
        }
    }

    public clear(): void {
        if (this.editor) {
            this.editor.innerHTML = '';
            this.updateValue();
        }
    }

    public static initAll(): void {
        document.querySelectorAll<HTMLElement>('.rich-editor-wrapper').forEach((wrapper) => {
            if (!wrapper.dataset.initialized) {
                wrapper.dataset.initialized = 'true';
                RichEditorManager.instances.set(wrapper, new RichEditorManager(wrapper));
            }
        });
    }

    public static getInstance(wrapper: HTMLElement): RichEditorManager | undefined {
        return RichEditorManager.instances.get(wrapper);
    }
}
