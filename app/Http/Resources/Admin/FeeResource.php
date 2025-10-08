<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'fee_code' => $this->fee_code,
            'student' => $this->whenLoaded('student', [
                'id' => $this->student?->id,
                'name' => $this->student?->name,
                'student_number' => $this->student?->student_number,
                'faculty' => $this->student?->faculty?->name,
                'departement' => $this->student?->departement?->name,
                'classroom' => $this->student?->classroom?->name,
            ]),
            'fee_group' => $this->whenLoaded('feeGroup', [
                'id' => $this->feeGroup?->id,
                'name' => $this->feeGroup?->name,
                'amount' => $this->feeGroup?->amount,
            ]),
            'academic_year' => $this->whenLoaded('academicYear', [
                'id' => $this->academicYear?->id,
                'name' => $this->academicYear?->name,
            ]),
            'semester' => $this->semester,
            
        ];
    }
}
