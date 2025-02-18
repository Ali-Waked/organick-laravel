<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'avatar' => $this->avatar,
            'email' => $this->email,
            'birthday' => $this->birthday,
            'last_active_at' => $this->last_active_at,
            'gender' => $this->gender,
            'billing_address' => [
                'phone_number' => $this->billingAddress?->phone_number,
                'street' => $this->billingAddress?->street,
                'city' => $this->billingAddress?->city->name,
                'note' => $this->billingAddress?->note,
            ]
        ];
    }
}
