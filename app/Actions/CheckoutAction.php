<?php

namespace App\Actions;

use App\Repositories\CartRepository;
use App\Repositories\OrderItemRepository;
use App\Repositories\OrderPaymentRepository;
use App\Repositories\OrderRepository;
use App\Core\Response;
use Illuminate\Database\Capsule\Manager as DB;

class CheckoutAction
{
    protected $cartRepository;
    protected $orderRepository;
    protected $orderItemRepository;
    protected $orderPaymentRepository;

    public function __construct(
        CartRepository $cartRepository, 
        OrderRepository $orderRepository, 
        OrderItemRepository $orderItemRepository, 
        OrderPaymentRepository $orderPaymentRepository
    ) {
        $this->cartRepository = $cartRepository;
        $this->orderRepository = $orderRepository;
        $this->orderItemRepository = $orderItemRepository;
        $this->orderPaymentRepository = $orderPaymentRepository;
    }

    public function handle($userId)
    {
        DB::beginTransaction();

        try {
            $cartItems = $this->cartRepository->getCartItems($userId);

            // Check if cart is empty
            if (!$cartItems->count()) {
                return Response::send(false, HTTP_BAD_REQUEST, 'Your cart is empty. Please add a product to your cart before proceeding to checkout.');
            }

            $order = $this->storeOrder($cartItems, $userId);

            // Store order items
            $this->storeOrderItems($cartItems, $order->id);

            // Process payment
            $this->processPayment($order->id, $order->total_amount);

            // Clear the user cart
            $this->cartRepository->clearCart($userId);

            DB::commit();

            return $order;

        } catch (\Exception $e) {
            DB::rollBack();
            return Response::send(false, HTTP_INTERNAL_SERVER_ERROR, 'An error occurred while processing your order. Please try again later.');
        }
    }


    /**
     * Calculate the total amount of the cart items.
     *
     * @param $cartItems
     * @return int
     */
    private function calculateTotalAmount($cartItems): int
    {
        $totalAmount = 0;
        $cartItems->each(function ($item) use (&$totalAmount) {
            $totalAmount += $item->price * $item->quantity;
        });

        return $totalAmount;
    }

    /**
     * Create an array of order items from the cart items.
     *
     * @param object $cartItems
     * @param int $userId
     * @param int $orderId
     * @return object
     */
    private function storeOrder(object $cartItems, int $userId): object
    {
        // Calculate total amount
        $totalAmount = $this->calculateTotalAmount($cartItems);

        // Store order data
        $orderData = [ 
            'user_id' => $userId,
            'total_amount' => $totalAmount
        ];

        return $this->orderRepository->storeOrder($orderData);
    }

    /**
     * Create an array of order items from the cart items.
     *
     * @param object $cartItems
     * @param int $orderId
     * @return void
     */
    private function storeOrderItems(object $cartItems, int $orderId): void
    {
        $orderItems = [];
        $cartItems->each(function ($item) use (&$orderItems, $orderId) {
            $orderItems[] = [
                'product_id' => $item->product_id,
                'order_id' => $orderId,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'total_amount' => $item->price * $item->quantity,
            ];
        });

        $this->orderItemRepository->storeOrderItems($orderItems);
    }

    /**
     * Process payment for the given order.
     *
     * @param int $orderId
     * @param int $totalAmount
     * @return void
     */
    protected function processPayment(int $orderId, int $totalAmount): void
    {
        $paymentData = [
            'amount' => $totalAmount,
            'order_id' => $orderId,
            'payment_method' => DEFAULT_PAYMENT_METHOD
        ];

        $this->orderPaymentRepository->storePayment($paymentData);
    } 
}
