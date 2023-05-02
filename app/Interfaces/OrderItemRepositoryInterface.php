<?php

namespace App\Interfaces;

interface OrderItemRepositoryInterface
{
    public function baseQuery();

    public function storeOrderItems(array $data);
}