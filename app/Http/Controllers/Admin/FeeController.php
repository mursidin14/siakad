<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\FeeResource;
use App\Models\Fee;
use Illuminate\Http\Request;
use Inertia\Response;

class FeeController extends Controller
{
    public function index(): Response
    {
        $fees = Fee::query()
        ->select(['fees.id', 'fees.fee_code', 'fees.student_id', 'fees.fee_group_id', 'fees.academic_year_id', 'fees.semester', 'fees.status', 'fees.created_at'])
        ->filter(request()->only(['search']))
        ->sorting(request()->only(['field', 'direction']))
        ->paginate(request()->load ?? 10);

        return inertia('Admin/Fees/Index', [
            'page_settings' => [
                'title' => 'Uang Kuliah Tunggal',
                'subtitle' => 'Menampilkan daftar UKT mahasiswa yang ada',
            ],

            'fees' => FeeResource::collection($fees)->additional([
                'meta' => [
                    'has_pages' => $fees->hasPages()
                ],
            ]),

            'state' => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => 10,
            ],
        ]);
    }
}
