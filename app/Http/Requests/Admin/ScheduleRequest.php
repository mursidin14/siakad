<?php

namespace App\Http\Requests\Admin;

use App\Enums\ScheduleDay;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('Admin');
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
                'date_format:H:i'
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
            'faculty_id' => [
                'required',
                'exists:faculties,id'
            ],
            'departement_id' => [
                'required',
                'exists:departements,id'
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


    public function attributes()
    {
        return [
            'start_time' => 'Waktu Mulai',
            'end_time' => 'Waktu Selesai',
            'day_of_week' => 'Hari',
            'quota' => 'Kuota',
            'faculty_id' => 'Fakultas',
            'departement_id' => 'Program Studi',
            'course_id' => 'Mata Kuliah',
            'class_room_id' => 'Ruang Kelas',
        ];
    }
}
