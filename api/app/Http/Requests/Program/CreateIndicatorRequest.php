<?php

namespace App\Http\Requests\Program;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateIndicatorRequest extends FormRequest
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
            $indicatorId= $this->input('program_indicator_id');

        return [
            'indicator_name' => 'required|string',
            'indicator_code' =>  ['required','string',Rule::unique('pk_program_indicators', 'indicator_code')->ignore($indicatorId, 'program_indicator_id')],
            'indicator_active' => 'required|numeric',
            'sub_program_id' => 'required|exists:pk_sub_programs,sub_program_id'
        ];
    }
}
