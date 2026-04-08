<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Syllabus extends Model
{
    use HasFactory;

    protected $table = 'syllabus';
    protected $fillable = [
        'class_id',
        'subject_id'
    ];

    public function class()
    {
        return $this->belongsTo(ClassSchool::class)->withTrashed();
    }
    
    public function subject()
    {
        return $this->belongsTo(Subject::class)->withTrashed();
    }

    public function contents()
    {
        return $this->hasMany(SyllabusContent::class, 'syllabus_id');
    }
}
