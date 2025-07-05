<?php

namespace App\Http\Requests;

use App\Enums\NewsStatus;
use App\Enums\NewsType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NewsRequest extends FormRequest
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
        $validation = in_array($this->method(), ['PUT', 'PATCH']) ? ['sometimes', 'required'] : ['required'];
        return [
            'image' => [...$validation, 'image'],
            'title' => [...$validation, 'string', 'max:255'],
            'subtitle' => [...$validation, 'string', 'max:255'],
            'content' => [...$validation],
            // 'category_id' => [...$validation, 'int', 'exists:categories,id'],
            'type' => [...$validation, 'string', Rule::enum(NewsType::class)],
            'is_published' => ['sometimes', 'required', 'in:0,1'],
            // 'status' => ['sometimes', 'required', Rule::enum(NewsStatus::class)]
        ];
    }

    public function messages(): array
    {
        return [
            'image.required' => 'must be upload image for news',
            'status' => 'status must be type of published or archived',
        ];
    }
}
