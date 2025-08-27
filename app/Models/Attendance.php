<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'status',
        'section',
        'course_id',
        'student_id',
        'class_room_id',
    ];
}
