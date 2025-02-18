<?php

namespace App\Http\Requests;

use App\Enums\ProductStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
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
        // dd($this->tags, gettype($this->tags));
        $validation = in_array($this->method(), ['PUT', 'PATCH']) ? ['sometimes', 'required'] : ['required'];
        return [
            'name' => [...$validation, 'string', 'max:255'],
            'image' => [...$validation, 'image'],
            'description' => [...$validation, 'string'],
            'price' => [...$validation, 'numeric', 'min:1'],
            'low_stock_threshold' => ['nullable', 'integer', 'min:0'],
            'quantity' => ['nullable', 'integer', 'min:0'],
            // 'discount' => [...$validation, 'numeric', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'category_id' => [...$validation, 'int', 'exists:categories,id'],
            'tags' => ['nullable', 'string'],
        ];
    }
}
