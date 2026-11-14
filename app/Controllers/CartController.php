<?php

class CartController
{
    public function index($request)
    {
        $lines = Cart::lines();

        return view('cart/index', ['lines' => $lines, 'total' => Cart::total($lines)]);
    }

    public function add($request)
    {
        check_csrf($request);

        $product = Product::find($request->input('id'));
        $size = $request->input('size');
        $qty = max(1, (int) $request->input('qty'));

        if (!$product || !in_array($size, explode(',', $product['sizes']))) {
            flash('Please choose a size.', 'error');
            redirect('/product?id=' . (int) $request->input('id'));
        }

        if ($qty > $product['stock']) {
            flash("Only {$product['stock']} left in stock.", 'error');
            redirect('/product?id=' . $product['id']);
        }

        Cart::add($product['id'], $size, $qty);
        flash($product['name'] . ' was added to your cart.');
        redirect('/cart');
    }

    public function update($request)
    {
        check_csrf($request);

        Cart::update($request->input('key'), (int) $request->input('qty'));
        redirect('/cart');
    }
}
