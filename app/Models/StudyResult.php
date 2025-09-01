<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyResult extends Model
{
    protected $fillable = [
        'semester',
        'gpa',
        'student_id',
        'academic_year_id',
    ];
}
