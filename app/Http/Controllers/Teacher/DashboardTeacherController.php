<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Course;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Inertia\Response;

class DashboardTeacherController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(): Response
    {
        return inertia('Teacher/Dashboard', [
            'page_settings' => [
                'title' => 'Dashboard',
                'subtitle' => 'Menampilkan semua statistik pada platform ini.',
            ],

            'count' => [
                'courses' => Course::query()
                ->where('teacher_id', auth()->user()->teacher->id)
                ->count(),

                'classroom' => ClassRoom::query()
                ->whereHas('schedules.course', fn($query) => $query->where('teacher_id', auth()->user()->teacher->id))
                ->count(),

                'schedules' => Schedule::query()
                ->whereHas('course', fn($query) => $query->where('teacher_id', auth()->user()->teacher->id))
                ->count()
            ],
        ]);
    }
}
