<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DepartementRequest;
use App\Http\Resources\Admin\DepartementResource;
use App\Models\Departement;
use App\Models\Faculty;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use Throwable;

class DepartementController extends Controller
{
    public function index(): Response
    {
        $departement = Departement::query()
        ->select(['id', 'faculty_id', 'name', 'code', 'slug', 'created_at'])
        ->filter(request()->only(['search']))
        ->sorting(request()->only(['field', 'direction']))
        ->paginate(request()->load ?? 10);

        return inertia('Admin/Departements/Index', [
            'page_settings' => [
                'title' => 'Program Studi',
                'subtitle' => 'Menampilkan daftar Program Studi yang ada',
            ],

            'departements' => DepartementResource::collection($departement)->additional([
                'meta' => [
                    'has_pages' => $departement->hasPages(),
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
        return inertia('Admin/Departements/Create', [
            'page_settings' => [
                'title' => 'Tambah Program Studi',
                'subtitle' => 'Menambahkan Program Studi baru',
                'method' => 'POST',
                'action' => route('admin.departements.store'),
            ],

            'faculties' => Faculty::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
        ]);
    }


    public function store(DepartementRequest $request): RedirectResponse
    {
        try {
            Departement::create([
                'faculty_id' => $request->faculty_id,
                'name' => $request->name,
                'code' => str()->random(6),
            ]);

            flashMessage(MessageType::CREATED->message('Program Studi'));
            return to_route('admin.departements.index');

        }catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.departements.index');

        }
    }


    public function edit(Departement $departement): Response
    {
        return inertia('Admin/Departements/Edit', [
            'page_settings' => [
                'title' => 'Edit Program Studi',
                'subtitle' => 'Mengedit Program Studi yang ada',
                'method' => 'PUT',
                'action' => route('admin.departements.update', $departement),
            ],

            'departement' => $departement,
            'faculties' => Faculty::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
        ]);
    }


    public function update(Departement $departement, DepartementRequest $request): RedirectResponse
    {
        try {
            $departement->update([
                'faculty_id' => $request->faculty_id,
                'name' => $request->name,
            ]);

            flashMessage(MessageType::UPDATED->message('Program Studi'));
            return to_route('admin.departements.index');


        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.departements.index');
        }
    }


    public function destroy(Departement $departement): RedirectResponse
    {
        try{
            $departement->delete();

            flashMessage(MessageType::DELETED->message('Program Studi'));
            return to_route('admin.departements.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.departements.index');
        }
    }
    
}
