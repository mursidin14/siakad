<?php

namespace App\Http\Requests\Operator;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TeacherOperatorRequest extends FormRequest
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
                Rule::unique('users')->ignore($this->teacher?->user),
            ],
            'password' => Rule::when($this->routeIs('operator.teachers.store'), [
                'required',
                'min:8',
                'max:255',
            ]),
            Rule::when($this->routeIs('operator.teachers.update'), [
                'nullable',
                'min:8',
                'max:255',
            ]),
            'teacher_number' => [
                'required',
                'string',
                'min:3',
                'max:13',
                Rule::unique('teachers')->ignore($this->teacher),
            ],
            'academic_title' => [
                'required',
                'string',
                'min:3',
                'max:255',
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
            'avatar' => 'Foto Profil',
            'teacher_number' => 'Nomor Induk',
            'academic_title' => 'Gelar',
            'user_id' => 'Pengguna',
            'faculty_id' => 'Fakultas',
            'departement_id' => 'Program Studi',
        ];
    }
}
