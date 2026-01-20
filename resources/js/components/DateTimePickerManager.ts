/**
 * DateTimePicker Manager
 *
 * Manages Flatpickr date/time picker initialization and functionality.
 */

// Patch CSSStyleSheet methods to prevent SecurityError on cross-origin stylesheets
// This must be done BEFORE importing flatpickr
if (typeof window !== 'undefined' && typeof CSSStyleSheet !== 'undefined') {
    // Patch cssRules getter
    const originalCssRulesDescriptor = Object.getOwnPropertyDescriptor(CSSStyleSheet.prototype, 'cssRules');
    if (originalCssRulesDescriptor) {
        Object.defineProperty(CSSStyleSheet.prototype, 'cssRules', {
            get: function() {
                try {
                    return originalCssRulesDescriptor.get?.call(this);
                } catch {
                    return [];
                }
            },
            configurable: true
        });
    }

    // Patch insertRule method
    const originalInsertRule = CSSStyleSheet.prototype.insertRule;
    CSSStyleSheet.prototype.insertRule = function(rule: string, index?: number) {
        try {
            return originalInsertRule.call(this, rule, index);
        } catch {
            // Silently fail for cross-origin stylesheets
            return 0;
        }
    };

    // Patch deleteRule method
    const originalDeleteRule = CSSStyleSheet.prototype.deleteRule;
    CSSStyleSheet.prototype.deleteRule = function(index: number) {
        try {
            return originalDeleteRule.call(this, index);
        } catch {
            // Silently fail for cross-origin stylesheets
        }
    };
}

import flatpickr from 'flatpickr';
import type { Instance as FlatpickrInstance } from 'flatpickr/dist/types/instance';
import type { Options as FlatpickrOptions } from 'flatpickr/dist/types/options';

// Import Flatpickr CSS
import 'flatpickr/dist/flatpickr.min.css';

