<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MessageType;
use App\Enums\ScheduleDay;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ScheduleRequest;
use App\Http\Resources\Admin\ScheduleResource;
use App\Models\AcademicYear;
use App\Models\ClassRoom;
use App\Models\Course;
use App\Models\Departement;
use App\Models\Faculty;
use App\Models\Schedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Redirect;
use Inertia\Response;
use Throwable;

class ScheduleController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('validateClassroom', only:['store', 'update']),
            new Middleware('validateCourse', only:['store', 'update']),
            new Middleware('validateDepartement', only:['store', 'update'])
        ];
    }

    public function index(): Response
    {
        $schedules = Schedule::query()
        ->select(['schedules.id', 'schedules.start_time', 'schedules.end_time', 'schedules.day_of_week', 'schedules.quota', 'schedules.faculty_id', 'schedules.departement_id', 'schedules.course_id', 'schedules.class_room_id', 'schedules.academic_year_id', 'schedules.created_at'])
        ->with(['faculty:id,name', 'departement:id,name', 'course:id,name', 'classRoom:id,name,slug', 'academicYear:id,name'])
        ->filter(request()->only(['search']))
        ->sorting(request()->only(['field', 'direction']))
        ->paginate(request()->load ?? 10);

        return inertia('Admin/Schedules/Index', [
            'page_settings' => [
                'title' => 'Jadwal Kuliah',
                'subtitle' => 'Menampilkan daftar jadwal kuliah yang ada.'
            ],

            'schedules' => ScheduleResource::collection($schedules)->additional([
                'meta' => [
                    'has_pages' => $schedules->hasPages()
                ],
            ]),

            'state' => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => 10
            ],
        ]);
    }


    public function create(Request $request): Response
    {
        $departements = Departement::query()
        ->when($request->faculty_id, fn ($q) => $q->where('faculty_id', $request->faculty_id))
        ->select(['id', 'name'])
        ->orderBy('name')
        ->get()
        ->map(fn ($item) => [
            'value' => $item->id,
            'label' => $item->name,
        ]);

        return inertia('Admin/Schedules/Create', [
            'page_settings' => [
                'title' => 'Tambah Jadwal Kuliah',
                'subtitle' => 'Halaman untuk menambahkan jadwal kuliah baru.',
                'method' => 'POST',
                'action' => route('admin.schedules.store'),
            ],

            'faculties' => Faculty::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),

            'departements' => Departement::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),

            'courses' => Course::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),

            'classrooms' => ClassRoom::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),

            'days' => ScheduleDay::options(),
            'state' => (object) $request->only(['faculty_id']),
        ]);
    }


    public function store(ScheduleRequest $request): RedirectResponse
    {
        try{
            Schedule::create([
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'day_of_week' => $request->day_of_week,
                'quota' => $request->quota,
                'faculty_id' => $request->faculty_id,
                'departement_id' => $request->departement_id,
                'course_id' => $request->course_id,
                'class_room_id' => $request->class_room_id,
                'academic_year_id' => activeAcademicYear()->id,
            ]);

            flashMessage(MessageType::CREATED->message('Jadwal Kuliah'));
            return to_route('admin.schedules.index');

        }catch(Throwable $e){
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.schedules.index');
        }
    }



    public function edit(Schedule $schedule, Request $request): Response
    {
        $departements = Departement::query()
            ->when($request->faculty_id, fn ($q) => $q->where('faculty_id', $request->faculty_id))
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get()
            ->map(fn ($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]);

        return inertia('Admin/Schedules/Edit', [
            'page_settings' => [
                'title' => 'Edit Jadwal Kuliah',
                'subtitle' => 'Halaman untuk mengedit jadwal kuliah.',
                'method' => 'PUT',
                'action' => route('admin.schedules.update', $schedule->id),
            ],

            'schedule' => $schedule->load(['faculty', 'departement', 'course', 'classRoom', 'academicYear']),

            'faculties' => Faculty::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),

            'departements' => Departement::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),

            'courses' => Course::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),

            'classrooms' => ClassRoom::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),

            'days' => ScheduleDay::options(),
            'state' => (object) $request->only(['faculty_id']),
        ]);
    }


    public function update(Schedule $schedule, ScheduleRequest $request): RedirectResponse
    {
        try{
            $schedule->update([
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'day_of_week' => $request->day_of_week,
                'quota' => $request->quota,
                'faculty_id' => $request->faculty_id,
                'departement_id' => $request->departement_id,
                'course_id' => $request->course_id,
                'class_room_id' => $request->class_room_id,
            ]);

            flashMessage(MessageType::UPDATED->message('Jadwal Kuliah'));
            return to_route('admin.schedules.index');
        }catch(Throwable $e){
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.schedules.index');
        }
    }


    public function destroy(Schedule $schedule): RedirectResponse
    {
        try{
            $schedule->delete();

            flashMessage(MessageType::DELETED->message('Jadwal Kuliah'));
            return to_route('admin.schedules.index');
        }catch(Throwable $e){
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.schedules.index');
        }
    }
}