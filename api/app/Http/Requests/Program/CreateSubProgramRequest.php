<?php

namespace App\Http\Requests\Program;

use Illuminate\Foundation\Http\FormRequest;

class CreateSubProgramRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'program_id' => 'required|exists:pk_programs,program_id',
            'sub_program_name' => 'required|string',
            'sub_program_code' => 'required|string|unique:pk_sub_programs,sub_program_code',
            'sub_program_active' => 'required|numeric'
        ];
    }
}
