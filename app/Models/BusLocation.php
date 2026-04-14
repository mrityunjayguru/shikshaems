<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusLocation extends Model
{
    use HasFactory;

    protected $connection = 'school';
    protected $fillable = [
        'device_id',
        'trip_id',
        'device_time',
        'latitude',
        'longitude',
        'speed',
    ];
}
