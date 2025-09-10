<?php

namespace App\Models;

use App\Exceptions\OverlappingTripException;
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


    public static function hasOverlap(array $data, ?int $ignoreId = null): bool
    {
        return self::where(function ($query) use ($data) {
                $query->where('driver_id', $data['driver_id'])
                      ->orWhere('vehicle_id', $data['vehicle_id']);
            })
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->where(function ($q) use ($data) {
                $q->where('start_time', '<', $data['end_time'])
                  ->where('end_time', '>', $data['start_time']);
            })
            ->exists();
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
