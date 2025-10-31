<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CourseRequest;
use App\Http\Resources\Admin\CourseResource;
use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\Departement;
use App\Models\Faculty;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Response;
use Throwable;

class CourseController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new Middleware('validateDepartement', only:['store', 'update'])
        ];
    }

    public function index(): Response
    {
        $courses = Course::query()
        ->select(['courses.id', 'courses.name', 'courses.code', 'courses.credits', 'courses.semester', 'courses.faculty_id', 'courses.departement_id', 'courses.teacher_id', 'courses.academic_year_id', 'courses.created_at'])
        ->filter(request()->only(['search']))
        ->sorting(request()->only(['field', 'direction']))
        ->paginate(request()->load ?? 10);

        return inertia('Admin/Courses/Index', [
            'page_settings' => [
                'title' => 'Mata Kuliah',
                'subtitle' => 'Menampilkan daftar mata kuliah yang ada.'
            ],

            'courses' => CourseResource::collection($courses)->additional([
                'meta' => [
                    'has_pages' => $courses->hasPages(),
                ],
            ]),

            'state' => [
                'page' =>request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => 10,
            ],
        ]);
    }


    public function create(): Response
    {
        return inertia('Admin/Courses/Create', [
            'page_settings' => [
                'title' => 'Tambah Mata Kuliah',
                'subtitle' => 'Menambah mata kuliah baru',
                'method' => 'POST',
                'action' => route('admin.courses.store'),
            ],

            'faculties' => Faculty::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),

            'departements' => Departement::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),

            'teachers' => Teacher::query()->select(['teachers.id', 'teachers.user_id'])->join('users', 'teachers.user_id', '=', 'users.id')->whereHas('user', function($query) {
                $query->whereHas('roles', fn($query) => $query->where('name', 'Teacher'));
            })->orderBy('users.name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->user->name
            ]),

            'academicYears' => AcademicYear::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name
            ])
        ]);
    }


    public function store(CourseRequest $request): RedirectResponse
    {
        try{
             Course::create([
            'name' => $request->name,
            'code' => $request->code,
            'credits' => $request->credits,
            'semester' => $request->semester,
            'faculty_id' => $request->faculty_id,
            'departement_id' => $request->departement_id,
            'teacher_id' => $request->teacher_id,
            'academic_year_id' => $request->academic_year_id,
        ]);

        flashMessage(MessageType::CREATED->message('Mata Kuliah'));
        return to_route('admin.courses.index');
        }catch(Throwable $e){
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.courses.index');
        }
    }


    public function edit(Course $course): Response
    {
        return inertia('Admin/Courses/Edit', [
            'course' => new CourseResource($course->load(['faculty', 'departement', 'teacher.user', 'academicYear'])),
            'page_settings' => [
                'title' => 'Edit Mata Kuliah',
                'subtitle' => 'Mengedit mata kuliah yang ada',
                'method' => 'PUT',
                'action' => route('admin.courses.update', $course),
            ],

            'faculties' => Faculty::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),

            'departements' => Departement::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),

            'teachers' => Teacher::query()->select(['teachers.id', 'teachers.user_id'])->join('users', 'teachers.user_id', '=', 'users.id')->whereHas('user', function($query) {
                $query->whereHas('roles', fn($query) => $query->where('name', 'Teacher'));
            })->orderBy('users.name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->user->name
            ]),

            'academicYears' => AcademicYear::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name
            ]),
        ]);
    }


    public function update(Course $course, CourseRequest $request): RedirectResponse
    {
        try{
            $course->update([
                'name' => $request->name,
                'code' => $request->code,
                'credits' => $request->credits,
                'semester' => $request->semester,
                'faculty_id' => $request->faculty_id,
                'departement_id' => $request->departement_id,
                'teacher_id' => $request->teacher_id,
                'academic_year_id' => $request->academic_year_id,
            ]);

            flashMessage(MessageType::UPDATED->message('Edit Mata Kuliah'));
            return to_route('admin.courses.index');
        }catch(Throwable $e){
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.courses.index');
        }
    }


    public function destroy(Course $course): RedirectResponse
    {
        try{
            $course->delete();
            flashMessage(MessageType::DELETED->message('Mata Kuliah'));
            return to_route('admin.courses.index');
        }catch(Throwable $e){
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.courses.index');
        }
    }
}
