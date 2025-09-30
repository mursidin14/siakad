<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
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
                Rule::unique('users')->ignore($this->student?->user),
            ],
            'password' => Rule::when($this->routeIs('admin.students.store'), [
                'required',
                'min:8',
                'max:255',
            ]),
            Rule::when($this->routeIs('admin.students.update'), [
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
            'class_room_id' => [
                'required',
                'exists:class_rooms,id'
            ],
            'fee_group_id' => [
                'required',
                'exists:fee_groups,id'
            ],
            'student_number' => [
                'required',
                'string',
                'min:3',
                'max:13',
            ],
            'semester' => [
                'required',
                'int',
            ],
            'batch' => [
                'required',
                'int',
            ],
            'avatar' => [
                'nullable',
                'mimes:png, jpg, jpeg, webp',
                'max:2048'
            ]
        ];
    }


    public function attributes(): array
    {
        return [
            'name' => 'Nama',
            'email' => 'Email',
            'password' => 'Password',
            'student_number' => 'Nomor Induk',
            'batch' => 'Batch',
            'avatar' => 'Foto Profil',
            'semester' => 'Semester',
            'user_id' => 'User',
            'faculty_id' => 'Fakultas',
            'departement_id' => 'Program Studi',
            'class_room_id' => 'Kelas',
            'fee_group_id' => 'Golongan UKT',
        ];
    }
}
