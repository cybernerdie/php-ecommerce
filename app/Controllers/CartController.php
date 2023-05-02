<?php

namespace App\Controllers;

use App\Core\Response;
use App\Repositories\CartRepository;
use App\Requests\StoreCartRequest;
use App\Requests\UpdateCartRequest;
use App\Resources\CartResource;

class CartController
{
    protected CartRepository $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

     /**
     * Get all items in the cart for a user.
     *
     * @param int $userId
     * @return Response
     */
    public function getCartItems()
    {
        $cartItems = $this->cartRepository->getCartItems(user()->id);
        $cartsResource = CartResource::collection($cartItems);

        return Response::send(true, HTTP_OK, 'Cart items retrieved successfully', $cartsResource);
    }

    /**
     * Store a cart item.
     *
     * @param StoreCartRequest $request
     * @return Response
     */
    public function storeCartItem(StoreCartRequest $request)
    {
        $validatedData = $request->validated();
        $userId = user()->id;
        $validatedData['user_id'] = $userId;

        $productExistInCart = $this->cartRepository->getCartItemByProductId($userId, $validatedData['product_id']);

        if( $productExistInCart) {
            return Response::send(false, HTTP_CONFLICT, 'Product already in cart');
        }

        $cart = $this->cartRepository->storeCartItem($validatedData);
        $cartResource = (new CartResource($cart))->toArray();

        return Response::send(true, HTTP_CREATED, 'Product added to cart successfully', $cartResource);
    }

    /**
     * Update a cart item.
     *
     * @param UpdateCartRequest $request
     * @param int $cartItemId
     * @return Response
     */
    public function updateCartItem(UpdateCartRequest $request, int $cartItemId)
    {
        $validatedData = $request->validated();
        $cart = $this->cartRepository->findById($cartItemId);
        $response = $this->checkCart($cart);

        if (!$response) {
            return $response;
        }

        $updatedCartItem = $this->cartRepository->updateCartItem($cartItemId, $validatedData);

        return Response::send(true, HTTP_OK, 'Cart item updated successfully', $updatedCartItem);
    }

    /**
     * Delete a cart item.
     *
     * @param int $cartId
     * @return Response
     */
    public function removeCartItem(int $cartItemId)
    {
        $cart = $this->cartRepository->findById($cartItemId);
        $response = $this->checkCart($cart);

        if (!$response) {
            return $response;
        }
    
        $this->cartRepository->removeCartItem($cartItemId);
    
        return Response::send(true, HTTP_OK, 'Cart item removed successfully');
    }   
    
    public function clearCart()
    {
        $this->cartRepository->clearCart(user()->id);

        return Response::send(true, HTTP_OK, 'Cart cleared successfully');
    }

    /**
     * Check if the given cart item exists and belongs to the authenticated user.
     *
     * @param object|null $cart
     * @return Response
     */
    private function checkCart(?object $cart)
    {
        if (!$cart) {
            return Response::send(false, HTTP_NOT_FOUND, 'Cart item not found');
        }

        if ($cart->user_id !== user()->id) {
            return Response::send(false, HTTP_UNAUTHORIZED, 'This cart item does not belong to you.');
        }

        return true;
    }
}
