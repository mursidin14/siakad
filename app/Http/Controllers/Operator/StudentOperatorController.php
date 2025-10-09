<?php

namespace App\Http\Controllers\Operator;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Operator\StudentOperatorRequest;
use App\Http\Resources\Operator\StudentOperatorResource;
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
use Illuminate\Support\Facades\Redirect;
use Inertia\Response;
use Throwable;

class StudentOperatorController extends Controller
{
    use hasFile;
    public function index(): Response
    {

        $operator = auth()->user()->operator;
        $faculty = $operator->faculty->name;
        $departement = $operator->departement->name;

        $students = Student::query()
        ->select(['students.id', 'students.user_id', 'students.faculty_id', 'students.departement_id', 'students.class_room_id', 'students.fee_group_id', 'student_number', 'batch', 'semester', 'students.created_at'])
        ->filter(request()->only(['search']))
        ->sorting(request()->only(['field', 'direction']))
        ->whereHas('user', function ($query) {
            $query->whereHas('roles', fn($query) => $query->where('name', 'Student'));
        })
        ->where('faculty_id', auth()->user()->operator->faculty_id)
        ->where('departement_id', auth()->user()->operator->departement_id)
        ->with(['user', 'classRoom', 'feeGroup'])
        ->paginate(request()->load ?? 10);

        return inertia('Operator/Students/Index', [
            'page_settings' => [
                'title' => 'Mahasiswa',
                'subtitle' => "Menampilkan daftar mahasiswa dari fakultas {$faculty} dan program studi {$departement}.",
            ],

            'students' => StudentOperatorResource::collection($students)->additional([
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
        return inertia('Operator/Students/Create', [
            'page_settings' => [
                'title' => 'Tambah Mahasiswa',
                'subtitle' => 'Menambahkan data mahasiswa baru pada sistem.',
                'method' => 'POST',
                'action' => route('operator.students.store'),
            ],

            'classRooms' => ClassRoom::query()
            ->select(['id', 'name'])
            ->where('faculty_id', auth()->user()->operator->faculty_id)
            ->where('departement_id', auth()->user()->operator->departement_id)
            ->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),

            'feeGroups' => FeeGroup::query()->select(['id', 'group', 'amount'])->orderBy('group')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => 'Golongan '.$item->group.' - Rp. '.number_format($item->amount, 0, ',', '.'),
            ]),
        ]);
    }


    public function store(StudentOperatorRequest $request): RedirectResponse
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
                'faculty_id' => auth()->user()->operator->faculty_id,
                'departement_id' => auth()->user()->operator->departement_id,
                'class_room_id' => $request->class_room_id,
                'fee_group_id' => $request->fee_group_id,
                'student_number' => $request->student_number,
                'batch' => $request->batch,
                'semester' => $request->semester,
            ]);

            $user->assignRole('Student');
            DB::commit();
            flashMessage(MessageType::CREATED->message('Mahasiswa'));
            return to_route('operator.students.index');

        }catch(Throwable $e){
            DB::rollBack();
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operator.students.index');
        }
    }


    public function edit(Student $student): Response
    {
        return inertia('Operator/Students/Edit', [
            'page_settings' => [
                'title' => 'Edit Mahasiswa',
                'subtitle' => 'Mengubah data mahasiswa pada sistem',
                'method' => 'PUT',
                'action' => route('operator.students.update', $student),
            ],

            'student' => $student->load('user'),

            'classRooms' => ClassRoom::query()
            ->select(['id', 'name'])
            ->where('faculty_id', auth()->user()->operator->faculty_id)
            ->where('departement_id', auth()->user()->operator->departement_id)
            ->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),

            'feeGroups' => FeeGroup::query()->select(['id', 'group', 'amount'])->orderBy('group')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => 'Golongan '.$item->group.' - Rp. '.number_format($item->amount, 0, ',', '.'),
            ]),
        ]);
    }


    public function update(Student $student, StudentOperatorRequest $request): RedirectResponse
    {
        try{
            DB::beginTransaction();

            $student->update([
                'faculty_id' => auth()->user()->operator->faculty_id,
                'departement_id' => auth()->user()->operator->departement_id,
                'class_room_id' => $request->class_room_id,
                'fee_group_id' => $request->fee_group_id,
                'student_number' => $request->student_number,
                'batch' => $request->batch,
                'semester' => $request->semester,
            ]);

            $student->user()->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password ? Hash::make($request->password) : $student->user->password,
                'avatar' => $this->uploadFile($request, $student->user, 'avatar', 'students'),
            ]);

            DB::commit();
            flashMessage(MessageType::UPDATED->message('Mahasiswa'));
            return to_route('operator.students.index');

        }catch (Throwable $e) {
            DB::rollBack();
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operator.students.index');
        }
    }


    public function destroy(Student $student): RedirectResponse
    {
        try{
            $this->deleteFile($student->user, 'avatar');
            $student->user()->delete();
            $student->delete();

            flashMessage(MessageType::DELETED->message('Mahasiswa'));
            return to_route('operator.students.index');
        }catch(Throwable $e){
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operator.students.index');
        }
    }
}
