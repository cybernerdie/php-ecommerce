<?php

namespace App\Resources;

use Core\Resource\AbstractResource;

class OrderResource extends AbstractResource
{
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'quantity' => $this->quantity,
            'total_amount' => floatval($this->total_amount / 100),
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

    public static function collection($items): array
    {
        return array_map(function ($item) {
            $totalAmount = 0;
            $orderItems = [];
            $orderId = '';
            $orderDate = '';

            foreach ($item as $orderItem) {
                $totalAmount = floatval($orderItem->order_total_amount / 100);
                $orderId = $orderItem->order_id;
                $orderDate = $orderItem->order_created_at;
                $orderItems[] = (new static($orderItem))->toArray();
            }

            return [
                'total_amount' => $totalAmount,
                'order_id' => $orderId,
                'order_date' => $orderDate,
                'items' => $orderItems,
            ];
        }, $items->toArray());
    }
}
