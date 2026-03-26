<?php

namespace App\Http\Requests\Program;

use Illuminate\Foundation\Http\FormRequest;

class CreateProgramRequest extends FormRequest
{
    /**
     * Authorization for creating/updating a Program.
     * Adjust this later if you add user authorization rules.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for Program input.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'parent_program_id' => 'required|exists:program_groups,id',
            'is_active' => 'required|boolean',
        ];
    }
}

