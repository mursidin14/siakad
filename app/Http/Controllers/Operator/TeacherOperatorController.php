<?php

namespace App\Http\Controllers\Operator;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Operator\TeacherOperatorRequest;
use App\Http\Resources\Operator\TeacherOperatorResource;
use App\Models\Teacher;
use App\Models\User;
use App\Traits\hasFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Response;
use Throwable;

class TeacherOperatorController extends Controller
{
    use hasFile;
    public function index(): Response
    {
        $operator = auth()->user()->operator;
        $faculty = $operator->faculty->name;
        $departement = $operator->departement->name;

        $teachers = Teacher::query()
        ->select(['teachers.id', 'teachers.user_id', 'teachers.faculty_id', 'teachers.departement_id', 'teacher_number', 'academic_title', 'teachers.created_at'])
        ->filter(request()->only(['search']))
        ->sorting(request()->only(['field', 'direction']))
        ->whereHas('user', function ($query) {
            $query->whereHas('roles', fn($query) => $query->where('name', 'Teacher'));
        })
        ->where('faculty_id', auth()->user()->operator->faculty_id)
        ->where('departement_id', auth()->user()->operator->departement_id)
        ->with(['user', 'faculty', 'departement'])
        ->paginate(request()->load ?? 10);

        return inertia('Operator/Teachers/Index', [
            'page_settings' => [
                'title' => 'Dosen',
                'subtitle' => "Menampilkan daftar dosen dari fakultas ${faculty} dan program studi ${departement}.",
            ],

            'teachers' => TeacherOperatorResource::collection($teachers)->additional([
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
        return inertia('Operator/Teachers/Create', [
            'page_settings' => [
                'title' => 'Tambah Dosen',
                'subtitle' => 'Menambahkan data dosen baru.',
                'method' => 'POST',
                'action' => route('operator.teachers.store'),
            ],
        ]);
    }

    
    public function store(TeacherOperatorRequest $request): RedirectResponse
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
                'teacher_number' => $request->teacher_number,
                'academic_title' => $request->academic_title,
                'faculty_id' => auth()->user()->operator->faculty_id,
                'departement_id' => auth()->user()->operator->departement_id,
            ]);

            $user->assignRole('Teacher');
            DB::commit();
            flashMessage(MessageType::CREATED->message('Dosen'));
            return to_route('operator.teachers.index');
        }catch(Throwable $e){
            DB::rollBack();
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operator.teachers.index');
        }
    }


    public function edit(Teacher $teacher): Response
    {
        return inertia('Operator/Teachers/Edit', [
            'page_settings' => [
                'title' => 'Edit Dosen',
                'subtitle' => 'Mengedit data dosen yang ada.',
                'method' => 'PUT',
                'action' => route('operator.teachers.update', $teacher),
            ],

            'teacher' => $teacher->load('user'),
        ]);
    }


    public function update(TeacherOperatorRequest $request, Teacher $teacher): RedirectResponse
    {
        try{
            DB::beginTransaction();
            $teacher->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password ? Hash::make($request->password) : $teacher->user->password,
                'avatar' => $this->uploadFile($request, 'avatar', 'teachers', $teacher->user->avatar),
            ]);

            $teacher->update([
                'teacher_number' => $request->teacher_number,
                'academic_title' => $request->academic_title,
            ]);

            DB::commit();
            flashMessage(MessageType::UPDATED->message('Dosen'));
            return to_route('operator.teachers.index');
        }catch(Throwable $e){
            DB::rollBack();
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operator.teachers.index');
        }
    }


    public function destroy(Teacher $teacher): RedirectResponse
    {
        try{
            $this->deleteFile($teacher->user, 'avatar');
            $teacher->user->delete();
            $teacher->delete();

            flashMessage(MessageType::DELETED->message('Dosen'));
            return to_route('operator.teachers.index');
        }catch(Throwable $e){
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operator.teachers.index');
        }
    }
}
