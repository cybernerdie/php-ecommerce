<?php

namespace App\Interfaces;

use Illuminate\Contracts\Database\Query\Builder;

interface ProductRepositoryInterface
{
    public function baseQuery();
    
    public function index();

    public function sort(Builder $products, array $filters);
}