<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ClassroomRequest;
use App\Http\Resources\Admin\ClassroomResource;
use App\Models\ClassRoom;
use App\Models\Departement;
use App\Models\Faculty;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Response;
use Throwable;

class ClassroomController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new Middleware('validateDepartement', only:['store', 'update'])
        ];
    }

    public function index(): Response
    {
        $classrooms = ClassRoom::query()
        ->select(['id', 'faculty_id', 'departement_id', 'academic_year_id','name', 'slug', 'created_at'])
        ->filter(request()->only(['search']))
        ->sorting(request()->only(['field', 'direction']))
        ->with(['faculty', 'departement', 'academicYear'])
        ->paginate(request()->load ?? 10);

        return inertia('Admin/Classrooms/Index', [
            'page_settings' => [
                'title' => 'Kelas',
                'subtitle' => 'Menampilkan daftar kelas yang ada',
            ],

            'classrooms' => ClassroomResource::collection($classrooms)->additional([
                'meta' => [
                    'has_pages' => $classrooms->hasPages(),
                ],
            ]),

            'state' => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => 10,
            ],
        ]);
    }


    public function create(Request $request): Response
    {
        return inertia('Admin/Classrooms/Create', [
            'page_settings' => [
                'title' => 'Tambah Kelas',
                'subtitle' => 'Menambahkan kelas baru',
                'method' => 'POST',
                'action' => route('admin.classrooms.store'),
            ],

            'faculties' => Faculty::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),

            'departements' => Departement::query()
            ->when($request->faculty_id, fn($q) => $q->where('faculty_id', $request->faculty_id))
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get()
            ->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
            'state' => (object) $request->only(['faculty_id']),
        ]);
    }


    public function store(ClassroomRequest $request): RedirectResponse
    {
        try {
            ClassRoom::create([
                'faculty_id' => $request->faculty_id,
                'departement_id' => $request->departement_id,
                'academic_year_id' => activeAcademicYear()->id,
                'name' => $request->name,
            ]);

            flashMessage(MessageType::CREATED->message('Kelas'));
            return to_route('admin.classrooms.index');

        }catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.classrooms.index');

        }
    }


    public function edit(ClassRoom $classroom, Request $request): Response
    {
        return inertia('Admin/Classrooms/Edit', [
            'page_settings' => [
                'title' => 'Edit Kelas',
                'subtitle' => 'Mengedit kelas yang ada',
                'method' => 'PUT',
                'action' => route('admin.classrooms.update', $classroom),
            ],

            'classroom' => $classroom,

            'faculties' => Faculty::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),

            'departements' => Departement::query()
            ->when($request->faculty_id, fn($q) => $q->where('faculty_id', $request->faculty_id))
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get()
            ->map(fn ($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
            'state' => (object) $request->only(['faculty_id']),
        ]);
    }


    public function update(ClassRoom $classroom, ClassroomRequest $request): RedirectResponse
    {
        try {
            $classroom->update([
                'faculty_id' => $request->faculty_id,
                'department_id' => $request->departement_id,
                'name' => $request->name,
            ]);

            flashMessage(MessageType::UPDATED->message('Kelas'));
            return to_route('admin.classrooms.index');

        }catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.classrooms.index');

        }
    }


    public function destroy(ClassRoom $classroom): RedirectResponse
    {
        try {

            $classroom->delete();
            flashMessage(MessageType::DELETED->message('Kelas'));
            return to_route('admin.classrooms.index');

        }catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.classrooms.index');
        }
    }
}
