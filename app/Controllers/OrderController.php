<?php

namespace App\Controllers;

use App\Actions\CheckoutAction;
use App\Core\Response;
use App\Repositories\OrderRepository;
use App\Resources\OrderResource;

class OrderController
{
    protected OrderRepository $orderRepository;
    protected CheckoutAction $checkoutAction;

    public function __construct(
        OrderRepository $orderRepository,
        CheckoutAction $checkoutAction
    ) {
        $this->orderRepository = $orderRepository;
        $this->checkoutAction = $checkoutAction;
    }

     /**
     * Get all orders for a user.
     *
     * @return Response
     */
    public function getOrders()
    {
        $orders = $this->orderRepository->getOrders(user()->id);
        $ordersResource = OrderResource::collection($orders);

        return Response::send(true, HTTP_OK, 'Orders retrieved successfully', $ordersResource);
    }

    /**
     * Checkout the user's cart and create an order.
     *
     * @return Response
     */
    public function checkout()
    {
        $userId = user()->id;
        $order = $this->checkoutAction->handle($userId);

        return Response::send(true, HTTP_CREATED, 'Order created successfully', $order);
    }
}
