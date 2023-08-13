<?php

namespace App\Http\Resources\Unit;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'name' => $this->name,
            'short_name' => $this->short_name,
            'base_unit_id' => $this->base_unit_id,
            'operator' => $this->operator,
            'operation_value' => $this->operation_value,
        ];
    }
}
