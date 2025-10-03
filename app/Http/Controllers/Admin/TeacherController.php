<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TeacherRequest;
use App\Http\Resources\Admin\TeacherResource;
use App\Models\Departement;
use App\Models\Faculty;
use App\Models\Teacher;
use App\Models\User;
use App\Traits\hasFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Response;
use Throwable;

class TeacherController extends Controller
{
    use hasFile;
    public function index(): Response
    {
        $teachers = Teacher::query()
        ->select(['teachers.id', 'teachers.user_id', 'teachers.faculty_id', 'teachers.departement_id', 'teacher_number', 'academic_title', 'teachers.created_at'])
        ->filter(request()->only(['search']))
        ->sorting(request()->only(['field', 'direction']))
        ->paginate(request()->load ?? 10);

        return inertia('Admin/Teachers/Index', [
            'page_settings' => [
                'title' => 'Dosen',
                'subtitle' => 'Menampilkan daftar dosen yang ada',
            ],

            'teachers' => TeacherResource::collection($teachers)->additional([
                'meta' => [
                    'has_pages' => $teachers->hasPages(),
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
        return inertia('Admin/Teachers/Create', [
            'page_settings' => [
                'title' => 'Tambah Dosen',
                'subtitle' => 'Menambahkan dosen baru',
                'method' => 'POST',
                'action' => route('admin.teachers.store'),
            ],

            'faculties' => Faculty::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),

            'departements' => Departement::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,            
            ]),
        ]);
    }


    public function store(TeacherRequest $request): RedirectResponse
    {
        try{
            DB::beginTransaction();
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'avatar' => $this->uploadFile($request, 'avatar', 'teachers'),
            ]);

            $user->teacher()->create([
                'faculty_id' => $request->faculty_id,
                'departement_id' => $request->departement_id,
                'teacher_number' => $request->teacher_number,
                'academic_title' => $request->academic_title,
            ]);

            $user->assignRole('teacher');
            DB::commit();
            flashMessage(MessageType::CREATED->message('Dosen'));
            return to_route('admin.teachers.index');

        }catch(Throwable $e){
            DB::rollBack();
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.teachers.index');
        }
    }


    public function edit(Teacher $teacher): Response
    {
        return inertia('Admin/Teachers/Edit', [
            'page_settings' => [
                'title' => 'Edit Dosen',
                'subtitle' => 'Mengedit dosen yang ada',
                'method' => 'PUT',
                'action' => route('admin.teachers.update', $teacher),
            ],

            'teacher' => $teacher->load('user'),

            'faculties' => Faculty::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),

            'departements' => Departement::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
        ]);
    }


    public function update(Teacher $teacher, TeacherRequest $request): RedirectResponse
    {
        try{
            DB::beginTransaction();
            $teacher->update([
                'faculty_id' => $request->faculty_id,
                'departement_id' => $request->departement_id,
                'teacher_number' => $request->teacher_number,
                'academic_title' => $request->academic_title,
            ]);

            $teacher->user()->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password ? Hash::make($request->password) : $teacher->user->password,
                'avatar' => $this->updateFile($request, $teacher->user, 'avatar', 'teachers'),
            ]);

            DB::commit();
            flashMessage(MessageType::UPDATED->message('Dosen'));
            return to_route('admin.teachers.index');

        }catch(Throwable $e){
            DB::rollBack();
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.teachers.index');
        }
    }


    public function destroy(Teacher $teacher): RedirectResponse
    {
        try{
            $this->deleteFile($teacher->user, 'avatar');
            $teacher->user()->delete();
            $teacher->delete();

            flashMessage(MessageType::DELETED->message('Dosen'));
            return to_route('admin.teachers.index');

        }catch(Throwable $e){
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.teachers.index');
        }
    }
}
