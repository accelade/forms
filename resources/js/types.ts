/**
 * Forms type definitions
 */

export interface FormsConfig {
    /** Auto-initialize components on page load */
    autoInit: boolean;

    /** Selector prefix for form components */
    selectorPrefix: string;

    /** Default animation duration in ms */
    animationDuration: number;
}

export interface BaseComponentOptions {
    element: HTMLElement;
    name?: string;
}

export interface PinInputOptions extends BaseComponentOptions {
    length: number;
    mask: boolean;
    otp: boolean;
    type: 'numeric' | 'alpha' | 'alphanumeric';
}

export interface TagsInputOptions extends BaseComponentOptions {
    separator: string;
    maxTags: number;
    suggestions: string[];
}

export interface RateInputOptions extends BaseComponentOptions {
    max: number;
    allowHalf: boolean;
    clearable: boolean;
    color?: string;
}

export interface SliderOptions extends BaseComponentOptions {
    min: number;
    max: number;
    step: number;
    showValue: boolean;
    range: boolean;
}

export interface NumberFieldOptions extends BaseComponentOptions {
    min?: number;
    max?: number;
    step: number;
}

export interface IconPickerOptions extends BaseComponentOptions {
    icons: string[];
    searchable: boolean;
    multiple: boolean;
    maxItems?: number;
    gridColumns: number;
}

export interface KeyValueOptions extends BaseComponentOptions {
    addable: boolean;
    deletable: boolean;
    reorderable: boolean;
}

export interface RepeaterOptions extends BaseComponentOptions {
    minItems: number;
    maxItems: number;
    collapsible: boolean;
    cloneable: boolean;
    reorderable: boolean;
}
