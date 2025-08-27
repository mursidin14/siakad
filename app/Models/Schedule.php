<?php

namespace App\Models;

use App\Enums\ScheduleDay;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'start_time',
        'end_time',
        'day_of_week',
        'quota',
        'faculty_id',
        'departement_id',
        'course_id',
        'classroom_id',
        'academic_year_id',
    ];

    protected function casts(): array
    {
        return [
            'day_of_week' => ScheduleDay::class,
        ];
    }
}
