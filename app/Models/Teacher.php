<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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


    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function($query) use ($search) {
                $query->where('teacher_number', 'like', '%' . $search . '%')
                    ->orWhere('academic_title', 'like', '%' . $search . '%');
            })
            ->orWhereHas('user', function($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            })
            ->orWhereHas('faculty', function($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->orWhereHas('departement', function($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
        });
    }


    public function scopeSorting(Builder $query, array $sorts): void
    {
        $query->when($sorts['field'] ?? null && $sorts['direction'] ?? null, function($query) use ($sorts) {
            match($sorts['field']) {
                'faculty_id' => $query->join('faculties', 'teachers.faculty_id', '=', 'faculties.id')
                ->orderBy('faculties.name', $sorts['direction']),

                'departement_id' => $query->join('departements', 'teachers.departement_id', '=', 'departements.id')
                ->orderBy('departements.name', $sorts['direction']),

                'name' => $query->join('users', 'teachers.user_id', '=', 'users.id')
                ->orderBy('users.name', $sorts['direction']),

                'email' => $query->join('users', 'teachers.user_id', '=', 'users.id')
                ->orderBy('users.email', $sorts['direction']),

                default => $query->orderBy($sorts['field'], $sorts['direction']),
            };
        });
    }
}
