<?php

namespace App\Http\Requests\Program;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class UpdateIndicatorRequest extends FormRequest
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
        $indicatorId = $this->input('indicator_id');

        return [
            'program_indicator_id' => 'required|exists:pk_program_indicators,program_indicator_id',
            'indicator_name' => 'required|string',
            'indicator_code' => Rule::unique('pk_program_indicators', 'program_indicator_id')->ignore($indicatorId, 'program_indicator_id'),
            'indicator_active' => 'required|numeric',
            'sub_program_id' => 'required|exists:pk_sub_programs,sub_program_id'
        ];
    }
}
