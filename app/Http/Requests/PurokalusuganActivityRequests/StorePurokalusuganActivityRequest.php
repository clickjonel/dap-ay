<?php

namespace App\Http\Requests\PurokalusuganActivityRequests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePurokalusuganActivityRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'activity_name' => 'required|string',
            'type'          => 'required',
            'date_start'    => 'required|date',
            'date_end'      => 'required|date',
            'total_clients' => 'required|integer',

            'programs'      => 'required|array|min:1',
            'program.*'     => 'exists:programs,id',

            'barangays'     => 'required|array|min:1',
            'barangays.*'   => 'exists:barangays,id',
            
            'hrh'           => 'nullable',
            'hrh.*'         => 'exists:users,id',
        ];
    }
}
