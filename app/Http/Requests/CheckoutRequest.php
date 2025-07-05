<?php

namespace App\Http\Requests;

use App\Enums\CurrencyCode;
use App\Enums\PaymentMethods;
use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\Intl\Countries;

class CheckoutRequest extends FormRequest
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
            'pay_method' => ['required', Rule::enum(PaymentMethods::class)],
            'phone_number' => ['required', 'numeric'],
            'street' => ['required'],
            'city_id' => ['required', 'integer', 'exists:cities,id'],
            'note' => ['sometimes', 'required', 'string'],
            // 'city' => ['required', 'max:255'],
            // 'postal_code' => ['required', 'max:255'],
            // 'state' => ['required', 'max:255'],
            'currency' => ['required', 'max:3', Rule::enum(CurrencyCode::class)]
        ];
    }
}
