<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'teacher_number',
        'academic_title',
        'user_id',
        'faculty_id',
        'departement_id',
    ];
}
