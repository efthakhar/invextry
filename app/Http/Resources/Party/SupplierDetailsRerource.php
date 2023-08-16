<?php

namespace App\Http\Resources\Party;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierDetailsRerource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'billing_address' => $this->billing_address,
            'shipping_address' => $this->shipping_address,
            'tax_number' => $this->tax_number,
            'country' => $this->country,
            'city' => $this->city,
            'postal_code' => $this->postal_code,
            'purchase_due' => $this->purchase_due,
            'purchase_return_due' => $this->purchase_return_due,
        ];
    }
}
