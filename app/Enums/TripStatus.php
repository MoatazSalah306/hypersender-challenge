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

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case) => [
                $case->value => ucfirst($case->name),
            ])
            ->toArray(); // to map it to array with keys like : "['scheduled' => 'Scheduled', ...]"
    }
}
