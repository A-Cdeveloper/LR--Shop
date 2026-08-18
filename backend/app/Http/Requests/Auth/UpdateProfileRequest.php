<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (! $this->has('phone') || $this->input('phone') === null) {
            return;
        }

        $phone = preg_replace('/[\s\-\/\(\)]/', '', (string) $this->input('phone'));

        $this->merge([
            'phone' => $phone === '' ? null : $phone,
        ]);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => [
                'sometimes',
                'required',
                'email',
                'max:255',
                'unique:users,email,' . $this->user()->id,
            ],
            'phone' => ['sometimes', 'nullable', 'string', 'max:50', 'regex:/^\+[1-9]\d{7,14}$/'],
            'shipping_address' => ['sometimes', 'nullable', 'string', 'max:255'],
            'city' => ['sometimes', 'nullable', 'string', 'max:100'],
            'state' => ['sometimes', 'nullable', 'string', 'max:100'],
            'zip' => ['sometimes', 'nullable', 'string', 'max:20'],
            'country' => ['sometimes', 'nullable', 'string', 'max:100'],
        ];
    }
}
