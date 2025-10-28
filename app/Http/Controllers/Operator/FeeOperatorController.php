<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Http\Resources\Operator\FeeOperatorResource;
use App\Models\Fee;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Response;

class FeeOperatorController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Student $student): Response
    {
        $fees = Fee::query()
        ->select(['fees.id', 'fees.fee_code', 'fees.semester', 'fees.status', 'fees.created_at'])
        ->where('student_id', $student->id)
        ->filter(request()->only(['search']))
        ->sorting(request()->only(['field', 'direction']))
        ->with(['student', 'feeGroup', 'academicYear'])
        ->paginate(request()->load ?? 10);

        return inertia('Operator/Students/Fees/Index', [
            'page_settings' => [
                'title' => 'Pembayaran',
                'subtitle' => "Menampilkan daftar pembayaran mahasiswa {$student->user->name}."
            ],

            'fees' => FeeOperatorResource::collection($fees)->additional([
                'meta' => [
                    'has_pages' => $fees->hasPages(),
                ],
            ]),

            'student' => $student,

            'state' => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => 10,
            ],
        ]);
    }
}
