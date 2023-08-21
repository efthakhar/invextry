<?php

namespace App\Http\Resources\Accounting;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdjustmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'account_id' => $this->account_id,
            'account' => $this->account,
            'amount' => $this->amount,
            'type' => $this->type,
            'date' => $this->date,
            'note' => $this->note,
        ];
    }
}
