<?php

class OrderController
{
    public function checkoutForm($request)
    {
        $lines = Cart::lines();

        if (!$lines) {
            redirect('/cart');
        }

        return view('orders/checkout', ['lines' => $lines, 'total' => Cart::total($lines)]);
    }

    public function checkout($request)
    {
        check_csrf($request);

        $address = trim($request->input('address'));
        $phone = trim($request->input('phone'));

        if ($address === '' || !preg_match('/^09\d{9}$/', $phone)) {
            flash('Please enter your address and an 11-digit mobile number (09XXXXXXXXX).', 'error');
            redirect('/checkout');
        }

        try {
            $orderId = Order::place(current_user()['id'], Cart::lines(), $address, $phone);
        } catch (Exception $e) {
            flash($e->getMessage(), 'error');
            redirect('/cart');
        }

        Cart::clear();
        flash('Thank you! Your order was placed. Pay cash on delivery.');
        redirect('/order?id=' . $orderId);
    }

    public function index($request)
    {
        return view('orders/index', ['orders' => Order::forUser(current_user()['id'])]);
    }

    public function show($request)
    {
        $order = Order::find($request->input('id'));

        // Customers may only see their own orders
        if (!$order || ($order['user_id'] != current_user()['id'] && current_user()['role'] !== 'admin')) {
            return new Response('<h1>404 - Order not found</h1>', 404);
        }

        return view('orders/show', ['order' => $order, 'items' => Order::items($order['id'])]);
    }
}
