/**
 * Textarea Component Manager
 * Handles auto-resize functionality for textareas
 */

export class TextareaManager {
    private static instances = new WeakMap<HTMLTextAreaElement, TextareaManager>();

    private textarea: HTMLTextAreaElement;
    private minHeight: number;
    private borderTop: number;
    private borderBottom: number;
    private resizeTimeout: ReturnType<typeof setTimeout> | null = null;

    constructor(textarea: HTMLTextAreaElement) {
        this.textarea = textarea;

        // Store original styles
        const computedStyle = window.getComputedStyle(textarea);
        this.minHeight = textarea.offsetHeight;
        this.borderTop = parseInt(computedStyle.borderTopWidth, 10) || 0;
        this.borderBottom = parseInt(computedStyle.borderBottomWidth, 10) || 0;

        this.init();
    }

    private init(): void {
        // Set initial styles for autosize
        this.textarea.style.overflow = 'hidden';
        this.textarea.style.resize = 'none';
        this.textarea.style.boxSizing = 'border-box';

        // Initial resize
        this.resize();

        // Resize on input
        this.textarea.addEventListener('input', () => this.resize());

        // Resize on window resize (in case of responsive layouts)
        window.addEventListener('resize', () => {
            if (this.resizeTimeout) {
                clearTimeout(this.resizeTimeout);
            }
            this.resizeTimeout = setTimeout(() => this.resize(), 100);
        });

        // Handle programmatic value changes
        const descriptor = Object.getOwnPropertyDescriptor(HTMLTextAreaElement.prototype, 'value');
        if (descriptor && descriptor.set && descriptor.get) {
            const self = this;
            Object.defineProperty(this.textarea, 'value', {
                get: function() {
                    return descriptor.get!.call(this);
                },
                set: function(val: string) {
                    descriptor.set!.call(this, val);
                    self.resize();
                }
            });
        }

        // Store resize function for external use
        (this.textarea as any)._autosizeResize = () => this.resize();
    }

    private resize(): void {
        // Reset height to auto to get the correct scrollHeight
        this.textarea.style.height = 'auto';
        // Calculate new height
        const newHeight = this.textarea.scrollHeight + this.borderTop + this.borderBottom;
        // Apply minimum height
        this.textarea.style.height = Math.max(this.minHeight, newHeight) + 'px';
    }

    /**
     * Force a resize (useful after content changes)
     */
    public forceResize(): void {
        this.resize();
    }

    /**
     * Get the current instance for a textarea
     */
    public static getInstance(textarea: HTMLTextAreaElement): TextareaManager | undefined {
        return TextareaManager.instances.get(textarea);
    }

    /**
     * Initialize all autosize textareas
     */
    public static initAll(): void {
        document.querySelectorAll<HTMLTextAreaElement>('textarea[data-autosize]:not([data-autosize-initialized])').forEach((textarea) => {
            if (!TextareaManager.instances.has(textarea)) {
                textarea.dataset.autosizeInitialized = 'true';
                TextareaManager.instances.set(textarea, new TextareaManager(textarea));
            }
        });
    }

    /**
     * Initialize a single textarea
     */
    public static init(textarea: HTMLTextAreaElement): TextareaManager {
        let instance = TextareaManager.instances.get(textarea);
        if (!instance) {
            textarea.dataset.autosizeInitialized = 'true';
            instance = new TextareaManager(textarea);
            TextareaManager.instances.set(textarea, instance);
        }
        return instance;
    }
}

export default TextareaManager;
