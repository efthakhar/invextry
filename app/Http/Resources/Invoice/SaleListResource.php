<?php

namespace App\Http\Resources\Invoice;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleListResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'invoice_ref' => $this->invoice_ref,
            'invoice_date' => $this->invoice_date,
            'biller' => $this->creator->name,
            'customer' => $this->party->name,
            'warehouse' => $this->warehouse->name,
            'paid_amount' => $this->paid_amount,
            'due_amount' => $this->due_amount,
            'returned_amount' => $this->returned_amount,
            'total_amount' => $this->total_amount,
            'shipping_cost' => $this->shipping_cost,
            'invoice_status' => $this->invoice_status,
            'payment_status' => $this->payment_status,
        ];
    }
}
