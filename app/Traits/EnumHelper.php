<?php

namespace App\Traits;

trait EnumHelper
{
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function keys(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function keyValue(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [$case->name => $case->getLabel()])
            ->toArray();
    }

    /**
     * Get all account types as an array.
     */
    public static function toArray(): array
    {
        return self::values();
    }

    /**
     * Get options for a select input.
     */
    public static function toSelectOptions(): array
    {
        return collect(self::cases())
            ->map(fn (self $type) => [
                'label' => $type->getLabel(),
                'value' => $type->value,
            ])
            ->toArray();
    }
}
