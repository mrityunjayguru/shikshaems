<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'vehicle_number',
        'vehicle_type_id',
        'capacity',
        'status',
        'is_device',
        'iemi'
    ];

    public function routeVehicles()
    {
        return $this->hasMany(RouteVehicle::class, 'vehicle_id');
    }
}
