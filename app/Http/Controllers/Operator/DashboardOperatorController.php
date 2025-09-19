<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Course;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Inertia\Response;

class DashboardOperatorController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(): Response
    {
        return inertia('Operator/Dashboard', [
            'page_settings' => [
                'title' => 'Dashboard',
                'subtitle' => 'Menampilkan semua statistik pada platform ini.',
            ],

            'count' => [
                'students' => Student::query()
                ->where('faculty_id', auth()->user()->operator->faculty_id)
                ->where('departement_id', auth()->user()->operator->departement_id)
                ->count(),

                'teachers' => Teacher::query()
                ->where('faculty_id', auth()->user()->operator->faculty_id)
                ->where('departement_id', auth()->user()->operator->departement_id)
                ->count(),

                'classrooms' => ClassRoom::query()
                ->where('faculty_id', auth()->user()->operator->faculty_id)
                ->where('departement_id', auth()->user()->operator->departement_id)
                ->count(),

                'courses' => Course::query()
                ->where('faculty_id', auth()->user()->operator->faculty_id)
                ->where('departement_id', auth()->user()->operator->departement_id)
                ->count(),
            ]
        ]);
    }
}
