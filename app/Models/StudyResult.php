<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyResult extends Model
{
    protected $fillable = [
        'semester',
        'gpa',
        'student_id',
        'academic_year_id',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function studyResultGrades()
    {
        return $this->hasMany(StudyResultGrade::class);
    }

    public function grades()
    {
        return $this->hasMany(StudyResultGrade::class);
    }
}
