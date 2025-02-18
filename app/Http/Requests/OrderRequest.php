<?php

namespace App\Http\Requests;

use App\Enums\OrderStatus;
use App\Enums\PayMethods;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderRequest extends FormRequest
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
            'method' => ['sometimes', 'required', Rule::enum(PayMethods::class)],
            // 'status' => ['sometimes','required',Rule::in(OrderStatus::Canceled,OrderStatus:)]
            // 'items' => ['required','array'],
            // 'items.*.product_id' => ['required','integer','exists:products,id'],
        ];
    }
}
