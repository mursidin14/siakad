<?php

namespace App\Http\Controllers\Operator;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Operator\CourseOperatorRequest;
use App\Http\Resources\Operator\CourseOperatorResource;
use App\Models\Course;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use Throwable;

class CourseOperatorController extends Controller
{
    public function index(): Response
    {
        $operator = auth()->user()->operator;
        $faculty = $operator->faculty?->name ?? '';
        $departement = $operator->departement?->name ?? '';

        $courses = Course::query()
        ->select(['courses.id', 'courses.name', 'courses.code', 'courses.credits', 'courses.semester', 'courses.faculty_id', 'courses.departement_id', 'courses.teacher_id', 'courses.academic_year_id', 'courses.created_at'])
        ->filter(request()->only(['search']))
        ->sorting(request()->only(['field', 'direction']))
        ->where('faculty_id', auth()->user()->operator->faculty_id)
        ->where('departement_id', auth()->user()->operator->departement_id)
        ->with(['teacher', 'academicYear'])
        ->paginate(request()->load ?? 10);

        return inertia('Operator/Courses/Index', [
            'page_settings' => [
                'title' => 'Mata Kuliah',
                'subtitle' => "Menampilkan daftar mata kuliah dari fakultas {$faculty} dan program studi {$departement}",
            ],

            'courses' => CourseOperatorResource::collection($courses)->additional([
                'meta' => [
                    'has_pages' => $courses->hasPages(),
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
        return inertia('Operator/Courses/Create', [
            'page_settings' => [
                'title' => 'Tambah Mata Kuliah',
                'subtitle' => 'Menambah mata kuliah baru',
                'method' => 'POST',
                'action' => route('operator.courses.store'),
            ],

            'teachers' => Teacher::query()
            ->select(['teachers.id', 'teachers.user_id'])->join('users', 'teachers.user_id', '=', 'users.id')
            ->whereHas('user', function($query) {
                $query->whereHas('roles', fn($query) => $query->where('name', 'Teacher'));
            })
            ->where('faculty_id', auth()->user()->operator->faculty_id)
            ->where('departement_id', auth()->user()->operator->departement_id)
            ->orderBy('users.name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->user->name,
            ]),
        ]);
    }

    
    public function store(CourseOperatorRequest $request): RedirectResponse
    {
        try{
            Course::create([
            'faculty_id' => auth()->user()->operator->faculty_id,
            'departement_id' => auth()->user()->operator->departement_id,
            'academic_year_id' => activeAcademicYear()->id,
            'teacher_id' => $request->teacher_id,
            'name' => $request->name,
            'code' => $request->code,
            'credits' => $request->credits,
            'semester' => $request->semester,
            ]);

            flashMessage(MessageType::CREATED->message('Mata Kuliah'));
            return to_route('operator.courses.index');

        }catch(Throwable $e){
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operator.courses.index');
        }
    }


    public function edit(Course $course): Response
    {
        return inertia('Operator/Courses/Edit', [
            'page_settings' => [
                'title' => 'Edit Mata Kuliah',
                'subtitle' => 'Mengubah data mata kuliah',
                'method' => 'PUT',
                'action' => route('operator.courses.update', $course->code),
            ],

            'course' => $course,

            'teachers' => Teacher::query()
            ->select(['teachers.id', 'teachers.user_id'])->join('users', 'teachers.user_id', '=', 'users.id')
            ->whereHas('user', function($query) {
                $query->whereHas('roles', fn($query) => $query->where('name', 'Teacher'));
            })
            ->where('faculty_id', auth()->user()->operator->faculty_id)
            ->where('departement_id', auth()->user()->operator->departement_id)
            ->orderBy('users.name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->user->name,
            ]),
        ]);
    }


    public function update(CourseOperatorRequest $request, Course $course): RedirectResponse
    {
        try{
            $course->update([
                'faculty_id' => auth()->user()->operator->faculty_id,
                'departement_id' => auth()->user()->operator->departement_id,
                'academic_year_id' => activeAcademicYear()->id,
                'teacher_id' => $request->teacher_id,
                'name' => $request->name,
                'code' => $request->code,
                'credits' => $request->credits,
                'semester' => $request->semester,
            ]);

            flashMessage(MessageType::UPDATED->message('Mata Kuliah'));
            return to_route('operator.courses.index');
        }catch(Throwable $e){
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operator.courses.index');
        }
    }


    public function destroy(Course $course): RedirectResponse
    {
        try{
            $course->delete();
            flashMessage(MessageType::DELETED->message('Mata Kuliah'));
            return to_route('operator.courses.index');
        }catch(Throwable $e){
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operator.courses.index');
        }
    }
}
