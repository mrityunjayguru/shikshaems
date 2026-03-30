<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompulsoryFeeMonth extends Model
{
    use HasFactory;

    protected $fillable = [
        'compulsory_fee_id',
        'month_number',
        'month_name',
        'amount',
        'is_partial',
    ];

    protected $casts = [
        'is_partial' => 'boolean',
    ];

    public function compulsory_fee()
    {
        return $this->belongsTo(CompulsoryFee::class, 'compulsory_fee_id');
    }
}
