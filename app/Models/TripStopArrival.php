<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripStopArrival extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'stop_id',
        'arrival_time',
        'departure_time',
        'students_boarded'
    ];

    protected $casts = [
        'arrival_time' => 'datetime',
        'departure_time' => 'datetime'
    ];

    /**
     * Get the trip
     */
    public function trip()
    {
        return $this->belongsTo(RouteVehicleHistory::class, 'trip_id');
    }

    /**
     * Get the stop
     */
    public function stop()
    {
        return $this->belongsTo(RoutePickupPoint::class, 'stop_id');
    }
}
