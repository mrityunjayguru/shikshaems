<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleType extends Model
{
    // use HasFactory;
    use SoftDeletes;
    protected $connection = 'mysql';
    protected $fillable = [
        'vehicle_type',
        'vehicle_icon'
    ];
}
