<?php

declare(strict_types=1);

namespace Accelade\Forms\Enums;

/**
 * Available icon sets for the IconPicker component.
 */
enum IconSet: string
{
    case Emoji = 'emoji';
    case Boxicons = 'boxicons';
    case Heroicons = 'heroicons';
    case Lucide = 'lucide';

    /**
     * Get the display label for this icon set.
     */
    public function label(): string
    {
        return match ($this) {
            self::Emoji => 'Emoji',
            self::Boxicons => 'Boxicons',
            self::Heroicons => 'Heroicons',
            self::Lucide => 'Lucide',
        };
    }

    /**
     * Get all available icon sets.
     *
     * @return array<IconSet>
     */
    public static function all(): array
    {
        return [
            self::Emoji,
            self::Boxicons,
            self::Heroicons,
            self::Lucide,
        ];
    }

    /**
     * Get all icon sets as string values.
     *
     * @return array<string>
     */
    public static function values(): array
    {
        return array_map(fn (IconSet $set) => $set->value, self::all());
    }
}
