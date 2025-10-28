<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Http\Resources\Operator\StudyResultOperatorResource;
use App\Models\Student;
use App\Models\StudyResult;
use Illuminate\Http\Request;
use Inertia\Response;

class StudyResultOperatorController extends Controller
{
    public function __invoke(Student $student): Response
    {
        $studyResults = StudyResult::query()
        ->select(['id', 'student_id', 'academic_year_id', 'gpa', 'semester', 'created_at'])
        ->where('student_id', $student->id)
        ->with(['student', 'academicYear', 'grades'])
        ->paginate(request()->load ?? 10);

        return inertia('Operator/Students/StudyResults/Index', [
            'page_settings' => [
                'title' => 'Kartu Hasil Studi',
                'subtitle' => 'Menampilkan daftar kartu hasil studi mahasiswa'
            ],
            'studyResults' => StudyResultOperatorResource::collection($studyResults)->additional([
                'meta' => [
                    'has_pages' => $studyResults->hasPages(),
                ],
            ]),

            'student' => $student,

            'state' => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => 10,
            ],
        ]);
    }
}
