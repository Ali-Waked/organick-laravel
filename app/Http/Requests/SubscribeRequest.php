<?php

namespace App\Http\Requests;

use App\Enums\SubscriptionStatus;
use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubscribeRequest extends FormRequest
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
        // dd($this);

        return [
            'email' => [
                Rule::requiredIf(function () {
                    return $this->method() === 'POST';
                }),
                'email',
                Rule::unique('subscribers', 'email'),
                // $this->isMethod('post') ?
                //     'unique:subscribtions,email' :
                //     Rule::unique('subscribtions', 'email')->ignore($this->email),
            ],
            'status' => ['sometimes', 'required', Rule::enum(SubscriptionStatus::class)]
        ];
    }
    public function messages(): array
    {
        return [
            'email.email' => 'place enter valid email',
            'email.unique' => 'this email alrdy exists',
        ];
    }
}
