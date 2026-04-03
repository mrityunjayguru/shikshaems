<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Traits\DateFormatTrait;


class FeesClassType extends Model
{
    use HasFactory, DateFormatTrait;

    protected $fillable = [
        'class_id',
        'fees_id',
        'fees_class_id',
        'fees_type_id',
        'amount',
        'optional',
        'number_of_months',
        'applicable_months',
        'school_id',
        'deleted_at'
    ];

    protected $casts = [
        'applicable_months' => 'array',
    ];
    protected $appends = ['fees_type_name'];


    public function fees_type()
    {
        return $this->belongsTo(FeesType::class, 'fees_type_id')->withTrashed();
    }

    public function class()
    {
        return $this->belongsTo(ClassSchool::class, 'class_id')->with('medium')->withTrashed();
    }

    public function scopeOwner($query)
    {
        if (Auth::user()) {
            if (Auth::user()->hasRole('Super Admin')) {
                return $query;
            }

            if (Auth::user()->hasRole('School Admin') || Auth::user()->hasRole('Teacher')) {
                return $query->where('school_id', Auth::user()->school_id);
            }

            if (Auth::user()->hasRole('Student')) {
                return $query->where('school_id', Auth::user()->school_id);
            }
        }

        return $query;
    }

    public function optional_fees_paid()
    {
        return $this->hasMany(OptionalFee::class, 'fees_class_id')->withTrashed();
    }

    public function compulsory_fee_months()
    {
        return $this->hasMany(CompulsoryFeeMonth::class, 'compulsory_fee_id');
    }

    public function getFeesTypeNameAttribute()
    {
        if ($this->relationLoaded('fees_type')) {
            return $this->fees_type->name;
        }
    }

    protected function setDueDateAttribute($value)
    {
        $this->attributes['due_date'] = $this->formatDateValue($value);
    }

    public function getCreatedAtAttribute()
    {
        return $this->formatDateValue($this->getRawOriginal('created_at'));
    }

    public function getUpdatedAtAttribute()
    {
        return $this->formatDateValue($this->getRawOriginal('updated_at'));
    }
}
