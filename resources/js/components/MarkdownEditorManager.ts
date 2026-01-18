/**
 * Markdown Editor Component Manager
 * Provides a textarea-based markdown editor with toolbar and live preview functionality.
 * Filament-compatible API with edit/preview toggle tabs.
 * GitHub-style markdown rendering with GFM support.
 */

import MarkdownIt from 'markdown-it';

interface MarkdownEditorConfig {
    preview: boolean;
    maxLength: number | null;
    showCharacterCount: boolean;
    direction: 'ltr' | 'rtl';
    fileAttachments: {
        enabled: boolean;
        disk: string;
        directory: string;
        visibility: string;
        maxSize: number;
        acceptedTypes: string[];
    };
}

type EditorMode = 'edit' | 'preview';

export class MarkdownEditorManager {
    private static instances = new WeakMap<HTMLElement, MarkdownEditorManager>();

    private _wrapper: HTMLElement;
    private textarea: HTMLTextAreaElement | null;
    private toolbar: HTMLElement | null;
    private _preview: HTMLElement | null;
    private editTab: HTMLElement | null;
    private previewTab: HTMLElement | null;
    private editPanel: HTMLElement | null;
    private previewPanel: HTMLElement | null;
    private counterElement: HTMLElement | null;
    private hiddenInput: HTMLInputElement | null;
    private config: MarkdownEditorConfig;
    private mode: EditorMode = 'edit';
    private md: MarkdownIt;

    constructor(wrapper: HTMLElement) {
        this._wrapper = wrapper;
        this.textarea = wrapper.querySelector('.markdown-editor-input');
        this.toolbar = wrapper.querySelector('.markdown-editor-toolbar');
        this._preview = wrapper.querySelector('.markdown-editor-preview');
        this.editTab = wrapper.querySelector('[data-tab="edit"]');
        this.previewTab = wrapper.querySelector('[data-tab="preview"]');
        this.editPanel = wrapper.querySelector('.markdown-edit-panel');
        this.previewPanel = wrapper.querySelector('.markdown-preview-panel');
        this.counterElement = wrapper.querySelector('.markdown-counter .current-length');
        this.hiddenInput = wrapper.querySelector('input[type="hidden"]');

        // Parse config from data attribute
        const configStr = wrapper.dataset.config;
        this.config = configStr ? JSON.parse(configStr) : {
            preview: true,
            maxLength: null,
            showCharacterCount: false,
            direction: 'ltr',
            fileAttachments: {
                enabled: true,
                disk: 'public',
                directory: 'attachments',
                visibility: 'public',
                maxSize: 12288,
                acceptedTypes: ['image/png', 'image/jpeg', 'image/gif', 'image/webp'],
            },
        };

        // Initialize markdown-it with GitHub Flavored Markdown settings
        this.md = new MarkdownIt({
            html: true,
            linkify: true,
            typographer: false, // Disable typographer for GFM compatibility
            breaks: true, // GFM-style line breaks
        });

        // Enable strikethrough (~~text~~)
        this.enableStrikethrough();

        // Add custom rendering for task lists
        this.setupTaskListRendering();

        this.init();
    }

    /**
     * Enable strikethrough syntax (~~text~~) like GitHub
     */
    private enableStrikethrough(): void {
        // Add strikethrough parsing inline rule
        this.md.inline.ruler.before('emphasis', 'strikethrough', (state, silent) => {
            const marker = 0x7E; // ~
            const start = state.pos;
            const max = state.posMax;

            if (state.src.charCodeAt(start) !== marker) return false;
            if (start + 1 >= max || state.src.charCodeAt(start + 1) !== marker) return false;

            // Find closing ~~
            let pos = start + 2;
            while (pos < max) {
                if (state.src.charCodeAt(pos) === marker && pos + 1 < max && state.src.charCodeAt(pos + 1) === marker) {
                    if (!silent) {
                        const token = state.push('s_open', 's', 1);
                        token.markup = '~~';

                        const text = state.src.slice(start + 2, pos);
                        const tokenInline = state.push('text', '', 0);
                        tokenInline.content = text;

                        const tokenClose = state.push('s_close', 's', -1);
                        tokenClose.markup = '~~';
                    }
                    state.pos = pos + 2;
                    return true;
                }
                pos++;
            }
            return false;
        });

        // Render strikethrough as <del> (GitHub uses <del>)
        this.md.renderer.rules.s_open = () => '<del>';
        this.md.renderer.rules.s_close = () => '</del>';
    }

