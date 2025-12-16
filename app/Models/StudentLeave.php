<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentLeave extends Model
{
    use HasFactory;

        protected $fillable = [
            'user_id',
            'from_date',
            'to_date',
            'days',
            'reason',
            'attachment',
            'status',
        ];


    public function scopeOwner()
    {
        if (Auth::user()) {
            return $this->where('school_id', Auth::user()->school_id);
        }
    }

    /**
     * Get the user that owns the Leave
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    // StudentLeave.php
    public function student()
    {
        return $this->belongsTo(Students::class, 'user_id', 'user_id');
    }



}
