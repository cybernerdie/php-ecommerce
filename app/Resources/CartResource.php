<?php

namespace App\Resources;

use Core\Resource\AbstractResource;

class CartResource extends AbstractResource
{
    public function toArray(): array
    {
        return [
            'id' => $this->cart_id,
            'user_id' => $this->user_id,
            'quantity' => $this->quantity,
            'product' => [
                'id' => $this->product_id,
                'name' => $this->name,
                'price' => floatval($this->price / 100),
                'image_url' => $this->image_url,
                'brand' => $this->brand,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
