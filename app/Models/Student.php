<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'student_number',
        'batch',
        'semester',
        'user_id',
        'faculty_id',
        'departement_id',
        'class_room_id',
        'fee_group_id',
    ];
}
