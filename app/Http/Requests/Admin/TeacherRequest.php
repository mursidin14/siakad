<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TeacherRequest extends FormRequest
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
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->teacher?->user),
            ],
            'password' => Rule::when($this->routeIs('admin.teachers.store'), [
                'required',
                'min:8',
                'max:255',
            ]),
            Rule::when($this->routeIs('admin.teachers.update'), [
                'nullable',
                'min:8',
                'max:255',
            ]),
            'faculty_id' => [
                'required',
                'exists:faculties,id'
            ],
            'departement_id' => [
                'required',
                'exists:departements,id'
            ],
            'teacher_number' => [
                'required',
                'string',
                'min:3',
                'max:13',
            ],
            'academic_title' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],
        ];
    }


    public function attributes()
    {
        return [
            'name' => 'Nama',
            'email' => 'Email',
            'password' => 'Password',
            'teacher_number' => 'Nomor Induk',
            'academic_title' => 'Gelar',
            'user_id' => 'User',
            'faculty_id' => 'Fakultas',
            'departement_id' => 'Program Studi',
        ];
    }
}
