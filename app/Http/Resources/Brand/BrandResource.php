<?php

namespace App\Http\Resources\Brand;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        $logo = [];

        if ($this->media) {
            foreach ($this->media as $m) {
                $logo[] = [
                    'id' => $m->id,
                    'url' => $m->getUrl(),
                ];
            }
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'logo' => $logo,
        ];
    }
}
