<?php

namespace App\Interfaces;

interface CartRepositoryInterface
{
    public function baseQuery();
    
    public function getCartItems(int $userId);

    public function storeCartItem(array $data);

    public function findById(int $cartItemId);

    public function updateCartItem(int $cartItemId, array $data);

    public function removeCartItem(int $cartItemId);

    public function clearCart(int $userId);

    public function getCartItemByProductId(int $userId, int $productId);
}