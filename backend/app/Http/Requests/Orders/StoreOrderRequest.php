<?php

namespace App\Http\Requests\Orders;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $user = $this->user();

        if (! $user) {
            return;
        }

        $this->merge([
            'customer_name' => $this->input('customer_name', $user->name),
            'customer_phone' => $this->input('customer_phone', $user->phone),
            'shipping_address' => $this->input('shipping_address', $user->shipping_address),
            'city' => $this->input('city', $user->city),
            'state' => $this->input('state', $user->state),
            'zip' => $this->input('zip', $user->zip),
            'country' => $this->input('country', $user->country),
        ]);

        $phone = preg_replace('/[\s\-\/\(\)]/', '', (string) $this->input('customer_phone'));

        $this->merge([
            'customer_phone' => $phone === '' ? null : $phone,
        ]);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:50', 'regex:/^\+[1-9]\d{7,14}$/'],
            'shipping_address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'zip' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:100'],
            'delivery_method_id' => ['required', 'exists:delivery_methods,id'],
            'payment_method_id' => ['required', 'exists:payment_methods,id'],
        ];
    }
}
