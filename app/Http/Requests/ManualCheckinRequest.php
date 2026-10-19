<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ManualCheckinRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isStaff() ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['nullable', 'exists:users,id'],
            'identifier' => ['nullable', 'string', 'max:100'],
            'guest_name' => ['required_without_all:user_id,identifier', 'nullable', 'string', 'max:150'],
            'guest_origin' => ['nullable', 'string', 'max:150'],
            'guest_purpose' => ['nullable', 'string', 'max:255'],
            'guest_phone' => ['nullable', 'string', 'max:30'],
            'device_note' => ['nullable', 'string', 'max:150'],
        ];
    }
}
