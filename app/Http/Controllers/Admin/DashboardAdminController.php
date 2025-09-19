<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Course;
use App\Models\Departement;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Inertia\Response;

class DashboardAdminController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(): Response
    {
        return inertia('Admin/Dashboard', [
            'page_settings' => [
                'title' => 'Dashboard',
                'subtitle' => 'Menampilkan semua statistik pada platform ini.',
            ],

            'count' => [
                'faculty' => Faculty::count(),
                'departement' => Departement::count(),
                'classroom' => ClassRoom::count(),
                'course' => Course::count(),
            ]
        ]);
    }
}
