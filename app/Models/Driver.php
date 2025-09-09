<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = ['company_id', 'name', 'license_number'];

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

     public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
