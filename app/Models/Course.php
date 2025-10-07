<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }


    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when($filters['search'] ?? null, function($query, $search) {
            $query->where(function($query) use ($search) {
                $query->where('courses.name', 'like', '%' . $search . '%')
                    ->orWhere('courses.code', 'like', '%' . $search . '%')
                    ->orWhere('courses.credits', 'like', '%' . $search . '%')
                    ->orWhere('courses.semester', 'like', '%' . $search . '%');
            })

            ->orWhereHas('faculty', function($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })

            ->orWhereHas('departement', function($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })

            ->orWhereHas('teacher.user', function($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
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
                'faculty_id' => $query->join('faculties', 'courses.faculty_id', '=', 'faculties.id')
                ->orderBy('faculties.name', $sorts['direction']),

                'departement_id' => $query->join('departements', 'courses.departement_id', '=', 'departements.id')
                ->orderBy('departements.name', $sorts['direction']),

                'teacher_id' => $query->join('teachers', 'courses.teacher_id', '=', 'teachers.id')
                ->join('users', 'teachers.user_id', '=', 'users.id')
                ->orderBy('users.name', $sorts['direction']),

                'academic_year_id' => $query->join('academic_years', 'courses.academic_year_id', '=', 'academic_years.id')
                ->orderBy('academic_years.name', $sorts['direction']),

                default => $query->orderBy($sorts['field'], $sorts['direction']),
            };
        });
    }
}
