<?php

namespace App\Http\Requests;

use App\Enums\AbilityStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
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
        $role = $this->route('role', 0);
        $validation = in_array($this->method(), ['PUT', 'PATCH']) ? ['sometimes', 'required'] : ['required'];
        return [
            'name' => [...$validation, 'string', 'max:100', Rule::unique('roles', 'name')->ignore($role)],
            'abilities' => [...$validation, 'array'],
            'abilities.*.ability' => ['string'],
            'abilities.*.status' => ['string', Rule::enum(AbilityStatus::class)],
        ];
    }
}
