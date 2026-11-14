<?php

class AdminController
{
    public function dashboard($request)
    {
        $pdo = db();

        return view('admin/dashboard', [
            'orders'   => Order::all(),
            'sales'    => $pdo->query("SELECT COALESCE(SUM(total), 0) FROM orders WHERE status != 'cancelled'")->fetchColumn(),
            'products' => $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn(),
            'users'    => $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'customer'")->fetchColumn(),
            'rates'    => CurrencyService::rates(),
        ]);
    }

    public function updateStatus($request)
    {
        check_csrf($request);

        if (in_array($request->input('status'), Order::STATUSES)) {
            Order::setStatus($request->input('id'), $request->input('status'));
            flash('Order status updated.');
        }

        redirect('/admin');
    }

    public function products($request)
    {
        return view('admin/products', ['products' => Product::search()]);
    }

    public function productForm($request)
    {
        $id = $request->input('id');

        return view('admin/product-form', [
            'product'    => $id ? Product::find($id) : null,
            'categories' => Product::categories(),
        ]);
    }

    public function saveProduct($request)
    {
        check_csrf($request);

        $id = $request->input('id');
        $data = [
            'category_id' => (int) $request->input('category_id'),
            'name'        => trim($request->input('name')),
            'color'       => trim($request->input('color')),
            'description' => trim($request->input('description')),
            'price'       => (float) $request->input('price'),
            'sizes'       => strtoupper(str_replace(' ', '', $request->input('sizes'))),
            'stock'       => max(0, (int) $request->input('stock')),
            'image'       => trim($request->input('image')) ?: 'placeholder.svg',
            'is_featured' => $request->input('is_featured') ? 1 : 0,
        ];

        if ($data['name'] === '' || $data['price'] <= 0 || $data['sizes'] === '') {
            flash('Name, price and sizes are required.', 'error');
            redirect('/admin/product-form' . ($id ? '?id=' . (int) $id : ''));
        }

        Product::save($data, $id);
        flash('Product saved.');
        redirect('/admin/products');
    }

    public function deleteProduct($request)
    {
        check_csrf($request);

        try {
            Product::delete($request->input('id'));
            flash('Product deleted.');
        } catch (PDOException $e) {
            // Foreign key: products that were already ordered cannot be deleted
            flash('This product has orders, so it cannot be deleted. Set its stock to 0 instead.', 'error');
        }

        redirect('/admin/products');
    }
}
