<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OperatorRequest;
use App\Http\Resources\Admin\OperatorResource;
use App\Models\Departement;
use App\Models\Faculty;
use App\Models\Operator;
use App\Models\User;
use App\Traits\hasFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;
use Inertia\Response;
use Throwable;

class OperatorController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new Middleware('validateDepartement', only:['store', 'update'])
        ];
    }

    use hasFile;
    public function index(): Response
    {
        $operators = Operator::query()
        ->select(['operators.id', 'operators.user_id', 'operators.faculty_id', 'operators.departement_id', 'employee_number', 'operators.created_at'])
        ->filter(request()->only(['search']))
        ->sorting(request()->only(['field', 'direction']))
        ->paginate(request()->load ?? 10);

        return inertia('Admin/Operators/Index', [
            'page_settings' => [
                'title' => 'Operator',
                'subtitle' => 'Menampilkan daftar operator yang ada',
            ],

            'operators' => OperatorResource::collection($operators)->additional([
                'meta' => [
                    'has_pages' => $operators->hasPages(),
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
        return inertia('Admin/Operators/Create', [
            'page_settings' => [
                'title' => 'Tambah Operator',
                'subtitle' => 'Menambahkan operator baru',
                'method' => 'POST',
                'action' => route('admin.operators.store'),
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


    public function store(OperatorRequest $request): RedirectResponse
    {
        try{
            DB::beginTransaction();
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'avatar' => $this->uploadFile($request, 'avatar', 'operators'),
            ]);

            $user->operator()->create([
                'faculty_id' => $request->faculty_id,
                'departement_id' => $request->departement_id,
                'employee_number' => $request->employee_number,
            ]);

            $user->assignRole('operator');
            DB::commit();
            flashMessage(MessageType::CREATED->message('Operator'));
            return to_route('admin.operators.index');

        }catch(Throwable $e){
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.operators.index');
        }
    }


    public function edit(Operator $operator, Request $request): Response
    {
        return inertia('Admin/Operators/Edit', [
            'page_settings' => [
                'title' => 'Edit Operator',
                'subtitle' => 'Mengedit operator yang ada',
                'method' => 'PUT',
                'action' => route('admin.operators.update', $operator),
            ],

            'operator' => $operator->load('user'),

            'faculties' => Faculty::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),

            'departements' => Departement::query()
            ->where('faculty_id', $request->input('faculty_id', $operator->faculty_id))
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


    public function update(Operator $operator, OperatorRequest $request): RedirectResponse
    {
        try{
            DB::beginTransaction();
            $operator->update([
                'faculty_id' => $request->faculty_id,
                'departement_id' => $request->departement_id,
                'employee_number' => $request->employee_number,
            ]);

            $operator->user()->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password ? Hash::make($request->password) : $operator->user->password,
                'avatar' => $this->updateFile($request, $operator->user, 'avatar', 'operators'),
            ]);

            DB::commit();
            flashMessage(MessageType::UPDATED->message('Operator'));
            return to_route('admin.operators.index');

        }catch(Throwable $e){
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.operators.index');
        }
    }


    public function destroy(Operator $operator): RedirectResponse
    {
        try{
            $this->deleteFile($operator->user, 'avatar');
            $operator->user()->delete();
            $operator->delete();

            flashMessage(MessageType::DELETED->message('Operator'));
            return to_route('admin.operators.index');

        }catch(Throwable $e){
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.operators.index');
        }
    }
}
