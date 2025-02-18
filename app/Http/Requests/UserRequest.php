<?php

namespace App\Http\Requests;

use App\Enums\UserGender;
use App\Enums\UserTypes;
use App\Rules\ValidateSocialLink;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->is_admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $date = now()->subYears(20);
        $email = $this->route('user');
        // log($email);
        return [
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'birthday' => [Rule::requiredIf(in_array($this->input(key: 'type'), [UserTypes::Moderator->value])), 'date', "before:$date"],
            'image' => ['image', 'max:10020'],
            'email' => ['required', Rule::unique('users', 'email')->ignore($email)],
            'gender' => ['required', Rule::enum(UserGender::class)],
            'phone_number' => [Rule::requiredIf(in_array($this->input(key: 'type'), [UserTypes::Driver->value])), 'max:15'],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'first name is required',
            'last_name.required' => 'last name is required',
            '*.max' => 'the max value :value',
        ];
    }
}
