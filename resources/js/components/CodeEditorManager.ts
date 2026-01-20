/**
 * CodeEditor Manager
 *
 * Manages CodeMirror-based code editors with syntax highlighting.
 */

import { EditorView, keymap, lineNumbers, highlightActiveLine, highlightSpecialChars, drawSelection, dropCursor, rectangularSelection, crosshairCursor } from '@codemirror/view';
import { EditorState, Extension } from '@codemirror/state';
import { defaultHighlightStyle, syntaxHighlighting, indentOnInput, bracketMatching, foldGutter, foldKeymap } from '@codemirror/language';
import { defaultKeymap, history, historyKeymap, indentWithTab } from '@codemirror/commands';
import { searchKeymap, highlightSelectionMatches } from '@codemirror/search';
import { autocompletion, completionKeymap, closeBrackets, closeBracketsKeymap } from '@codemirror/autocomplete';
import { lintKeymap } from '@codemirror/lint';
import { oneDark } from '@codemirror/theme-one-dark';

// Language imports
import { cpp } from '@codemirror/lang-cpp';
import { css } from '@codemirror/lang-css';
import { html } from '@codemirror/lang-html';
import { java } from '@codemirror/lang-java';
import { javascript } from '@codemirror/lang-javascript';
import { json } from '@codemirror/lang-json';
import { markdown } from '@codemirror/lang-markdown';
import { php } from '@codemirror/lang-php';
import { python } from '@codemirror/lang-python';
import { sql } from '@codemirror/lang-sql';
import { xml } from '@codemirror/lang-xml';
import { yaml } from '@codemirror/lang-yaml';
import { rust } from '@codemirror/lang-rust';
import { go } from '@codemirror/lang-go';

interface CodeEditorConfig {
    language: string;
    theme: string;
    lineNumbers: boolean;
    minHeight: number;
    maxHeight: number | null;
    lineWrapping: boolean;
    tabSize: number;
    indentWithTabs: boolean;
    highlightActiveLine: boolean;
    bracketMatching: boolean;
    autoCloseBrackets: boolean;
    foldGutter: boolean;
    readOnly: boolean;
    placeholder: string | null;
}

/**
 * Get language extension by name
 */
function getLanguageExtension(lang: string): Extension | null {
    const langMap: Record<string, () => Extension> = {
        'cpp': () => cpp(),
        'c++': () => cpp(),
        'css': () => css(),
        'html': () => html(),
        'java': () => java(),
        'javascript': () => javascript(),
        'js': () => javascript(),
        'jsx': () => javascript({ jsx: true }),
        'typescript': () => javascript({ typescript: true }),
        'ts': () => javascript({ typescript: true }),
        'tsx': () => javascript({ typescript: true, jsx: true }),
        'json': () => json(),
        'markdown': () => markdown(),
        'md': () => markdown(),
        'php': () => php(),
        'python': () => python(),
        'py': () => python(),
        'sql': () => sql(),
        'xml': () => xml(),
        'yaml': () => yaml(),
        'yml': () => yaml(),
        'rust': () => rust(),
        'go': () => go(),
        'bash': () => javascript(), // Fallback for now
        'shell': () => javascript(), // Fallback for now
    };

    const factory = langMap[lang.toLowerCase()];
    return factory ? factory() : null;
}

export class CodeEditorManager {
    private container: HTMLElement;
    private textarea: HTMLTextAreaElement;
    private mountPoint: HTMLElement;
    private config: CodeEditorConfig;
    private editor: EditorView | null = null;

    constructor(container: HTMLElement) {
        this.container = container;

        const textarea = container.querySelector<HTMLTextAreaElement>('.code-editor-fallback');
        const mountPoint = container.querySelector<HTMLElement>('.code-editor-mount');

        if (!textarea || !mountPoint) {
            throw new Error('CodeEditor: Required elements not found');
        }

        this.textarea = textarea;
        this.mountPoint = mountPoint;
        this.config = this.parseConfig();

        this.init();
    }

    /**
     * Parse configuration from data attribute
     */
    private parseConfig(): CodeEditorConfig {
        const configStr = this.container.dataset.codeEditorConfig || '{}';
        try {
            return JSON.parse(configStr) as CodeEditorConfig;
        } catch {
            return {
                language: 'javascript',
                theme: 'light',
                lineNumbers: true,
                minHeight: 300,
                maxHeight: null,
                lineWrapping: false,
                tabSize: 4,
                indentWithTabs: false,
                highlightActiveLine: true,
                bracketMatching: true,
                autoCloseBrackets: true,
                foldGutter: false,
                readOnly: false,
                placeholder: null,
            };
        }
    }

    /**
     * Initialize the CodeMirror editor
     */
    private init(): void {
        const extensions = this.buildExtensions();
        const initialValue = this.textarea.value || '';

        const state = EditorState.create({
            doc: initialValue,
            extensions,
        });

        this.editor = new EditorView({
            state,
            parent: this.mountPoint,
        });

        // Apply theme class to container
        if (this.config.theme === 'dark') {
            this.container.classList.add('dark-theme');
        }

        // Mark as initialized
        this.container.dataset.initialized = 'true';

        // Set minimum height on the editor
        this.mountPoint.style.minHeight = `${this.config.minHeight}px`;
        if (this.config.maxHeight) {
            this.mountPoint.style.maxHeight = `${this.config.maxHeight}px`;
            this.mountPoint.style.overflowY = 'auto';
        }
    }

