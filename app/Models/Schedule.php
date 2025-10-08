<?php

namespace App\Models;

use App\Enums\ScheduleDay;
use Illuminate\Database\Eloquent\Builder;
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
        'class_room_id',
        'academic_year_id',
    ];

    protected function casts(): array
    {
        return [
            'day_of_week' => ScheduleDay::class,
        ];
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function studyPlans()
    {
        return $this->hasManyThrough(StudyPlan::class, Course::class)->withTimestamps();
    }


    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function($query) use ($search) {
                $query->where('start_time', 'like', '%' . $search . '%')
                    ->orWhere('end_time', 'like', '%' . $search . '%')
                    ->orWhere('day_of_week', 'like', '%' . $search . '%')
                    ->orWhere('quota', 'like', '%' . $search . '%');
            })
            ->orWhereHas('faculty', function($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->orWhereHas('departement', function($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->orWhereHas('course', function($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->orWhereHas('classroom', function($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('slug', 'like', '%' . $search . '%');
            })
            ->orWhereHas('academicYear', function($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
        });
    }


    public function scopeSorting(Builder $query, array $sorts): void
    {
        $query->when($sorts['field'] ?? null && $sorts['direction'] ?? null, function($query) use ($sorts) {
            match($sorts['field']) {
                'faculty_id' => $query->join('faculties', 'schedules.faculty_id', '=', 'faculties.id')
                ->orderBy('faculties.name', $sorts['direction']),
                'departement_id' => $query->join('departements', 'schedules.departement_id', '=', 'departements.id')
                ->orderBy('departements.name', $sorts['direction']),
                'course_id' => $query->join('courses', 'schedules.course_id', '=', 'courses.id')
                ->orderBy('courses.name', $sorts['direction']),
                'class_room_id' => $query->join('class_rooms', 'schedules.class_room_id', '=', 'class_rooms.id')
                ->orderBy('class_rooms.name', $sorts['direction']),
                'academic_year_id' => $query->join('academic_years', 'schedules.academic_year_id', '=', 'academic_years.id')
                ->orderBy('academic_years.name', $sorts['direction']),
                default => $query->orderBy($sorts['field'], $sorts['direction']),
            };
        });
    }
}
