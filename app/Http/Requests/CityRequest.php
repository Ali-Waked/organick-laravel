<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CityRequest extends FormRequest
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
            "name" => ["required", "string", "min:3", "max:255"],
            "driver_price" => ["required", "numeric"],
        ];
    }
    public function messages(): array
    {
        return [
            "driver_price.required" => "driver price for city is required",
            "driver_price.numeric" => "driver must be numeric type",
        ];
    }
}
