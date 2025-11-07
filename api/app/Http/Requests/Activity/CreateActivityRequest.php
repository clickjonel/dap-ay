<?php

namespace App\Http\Requests\Activity;

use Illuminate\Foundation\Http\FormRequest;

class CreateActivityRequest extends FormRequest
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
            'activity_name' => 'required|string',
            'activity_venue' => 'required|string',
            'activity_budget' => 'required|numeric',
            'activity_actual_budget' => 'required|numeric',
            'activity_fund_source' => 'required|string',
            'activity_proponents' => 'required|string',
            'activity_partners' => 'required|string',
            'activity_date_start' => 'required|date',
            'activity_date_end' => 'required|date',
            'activity_type' => 'required|string', 
            'activity_scope' => 'required|string'
        ];
    }
}
