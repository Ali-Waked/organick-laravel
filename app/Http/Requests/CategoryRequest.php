<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
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
        $category = $this->route('category', 0);
        $validation = in_array($this->method(), ['PUT', 'PATCH']) ? ['sometimes', 'required'] : ['required'];
        return [
            'name' => [...$validation, 'string', 'max:255', 'min:3', Rule::unique('categories', 'name')->ignore($category)],
            'description' => ['nullable', 'string'],
            'image' => [...$validation, 'image'],
            'is_active' => ['nullable', 'boolean'],
            'parent_id' => ['nullable', 'int', Rule::exists('categories', 'id')]
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'category name is required',
            'image.required' => 'must uploaded image for category',
            'image.image' => 'must be type is image',
            'is_active' => 'category status is invalid',
            'parent_id.exists' => 'category must be exists',
        ];
    }
}