    private setupTaskListRendering(): void {
        // Custom renderer for task list checkboxes
        const defaultRender = this.md.renderer.rules.list_item_open || function(tokens, idx, options, _env, self) {
            return self.renderToken(tokens, idx, options);
        };

        this.md.renderer.rules.list_item_open = (tokens, idx, options, env, self) => {
            const token = tokens[idx];
            // Check if this is a task list item
            if (tokens[idx + 2] && tokens[idx + 2].content) {
                const content = tokens[idx + 2].content;
                if (content.startsWith('[ ] ') || content.startsWith('[x] ') || content.startsWith('[X] ')) {
                    token.attrJoin('class', 'task-list-item');
                }
            }
            return defaultRender(tokens, idx, options, env, self);
        };
    }

    private init(): void {
        if (!this.textarea) return;

        // Bind toolbar button events
        this.toolbar?.querySelectorAll('.toolbar-button').forEach((button) => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const action = (button as HTMLElement).dataset.action;
                if (action) {
                    this.executeAction(action);
                }
            });
        });

        // Bind tab switching events
        this.editTab?.addEventListener('click', () => this.switchMode('edit'));
        this.previewTab?.addEventListener('click', () => this.switchMode('preview'));

        // Update counter on input
        this.textarea.addEventListener('input', () => {
            this.updateCounter();
            this.syncHiddenInput();
        });

        // Handle tab key for indentation
        this.textarea.addEventListener('keydown', (e) => this.handleKeydown(e));

        // Handle drag and drop for file attachments
        if (this.config.fileAttachments.enabled) {
            this.setupDragAndDrop();
        }

        // Initial counter update
        this.updateCounter();
        this.syncHiddenInput();
    }

    private switchMode(mode: EditorMode): void {
        if (this.mode === mode) return;
        this.mode = mode;

        if (mode === 'edit') {
            this.editTab?.classList.add('is-active');
            this.previewTab?.classList.remove('is-active');
            this.editPanel?.classList.remove('hidden');
            this.previewPanel?.classList.add('hidden');
            this.toolbar?.classList.remove('opacity-50', 'pointer-events-none');
            this.textarea?.focus();
        } else {
            this.editTab?.classList.remove('is-active');
            this.previewTab?.classList.add('is-active');
            this.editPanel?.classList.add('hidden');
            this.previewPanel?.classList.remove('hidden');
            this.toolbar?.classList.add('opacity-50', 'pointer-events-none');
            this.updatePreview();
        }
    }

    private updatePreview(): void {
        if (!this.previewPanel || !this.textarea) return;

        const markdown = this.textarea.value;
        let html = this.md.render(markdown);

        // Process task list checkboxes
        html = html
            .replace(/\[ \] /g, '<input type="checkbox" disabled class="task-checkbox"> ')
            .replace(/\[x\] /gi, '<input type="checkbox" checked disabled class="task-checkbox"> ');

        // Set the preview content
        const previewContent = this.previewPanel.querySelector('.preview-content');
        if (previewContent) {
            previewContent.innerHTML = html || '<p class="text-neutral-400 dark:text-neutral-500 italic">Nothing to preview</p>';
        } else {
            this.previewPanel.innerHTML = html || '<p class="text-neutral-400 dark:text-neutral-500 italic">Nothing to preview</p>';
        }
    }

    private executeAction(action: string): void {
        if (!this.textarea) return;

        // If in preview mode, switch to edit mode first
        if (this.mode === 'preview') {
            this.switchMode('edit');
        }

        const start = this.textarea.selectionStart;
        const end = this.textarea.selectionEnd;
        const selectedText = this.textarea.value.substring(start, end);
        const beforeText = this.textarea.value.substring(0, start);
        const afterText = this.textarea.value.substring(end);

        let insertText = '';
        let cursorOffset = 0;
        let selectLength = 0;

        switch (action) {
            case 'bold':
                if (selectedText) {
                    insertText = `**${selectedText}**`;
                    cursorOffset = insertText.length;
                } else {
                    insertText = '**bold text**';
                    cursorOffset = 2;
                    selectLength = 9;
                }
                break;

            case 'italic':
                if (selectedText) {
                    insertText = `*${selectedText}*`;
                    cursorOffset = insertText.length;
                } else {
                    insertText = '*italic text*';
                    cursorOffset = 1;
                    selectLength = 11;
                }
                break;

            case 'strike':
            case 'strikethrough':
                if (selectedText) {
                    insertText = `~~${selectedText}~~`;
                    cursorOffset = insertText.length;
                } else {
                    insertText = '~~strikethrough~~';
                    cursorOffset = 2;
                    selectLength = 13;
                }
                break;

            case 'heading':
            case 'h2':
                insertText = this.ensureNewLine(beforeText) + `## ${selectedText || 'Heading'}`;
                cursorOffset = insertText.length;
                break;

            case 'h1':
                insertText = this.ensureNewLine(beforeText) + `# ${selectedText || 'Heading 1'}`;
                cursorOffset = insertText.length;
                break;

            case 'h3':
                insertText = this.ensureNewLine(beforeText) + `### ${selectedText || 'Heading 3'}`;
                cursorOffset = insertText.length;
                break;

            case 'bulletList':
            case 'ul':
                if (selectedText) {
                    insertText = selectedText.split('\n').map(line => `- ${line}`).join('\n');
                } else {
                    insertText = this.ensureNewLine(beforeText) + '- List item';
                }
                cursorOffset = insertText.length;
                break;

            case 'orderedList':
            case 'ol':
                if (selectedText) {
                    insertText = selectedText.split('\n').map((line, i) => `${i + 1}. ${line}`).join('\n');
                } else {
                    insertText = this.ensureNewLine(beforeText) + '1. List item';
                }
                cursorOffset = insertText.length;
                break;

            case 'blockquote':
            case 'quote':
                if (selectedText) {
                    insertText = selectedText.split('\n').map(line => `> ${line}`).join('\n');
                } else {
                    insertText = this.ensureNewLine(beforeText) + '> Quote';
                }
                cursorOffset = insertText.length;
                break;

            case 'codeBlock':
            case 'code':
                if (selectedText && selectedText.includes('\n')) {
                    insertText = `\`\`\`\n${selectedText}\n\`\`\``;
                } else if (selectedText) {
                    insertText = `\`${selectedText}\``;
                } else {
                    insertText = this.ensureNewLine(beforeText) + '```\ncode\n```';
                    cursorOffset = 4;
                }
                cursorOffset = cursorOffset || insertText.length;
                break;

            case 'link':
                if (selectedText.startsWith('http')) {
                    insertText = `[link text](${selectedText})`;
                    cursorOffset = 1;
                    selectLength = 9;
                } else if (selectedText) {
                    insertText = `[${selectedText}](url)`;
                    cursorOffset = insertText.length - 4;
                    selectLength = 3;
                } else {
                    insertText = '[link text](url)';
                    cursorOffset = 1;
                    selectLength = 9;
                }
                break;

            case 'attachFiles':
            case 'image':
                if (selectedText.startsWith('http')) {
                    insertText = `![alt text](${selectedText})`;
                } else {
                    insertText = `![${selectedText || 'alt text'}](image-url)`;
                }
                cursorOffset = 2;
                selectLength = selectedText ? selectedText.length : 8;
                break;

            case 'table':
                insertText = this.ensureNewLine(beforeText) +
                    '| Header 1 | Header 2 | Header 3 |\n' +
                    '|----------|----------|----------|\n' +
                    '| Cell 1   | Cell 2   | Cell 3   |';
                cursorOffset = insertText.length;
                break;

            case 'hr':
            case 'horizontalRule':
                insertText = this.ensureNewLine(beforeText) + '---\n';
                cursorOffset = insertText.length;
                break;

            case 'undo':
                document.execCommand('undo');
                return;

            case 'redo':
                document.execCommand('redo');
                return;

            default:
                return;
        }

        if (insertText) {
            // For items that need newline prefix, handle the insertion differently
            if (insertText.startsWith('\n')) {
                this.textarea.value = beforeText + insertText + afterText;
            } else {
                this.textarea.value = beforeText + insertText + afterText;
            }

            this.textarea.focus();

            const newCursorPos = start + cursorOffset;
            if (selectLength > 0) {
                this.textarea.setSelectionRange(newCursorPos, newCursorPos + selectLength);
            } else {
                this.textarea.setSelectionRange(newCursorPos, newCursorPos);
            }

            this.textarea.dispatchEvent(new Event('input', { bubbles: true }));
        }
    }

    private ensureNewLine(text: string): string {
        if (text.length === 0) return '';
        if (text.endsWith('\n\n')) return '';
        if (text.endsWith('\n')) return '\n';
        return '\n\n';
    }

    private handleKeydown(e: KeyboardEvent): void {
        if (!this.textarea) return;

        // Tab key for indentation
        if (e.key === 'Tab') {
            e.preventDefault();
            const start = this.textarea.selectionStart;
            const end = this.textarea.selectionEnd;

            if (e.shiftKey) {
                // Remove indentation
                const beforeText = this.textarea.value.substring(0, start);
                const lineStart = beforeText.lastIndexOf('\n') + 1;
                const lineText = this.textarea.value.substring(lineStart, end);

                if (lineText.startsWith('  ')) {
                    this.textarea.value =
                        this.textarea.value.substring(0, lineStart) +
                        lineText.substring(2) +
                        this.textarea.value.substring(end);
                    this.textarea.setSelectionRange(Math.max(lineStart, start - 2), Math.max(lineStart, start - 2));
                }
            } else {
                // Add indentation
                this.textarea.value =
                    this.textarea.value.substring(0, start) +
                    '  ' +
                    this.textarea.value.substring(end);
                this.textarea.setSelectionRange(start + 2, start + 2);
            }

            this.textarea.dispatchEvent(new Event('input', { bubbles: true }));
        }

        // Keyboard shortcuts
        if (e.metaKey || e.ctrlKey) {
            switch (e.key.toLowerCase()) {
                case 'b':
                    e.preventDefault();
                    this.executeAction('bold');
                    break;
                case 'i':
                    e.preventDefault();
                    this.executeAction('italic');
                    break;
                case 'k':
                    e.preventDefault();
                    this.executeAction('link');
                    break;
            }
        }
    }

    private setupDragAndDrop(): void {
        if (!this.textarea) return;

        const dropZone = this.editPanel || this.textarea;

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('drag-over');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('drag-over');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('drag-over');

            const files = (e as DragEvent).dataTransfer?.files;
            if (files && files.length > 0) {
                this.handleFileUpload(files[0]);
            }
        });
    }

    private handleFileUpload(file: File): void {
        // Check file type
        if (!this.config.fileAttachments.acceptedTypes.includes(file.type)) {
            console.warn('File type not accepted:', file.type);
            return;
        }

        // Check file size
        if (file.size > this.config.fileAttachments.maxSize * 1024) {
            console.warn('File too large:', file.size);
            return;
        }

        // For now, insert a placeholder. In production, this would upload to server.
        const placeholder = `![Uploading ${file.name}...](uploading)`;
        this.insertAtCursor(placeholder);

        // TODO: Implement actual file upload
        // this.uploadFile(file).then(url => {
        //     const markdown = `![${file.name}](${url})`;
        //     // Replace placeholder with actual markdown
        // });
    }

    private insertAtCursor(text: string): void {
        if (!this.textarea) return;

        const start = this.textarea.selectionStart;
        const end = this.textarea.selectionEnd;

        this.textarea.value =
            this.textarea.value.substring(0, start) +
            text +
            this.textarea.value.substring(end);

        const newPos = start + text.length;
        this.textarea.setSelectionRange(newPos, newPos);
        this.textarea.focus();
        this.textarea.dispatchEvent(new Event('input', { bubbles: true }));
    }

    private updateCounter(): void {
        if (!this.counterElement || !this.textarea) return;

        const length = this.textarea.value.length;
        this.counterElement.textContent = String(length);

        if (this.config.maxLength && length > this.config.maxLength) {
            this.counterElement.classList.add('text-red-500');
            this.counterElement.classList.remove('text-neutral-400', 'dark:text-neutral-500');
        } else {
            this.counterElement.classList.remove('text-red-500');
            this.counterElement.classList.add('text-neutral-400', 'dark:text-neutral-500');
        }
    }

    private syncHiddenInput(): void {
        if (this.hiddenInput && this.textarea) {
            this.hiddenInput.value = this.textarea.value;
        }
    }

    public getValue(): string {
        return this.textarea?.value || '';
    }

    public setValue(markdown: string): void {
        if (this.textarea) {
            this.textarea.value = markdown;
            this.updateCounter();
            this.syncHiddenInput();

            if (this.mode === 'preview') {
                this.updatePreview();
            }
        }
    }

    public clear(): void {
        if (this.textarea) {
            this.textarea.value = '';
            this.updateCounter();
            this.syncHiddenInput();

            if (this.mode === 'preview') {
                this.updatePreview();
            }
        }
    }

    public getMode(): EditorMode {
        return this.mode;
    }

    public static initAll(): void {
        document.querySelectorAll<HTMLElement>('.markdown-editor-wrapper').forEach((wrapper) => {
            if (!wrapper.dataset.initialized) {
                wrapper.dataset.initialized = 'true';
                MarkdownEditorManager.instances.set(wrapper, new MarkdownEditorManager(wrapper));
            }
        });
    }

    public static getInstance(wrapper: HTMLElement): MarkdownEditorManager | undefined {
        return MarkdownEditorManager.instances.get(wrapper);
    }
}
