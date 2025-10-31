<?php

namespace App\Http\Controllers\Student;

use App\Enums\MessageType;
use App\Enums\StudyPlanStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StudyPlanRequest;
use App\Http\Resources\Admin\ScheduleResource;
use App\Http\Resources\Student\StudyPlanResource;
use App\Http\Resources\Student\StudyPlanScheduleResource;
use App\Models\Schedule;
use App\Models\StudyPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Inertia\Response;
use Throwable;

class StudyPlanController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('checkActiveAcademicYear'),
            new Middleware('checkFeeStudent')
        ];
    }

    public function index(): Response
    {
        $studyPlans = StudyPlan::query()
        ->select(['id', 'student_id', 'academic_year_id', 'status', 'created_at'])
        ->where('student_id', auth()->user()->student->id)
        ->with(['academicYear'])
        ->latest('created_at')
        ->paginate(request()->load ?? 10);

        return inertia('Student/StudyPlans/Index', [
            'page_settings' => [
                'title' => 'Kartu Rencana Studi',
                'subtitle' => 'Menampilkan daftar kartu rencana yang ada.'
            ],

            'studyPlans' => StudyPlanResource::collection($studyPlans)->additional([
                'meta' => [
                    'has_pages' => $studyPlans->hasPages(),
                ],
            ]),

            'state' => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => 10,
            ],
        ]);
    }


    public function create(): Response | RedirectResponse
    {
        $activeAcademicYear = activeAcademicYear(); 

        if (!$activeAcademicYear) { 
            return back();
        }


        $schedules = Schedule::query()
        ->where('faculty_id', auth()->user()->student->faculty_id)
        ->where('departement_id', auth()->user()->student->departement_id)
        ->where('academic_year_id', $activeAcademicYear->id)
        ->with(['course', 'classRoom'])
        ->withCount(['studyPlans as taken_quota' => fn($query) => $query->where('academic_year_id', $activeAcademicYear->id)])
        ->orderByDesc('day_of_week')
        ->get();


        if ($schedules->isEmpty()) {
            flashMessage('Tidak ada jadwal tersedia...', 'warning');
            return to_route('student.study-plans.index');
        }


        $studyPlans = StudyPlan::query()
        ->where('student_id', auth()->user()->student->id)
        ->where('academic_year_id', $activeAcademicYear->id)
        ->where('semester', auth()->user()->student->semester)
        ->where('status', '!=', StudyPlanStatus::REJECTED)
        ->exists();


        if ($studyPlans) {
            flashMessage('Anda sudah mengajukan KRS', 'warning');
            return to_route('student.study-plans.index');
        }


        return inertia('Student/StudyPlans/Create', [
            'page_settings' => [
                'title' => 'Kartu Rencana Studi',
                'subtitle' => 'Menambahkan kartu rencana studi baru.',
                'method' => 'POST',
                'action' => route('student.study-plans.store'),
            ],

            'schedules' => ScheduleResource::collection($schedules),
        ]);
    }


    public function store(StudyPlanRequest $request): RedirectResponse
    {
        try{
            DB::beginTransaction();
            $studyPlan = StudyPlan::create([
                'student_id' => auth()->user()->student->id,
                'academic_year_id' => activeAcademicYear()->id,
            ]);

            $studyPlan->schedules()->attach($request->schedule_id);

            DB::commit();
            flashMessage('Berhasil mengajukan kartu rencana studi.', 'success');
            return to_route('student.study-plans.index');

        }catch(Throwable $e){
            DB::rollBack();
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('student.study-plans.index');
        }
    }


    public function show(StudyPlan $studyPlan): Response
    {
        return inertia('Student/StudyPlans/Show', [
            'page_settings' => [
                'title' => 'Kartu Rencana Studi',
                'subtitle' => 'Menampilkan kartu rencana studi yang ada.'
            ],

            'studyPlan' => new StudyPlanScheduleResource($studyPlan->load('schedules')),
        ]);
    }
}
