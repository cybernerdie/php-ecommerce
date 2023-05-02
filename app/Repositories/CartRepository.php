<?php

namespace App\Repositories;

use App\Interfaces\CartRepositoryInterface;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Capsule\Manager as DB;

class CartRepository implements CartRepositoryInterface
{
    /**
     * Returns the base query builder instance for carts table.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function baseQuery(): Builder
    {
        return DB::table('carts');
    }

    /**
     * Get all items in the cart for a user.
     *
     * @param int $userId
     * @return mixed
     */
    public function getCartItems(int $userId)
    {
        return $this->baseQuery()
            ->select('carts.*', 'carts.id as cart_id', 'products.*') 
            ->join('products', 'carts.product_id', '=', 'products.id')
            ->where('carts.user_id', $userId)
            ->get();
    }    

    /**
     * Add a product to the cart.
     *
     * @param array $data
     * @return mixed
     */
    public function storeCartItem(array $data)
    {
        $cartId = $this->baseQuery()
            ->insertGetId($data);

        return $this->findById($cartId);
    }

    /**
     * Find a cart item by ID.
     *
     * @param int $cartItemId
     * @return mixed
     */
    public function findById(int $cartItemId)
    {
        return $this->baseQuery()
            ->select('carts.*', 'carts.id as cart_id', 'products.*') 
            ->join('products', 'products.id', '=', 'carts.product_id')
            ->where('carts.id', $cartItemId)
            ->first();
    }    

    /**
     * Update a cart item.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateCartItem(int $cartItemId, array $data)
    {
        $this->baseQuery()
            ->where('id', $cartItemId)
            ->update($data);

        return $this->findById($cartItemId);
    }    

    /**
     * Remove a cart item.
     *
     * @param int $cartItemId
     * @return mixed
     */
    public function removeCartItem(int $cartItemId)
    {
        return $this->baseQuery()
            ->where('id', $cartItemId)
            ->delete();
    }

    /**
     * Removes all cart items for a user.
     *
     * @param int $userId
     * @return mixed
     */
    public function clearCart(int $userId)
    {
        return $this->baseQuery()
            ->where('user_id', $userId)
            ->delete();
    }

    /**
     * Get the cart item for a given user ID and product ID.
     *
     * @param int $userId The user ID.
     * @param int $productId The product ID.
     * @return object|null
     */
    public function getCartItemByProductId(int $userId, int $productId): ?object
    {
        return $this->baseQuery()
            ->where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();
    }
}
