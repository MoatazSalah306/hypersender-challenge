<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = ['company_id', 'driver_id', 'vehicle_id', 'start_time', 'end_time', 'status', 'description'];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($trip) {
            if (!$trip->end_time) {
                $trip->end_time = $trip->start_time->addHours(2);
            }

            // Overlap check
            $overlaps = self::where('driver_id', $trip->driver_id)
                ->where('vehicle_id', $trip->vehicle_id)
                ->where('id', '!=', $trip->id ?? 0) // Exclude self for updates
                ->where(function ($q) use ($trip) {
                    $q->where('start_time', '<', $trip->end_time)
                        ->where('end_time', '>', $trip->start_time);
                })
                ->exists();

            if ($overlaps) {
                throw new \Exception('Driver or vehicle is already booked for overlapping time.');
            }
        });
    }

    public function scopeActiveNow($query)
    {
        return $query->where('start_time', '<=', now())
            ->where('end_time', '>=', now());
    }

    public function scopeOverlapping($query, $start, $end)
    {
        return $query->where('start_time', '<', $end)
            ->where('end_time', '>', $start);
    }

    public function scopeCompletedThisMonth($query)
    {
        return $query->whereMonth('end_time', now()->month)
            ->whereYear('end_time', now()->year)
            ->where('status', 'completed');
    }
}
