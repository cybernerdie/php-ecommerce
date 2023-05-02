<?php

namespace App\Interfaces;

interface OrderPaymentRepositoryInterface
{
    public function baseQuery();

    public function storePayment(array $data);
}