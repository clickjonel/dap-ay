<?php

namespace App\Http\Requests\Program;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSubProgramRequest extends FormRequest
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
         $subProgramId = $this->input('sub_program_id');

        return [
            'sub_program_id' => 'required|exists:pk_sub_programs,sub_program_id',
            'sub_program_name' => 'required|string',
            'sub_program_code' => Rule::unique('pk_sub_programs', 'sub_program_code')->ignore($subProgramId, 'sub_program_id'),
            'sub_program_active' => 'required|numeric'
        ];
    }
}
