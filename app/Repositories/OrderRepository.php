<?php

namespace App\Repositories;

use App\Interfaces\OrderRepositoryInterface;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Capsule\Manager as DB;

class OrderRepository implements OrderRepositoryInterface
{
    /**
     * Returns the base query builder instance for orders table.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function baseQuery(): Builder
    {
        return DB::table('orders');
    }

    /**
     * Get all items in the cart for a user.
     *
     * @param int $userId
     * @return mixed
     */
    public function getOrders(int $userId)
    {
        $orders = $this->baseQuery()
            ->select('orders.*', 'order_items.*', 'products.*', 'orders.total_amount as order_total_amount', 'orders.created_at as order_created_at')
            ->join('order_items', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->where('orders.user_id', '=', $userId)
            ->orderBy('orders.created_at', 'DESC')
            ->get();

        return $orders->groupBy('order_id')->values();
    }    

    /**
     * Store an order
     *
     * @param array $data
     * @return mixed
     */
    public function storeOrder(array $data)
    {
        $orderId = $this->baseQuery()
            ->insertGetId($data);

        return $this->findById($orderId);
    }

    /**
     * Find an order by ID.
     *
     * @param int $orderId
     * @return mixed
     */
    public function findById(int $orderId)
    {
        return $this->baseQuery()
            ->where('id', $orderId)
            ->first();
    }    

    /**
     * Update an order
     *
     * @param int $orderId
     * @param array $data
     * @return mixed
     */
    public function updateOrder(int $orderId, array $data)
    {
        $this->baseQuery()
            ->where('id', $orderId)
            ->update($data);

        return $this->findById($orderId);
    }    

    /**
     * Delete an order
     *
     * @param int $orderId
     * @return mixed
     */
    public function deleteOrder(int $orderId)
    {
        return $this->baseQuery()
            ->where('id', $orderId)
            ->delete();
    }
}
