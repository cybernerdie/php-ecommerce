<?php

namespace App\Repositories;

use App\Interfaces\ProductRepositoryInterface;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Capsule\Manager as DB;

class ProductRepository implements ProductRepositoryInterface
{
    /**
     * Returns the base query builder instance for products table.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function baseQuery(): Builder
    {
        return DB::table('products');
    }

    /**
     * Get all the products.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function index(): Builder
    {
        return $this->baseQuery();
    }

    /**
     * Sort and paginate the products based on the given filters.
     *
     * @param \Illuminate\Database\Query\Builder $products
     * @param array $filters
     * @return \Illuminate\Support\Collection
     */
    public function sort(Builder $products, array $filters)
    {
        if (isset($filters['sort_by']) && isset($filters['sort_order'])) {
            if($filters['sort_by'] === 'date') {
                $products->orderBy('created_at', $filters['sort_order']);
            } else {
                $products->orderBy($filters['sort_by'], $filters['sort_order']);
            }
        }        

        if (isset($filters['search_item'])) {
            $products->where('name', 'LIKE', "%{$filters['search_item']}%");
        } 

        $perPage = PER_PAGE;
        $currentPage = $filters['page'] ?? 1;
        $offset = ($currentPage - 1) * $perPage;
        $products->limit($perPage)->offset($offset);

        return $products->get();
    } 
}