    /**
     * Build CodeMirror extensions based on config
     */
    private buildExtensions(): Extension[] {
        const extensions: Extension[] = [];

        // Basic setup extensions
        extensions.push(
            highlightSpecialChars(),
            history(),
            drawSelection(),
            dropCursor(),
            EditorState.allowMultipleSelections.of(true),
            indentOnInput(),
            syntaxHighlighting(defaultHighlightStyle, { fallback: true }),
            rectangularSelection(),
            crosshairCursor(),
            highlightSelectionMatches(),
            keymap.of([
                ...closeBracketsKeymap,
                ...defaultKeymap,
                ...searchKeymap,
                ...historyKeymap,
                ...foldKeymap,
                ...completionKeymap,
                ...lintKeymap,
                indentWithTab,
            ])
        );

        // Line numbers
        if (this.config.lineNumbers) {
            extensions.push(lineNumbers());
        }

        // Active line highlighting
        if (this.config.highlightActiveLine) {
            extensions.push(highlightActiveLine());
        }

        // Bracket matching
        if (this.config.bracketMatching) {
            extensions.push(bracketMatching());
        }

        // Auto-close brackets
        if (this.config.autoCloseBrackets) {
            extensions.push(closeBrackets());
            extensions.push(autocompletion());
        }

        // Fold gutter
        if (this.config.foldGutter) {
            extensions.push(foldGutter());
        }

        // Language support
        const langExtension = getLanguageExtension(this.config.language);
        if (langExtension) {
            extensions.push(langExtension);
        }

        // Theme
        if (this.config.theme === 'dark') {
            extensions.push(oneDark);
        }

        // Read-only mode
        if (this.config.readOnly) {
            extensions.push(EditorState.readOnly.of(true));
        }

        // Tab size
        extensions.push(EditorState.tabSize.of(this.config.tabSize));

        // Line wrapping
        if (this.config.lineWrapping) {
            extensions.push(EditorView.lineWrapping);
        }

        // Update listener to sync with hidden textarea
        extensions.push(
            EditorView.updateListener.of((update) => {
                if (update.docChanged) {
                    this.textarea.value = update.state.doc.toString();
                    // Dispatch input event for frameworks that listen
                    this.textarea.dispatchEvent(new Event('input', { bubbles: true }));
                }
            })
        );

        // Placeholder
        if (this.config.placeholder) {
            extensions.push(
                EditorView.contentAttributes.of({
                    'aria-placeholder': this.config.placeholder,
                })
            );
        }

        return extensions;
    }

    /**
     * Get the current editor value
     */
    getValue(): string {
        return this.editor?.state.doc.toString() || this.textarea.value;
    }

    /**
     * Set the editor value
     */
    setValue(value: string): void {
        if (this.editor) {
            this.editor.dispatch({
                changes: {
                    from: 0,
                    to: this.editor.state.doc.length,
                    insert: value,
                },
            });
        }
        this.textarea.value = value;
    }

    /**
     * Get the CodeMirror EditorView instance
     */
    getEditor(): EditorView | null {
        return this.editor;
    }

    /**
     * Focus the editor
     */
    focus(): void {
        this.editor?.focus();
    }

    /**
     * Destroy the editor
     */
    destroy(): void {
        this.editor?.destroy();
        this.editor = null;
        this.container.dataset.initialized = 'false';
    }

    /**
     * Change the language dynamically
     */
    setLanguage(language: string): void {
        if (!this.editor) return;

        this.config.language = language;
        const currentValue = this.getValue();

        // Recreate the editor with new language
        this.destroy();
        this.init();
        this.setValue(currentValue);
    }

    /**
     * Toggle theme between light and dark
     */
    setTheme(theme: 'light' | 'dark'): void {
        if (!this.editor) return;

        this.config.theme = theme;
        const currentValue = this.getValue();

        // Recreate the editor with new theme
        this.destroy();
        this.init();
        this.setValue(currentValue);
    }

    /**
     * Initialize all code editors on the page
     */
    static initAll(): void {
        document.querySelectorAll<HTMLElement>('[data-code-editor]:not([data-initialized="true"])').forEach((container) => {
            try {
                new CodeEditorManager(container);
            } catch (error) {
                console.error('Failed to initialize CodeEditor:', error);
            }
        });
    }

    /**
     * Get CodeEditorManager instance by container ID
     */
    static getInstance(id: string): CodeEditorManager | null {
        const container = document.querySelector<HTMLElement>(`[data-code-editor-id="${id}"]`);
        if (!container || container.dataset.initialized !== 'true') {
            return null;
        }
        // Note: This returns a new instance - for better instance management,
        // you might want to store instances in a WeakMap
        return new CodeEditorManager(container);
    }
}
