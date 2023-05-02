<?php

namespace App\Repositories;

use App\Interfaces\OrderItemRepositoryInterface;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Capsule\Manager as DB;

class OrderItemRepository implements OrderItemRepositoryInterface
{
    /**
     * Returns the base query builder instance for order_items table.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function baseQuery(): Builder
    {
        return DB::table('order_items');
    }

    /**
     * Store order items.
     *
     * @param array $data
     * @return bool
     */
    public function storeOrderItems(array $data)
    {
        return $this->baseQuery()->insert($data);
    }
}
