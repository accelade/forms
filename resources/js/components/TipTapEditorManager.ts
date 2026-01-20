/**
 * TipTap Editor Component Manager
 *
 * A powerful rich text editor using TipTap with full extension support.
 * Compatible with Filament TipTap Editor API.
 *
 * @see https://tiptap.dev/docs
 * @see https://github.com/awcodes/filament-tiptap-editor
 */

import { Editor } from '@tiptap/core';
import StarterKit from '@tiptap/starter-kit';
import Underline from '@tiptap/extension-underline';
import Link from '@tiptap/extension-link';
import Image from '@tiptap/extension-image';
import TextAlign from '@tiptap/extension-text-align';
import Placeholder from '@tiptap/extension-placeholder';
import CharacterCount from '@tiptap/extension-character-count';
import { Table } from '@tiptap/extension-table';
import { TableRow } from '@tiptap/extension-table-row';
import { TableCell } from '@tiptap/extension-table-cell';
import { TableHeader } from '@tiptap/extension-table-header';
import Highlight from '@tiptap/extension-highlight';
import Subscript from '@tiptap/extension-subscript';
import Superscript from '@tiptap/extension-superscript';
import { TextStyle } from '@tiptap/extension-text-style';
import { Color } from '@tiptap/extension-color';
import BubbleMenu from '@tiptap/extension-bubble-menu';
import FloatingMenu from '@tiptap/extension-floating-menu';

export interface TipTapConfig {
    profile?: string;
    tools?: string[];
    output?: 'html' | 'json' | 'text';
    maxContentWidth?: string;
    floatingMenus?: boolean;
    bubbleMenus?: boolean;
    floatingMenuTools?: string[];
    placeholder?: string;
    nodePlaceholders?: Record<string, string>;
    mergeTags?: string[];
    maxLength?: number;
    showCharacterCount?: boolean;
    direction?: 'ltr' | 'rtl';
    presetColors?: Record<string, string>;
}

export class TipTapEditorManager {
    private static instances = new WeakMap<HTMLElement, TipTapEditorManager>();

    private _wrapper: HTMLElement;
    private editorElement: HTMLElement | null;
    private toolbar: HTMLElement | null;
    private hiddenInput: HTMLInputElement | null;
    private bubbleMenuElement: HTMLElement | null;
    private floatingMenuElement: HTMLElement | null;
    private counterElement: HTMLElement | null;
    private editor: Editor | null = null;
    private config: TipTapConfig;

    constructor(wrapper: HTMLElement) {
        this._wrapper = wrapper;
        this.editorElement = wrapper.querySelector('.tiptap-content');
        this.toolbar = wrapper.querySelector('.tiptap-toolbar');
        this.hiddenInput = wrapper.querySelector('.tiptap-value');
        this.bubbleMenuElement = wrapper.querySelector('.tiptap-bubble-menu');
        this.floatingMenuElement = wrapper.querySelector('.tiptap-floating-menu');
        this.counterElement = wrapper.querySelector('.tiptap-counter');

        // Parse config from data attribute
        const configStr = wrapper.dataset.config;
        this.config = configStr ? JSON.parse(configStr) : {};

        this.init();
    }

    private init(): void {
        if (!this.editorElement) return;

        this.createEditor();
        this.bindToolbarEvents();
    }

    private createEditor(): void {
        if (!this.editorElement) return;

        const extensions = this.buildExtensions();

        this.editor = new Editor({
            element: this.editorElement,
            extensions,
            content: this.hiddenInput?.value || '',
            editorProps: {
                attributes: {
                    class: 'tiptap-editor-content prose prose-sm sm:prose focus:outline-none max-w-none dark:prose-invert',
                    dir: this.config.direction || 'ltr',
                },
            },
            onUpdate: ({ editor }) => {
                this.updateValue(editor);
            },
            onSelectionUpdate: () => {
                this.updateToolbarState();
            },
        });
    }

