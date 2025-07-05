<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
        info(request()->all());
        return [
            'first_name' => ['sometimes', 'required', 'max:100'],
            'last_name' => ['sometimes', 'required', 'max:100'],
            'email' => ['sometimes', 'required', 'max:255', 'email'],
            'birthday' => ['sometimes', 'required'],
            'avatar' => ['sometimes', 'required', 'image'],
            'billing_address' => ['sometimes', 'required', 'array'],
            'billing_address.city_id' => ['sometimes', 'required', 'exists:cities,id'],
            'billing_address.street' => ['sometimes', 'required', 'max:255'],
            'billing_address.phone_number' => ['sometimes', 'required', 'max:15'],
            'billing_address.notes' => ['sometimes', 'required'],
        ];
    }
}
