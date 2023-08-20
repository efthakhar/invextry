<?php

namespace App\Http\Resources\Accounting;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'bank_name' => $this->bank_name,
            'branch_name' => $this->branch_name,
            'account_number' => $this->account_number,
            'balance' => $this->balance,
            'details' => $this->details,
            'status' => $this->status,
        ];
    }
}
