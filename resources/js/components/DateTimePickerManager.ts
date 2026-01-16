/**
 * DateTimePicker Manager
 *
 * Manages Flatpickr date/time picker initialization and functionality.
 */

import flatpickr from 'flatpickr';
import type { Instance as FlatpickrInstance, Options as FlatpickrOptions } from 'flatpickr/dist/types/instance';

// Import Flatpickr CSS
import 'flatpickr/dist/flatpickr.min.css';

interface DateTimePickerConfig extends Partial<FlatpickrOptions> {
    enableTime?: boolean;
    noCalendar?: boolean;
    enableSeconds?: boolean;
    time_24hr?: boolean;
    dateFormat?: string;
    altInput?: boolean;
    altFormat?: string;
    inline?: boolean;
    weekNumbers?: boolean;
    minDate?: string;
    maxDate?: string;
    minTime?: string;
    maxTime?: string;
    minuteIncrement?: number;
    hourIncrement?: number;
    mode?: 'single' | 'multiple' | 'range';
    disable?: (string | Date | { from: string; to: string })[];
    enable?: (string | Date | { from: string; to: string })[];
    locale?: {
        firstDayOfWeek?: number;
    };
}

export class DateTimePickerManager {
    private container: HTMLElement;
    private input: HTMLInputElement;
    private config: DateTimePickerConfig;
    private flatpickrInstance: FlatpickrInstance | null = null;

    /**
     * Initialize all datetime picker fields
     */
    static initAll(): void {
        const fields = document.querySelectorAll<HTMLElement>('[data-flatpickr]:not([data-flatpickr-initialized])');

        fields.forEach((container) => {
            try {
                new DateTimePickerManager(container);
                container.dataset.flatpickrInitialized = 'true';
            } catch (error) {
                // Silent fail - errors can be debugged by checking the DOM
            }
        });
    }

    constructor(container: HTMLElement) {
        this.container = container;
        this.config = this.parseConfig();

        // Find the input element
        const input = container.querySelector<HTMLInputElement>('.flatpickr-input, input[type="text"]');
        if (!input) {
            throw new Error('DateTimePickerManager: No input element found');
        }
        this.input = input;

        this.init();
    }

    /**
     * Parse configuration from data attribute
     */
    private parseConfig(): DateTimePickerConfig {
        const configAttr = this.container.dataset.flatpickr;
        if (!configAttr) {
            return {};
        }

        try {
            return JSON.parse(configAttr);
        } catch {
            return {};
        }
    }

    /**
     * Initialize Flatpickr
     */
    private init(): void {
        const options: FlatpickrOptions = {
            enableTime: this.config.enableTime ?? false,
            noCalendar: this.config.noCalendar ?? false,
            enableSeconds: this.config.enableSeconds ?? false,
            time_24hr: this.config.time_24hr ?? true,
            dateFormat: this.config.dateFormat ?? 'Y-m-d',
            inline: this.config.inline ?? false,
            weekNumbers: this.config.weekNumbers ?? false,
            allowInput: true,
            clickOpens: true,
            // Theme customization
            disableMobile: false,
        };

        // Alt input for display format
        if (this.config.altInput && this.config.altFormat) {
            options.altInput = true;
            options.altFormat = this.config.altFormat;
        }

        // Min/Max date
        if (this.config.minDate) {
            options.minDate = this.config.minDate;
        }
        if (this.config.maxDate) {
            options.maxDate = this.config.maxDate;
        }

        // Min/Max time
        if (this.config.minTime) {
            options.minTime = this.config.minTime;
        }
        if (this.config.maxTime) {
            options.maxTime = this.config.maxTime;
        }

        // Time increments
        if (this.config.minuteIncrement) {
            options.minuteIncrement = this.config.minuteIncrement;
        }
        if (this.config.hourIncrement) {
            options.hourIncrement = this.config.hourIncrement;
        }

        // Mode (single, multiple, range)
        if (this.config.mode) {
            options.mode = this.config.mode;
        }

        // Disabled dates
        if (this.config.disable && this.config.disable.length > 0) {
            options.disable = this.config.disable;
        }

        // Enabled dates
        if (this.config.enable && this.config.enable.length > 0) {
            options.enable = this.config.enable;
        }

        // Locale settings
        if (this.config.locale) {
            options.locale = this.config.locale;
        }

        // Create Flatpickr instance
        this.flatpickrInstance = flatpickr(this.input, options);

        // Setup toggle button if exists
        this.setupToggleButton();

        // Setup clear functionality
        this.setupClearButton();
    }

    /**
     * Setup toggle button to open calendar
     */
    private setupToggleButton(): void {
        const toggleBtn = this.container.querySelector<HTMLButtonElement>('.datetime-picker-toggle');
        if (toggleBtn && this.flatpickrInstance) {
            toggleBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.flatpickrInstance?.toggle();
            });
        }
    }

    /**
     * Setup clear button if present
     */
    private setupClearButton(): void {
        const clearBtn = this.container.querySelector<HTMLButtonElement>('.datetime-picker-clear');
        if (clearBtn && this.flatpickrInstance) {
            clearBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.flatpickrInstance?.clear();
            });
        }
    }

    /**
     * Get the Flatpickr instance
     */
    getInstance(): FlatpickrInstance | null {
        return this.flatpickrInstance;
    }

    /**
     * Set the date
     */
    setDate(date: string | Date | (string | Date)[]): void {
        this.flatpickrInstance?.setDate(date, true);
    }

    /**
     * Clear the date
     */
    clear(): void {
        this.flatpickrInstance?.clear();
    }

    /**
     * Open the calendar
     */
    open(): void {
        this.flatpickrInstance?.open();
    }

    /**
     * Close the calendar
     */
    close(): void {
        this.flatpickrInstance?.close();
    }

    /**
     * Destroy the Flatpickr instance
     */
    destroy(): void {
        this.flatpickrInstance?.destroy();
        this.flatpickrInstance = null;
    }
}

// Export for global access
if (typeof window !== 'undefined') {
    (window as any).DateTimePickerManager = DateTimePickerManager;
}
