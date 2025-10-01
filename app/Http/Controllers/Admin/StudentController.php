<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StudentRequest;
use App\Http\Resources\Admin\StudentResource;
use App\Models\ClassRoom;
use App\Models\Departement;
use App\Models\Faculty;
use App\Models\FeeGroup;
use App\Models\Student;
use App\Models\User;
use App\Traits\hasFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Response;
use Throwable;

class StudentController extends Controller
{
    use hasFile;
    public function index(): Response
    {

        $students = Student::query()
        ->select(['students.id', 'students.user_id', 'students.faculty_id', 'students.departement_id', 'students.class_room_id', 'students.fee_group_id', 'student_number', 'batch', 'semester', 'students.created_at'])
        ->filter(request()->only(['search']))
        ->sorting(request()->only(['field', 'direction']))
        ->paginate(request()->load ?? 10);


        return inertia('Admin/Students/Index', [
            'page_settings' => [
                'title' => 'Mahasiswa',
                'subtitle' => 'Menampilkan daftar mahasiswa yang ada',
            ],

            'students' => StudentResource::collection($students)->additional([
                'meta' => [
                    'has_pages' => $students->hasPages(),
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
        return inertia('Admin/Students/Create', [
            'page_settings' => [
                'title' => 'Tambah Mahasiswa',
                'subtitle' => 'Menambahkan mahasiswa baru',
                'method' => 'POST',
                'action' => route('admin.students.store'),
            ],

            'faculties' => Faculty::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),

            'departements' => Departement::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),

            'classRooms' => ClassRoom::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),

            'feeGroups' => FeeGroup::query()->select(['id', 'group', 'amount'])->orderBy('group')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => 'Golongan'.$item->group.'-'.number_format($item->amount, 0, ',', '.'),
            ]),
        ]);
    }


    public function store(StudentRequest $request): RedirectResponse
    {
        try{

            DB::beginTransaction();
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'avatar' => $this->uploadFile($request, 'avatar', 'students'),
            ]);

            $user->student()->create([
                'faculty_id' => $request->faculty_id,
                'departement_id' => $request->departement_id,
                'class_room_id' => $request->class_room_id,
                'fee_group_id' => $request->fee_group_id,
                'student_number' => $request->student_number,
                'semester' => $request->semester,
                'batch' => $request->batch,
            ]);

            $user->assignRole('student');
            DB::commit();
            flashMessage(MessageType::CREATED->message('Mahasiswa'));
            return to_route('admin.students.index');

        }catch (Throwable $e) {
            DB::rollBack();
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.students.index');
        }
    }


    public function edit(Student $student): Response
    {
        return inertia('Admin/Students/Edit', [
            'page_settings' => [
                'title' => 'Edit Mahasiswa',
                'subtitle' => 'Mengedit mahasiswa yang ada',
                'method' => 'PUT',
                'action' => route('admin.students.update', $student),
            ],

            'student' => $student->load('user'),

            'faculties' => Faculty::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),

            'departements' => Departement::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),

            'classRooms' => ClassRoom::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),

            'feeGroups' => FeeGroup::query()->select(['id', 'group', 'amount'])->orderBy('group')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->group,
            ]),
        ]);
    }


    public function update(Student $student, StudentRequest $request): RedirectResponse
    { 
        try{
            DB::beginTransaction();

            $student->update([
                'faculty_id' => $request->faculty_id,
                'departement_id' => $request->departement_id,
                'class_room_id' => $request->class_room_id,
                'fee_group_id' => $request->fee_group_id,
                'student_number' => $request->student_number,
                'semester' => $request->semester,
                'batch' => $request->batch,
            ]);

            $student->user()->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password ? Hash::make($request->password) : $student->user->password,
                'avatar' => $this->uploadFile($request, $student->user, 'avatar', 'students'),
            ]);

            DB::commit();

            flashMessage(MessageType::UPDATED->message('Mahasiswa'));
            return to_route('admin.students.index');

        }catch (Throwable $e) {
                DB::rollBack();
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.students.index');
        }
    }


    public function destroy(Student $student): RedirectResponse
    {
        try{

            $this->deleteFile($student->user, 'avatar');
            $student->user()->delete();
            $student->delete();

            flashMessage(MessageType::DELETED->message('Mahasiswa'));
            return to_route('admin.students.index');

        }catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.students.index');

        }
    }
 
}
