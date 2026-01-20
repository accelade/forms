/**
 * Forms initialization
 */

// IMPORTANT: DateTimePickerManager must be imported FIRST
// It patches CSSStyleSheet methods to prevent SecurityError on cross-origin stylesheets
import { DateTimePickerManager } from './components/DateTimePickerManager';

// Then import FileUploadManager (has its own style injection)
import { FileUploadManager } from './components/FileUploadManager';

// Other managers
import { PinInputManager } from './components/PinInputManager';
import { TagsInputManager } from './components/TagsInputManager';
import { KeyValueManager } from './components/KeyValueManager';
import { RateInputManager } from './components/RateInputManager';
import { SliderManager } from './components/SliderManager';
import { NumberFieldManager } from './components/NumberFieldManager';
import { IconPickerManager } from './components/IconPickerManager';
import { ColorPickerManager } from './components/ColorPickerManager';
import { RepeaterManager } from './components/RepeaterManager';
import { ToggleManager } from './components/ToggleManager';
import { RichEditorManager } from './components/RichEditorManager';
import { TipTapEditorManager } from './components/TipTapEditorManager';
import { MarkdownEditorManager } from './components/MarkdownEditorManager';
import { EmojiInputManager } from './components/EmojiInputManager';
import { SelectManager } from './components/SelectManager';
import { CheckboxListManager } from './components/CheckboxListManager';
import { MediaBrowserManager } from './components/MediaBrowserManager';
import { TextareaManager } from './components/TextareaManager';
import { GoogleMapPickerManager } from './components/GoogleMapPickerManager';
import { MapboxPickerManager } from './components/MapboxPickerManager';
import { CodeEditorManager } from './components/CodeEditorManager';

/**
 * Initialize all form components
 */
export function initForms(): void {
    PinInputManager.initAll();
    TagsInputManager.initAll();
    KeyValueManager.initAll();
    RateInputManager.initAll();
    SliderManager.initAll();
    NumberFieldManager.initAll();
    IconPickerManager.initAll();
    ColorPickerManager.initAll();
    RepeaterManager.initAll();
    ToggleManager.initAll();
    RichEditorManager.initAll();
    TipTapEditorManager.initAll();
    MarkdownEditorManager.initAll();
    EmojiInputManager.initAll();
    SelectManager.initAll();
    CheckboxListManager.initAll();
    FileUploadManager.initAll();
    MediaBrowserManager.initAll();
    DateTimePickerManager.initAll();
    TextareaManager.initAll();
    GoogleMapPickerManager.initAll();
    MapboxPickerManager.initAll();
    CodeEditorManager.initAll();
}

/**
 * Re-initialize forms (for SPA navigation)
 */
export function reinitForms(): void {
    // Re-init all components that haven't been initialized
    initForms();
}

/**
 * Expose forms API globally
 */
if (typeof window !== 'undefined') {
    (window as any).AcceladeForms = {
        init: initForms,
        reinit: reinitForms,
        PinInput: PinInputManager,
        TagsInput: TagsInputManager,
        KeyValue: KeyValueManager,
        RateInput: RateInputManager,
        Slider: SliderManager,
        NumberField: NumberFieldManager,
        IconPicker: IconPickerManager,
        ColorPicker: ColorPickerManager,
        Repeater: RepeaterManager,
        Toggle: ToggleManager,
        RichEditor: RichEditorManager,
        TipTapEditor: TipTapEditorManager,
        MarkdownEditor: MarkdownEditorManager,
        EmojiInput: EmojiInputManager,
        Select: SelectManager,
        CheckboxList: CheckboxListManager,
        FileUpload: FileUploadManager,
        MediaBrowser: MediaBrowserManager,
        DateTimePicker: DateTimePickerManager,
        Textarea: TextareaManager,
        GoogleMapPicker: GoogleMapPickerManager,
        MapboxPicker: MapboxPickerManager,
        CodeEditor: CodeEditorManager,
    };
}
