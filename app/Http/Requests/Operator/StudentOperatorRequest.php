<?php

namespace App\Http\Requests\Operator;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentOperatorRequest extends FormRequest
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
            'password' => Rule::when($this->routeIs('operator.students.store'), [
                'required',
                'min:8',
                'max:255',
            ]),
            Rule::when($this->routeIs('operator.students.update'), [
                'nullable',
                'min:8',
                'max:255',
            ]),
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
                'integer',
            ],
            'batch' => [
                'required',
                'integer',
            ],
            'avatar' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg, webp',
                'max:2048',
            ],
        ];
    }


    public function attributes()
    {
        return [
            'name' => 'Nama',
            'email' => 'Email',
            'password' => 'Password',
            'student_number' => 'Nomor Induk',
            'batch' => 'Angkatan',
            'avatar' => 'Foto Profil',
            'semester' => 'Semester',
            'user_id' => 'Pengguna',
            'faculty_id' => 'Fakultas',
            'departement_id' => 'Program Studi',
            'class_room_id' => 'Kelas',
            'fee_group_id' => 'Golongan UKT',
        ];
    }
}
