<?php

namespace App\Http\Requests;

use App\Enums\PaymentGatewayMethodStatus;
use App\Services\PaymentMethodService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;

class PaymentMethodRequest extends FormRequest
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
        $gateway = app()->make(PaymentMethodService::class)->getGateway($this->route('payment_method')->slug);

        // // Build validation rules for options dynamically
        // $optionsValidation = collect($gateway->formOptions())->mapWithKeys(function ($value, $key) {
        //     return ["options.{$key}" => $value['validation']];
        // })->toArray();

        // this code not valied
        $optionsValidation = [];
        foreach ($gateway->formOptions() as $key => $value) {
            $optionsValidation[] = ["options.{$key}" => $value['validation']];
        }
        return array_merge([
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['sometimes', 'required', 'nullable'],
            'is_active' => ['sometimes', 'required', 'in:0,1'],
        ], $optionsValidation);
    }
}
