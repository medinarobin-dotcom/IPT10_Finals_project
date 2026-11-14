<?php

// Loads the store's files. Called from routes/web.php.

date_default_timezone_set('Asia/Manila');

require_once __DIR__ . '/helpers.php';

require_once __DIR__ . '/Middleware/AdminMiddleware.php';

require_once __DIR__ . '/Models/Product.php';
require_once __DIR__ . '/Models/Order.php';
require_once __DIR__ . '/Models/User.php';

require_once __DIR__ . '/Services/Cart.php';
require_once __DIR__ . '/Services/CurrencyService.php';

require_once __DIR__ . '/Controllers/ShopController.php';
require_once __DIR__ . '/Controllers/CartController.php';
require_once __DIR__ . '/Controllers/AuthController.php';
require_once __DIR__ . '/Controllers/OrderController.php';
require_once __DIR__ . '/Controllers/AdminController.php';
