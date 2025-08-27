<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'name',
        'code',
        'credits',
        'semester',
        'faculty_id',
        'departement_id',
        'teacher_id',
        'academic_year_id',
    ];
}
