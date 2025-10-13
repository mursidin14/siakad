<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255'
            ],
            'code' => [
                'required',
                'string',
                'min:3',
                'max:100',
            ],
            'credits' => [
                'required',
                'int',
            ],
            'semester' => [
                'required',
                'int',
            ],
            'faculty_id' => [
                'required',
                'exists:faculties,id'
            ],
            'departement_id' => [
                'required',
                'exists:departements,id'
            ],
            'teacher_id' => [
                'required',
                'exists:teachers,id'
            ],
            'academic_year_id' => [
                'required',
                'exists:academic_years,id'
            ],
        ];
    }


    public function attributes()
    {
        return [
            'name' => 'Nama',
            'code' => 'Kode',
            'credits' => 'SKS',
            'semester' => 'Semester',
            'faculty_id' => 'Fakultas',
            'departement_id' => 'Program Studi',
            'teacher_id' => 'Dosen Pengampu',
            'academic_year_id' => 'Tahun Akademik',
        ];
    }
}
