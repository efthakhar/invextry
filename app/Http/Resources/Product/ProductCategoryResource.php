<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        $thumbnail = [];

        if ($this->getMedia('thumbnail')) {
            foreach ($this->getMedia('thumbnail') as $m) {
                $thumbnail[] = [
                    'id' => $m->id,
                    'url' => $m->getUrl(),
                ];
            }
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'thumbnail' => $thumbnail,
        ];
    }
}
