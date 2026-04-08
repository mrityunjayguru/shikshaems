<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyllabusContent extends Model
{
    use HasFactory;

    protected $fillable = ['syllabus_id', 'title', 'description'];

    public function syllabus()
    {
        return $this->belongsTo(Syllabus::class, 'syllabus_id');
    }
}
