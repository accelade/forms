/**
 * Accelade Forms - TypeScript Entry Point
 *
 * Provides enhanced functionality for form components.
 * Integrates with Accelade's event system for SPA navigation.
 */

// Component modules
export { PinInputManager } from './components/PinInputManager';
export { TagsInputManager } from './components/TagsInputManager';
export { KeyValueManager } from './components/KeyValueManager';
export { RateInputManager } from './components/RateInputManager';
export { SliderManager } from './components/SliderManager';
export { NumberFieldManager } from './components/NumberFieldManager';
export { IconPickerManager } from './components/IconPickerManager';
export { ColorPickerManager } from './components/ColorPickerManager';
export { RepeaterManager } from './components/RepeaterManager';
export { ToggleManager } from './components/ToggleManager';
export { RichEditorManager } from './components/RichEditorManager';
export { TipTapEditorManager } from './components/TipTapEditorManager';
export { MarkdownEditorManager } from './components/MarkdownEditorManager';
export { EmojiInputManager } from './components/EmojiInputManager';
export { CheckboxListManager } from './components/CheckboxListManager';
export { FileUploadManager } from './components/FileUploadManager';
export { MediaBrowserManager } from './components/MediaBrowserManager';
export { DateTimePickerManager } from './components/DateTimePickerManager';
export { TextareaManager } from './components/TextareaManager';
export { CodeEditorManager } from './components/CodeEditorManager';

// Types
export type { FormsConfig } from './types';

// Main initialization
import { initForms, reinitForms } from './init';

export { initForms, reinitForms };

// Auto-initialize
if (typeof window !== 'undefined') {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initForms);
    } else {
        initForms();
    }

    // Re-initialize on Accelade navigation events
    document.addEventListener('accelade:navigated', reinitForms);
    document.addEventListener('accelade:updated', reinitForms);
}
