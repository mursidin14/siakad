<?php

namespace App\Http\Controllers\Operator;

use App\Enums\MessageType;
use App\Enums\ScheduleDay;
use App\Http\Controllers\Controller;
use App\Http\Requests\Operator\ScheduleOperatorRequest;
use App\Http\Resources\Operator\ScheduleOperatorResource;
use App\Models\ClassRoom;
use App\Models\Course;
use App\Models\Schedule;
use GuzzleHttp\Psr7\Message;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use Throwable;

class ScheduleOperatorController extends Controller
{
    public function index(): Response
    {
        $operator = auth()->user()->operator;
        $faculty = $operator->faculty->name;
        $departement = $operator->departement->name;

        $schedules = Schedule::query()
        ->select(['schedules.id', 'schedules.start_time', 'schedules.end_time', 'schedules.day_of_week', 'schedules.quota', 'schedules.faculty_id', 'schedules.departement_id', 'schedules.course_id', 'schedules.class_room_id', 'schedules.academic_year_id', 'schedules.created_at'])
        ->filter(request()->only(['search']))
        ->sorting(request()->only(['field', 'direction']))
        ->where('faculty_id', auth()->user()->operator->faculty_id)
        ->where('departement_id', auth()->user()->operator->departement_id)
        ->with(['faculty', 'departement', 'course', 'classRoom', 'academicYear'])
        ->paginate(request()->load ?? 10);

        return inertia('Operator/Schedules/Index', [
            'page_settings' => [
                'title' => 'Jadwal Kuliah',
                'subtitle' => "Menampilkan daftar jadwal kuliah dari fakultas {$faculty} dan program studi {$departement}.",
            ],

            'schedules' => ScheduleOperatorResource::collection($schedules)->additional([
                'meta' => [
                    'has_pages' => $schedules->hasPages(),
                ],
            ]),

            'state' => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => 10,
            ],
        ]);
    }


    public function create(): Response
    {
        return inertia('Operator/Schedules/Create', [
            'page_settings' => [
                'title' => 'Tambah Jadwal Kuliah',
                'subtitle' => 'Halaman untuk menambahkan jadwal kuliah baru.',
                'method' => 'POST',
                'action' => route('operator.schedules.store'),
            ],

            'classrooms' => ClassRoom::query()
            ->select(['id', 'name'])
            ->where('faculty_id', auth()->user()->operator->faculty_id)
            ->where('departement_id', auth()->user()->operator->departement_id)
            ->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),

            'courses' => Course::query()
            ->select(['id', 'name'])
            ->where('faculty_id', auth()->user()->operator->faculty_id)
            ->where('departement_id', auth()->user()->operator->departement_id)
            ->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),

            'days' => ScheduleDay::options(),
        ]);
    }


    public function store(ScheduleOperatorRequest $request): RedirectResponse
    {
        try{
            Schedule::create([
                'faculty_id' => auth()->user()->operator->faculty_id,
                'departement_id' => auth()->user()->operator->departement_id,
                'academic_year_id' => activeAcademicYear()->id,
                'course_id' => $request->course_id,
                'class_room_id' => $request->class_room_id,
                'day_of_week' => $request->day_of_week,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'quota' => $request->quota,
            ]);

            flashMessage(MessageType::CREATED->message('Jadwal Kuliah'));
            return to_route('operator.schedules.index');

        }catch(Throwable $e){

            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operator.schedules.index');
        }
    }


    public function edit(Schedule $schedule): Response
    {
        return inertia('Operator/Schedules/Edit', [
            'page_settings' => [
                'title' => 'Edit Jadwal Kuliah',
                'subtitle' => 'Halaman untuk mengedit jadwal kuliah.',
                'method' => 'PUT',
                'action' => route('operator.schedules.update', $schedule->id),
            ],

            'schedule' => $schedule,

            'classrooms' => ClassRoom::query()
            ->select(['id', 'name'])
            ->where('faculty_id', auth()->user()->operator->faculty_id)
            ->where('departement_id', auth()->user()->operator->departement_id)
            ->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),

            'courses' => Course::query()
            ->select(['id', 'name'])
            ->where('faculty_id', auth()->user()->operator->faculty_id)
            ->where('departement_id', auth()->user()->operator->departement_id)
            ->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),

            'days' => ScheduleDay::options(),
        ]);
    }


    public function update(Schedule $schedule, ScheduleOperatorRequest $request): RedirectResponse
    {
        try{
            $schedule->update([
                'start_time' => $request->start_time,
                'end_time' => $request->start_time,
                'day_of_week' => $request->day_of_week,
                'quota' => $request->quota,
                'faculty_id' => auth()->user()->operator->faculty_id,
                'departement_id' => auth()->user()->operator->departement_id,
                'class_room_id' => $request->class_room_id,
                'academic_year_id' => activeAcademicYear()->id,
            ]);

            flashMessage(MessageType::UPDATED->message('Jadwal kuliah'));
            return to_route('operator.schedules.index');

        }catch(Throwable $e){
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operator.schedules.index');
        }
    }


    public function destroy(Schedule $schedule): RedirectResponse
    {
        try{
            $schedule->delete();

            flashMessage(MessageType::DELETED->message('Jadwal Kuliah'));
            return to_route('operator.schedule.index');

        }catch(Throwable $e){
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operator.schedules.index');
        }
    }
}
