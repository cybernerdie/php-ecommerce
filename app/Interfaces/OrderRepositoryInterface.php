<?php

namespace App\Interfaces;

interface OrderRepositoryInterface
{
    public function baseQuery();
    
    public function getOrders(int $userId);

    public function storeOrder(array $data);

    public function findById(int $orderId);

    public function updateOrder(int $orderId, array $data);

    public function deleteOrder(int $userId);
}