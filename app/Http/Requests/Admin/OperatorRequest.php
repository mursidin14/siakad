<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OperatorRequest extends FormRequest
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
                Rule::unique('users')->ignore($this->operator?->user),
            ],
            'password' => Rule::when($this->routeIs('admin.operators.store'), [
                'required',
                'min:8',
                'max:255',
            ]),
            Rule::when($this->routeIs('admin.operators.update'), [
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
                'exists:departements,id',
           ],
           'employee_number' => [
                'required',
                'string',
                'min:3',
                'max:13'
           ],
           'avatar' => [
                'nullable',
                'mimes:png, jpg, jpeg, webp',
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
            'employee_number' => 'Nomor Induk',
        ];
    }
}