    private buildExtensions(): any[] {
        const extensions: any[] = [
            StarterKit.configure({
                heading: {
                    levels: [1, 2, 3, 4, 5, 6],
                },
            }),
            Underline,
            Link.configure({
                openOnClick: false,
                HTMLAttributes: {
                    class: 'text-primary-600 underline dark:text-primary-400',
                },
            }),
            Image.configure({
                HTMLAttributes: {
                    class: 'max-w-full h-auto rounded',
                },
            }),
            TextAlign.configure({
                types: ['heading', 'paragraph'],
            }),
            TextStyle,
            Color,
            Highlight.configure({
                multicolor: true,
            }),
            Subscript,
            Superscript,
            Table.configure({
                resizable: true,
                HTMLAttributes: {
                    class: 'border-collapse border border-gray-300 dark:border-gray-600',
                },
            }),
            TableRow,
            TableCell.configure({
                HTMLAttributes: {
                    class: 'border border-gray-300 dark:border-gray-600 p-2',
                },
            }),
            TableHeader.configure({
                HTMLAttributes: {
                    class: 'border border-gray-300 dark:border-gray-600 p-2 bg-gray-50 dark:bg-gray-800 font-semibold',
                },
            }),
        ];

        // Add placeholder
        if (this.config.placeholder) {
            extensions.push(
                Placeholder.configure({
                    placeholder: ({ node }) => {
                        if (this.config.nodePlaceholders && this.config.nodePlaceholders[node.type.name]) {
                            return this.config.nodePlaceholders[node.type.name];
                        }
                        return this.config.placeholder || '';
                    },
                })
            );
        }

        // Add character count
        if (this.config.maxLength || this.config.showCharacterCount) {
            extensions.push(
                CharacterCount.configure({
                    limit: this.config.maxLength || undefined,
                })
            );
        }

        // Add bubble menu
        if (this.config.bubbleMenus !== false && this.bubbleMenuElement) {
            extensions.push(
                BubbleMenu.configure({
                    element: this.bubbleMenuElement,
                    tippyOptions: {
                        duration: 100,
                    },
                })
            );
        }

        // Add floating menu
        if (this.config.floatingMenus !== false && this.floatingMenuElement) {
            extensions.push(
                FloatingMenu.configure({
                    element: this.floatingMenuElement,
                    tippyOptions: {
                        duration: 100,
                    },
                })
            );
        }

        return extensions;
    }

