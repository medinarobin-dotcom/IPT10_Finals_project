<?php

// Load the Clothing Store application files
require_once __DIR__ . '/../app/bootstrap.php';

$shop  = new ShopController();
$cart  = new CartController();
$auth  = new AuthController();
$order = new OrderController();
$admin = new AdminController();

$log      = new LoggingMiddleware();
$loggedIn = [$log, new AuthMiddleware()];
$isAdmin  = [$log, new AdminMiddleware()];

// Shop
$router->get('/', [$shop, 'home'], [$log]);
$router->get('/shop', [$shop, 'index'], [$log]);
$router->get('/product', [$shop, 'show'], [$log]);
$router->post('/currency', [$shop, 'currency'], [$log]);

// Cart
$router->get('/cart', [$cart, 'index'], [$log]);
$router->post('/cart/add', [$cart, 'add'], [$log]);
$router->post('/cart/update', [$cart, 'update'], [$log]);

// Login / register
$router->get('/login', [$auth, 'loginForm'], [$log]);
$router->post('/login', [$auth, 'login'], [$log]);
$router->get('/register', [$auth, 'registerForm'], [$log]);
$router->post('/register', [$auth, 'register'], [$log]);
$router->post('/logout', [$auth, 'logout'], [$log]);

// Checkout and orders (must be logged in)
$router->get('/checkout', [$order, 'checkoutForm'], $loggedIn);
$router->post('/checkout', [$order, 'checkout'], $loggedIn);
$router->get('/orders', [$order, 'index'], $loggedIn);
$router->get('/order', [$order, 'show'], $loggedIn);

// Admin (must be admin)
$router->get('/admin', [$admin, 'dashboard'], $isAdmin);
$router->post('/admin/order-status', [$admin, 'updateStatus'], $isAdmin);
$router->get('/admin/products', [$admin, 'products'], $isAdmin);
$router->get('/admin/product-form', [$admin, 'productForm'], $isAdmin);
$router->post('/admin/product-save', [$admin, 'saveProduct'], $isAdmin);
$router->post('/admin/product-delete', [$admin, 'deleteProduct'], $isAdmin);

// Original CorePHP pages
$router->get('/about', [$home, 'about']);
