<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = [
        'grade',
        'section',
        'course_id',
        'student_id',
        'class_room_id',
    ];
}
