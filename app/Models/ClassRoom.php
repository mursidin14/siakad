<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'department_id',
        'academic_year_id',
        'faculty_id',
    ];
}
