<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RouteVehicle;
use App\Models\Shift;

class TransportationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'pickup_point_id',
        'user_id',
        'transportation_fee_id',
        'route_vehicle_id',
        'shift_id',
        'status',
        'session_year_id'
    ];

    /**
     * Pickup point relation.
     */
    public function pickupPoint()
    {
        return $this->belongsTo(PickupPoint::class);
    }

    /**
     * User (student/parent) relation.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Transportation fee relation.
     */
    public function transportationFee()
    {
        return $this->belongsTo(TransportationFee::class);
    }

    /**
     * Session year relation.
     */
    public function sessionYear()
    {
        return $this->belongsTo(SessionYear::class);
    }

    public function paymentTransaction()
    {
        return $this->belongsTo(PaymentTransaction::class);
    }

    public function routeVehicle()
    {
        return $this->belongsTo(RouteVehicle::class, 'route_vehicle_id');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'shift_id');
    }
}
