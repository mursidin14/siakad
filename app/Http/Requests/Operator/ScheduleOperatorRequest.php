<?php

namespace App\Http\Requests\Operator;

use App\Enums\ScheduleDay;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ScheduleOperatorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('Operator');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'start_time' => [
                'required',
                'date_format:H:i',
            ],
            'end_time' => [
                'required',
                'date_format:H:i',
                'after:start_time',
            ],
            'day_of_week' => [
                'required',
                new Enum(ScheduleDay::class),
            ],
            'quota' => [
                'required',
                'integer',
                'min:1'
            ],
            'course_id' => [
                'required',
                'exists:courses,id'
            ],
            'class_room_id' => [
                'required',
                'exists:class_rooms,id'
            ],
        ];
    }


    public function attributes(): array
    {
        return [
            'start_time' => 'Waktu Mulai',
            'end_time' => 'Waktu Selesai',
            'day_of_week' => 'Hari',
            'quota' => 'Kuota',
            'course_id' => 'Mata Kuliah',
            'class_room_id' => 'Ruang Kelas',
        ];
    }
}
