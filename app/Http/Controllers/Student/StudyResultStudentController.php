<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\Student\StudyResultStudentResource;
use App\Models\StudyResult;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Response;

class StudyResultStudentController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new Middleware('checkActiveAcademicYear'),
            new Middleware('checkFeeStudent')
        ];
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        $studyResults = StudyResult::query()
        ->select(['id', 'student_id', 'academic_year_id', 'gpa', 'semester', 'created_at'])
        ->where('student_id', auth()->user()->student->id)
        ->with(['academicYear', 'grades'])
        ->paginate(request()->load ?? 10);

        return inertia('Student/StudyResults/Index', [
            'page_settings' => [
                'title' => 'Kartu Hasil Studi',
                'subtitle' => 'Menampilkan data kartu hasil studi mahasiswa'
            ],

            'studyResults' => StudyResultStudentResource::collection($studyResults)->additional([
                'meta' => [
                    'has_pages' => $studyResults->hasPages()
                ]
            ]),

            'state' => [
                'page' => request()->page ?? 1,
                'load' => 10,
            ],

        ]);
    }
}
