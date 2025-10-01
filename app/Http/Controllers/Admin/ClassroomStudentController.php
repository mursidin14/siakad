<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ClassroomStudentRequest;
use App\Http\Resources\Admin\ClassroomStudentResource;
use App\Models\ClassRoom;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use Throwable;

class ClassroomStudentController extends Controller
{
    public function index(ClassRoom $classroom): Response
    {

        $classroomStudents = Student::query()
        ->select(['id', 'user_id', 'class_room_id', 'student_number', 'created_at'])
        ->where('class_room_id', $classroom->id)
        ->whereHas('user', function ($query) {
            $query->whereHas('roles', fn($query) => $query->where('name', 'student'));
        })
        ->orderBy('student_number')
        ->with(['user'])
        ->paginate(10);

        return inertia('Admin/Classrooms/Students/Index', [
            'page_settings' => [
                'title' => "Kelas $classroom->name",
                'subtitle' => 'Menampilkan daftar mahasiswa yang ada di kelas ini',
                'method' => 'PUT',
                'action' => route('admin.classroom-students.sync', $classroom)
            ],

            'students' => Student::query()
            ->select(['id', 'user_id', 'faculty_id', 'departement_id', 'class_room_id'])
            ->whereHas('user', function($query) {
                $query->whereHas('roles', fn($query) => $query->select(['id', 'name'])->where('name', 'student'))->orderBy('name');
            })
            ->where('faculty_id', $classroom->faculty_id)
            ->where('departement_id', $classroom->departement_id)
            ->whereNull('class_room_id')
            ->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->user->name
            ]),

            'classroomStudents' => ClassroomStudentResource::collection($classroomStudents),
            'classroom' => $classroom,

        ]);
    }


    public function sync(ClassRoom $classroom, ClassroomStudentRequest $request): RedirectResponse
    {
        try{
            Student::whereHas('user', fn($query) => $query->where('name', $request->student))->update([
                'class_room_id' => $classroom->id,
            ]);

            flashMessage("Berhasil menmbahkan mahasiswa baru kedalam kelas $classroom->name.");
            return to_route('admin.classroom-students.index', $classroom);
        }catch(Throwable $e){
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.classroom-students.index', $classroom);
        }
    }


    public function destroy(ClassRoom $classroom, Student $student): RedirectResponse
    {
        try{
            $student->update([
                'class_room_id' => null,
            ]);

            flashMessage("Berhasil menghapus mahasiswa dari kelas $classroom->name.");
            return to_route('admin.classroom-students.index', $classroom);
        }catch(Throwable $e){
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.classroom-students.index', $classroom);
        }
    }
}