    private bindToolbarEvents(): void {
        this.toolbar?.querySelectorAll('.tiptap-button').forEach((button) => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const action = (button as HTMLElement).dataset.action;
                if (action) {
                    this.executeAction(action);
                }
            });
        });

        // Bind bubble menu buttons
        this.bubbleMenuElement?.querySelectorAll('.tiptap-button').forEach((button) => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const action = (button as HTMLElement).dataset.action;
                if (action) {
                    this.executeAction(action);
                }
            });
        });

        // Bind floating menu buttons
        this.floatingMenuElement?.querySelectorAll('.tiptap-button').forEach((button) => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const action = (button as HTMLElement).dataset.action;
                if (action) {
                    this.executeAction(action);
                }
            });
        });
    }

    private executeAction(action: string): void {
        if (!this.editor) return;

        const chain = this.editor.chain().focus();

        switch (action) {
            // Text formatting
            case 'bold':
                chain.toggleBold().run();
                break;
            case 'italic':
                chain.toggleItalic().run();
                break;
            case 'underline':
                chain.toggleUnderline().run();
                break;
            case 'strike':
                chain.toggleStrike().run();
                break;
            case 'subscript':
                chain.toggleSubscript().run();
                break;
            case 'superscript':
                chain.toggleSuperscript().run();
                break;
            case 'highlight':
                chain.toggleHighlight().run();
                break;
            case 'code':
                chain.toggleCode().run();
                break;

            // Headings
            case 'heading':
            case 'h1':
                chain.toggleHeading({ level: 1 }).run();
                break;
            case 'h2':
                chain.toggleHeading({ level: 2 }).run();
                break;
            case 'h3':
                chain.toggleHeading({ level: 3 }).run();
                break;
            case 'h4':
                chain.toggleHeading({ level: 4 }).run();
                break;
            case 'h5':
                chain.toggleHeading({ level: 5 }).run();
                break;
            case 'h6':
                chain.toggleHeading({ level: 6 }).run();
                break;
            case 'paragraph':
                chain.setParagraph().run();
                break;

            // Lists
            case 'bulletList':
                chain.toggleBulletList().run();
                break;
            case 'orderedList':
                chain.toggleOrderedList().run();
                break;

            // Block elements
            case 'blockquote':
                chain.toggleBlockquote().run();
                break;
            case 'codeBlock':
                chain.toggleCodeBlock().run();
                break;
            case 'horizontalRule':
                chain.setHorizontalRule().run();
                break;

            // Alignment
            case 'alignLeft':
                chain.setTextAlign('left').run();
                break;
            case 'alignCenter':
                chain.setTextAlign('center').run();
                break;
            case 'alignRight':
                chain.setTextAlign('right').run();
                break;
            case 'alignJustify':
                chain.setTextAlign('justify').run();
                break;

            // Links
            case 'link':
                this.insertLink();
                break;
            case 'unlink':
                chain.unsetLink().run();
                break;

            // Media
            case 'media':
            case 'image':
                this.insertImage();
                break;

            // Table
            case 'table':
                this.insertTable();
                break;
            case 'deleteTable':
                chain.deleteTable().run();
                break;
            case 'addColumnBefore':
                chain.addColumnBefore().run();
                break;
            case 'addColumnAfter':
                chain.addColumnAfter().run();
                break;
            case 'deleteColumn':
                chain.deleteColumn().run();
                break;
            case 'addRowBefore':
                chain.addRowBefore().run();
                break;
            case 'addRowAfter':
                chain.addRowAfter().run();
                break;
            case 'deleteRow':
                chain.deleteRow().run();
                break;
            case 'mergeCells':
                chain.mergeCells().run();
                break;
            case 'splitCell':
                chain.splitCell().run();
                break;
            case 'toggleHeaderRow':
                chain.toggleHeaderRow().run();
                break;
            case 'toggleHeaderColumn':
                chain.toggleHeaderColumn().run();
                break;

            // History
            case 'undo':
                chain.undo().run();
                break;
            case 'redo':
                chain.redo().run();
                break;

            // Clear formatting
            case 'clearFormatting':
                chain.unsetAllMarks().clearNodes().run();
                break;

            default:
                console.warn(`TipTap: Unknown action "${action}"`);
        }

        this.updateToolbarState();
    }

    private insertLink(): void {
        if (!this.editor) return;

        const previousUrl = this.editor.getAttributes('link').href;
        const url = prompt('Enter URL:', previousUrl || 'https://');

        if (url === null) return;

        if (url === '') {
            this.editor.chain().focus().unsetLink().run();
            return;
        }

        this.editor
            .chain()
            .focus()
            .extendMarkRange('link')
            .setLink({ href: url })
            .run();
    }

    private insertImage(): void {
        if (!this.editor) return;

        const url = prompt('Enter image URL:', 'https://');

        if (url && url !== 'https://') {
            this.editor.chain().focus().setImage({ src: url }).run();
        }
    }

    private insertTable(): void {
        if (!this.editor) return;

        const rows = parseInt(prompt('Number of rows:', '3') || '3', 10);
        const cols = parseInt(prompt('Number of columns:', '3') || '3', 10);

        if (rows > 0 && cols > 0) {
            this.editor
                .chain()
                .focus()
                .insertTable({ rows, cols, withHeaderRow: true })
                .run();
        }
    }

    private updateToolbarState(): void {
        if (!this.editor || !this.toolbar) return;

        this.toolbar.querySelectorAll('.tiptap-button').forEach((button) => {
            const action = (button as HTMLElement).dataset.action;
            if (!action) return;

            const isActive = this.isActionActive(action);
            button.classList.toggle('is-active', isActive);
        });

        this.updateCharacterCount();
    }

    private isActionActive(action: string): boolean {
        if (!this.editor) return false;

        switch (action) {
            case 'bold':
                return this.editor.isActive('bold');
            case 'italic':
                return this.editor.isActive('italic');
            case 'underline':
                return this.editor.isActive('underline');
            case 'strike':
                return this.editor.isActive('strike');
            case 'subscript':
                return this.editor.isActive('subscript');
            case 'superscript':
                return this.editor.isActive('superscript');
            case 'highlight':
                return this.editor.isActive('highlight');
            case 'code':
                return this.editor.isActive('code');
            case 'h1':
            case 'heading':
                return this.editor.isActive('heading', { level: 1 });
            case 'h2':
                return this.editor.isActive('heading', { level: 2 });
            case 'h3':
                return this.editor.isActive('heading', { level: 3 });
            case 'h4':
                return this.editor.isActive('heading', { level: 4 });
            case 'h5':
                return this.editor.isActive('heading', { level: 5 });
            case 'h6':
                return this.editor.isActive('heading', { level: 6 });
            case 'bulletList':
                return this.editor.isActive('bulletList');
            case 'orderedList':
                return this.editor.isActive('orderedList');
            case 'blockquote':
                return this.editor.isActive('blockquote');
            case 'codeBlock':
                return this.editor.isActive('codeBlock');
            case 'link':
                return this.editor.isActive('link');
            case 'alignLeft':
                return this.editor.isActive({ textAlign: 'left' });
            case 'alignCenter':
                return this.editor.isActive({ textAlign: 'center' });
            case 'alignRight':
                return this.editor.isActive({ textAlign: 'right' });
            case 'alignJustify':
                return this.editor.isActive({ textAlign: 'justify' });
            default:
                return false;
        }
    }

    private updateCharacterCount(): void {
        if (!this.editor || !this.counterElement) return;

        const count = this.editor.storage.characterCount?.characters() || 0;
        const currentEl = this.counterElement.querySelector('.current-length');
        if (currentEl) {
            currentEl.textContent = String(count);
        }

        if (this.config.maxLength && count > this.config.maxLength) {
            this.counterElement.classList.add('text-red-500');
        } else {
            this.counterElement.classList.remove('text-red-500');
        }
    }

    private updateValue(editor: Editor): void {
        if (!this.hiddenInput) return;

        let value: string;

        switch (this.config.output) {
            case 'json':
                value = JSON.stringify(editor.getJSON());
                break;
            case 'text':
                value = editor.getText();
                break;
            case 'html':
            default:
                value = editor.getHTML();
                break;
        }

        this.hiddenInput.value = value;
        this.hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));

        this.updateCharacterCount();
    }

    public getEditor(): Editor | null {
        return this.editor;
    }

    public getValue(): string {
        return this.hiddenInput?.value || '';
    }

    public setValue(content: string): void {
        if (this.editor) {
            this.editor.commands.setContent(content);
        }
    }

    public clear(): void {
        if (this.editor) {
            this.editor.commands.clearContent();
        }
    }

    public destroy(): void {
        if (this.editor) {
            this.editor.destroy();
            this.editor = null;
        }
    }

    public static initAll(): void {
        document.querySelectorAll<HTMLElement>('.tiptap-wrapper').forEach((wrapper) => {
            if (!wrapper.dataset.initialized) {
                wrapper.dataset.initialized = 'true';
                TipTapEditorManager.instances.set(wrapper, new TipTapEditorManager(wrapper));
            }
        });
    }

    public static getInstance(wrapper: HTMLElement): TipTapEditorManager | undefined {
        return TipTapEditorManager.instances.get(wrapper);
    }
}
