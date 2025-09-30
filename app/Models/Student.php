<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class);
    }

    public function feeGroup()
    {
        return $this->belongsTo(FeeGroup::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function studyPlans()
    {
        return $this->hasMany(StudyPlan::class);
    }

    public function studyResults()
    {
        return $this->hasMany(StudyResult::class);
    }


    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when($filters['search'] ?? null, function($query, $search) {
            $query->whereAny([
            'student_number',
            'batch',
            'semester',
        ], 'REGEXP', $search)
        ->orWhereHas('user', fn($query) => $query->where('name', 'email', 'REGEXP', $search))
        ->orWhereHas('faculty', fn($query) => $query->where('name', 'REGEXP', $search))
        ->orWhereHas('departement', fn($query) => $query->where('name', 'REGEXP', $search));
        });
    }


    public function scopeSorting(Builder $query, array $sorts): void
    {
        $query->when($sorts['field'] ?? null && $sorts['direction'] ?? null, function($query) use ($sorts) {
            match($sorts['field']) {
                'faculty_id' => $query->select('faculties.*')->join('faculties', 'students.faculty_id', '=', 'faculties.id')
                ->orderBy('users.name', $sorts['direction']),
                'departement_id' => $query->select('departements.*')->join('departements', 'students.departement_id', '=', 'departements.id')
                ->orderBy('users.name', $sorts['direction']),
                'name' => $query->select('users.*')->join('users', 'students.user_id', '=', 'users.id')
                ->orderBy('users.name', $sorts['direction']),
                'email' => $query->select('users.*')->join('users', 'students.user_id', '=', 'users.id')
                ->orderBy('users.email', $sorts['direction']),
                'fee_group_id' => $query->select('fee_groups.*')->join('fee_groups', 'students.fee_group_id', '=', 'fee_groups.id')
                ->orderBy('fee_groups.group', $sorts['direction']),
                'classroom_id' => $query->select('class_rooms.*')->join('class_rooms', 'students.class_room_id', '=', 'class_rooms.id')
                ->orderBy('class_rooms.name', $sorts['direction']),
                default => $query->orderBy($sorts['field'], $sorts['direction']),
            };
        });
    }

}
