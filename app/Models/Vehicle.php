<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Jobs\IndexVehicleToElasticsearch;

class Vehicle extends Model
{
    protected $fillable = [
        'plate',
        'make',
        'model',
        'daily_rate',
    ];

    protected static function booted()
    {
        static::created(fn($vehicle) => dispatch(new IndexVehicleToElasticsearch($vehicle)));
        static::updated(fn($vehicle) => dispatch(new IndexVehicleToElasticsearch($vehicle)));
    }
    
}

