<?php

class ShopController
{
    public function home($request)
    {
        return view('shop/home', [
            'featured'   => Product::featured(),
            'categories' => Product::categories(),
        ]);
    }

    public function index($request)
    {
        $categoryId = $request->input('category');
        $keyword = trim($request->input('q') ?? '');

        return view('shop/index', [
            'products'   => Product::search($categoryId, $keyword),
            'categories' => Product::categories(),
            'categoryId' => $categoryId,
            'keyword'    => $keyword,
        ]);
    }

    public function show($request)
    {
        $product = Product::find($request->input('id'));

        if (!$product) {
            return new Response('<h1>404 - Product not found</h1>', 404);
        }

        return view('shop/show', ['product' => $product]);
    }

    // Remember the chosen display currency
    public function currency($request)
    {
        check_csrf($request);

        $currency = $request->input('currency');
        if (isset(CurrencyService::CURRENCIES[$currency])) {
            $_SESSION['currency'] = $currency;
        }

        redirect($_SERVER['HTTP_REFERER'] ?? '/');
    }
}
