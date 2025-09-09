<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{

    use HasFactory;

    protected $fillable = ['company_id', 'plate_number', 'model'];

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

     public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
