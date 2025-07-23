<?php

namespace App\Http\Requests;

use App\Enums\DiscountFor;
use App\Enums\DiscountMode;
use Illuminate\Foundation\Http\FormRequest;
use App\Enums\DiscountType;
use Illuminate\Validation\Rule;

class DiscountRequest extends FormRequest
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
        info($this->all());
        return [
            'name' => 'required|string',
            'discount_mode' => ['required', Rule::enum(DiscountMode::class)],
            'discount_type' => ["required_if:discount_mode," . DiscountMode::FIXED->value, Rule::enum(DiscountType::class)],
            'discount_for' => ['required', Rule::enum(DiscountFor::class)],
            'is_active' => 'sometimes|required|in:0,1',
            'value' => ["required_if:discount_mode," . DiscountMode::FIXED->value, 'numeric', 'min:0'],
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
            'description' => 'nullable|string',
            'product_ids' => ["required_if:discount_for," . DiscountFor::PRODUCT->value, 'array'],
            'product_ids.*' => ['exists:products,id'],
            'ranges' => ["required_if:discount_mode," . DiscountMode::RANGED->value, 'array'],
            'ranges.*.min' => 'required|numeric|min:0',
            'ranges.*.max' => 'required|numeric|gte:ranges.*.min',
            'ranges.*.value' => 'required|numeric|min:0',
            'ranges.*.type' => ['required', Rule::enum(DiscountType::class)],
        ];
    }
}
