<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GPS extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'g_p_s';

    public function device_type()
    {
        return $this->belongsTo(DeviceType::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'assigned_to');
    }
}
