<?php

namespace App\Models;

use App\Enums\StudyPlanStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class StudyPlan extends Model
{
    protected $fillable = [
        'student_id',
        'notes',
        'academic_year_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => StudyPlanStatus::class,
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function schedules():BelongsToMany
    {
        return $this->belongsToMany(Schedule::class, 'study_plan_schedule')->withTimestamps();
    }

    public function scopeApproved(Builder $query)
    {
        $query->where('status', StudyPlanStatus::APPROVED->value);
    }


    public function scopePending(Builder $query)
    {
        $query->where('status', StudyPlanStatus::PENDING->value);
    }

    public function scopeRejected(Builder $query)
    {
        $query->where('status', StudyPlanStatus::REJECTED->value);
    }

    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when($filters['search'] ?? null, function($query, $search) {
            $query->where(function($query) use ($search) {
                $query->where('semester', 'like', '%' . $search .'%');
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
                default => $query->orderBy($sorts['field'], $sorts['direction']),
            };
        });
    }
}
