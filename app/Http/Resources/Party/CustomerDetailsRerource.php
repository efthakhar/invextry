<?php

namespace App\Http\Resources\Party;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerDetailsRerource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->status,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'billing_address' => $this->billing_address,
            'shipping_address' => $this->shipping_address,
            'tax_number' => $this->tax_number,
            'country' => $this->country,
            'city' => $this->city,
            'postal_code' => $this->postal_code,
            'sale_due' => $this->sale_due,
            'sale_return_due' => $this->sale_return_due,
        ];
    }
}