// Inject custom light/dark mode CSS that overrides prefers-color-scheme
// This ensures flatpickr follows the page's dark mode class, not OS preference
const injectFlatpickrThemeStyles = (): void => {
    if (document.getElementById('flatpickr-theme-override')) return;

    const style = document.createElement('style');
    style.id = 'flatpickr-theme-override';
    style.textContent = `
        /* CRITICAL: Hide closed calendars - they should only show when 'open' class is present */
        .flatpickr-calendar {
            display: none !important;
            visibility: hidden !important;
        }
        .flatpickr-calendar.open {
            display: block !important;
            visibility: visible !important;
        }
        .flatpickr-calendar.inline {
            display: block !important;
            visibility: visible !important;
        }

        /* FilamentPHP-Style Flatpickr Theme */
        .flatpickr-calendar.open,
        .flatpickr-calendar.inline {
            background: #fff !important;
            border: none !important;
            border-radius: 0.75rem !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
            font-family: inherit !important;
            width: auto !important;
            min-width: 280px !important;
            padding: 1rem !important;
            position: absolute !important;
            z-index: 99999 !important;
        }
        /* Calendar with week numbers needs more width */
        .flatpickr-calendar.hasWeeks.open,
        .flatpickr-calendar.hasWeeks.inline {
            width: auto !important;
            min-width: 320px !important;
        }
        .flatpickr-calendar.inline {
            position: relative !important;
            box-shadow: none !important;
            border: 1px solid #e5e7eb !important;
        }
        .flatpickr-calendar.arrowTop:before,
        .flatpickr-calendar.arrowTop:after,
        .flatpickr-calendar.arrowBottom:before,
        .flatpickr-calendar.arrowBottom:after {
            display: none !important;
        }

        /* Inner wrapper - ensure proper layout */
        .flatpickr-innerContainer {
            display: flex !important;
            flex-direction: row !important;
        }

        /* Month header - clean layout like Filament */
        .flatpickr-months {
            background: transparent !important;
            padding: 0 0 0.75rem 0 !important;
            align-items: center !important;
            display: flex !important;
        }
        .flatpickr-months .flatpickr-month {
            background: transparent !important;
            color: #111827 !important;
            height: auto !important;
            flex: 1 !important;
            overflow: visible !important;
        }
        .flatpickr-current-month {
            padding: 0 !important;
            height: auto !important;
            display: flex !important;
            align-items: center !important;
            gap: 0.25rem !important;
            font-size: 1rem !important;
            left: 0 !important;
            width: 100% !important;
            position: relative !important;
        }
        .flatpickr-current-month .flatpickr-monthDropdown-months {
            color: #111827 !important;
            background: #fff !important;
            font-weight: 600 !important;
            font-size: 1rem !important;
            padding: 0.25rem 0.5rem !important;
            margin: 0 !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 0.375rem !important;
            cursor: pointer !important;
            -webkit-appearance: menulist !important;
            appearance: menulist !important;
        }
        .flatpickr-current-month .flatpickr-monthDropdown-months:hover {
            background: #f9fafb !important;
        }
        .flatpickr-current-month .flatpickr-monthDropdown-months option {
            background: #fff !important;
            color: #111827 !important;
            padding: 0.5rem !important;
        }
        .flatpickr-current-month input.cur-year {
            color: #111827 !important;
            background: #fff !important;
            font-weight: 600 !important;
            font-size: 1rem !important;
            padding: 0.25rem 0.5rem !important;
            margin: 0 0 0 0.5rem !important;
            width: 5rem !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 0.375rem !important;
            cursor: text !important;
        }
        .flatpickr-current-month input.cur-year:hover,
        .flatpickr-current-month input.cur-year:focus {
            background: #f9fafb !important;
            outline: none !important;
        }

        /* Prev/Next month buttons - show them with proper styling */
        .flatpickr-months .flatpickr-prev-month,
        .flatpickr-months .flatpickr-next-month {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            position: static !important;
            padding: 0.25rem !important;
            margin: 0 0.25rem !important;
            border-radius: 0.375rem !important;
            cursor: pointer !important;
            color: #6b7280 !important;
            fill: #6b7280 !important;
        }
        .flatpickr-months .flatpickr-prev-month:hover,
        .flatpickr-months .flatpickr-next-month:hover {
            background: #f3f4f6 !important;
            color: #111827 !important;
            fill: #111827 !important;
        }
        .flatpickr-months .flatpickr-prev-month svg,
        .flatpickr-months .flatpickr-next-month svg {
            width: 14px !important;
            height: 14px !important;
            fill: currentColor !important;
        }

        /* Weekdays */
        .flatpickr-weekdays {
            background: transparent !important;
            height: auto !important;
            padding: 0 0 0.5rem 0 !important;
            display: flex !important;
        }
        .flatpickr-weekdaycontainer {
            display: flex !important;
            flex: 1 !important;
        }
        span.flatpickr-weekday {
            color: #6b7280 !important;
            background: transparent !important;
            display: block !important;
            flex: 1 !important;
            text-align: center !important;
            font-size: 0.75rem !important;
            font-weight: 500 !important;
            line-height: 1 !important;
            padding: 0.25rem 0 !important;
            max-width: 36px !important;
        }

        /* Days container */
        .flatpickr-days {
            background: transparent !important;
            border: none !important;
            display: flex !important;
            width: auto !important;
            flex: 1 !important;
        }
        .flatpickr-days .dayContainer {
            display: flex !important;
            flex-wrap: wrap !important;
            justify-content: flex-start !important;
            width: 252px !important;
            min-width: 252px !important;
            max-width: 252px !important;
            padding: 0 !important;
        }

        /* Individual days - Filament style */
        .flatpickr-day {
            color: #111827 !important;
            background: transparent !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 36px !important;
            max-width: 36px !important;
            height: 36px !important;
            line-height: 36px !important;
            text-align: center !important;
            border-radius: 9999px !important;
            border: none !important;
            box-sizing: border-box !important;
            margin: 0 !important;
            cursor: pointer !important;
            font-size: 0.875rem !important;
            font-weight: 400 !important;
            flex-basis: 36px !important;
            transition: background-color 0.15s ease !important;
        }
        .flatpickr-day:hover {
            background: #f3f4f6 !important;
        }

        /* Today - subtle indicator */
        .flatpickr-day.today {
            font-weight: 600 !important;
            color: #ea580c !important;
        }
        .flatpickr-day.today:hover {
            background: #fff7ed !important;
            color: #ea580c !important;
        }

        /* Selected day - subtle gray background like Filament */
        .flatpickr-day.selected,
        .flatpickr-day.startRange,
        .flatpickr-day.endRange,
        .flatpickr-day.selected.inRange,
        .flatpickr-day.startRange.inRange,
        .flatpickr-day.endRange.inRange,
        .flatpickr-day.selected:focus,
        .flatpickr-day.startRange:focus,
        .flatpickr-day.endRange:focus,
        .flatpickr-day.selected:hover,
        .flatpickr-day.startRange:hover,
        .flatpickr-day.endRange:hover,
        .flatpickr-day.selected.prevMonthDay,
        .flatpickr-day.startRange.prevMonthDay,
        .flatpickr-day.endRange.prevMonthDay,
        .flatpickr-day.selected.nextMonthDay,
        .flatpickr-day.startRange.nextMonthDay,
        .flatpickr-day.endRange.nextMonthDay {
            background: #e5e7eb !important;
            color: #111827 !important;
            font-weight: 500 !important;
        }

        /* Range selection - Creative gradient design */
        .flatpickr-day.inRange {
            background: linear-gradient(135deg, rgba(234, 88, 12, 0.08) 0%, rgba(249, 115, 22, 0.12) 100%) !important;
            color: #92400e !important;
            border-radius: 0 !important;
            box-shadow: inset 0 1px 0 rgba(234, 88, 12, 0.1), inset 0 -1px 0 rgba(234, 88, 12, 0.1) !important;
        }
        .flatpickr-day.inRange:hover {
            background: linear-gradient(135deg, rgba(234, 88, 12, 0.15) 0%, rgba(249, 115, 22, 0.2) 100%) !important;
        }
        .flatpickr-day.startRange {
            background: linear-gradient(135deg, #ea580c 0%, #f97316 100%) !important;
            color: #fff !important;
            font-weight: 600 !important;
            border-radius: 9999px 0 0 9999px !important;
            box-shadow: 0 2px 8px rgba(234, 88, 12, 0.35) !important;
        }
        .flatpickr-day.startRange:hover {
            background: linear-gradient(135deg, #dc2626 0%, #ea580c 100%) !important;
            box-shadow: 0 4px 12px rgba(234, 88, 12, 0.45) !important;
        }
        .flatpickr-day.endRange {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%) !important;
            color: #fff !important;
            font-weight: 600 !important;
            border-radius: 0 9999px 9999px 0 !important;
            box-shadow: 0 2px 8px rgba(234, 88, 12, 0.35) !important;
        }
        .flatpickr-day.endRange:hover {
            background: linear-gradient(135deg, #ea580c 0%, #dc2626 100%) !important;
            box-shadow: 0 4px 12px rgba(234, 88, 12, 0.45) !important;
        }
        .flatpickr-day.startRange.endRange {
            border-radius: 9999px !important;
            background: linear-gradient(135deg, #ea580c 0%, #f97316 100%) !important;
        }
        /* First day in range after start */
        .flatpickr-day.inRange.startRange + .flatpickr-day.inRange {
            border-radius: 0 !important;
        }
        /* Selected range visual connection */
        .flatpickr-day.selected.startRange,
        .flatpickr-day.selected.endRange {
            background: linear-gradient(135deg, #ea580c 0%, #f97316 100%) !important;
            color: #fff !important;
        }

        /* Previous/next month days - very subtle */
        .flatpickr-day.prevMonthDay,
        .flatpickr-day.nextMonthDay {
            color: #d1d5db !important;
        }
        .flatpickr-day.prevMonthDay:hover,
        .flatpickr-day.nextMonthDay:hover {
            background: #f9fafb !important;
            color: #9ca3af !important;
        }

        /* Disabled days */
        .flatpickr-day.flatpickr-disabled,
        .flatpickr-day.flatpickr-disabled:hover {
            color: #e5e7eb !important;
            background: transparent !important;
            cursor: not-allowed !important;
        }
        .flatpickr-day.notAllowed,
        .flatpickr-day.notAllowed.prevMonthDay,
        .flatpickr-day.notAllowed.nextMonthDay {
            color: #e5e7eb !important;
        }

        /* Time picker - Clean minimal style without background */
        .flatpickr-time {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            background: transparent !important;
            border: none !important;
            border-top: 1px solid #e5e7eb !important;
            margin: 0.75rem -1rem -1rem -1rem !important;
            padding: 0.75rem 1rem !important;
            height: auto !important;
            max-height: none !important;
            overflow: visible !important;
            gap: 0.25rem !important;
        }
        .flatpickr-time .numInputWrapper {
            flex: 0 0 auto !important;
            width: 3rem !important;
            height: 2.5rem !important;
            position: relative !important;
        }
        .flatpickr-time input {
            color: #111827 !important;
            background: #f9fafb !important;
            font-size: 1.125rem !important;
            font-weight: 500 !important;
            font-family: inherit !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 0.375rem !important;
            height: 2.5rem !important;
            width: 3rem !important;
            padding: 0 !important;
            text-align: center !important;
            box-shadow: none !important;
            transition: all 0.15s ease !important;
        }
        .flatpickr-time input:hover {
            background: #f3f4f6 !important;
            border-color: #d1d5db !important;
        }
        .flatpickr-time input:focus {
            background: #fff !important;
            border-color: #ea580c !important;
            outline: none !important;
            box-shadow: 0 0 0 2px rgba(234, 88, 12, 0.1) !important;
        }
        .flatpickr-time .flatpickr-time-separator {
            color: #6b7280 !important;
            font-weight: 500 !important;
            font-size: 1.125rem !important;
            line-height: 2.5rem !important;
            width: 1rem !important;
            padding: 0 !important;
            height: 2.5rem !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        .flatpickr-time .flatpickr-am-pm {
            color: #6b7280 !important;
            background: #f9fafb !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 0.375rem !important;
            font-weight: 500 !important;
            font-size: 0.75rem !important;
            text-transform: uppercase !important;
            letter-spacing: 0.025em !important;
            height: 2.5rem !important;
            line-height: 2.5rem !important;
            padding: 0 0.75rem !important;
            margin-left: 0.5rem !important;
            cursor: pointer !important;
            width: auto !important;
            transition: all 0.15s ease !important;
        }
        .flatpickr-time .flatpickr-am-pm:hover {
            background: #f3f4f6 !important;
            border-color: #d1d5db !important;
            color: #111827 !important;
        }
        .flatpickr-time .flatpickr-am-pm.selected,
        .flatpickr-time .flatpickr-am-pm:focus {
            background: #fff !important;
            border-color: #ea580c !important;
            color: #ea580c !important;
        }

        /* DateTime picker input - ensure proper styling */
        .flatpickr-input,
        input.flatpickr-input {
            background-color: #fff !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 0.5rem !important;
            padding: 0.5rem 0.75rem !important;
            font-size: 0.875rem !important;
            color: #111827 !important;
            width: 100% !important;
            transition: border-color 0.15s ease, box-shadow 0.15s ease !important;
        }
        .flatpickr-input:hover,
        input.flatpickr-input:hover {
            border-color: #d1d5db !important;
        }
        .flatpickr-input:focus,
        input.flatpickr-input:focus {
            border-color: #ea580c !important;
            box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.1) !important;
            outline: none !important;
        }
        .flatpickr-input::placeholder,
        input.flatpickr-input::placeholder {
            color: #9ca3af !important;
        }
        /* Dark mode input styling */
        .dark .flatpickr-input,
        .dark input.flatpickr-input {
            background-color: #374151 !important;
            border-color: #4b5563 !important;
            color: #f9fafb !important;
        }
        .dark .flatpickr-input:hover,
        .dark input.flatpickr-input:hover {
            border-color: #6b7280 !important;
        }
        .dark .flatpickr-input:focus,
        .dark input.flatpickr-input:focus {
            border-color: #fb923c !important;
            box-shadow: 0 0 0 3px rgba(251, 146, 60, 0.15) !important;
        }
        .dark .flatpickr-input::placeholder,
        .dark input.flatpickr-input::placeholder {
            color: #6b7280 !important;
        }

        /* Number input wrapper - year up/down arrows */
        .numInputWrapper {
            height: auto !important;
            position: relative !important;
        }
        .numInputWrapper:hover {
            background: transparent !important;
        }
        .numInputWrapper span {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            position: absolute !important;
            right: 0 !important;
            width: 1.5rem !important;
            height: 50% !important;
            cursor: pointer !important;
            opacity: 0 !important;
            background: #f3f4f6 !important;
            border-left: 1px solid #e5e7eb !important;
            transition: opacity 0.15s ease !important;
        }
        .numInputWrapper:hover span {
            opacity: 1 !important;
        }
        .numInputWrapper span.arrowUp {
            top: 0 !important;
            border-radius: 0 0.375rem 0 0 !important;
            border-bottom: 1px solid #e5e7eb !important;
        }
        .numInputWrapper span.arrowDown {
            bottom: 0 !important;
            border-radius: 0 0 0.375rem 0 !important;
        }
        .numInputWrapper span:hover {
            background: #e5e7eb !important;
        }
        .numInputWrapper span:after {
            content: '' !important;
            display: block !important;
            width: 0 !important;
            height: 0 !important;
            border-left: 4px solid transparent !important;
            border-right: 4px solid transparent !important;
        }
        .numInputWrapper span.arrowUp:after {
            border-bottom: 4px solid #6b7280 !important;
        }
        .numInputWrapper span.arrowDown:after {
            border-top: 4px solid #6b7280 !important;
        }

        /* Week wrapper - for calendars with week numbers */
        .flatpickr-weekwrapper {
            display: flex !important;
            flex-direction: column !important;
            padding-right: 0.5rem !important;
            margin-right: 0.5rem !important;
            border-right: 1px solid #e5e7eb !important;
        }
        .flatpickr-weekwrapper .flatpickr-weekday {
            color: #9ca3af !important;
            font-size: 0.75rem !important;
            font-weight: 500 !important;
            height: auto !important;
            line-height: 1 !important;
            padding: 0.25rem 0 0.5rem 0 !important;
        }
        .flatpickr-weekwrapper .flatpickr-weeks {
            display: flex !important;
            flex-direction: column !important;
            background: transparent !important;
            border: none !important;
            padding: 0 !important;
            box-shadow: none !important;
        }
        .flatpickr-weekwrapper span.flatpickr-day,
        .flatpickr-weekwrapper .flatpickr-day {
            color: #9ca3af !important;
            font-size: 0.75rem !important;
            font-weight: 500 !important;
            width: 2rem !important;
            max-width: 2rem !important;
            height: 36px !important;
            line-height: 36px !important;
            flex-basis: auto !important;
            cursor: default !important;
            background: transparent !important;
        }
        .flatpickr-weekwrapper span.flatpickr-day:hover,
        .flatpickr-weekwrapper .flatpickr-day:hover {
            background: transparent !important;
        }

        /* Range mode calendar - proper width */
        .flatpickr-calendar.rangeMode.open {
            width: auto !important;
        }

        /* Dark mode overrides - only when page has .dark class */
        .dark .flatpickr-calendar.open,
        .dark .flatpickr-calendar.inline {
            background: #1f2937 !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.4), 0 8px 10px -6px rgba(0, 0, 0, 0.3) !important;
        }
        .dark .flatpickr-calendar.inline {
            border-color: #374151 !important;
        }
        /* Dark mode - month dropdown */
        .dark .flatpickr-current-month .flatpickr-monthDropdown-months {
            color: #f9fafb !important;
            background: #374151 !important;
            border-color: #4b5563 !important;
        }
        .dark .flatpickr-current-month .flatpickr-monthDropdown-months:hover {
            background: #4b5563 !important;
        }
        .dark .flatpickr-current-month .flatpickr-monthDropdown-months option {
            background: #374151 !important;
            color: #f9fafb !important;
        }
        /* Dark mode - year input */
        .dark .flatpickr-current-month input.cur-year {
            color: #f9fafb !important;
            background: #374151 !important;
            border-color: #4b5563 !important;
        }
        .dark .flatpickr-current-month input.cur-year:hover,
        .dark .flatpickr-current-month input.cur-year:focus {
            background: #4b5563 !important;
        }
        /* Dark mode - prev/next buttons */
        .dark .flatpickr-months .flatpickr-prev-month,
        .dark .flatpickr-months .flatpickr-next-month {
            color: #9ca3af !important;
            fill: #9ca3af !important;
        }
        .dark .flatpickr-months .flatpickr-prev-month:hover,
        .dark .flatpickr-months .flatpickr-next-month:hover {
            background: #374151 !important;
            color: #f9fafb !important;
            fill: #f9fafb !important;
        }
        /* Dark mode - weekdays */
        .dark span.flatpickr-weekday {
            color: #9ca3af !important;
        }
        /* Dark mode - days */
        .dark .flatpickr-day {
            color: #f9fafb !important;
        }
        .dark .flatpickr-day:hover {
            background: #374151 !important;
        }
        .dark .flatpickr-day.today {
            color: #fb923c !important;
        }
        .dark .flatpickr-day.today:hover {
            background: rgba(251, 146, 60, 0.2) !important;
            color: #fb923c !important;
        }
        .dark .flatpickr-day.selected,
        .dark .flatpickr-day.startRange,
        .dark .flatpickr-day.endRange,
        .dark .flatpickr-day.selected.inRange,
        .dark .flatpickr-day.startRange.inRange,
        .dark .flatpickr-day.endRange.inRange,
        .dark .flatpickr-day.selected:focus,
        .dark .flatpickr-day.startRange:focus,
        .dark .flatpickr-day.endRange:focus,
        .dark .flatpickr-day.selected:hover,
        .dark .flatpickr-day.startRange:hover,
        .dark .flatpickr-day.endRange:hover {
            background: #4b5563 !important;
            color: #f9fafb !important;
        }
        .dark .flatpickr-day.inRange {
            background: #374151 !important;
            color: #f9fafb !important;
        }
        .dark .flatpickr-day.prevMonthDay,
        .dark .flatpickr-day.nextMonthDay {
            color: #4b5563 !important;
        }
        .dark .flatpickr-day.prevMonthDay:hover,
        .dark .flatpickr-day.nextMonthDay:hover {
            background: #1f2937 !important;
            color: #6b7280 !important;
        }
        .dark .flatpickr-day.flatpickr-disabled,
        .dark .flatpickr-day.flatpickr-disabled:hover {
            color: #374151 !important;
        }
        .dark .flatpickr-day.notAllowed,
        .dark .flatpickr-day.notAllowed.prevMonthDay,
        .dark .flatpickr-day.notAllowed.nextMonthDay {
            color: #374151 !important;
        }
        /* Dark mode - range selection */
        .dark .flatpickr-day.inRange {
            background: linear-gradient(135deg, rgba(251, 146, 60, 0.15) 0%, rgba(249, 115, 22, 0.2) 100%) !important;
            color: #fdba74 !important;
            box-shadow: inset 0 1px 0 rgba(251, 146, 60, 0.15), inset 0 -1px 0 rgba(251, 146, 60, 0.15) !important;
        }
        .dark .flatpickr-day.inRange:hover {
            background: linear-gradient(135deg, rgba(251, 146, 60, 0.25) 0%, rgba(249, 115, 22, 0.3) 100%) !important;
        }
        .dark .flatpickr-day.startRange,
        .dark .flatpickr-day.endRange {
            background: linear-gradient(135deg, #ea580c 0%, #f97316 100%) !important;
            color: #fff !important;
        }
        .dark .flatpickr-day.startRange:hover,
        .dark .flatpickr-day.endRange:hover {
            background: linear-gradient(135deg, #f97316 0%, #fb923c 100%) !important;
        }
        /* Dark mode - time picker (clean minimal style) */
        .dark .flatpickr-time {
            background: transparent !important;
            border-top-color: #374151 !important;
        }
        .dark .flatpickr-time input {
            color: #f9fafb !important;
            background: #374151 !important;
            border-color: #4b5563 !important;
        }
        .dark .flatpickr-time input:hover {
            background: #4b5563 !important;
            border-color: #6b7280 !important;
        }
        .dark .flatpickr-time input:focus {
            background: #1f2937 !important;
            border-color: #fb923c !important;
            box-shadow: 0 0 0 2px rgba(251, 146, 60, 0.15) !important;
        }
        .dark .flatpickr-time .flatpickr-time-separator {
            color: #9ca3af !important;
        }
        .dark .flatpickr-time .flatpickr-am-pm {
            color: #9ca3af !important;
            background: #374151 !important;
            border-color: #4b5563 !important;
        }
        .dark .flatpickr-time .flatpickr-am-pm:hover {
            background: #4b5563 !important;
            border-color: #6b7280 !important;
            color: #f9fafb !important;
        }
        .dark .flatpickr-time .flatpickr-am-pm.selected,
        .dark .flatpickr-time .flatpickr-am-pm:focus {
            background: #1f2937 !important;
            border-color: #fb923c !important;
            color: #fb923c !important;
        }
        /* Dark mode - year arrows */
        .dark .numInputWrapper span {
            background: #374151 !important;
            border-color: #4b5563 !important;
        }
        .dark .numInputWrapper span:hover {
            background: #4b5563 !important;
        }
        .dark .numInputWrapper span.arrowUp:after {
            border-bottom-color: #9ca3af !important;
        }
        .dark .numInputWrapper span.arrowDown:after {
            border-top-color: #9ca3af !important;
        }
        /* Dark mode - week wrapper */
        .dark .flatpickr-weekwrapper {
            border-right-color: #374151 !important;
        }
        .dark .flatpickr-weekwrapper .flatpickr-weekday {
            color: #6b7280 !important;
        }
        .dark .flatpickr-weekwrapper span.flatpickr-day,
        .dark .flatpickr-weekwrapper .flatpickr-day {
            color: #6b7280 !important;
        }
    `;
    document.head.appendChild(style);
};

// Inject styles immediately
if (typeof document !== 'undefined') {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', injectFlatpickrThemeStyles);
    } else {
        injectFlatpickrThemeStyles();
    }
}

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
