<?php

namespace App\Resources;

use Core\Resource\AbstractResource;

class ProductResource extends AbstractResource
{
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'barcode' => $this->barcode,
            'price' => floatval($this->price / 100),
            'image_url' => $this->image_url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
