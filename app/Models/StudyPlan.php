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
        'course_id',
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
}
