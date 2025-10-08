<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    protected $fillable = [
        'fee_code',
        'student_id',
        'fee_group_id',
        'academic_year_id',
        'semester',
        'status',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function feeGroup()
    {
        return $this->belongsTo(FeeGroup::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }


    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('fee_code', 'like', '%' . $search . '%')
                ->orWhere('semester', 'like', '%' . $search . '%')
                ->orWhere('status', 'like', '%' . $search . '%');
            })
            ->orWhereHas('student', function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
            })
            ->orWhereHas('feeGroup', function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
           })
          ->orWhereHas('academicYear', function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
           });
        });
    }


    public function scopeSorting(Builder $query, array $sorts): void
    {
        $query->when($sorts['field'] ?? null && $sorts['direction'] ?? null, function ($query) use ($sorts) {
            match($sorts['field']) {
                'student_id' => $query->join('students', 'fees.student_id', '=', 'students.id')
                    ->orderBy('students.name', $sorts['direction']),
                'fee_group_id' => $query->join('fee_groups', 'fees.fee_group_id', '=', 'fee_groups.id')
                    ->orderBy('fee_groups.name', $sorts['direction']),
                'academic_year_id' => $query->join('academic_years', 'fees.academic_year_id', '=', 'academic_years.id')
                    ->orderBy('academic_years.name', $sorts['direction']),
                default => $query->orderBy($sorts['field'], $sorts['direction']),
            };
        });
    }
}
