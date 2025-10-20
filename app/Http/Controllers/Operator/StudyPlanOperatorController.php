<?php

namespace App\Http\Controllers\Operator;

use App\Enums\MessageType;
use App\Enums\StudyPlanStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Operator\StudyPlanApproveOperatorRequest;
use App\Http\Resources\Operator\StudyPlanOperatorResource;
use App\Models\Student;
use App\Models\StudyPlan;
use App\Models\StudyResult;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Response;
use Throwable;

class StudyPlanOperatorController extends Controller
{
    public function index(Student $student): Response
    {
        $studyPlans = StudyPlan::query()
        ->select(['id', 'student_id', 'academic_year_id', 'notes', 'status', 'semester', 'created_at'])
        ->filter(request()->only(['search']))
        ->sorting(request()->only(['field', 'direction']))
        ->where('student_id', $student->id)
        ->with(['academicYear', 'student', 'schedules'])
        ->paginate(request()->load ?? 10);


        return inertia('Operator/Students/StudyPlans/Index', [
            'page_settings' => [
                'title' => 'Kartu Rencana Studi',
                'subtitle' => 'Menampilkan daftar kartu rencana yang ada.'
    
            ],

            'studyPlans' => StudyPlanOperatorResource::collection($studyPlans)->additional([
                'meta' => [
                    'has_pages' => $studyPlans->hasPages(),
                ],
            ]),

            'student' => $student,
            'state' => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => 10,
            ],

            'statuses' => StudyPlanStatus::options(),
        ]);
    }


    public function approve(Student $student, StudyPlan $studyPlan, StudyPlanApproveOperatorRequest $request): RedirectResponse
    {
        try{
            DB::beginTransaction();
            $studyPlan->update([
                'status' => $request->status,
                'notes' => $request->notes,
            ]);

            if($studyPlan->status->value === StudyPlanStatus::APPROVED->value){
                $studyResult = StudyResult::create([
                    'student_id' => $studyPlan->student_id,
                    'academic_year_id' => $studyPlan->academic_year_id,
                    'semester' => $studyPlan->semester,
                ]);

                foreach($studyPlan->schedules->pluck('course_id') as $course){
                    $studyResult->studyResultGrades()->create([
                        'course_id' => $course,
                        'letter' => 'E',
                        'grade' => 0,
                    ]);
                };
            };

            DB::commit();

            match($studyPlan->status->value){
                StudyPlanStatus::REJECTED->value => flashMessage('Kartu rencana studi ditolak.', 'success'),
                StudyPlanStatus::APPROVED->value => flashMessage('Kartu rencana studi disetujui.', 'success'),
                default => null,
            };

            return to_route('operator.study-plans.index', $student);

        }catch(Throwable $e){
            DB::rollBack();
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operator.study-plans.index', $student);
        }
    }
}
