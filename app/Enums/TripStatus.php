<?php

namespace App\Enums;

enum TripStatus : string
{
    case Scheduled = 'scheduled';
    case Active = 'active';
    case Completed = 'completed';

    /**
     * Get all enum values as array of strings.
     */
    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
