<?php

namespace App\Http\Resources\Party;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierListResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'status' => $this->status,
            'purchase_due' => $this->purchase_due,
            'purchase_return_due' => $this->purchase_return_due,
        ];
    }
}
