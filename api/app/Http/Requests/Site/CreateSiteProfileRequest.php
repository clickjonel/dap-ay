<?php

namespace App\Http\Requests\Site;

use Illuminate\Foundation\Http\FormRequest;

class CreateSiteProfileRequest extends FormRequest
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
            // site
            'site' => 'required|array',
            'site.barangay_id' => 'required|numeric|exists:pk_barangays,barangay_id',
            'site.latitude' => 'nullable|string',
            'site.longitude' => 'nullable|string',

            // profile
            'profile' => 'required|array',
            'profile.total_purok' => 'nullable|numeric',
            'profile.total_sitio' => 'nullable|numeric',
            'profile.total_target_purok' => 'nullable|numeric',
            'profile.total_target_sitio' => 'nullable|numeric',
            'profile.total_target_population' => 'nullable|numeric',

            // programs
            'programs' => 'required|array',
            'programs.*' => 'exists:pk_programs,program_id'
        ];
    }
}
