<?php

namespace App\Repositories;

use App\Interfaces\OrderPaymentRepositoryInterface;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Capsule\Manager as DB;

class OrderPaymentRepository implements OrderPaymentRepositoryInterface
{
    /**
     * Returns the base query builder instance for order_payments table.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function baseQuery(): Builder
    {
        return DB::table('order_payments');
    }

    /**
     * Store an order payment.
     *
     * @param array $data
     * @return bool
     */
    public function storePayment(array $data)
    {
        return $this->baseQuery()->insert($data);
    }
}
