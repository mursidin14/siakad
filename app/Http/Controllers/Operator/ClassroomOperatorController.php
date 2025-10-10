<?php

namespace App\Http\Controllers\Operator;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Operator\ClassroomOperatorRequest;
use App\Http\Resources\Operator\ClassroomOperatorResource;
use App\Models\ClassRoom;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use Throwable;

class ClassroomOperatorController extends Controller
{
    public function index(): Response
    {
        $operator = auth()->user()->operator;
        $faculty = $operator->faculty->name;
        $departement = $operator->departement->name;

        $classrooms = ClassRoom::query()
        ->select(['class_rooms.id', 'class_rooms.faculty_id', 'class_rooms.departement_id', 'class_rooms.academic_year_id','class_rooms.name', 'class_rooms.slug', 'class_rooms.created_at'])
        ->filter(request()->only(['search']))
        ->sorting(request()->only(['field', 'direction']))
        ->where('faculty_id', auth()->user()->operator->faculty_id)
        ->where('departement_id', auth()->user()->operator->departement_id)
        ->with(['faculty', 'departement', 'academicYear'])
        ->paginate(request()->load ?? 10);

        return inertia('Operator/Classrooms/Index', [
            'page_settings' => [
                'title' => 'Kelas',
                'subtitle' => "Menampilkan daftar kelas dari fakultas {$faculty} dan program studi {$departement}.",
            ],

            'classrooms' => ClassroomOperatorResource::collection($classrooms)->additional([
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


    public function create(): Response
    {
        return inertia('Operator/Classrooms/Create', [
            'page_settings' => [
                'title' => 'Tambah Kelas',
                'subtitle' => 'Menambahkan kelas baru',
                'method' => 'POST',
                'action' => route('operator.classrooms.store'),
            ],
        ]);
    }


    public function store(ClassroomOperatorRequest $request): RedirectResponse
    {
        try{
            ClassRoom::create([
            'faculty_id' => auth()->user()->operator->faculty_id,
            'departement_id' => auth()->user()->operator->departement_id,
            'academic_year_id' => activeAcademicYear()->id,
            'name' => $request->name,
        ]);

        flashMessage(MessageType::CREATED->message('Kelas'));
        return to_route('operator.classrooms.index');

        }catch(Throwable $e){
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operator.classrooms.index');
        }

    }


    public function edit(ClassRoom $classroom): Response
    {
        return inertia('Operator/Classrooms/Edit', [
            'page_settings' => [
                'title' => 'Edit Kelas',
                'subtitle' => 'Mengubah data kelas',
                'method' => 'PUT',
                'action' => route('operator.classrooms.update', $classroom->slug),
            ],

            'classroom' => $classroom,
        ]);
    }


    public function update(ClassRoom $classroom, ClassroomOperatorRequest $request): RedirectResponse
    {
        try{
            $classroom->update([
                'faculty_id' => auth()->user()->operator->faculty_id,
                'departement_id' => auth()->user()->operator->departement_id,
                'name' => $request->name,
            ]);

            flashMessage(MessageType::UPDATED->message('Kelas'));
            return to_route('operator.classrooms.index');

        }catch(Throwable $e){
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operator.classrooms.index');
        }
    }


    public function destroy(ClassRoom $classroom): RedirectResponse
    {
        try{
            $classroom->delete();
            flashMessage(MessageType::DELETED->message('Kelas'));
            return to_route('operator.classrooms.index');
        }catch(Throwable $e){
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operator.classrooms.index');
        }
    }
}
