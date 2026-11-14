<?php

/**
 * Simple unit tests for CorePHP and the Clothing Store.
 * Run from the project folder:   php tests/run.php
 * (No MySQL or internet needed.)
 */

// Keep output buffered so the framework can still set HTTP status codes,
// and send expected API error messages to a temp log instead of the screen.
ob_start();
ini_set('error_log', sys_get_temp_dir() . '/corephp-tests.log');

require __DIR__ . '/../core/Request.php';
require __DIR__ . '/../core/Response.php';
require __DIR__ . '/../core/Router.php';
require __DIR__ . '/../app/Middleware/AuthMiddleware.php';
require __DIR__ . '/../app/Middleware/AdminMiddleware.php';
require __DIR__ . '/../app/helpers.php';
require __DIR__ . '/../app/Services/Cart.php';
require __DIR__ . '/../app/Services/CurrencyService.php';

$passed = 0;
$failed = 0;

function test($name, $callback)
{
    global $passed, $failed;
    $_SESSION = [];
    CurrencyService::reset();

    try {
        $callback();
        $passed++;
        echo "PASS  $name\n";
    } catch (Throwable $e) {
        $failed++;
        echo "FAIL  $name -> " . $e->getMessage() . "\n";
    }
}

function check($condition, $message = 'check failed')
{
    if (!$condition) {
        throw new Exception($message);
    }
}

// Runs a request through the router and returns the page output.
function visit($router, $method, $path)
{
    $_SERVER['REQUEST_METHOD'] = $method;
    $_SERVER['REQUEST_URI'] = $path;

    ob_start();
    $router->dispatch(new Request());

    return ob_get_clean();
}

// ---------------- Framework tests ----------------

test('Router runs the matching route', function () {
    $router = new Router();
    $router->get('/shop', fn ($request) => new Response('Shop page'));
    check(visit($router, 'GET', '/shop?q=shirt') === 'Shop page');
});

test('Router shows 404 for unknown pages', function () {
    $router = new Router();
    check(visit($router, 'GET', '/nothing-here') === '404 - Page Not Found');
});

test('Router separates GET and POST', function () {
    $router = new Router();
    $router->post('/cart/add', fn ($request) => 'added');
    check(visit($router, 'GET', '/cart/add') === '404 - Page Not Found');
    check(visit($router, 'POST', '/cart/add') === 'added');
});

test('AuthMiddleware blocks guests', function () {
    $router = new Router();
    $router->get('/orders', fn ($request) => 'my orders', [new AuthMiddleware()]);
    check(visit($router, 'GET', '/orders') === '403 - Access Denied');

    $_SESSION['user'] = ['id' => 1, 'name' => 'Juan', 'role' => 'customer'];
    check(visit($router, 'GET', '/orders') === 'my orders');
});

test('AdminMiddleware blocks customers but allows admins', function () {
    $router = new Router();
    $router->get('/admin', fn ($request) => 'dashboard', [new AdminMiddleware()]);

    $_SESSION['user'] = ['id' => 2, 'name' => 'Juan', 'role' => 'customer'];
    check(visit($router, 'GET', '/admin') === '403 - Admins only');

    $_SESSION['user'] = ['id' => 1, 'name' => 'Admin', 'role' => 'admin'];
    check(visit($router, 'GET', '/admin') === 'dashboard');
});

test('Request reads input and path', function () {
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['REQUEST_URI'] = '/product?id=5';
    $_GET = ['id' => '5'];
    $request = new Request();
    check($request->path() === '/product');
    check($request->input('id') === '5');
    $_GET = [];
});

// ---------------- Application tests ----------------

test('Cart adds, counts and removes items', function () {
    Cart::add(5, 'M', 1);
    Cart::add(5, 'M', 2);
    Cart::add(11, '32', 1);
    check(Cart::count() === 4, 'count should be 4');

    Cart::update('5|M', 0);
    check(Cart::count() === 1, 'item should be removed');
});

test('Cart total multiplies price by quantity', function () {
    $lines = [['price' => 499, 'qty' => 2], ['price' => 1999, 'qty' => 1]];
    check(Cart::total($lines) == 2997);
});

test('Currency API response is read correctly', function () {
    $fakeApi = fn ($url) => [200, '[{"date":"2026-11-08","base":"PHP","quote":"USD","rate":0.015868},{"quote":"EUR","rate":0.01415}]'];
    $rates = CurrencyService::fetchFromApi($fakeApi);
    check($rates['USD'] == 0.015868 && $rates['EUR'] == 0.01415);
});

test('Currency API errors are detected (429, 500, bad JSON)', function () {
    foreach ([[429, ''], [500, ''], [200, 'not json']] as $fake) {
        $thrown = false;
        try {
            CurrencyService::fetchFromApi(fn ($url) => $fake);
        } catch (Exception $e) {
            $thrown = true;
        }
        check($thrown, "expected an error for HTTP {$fake[0]}");
    }
});

test('Saved rates are used when the API is offline', function () {
    $pdo = new PDO('sqlite::memory:');
    $pdo->exec("CREATE TABLE currency_rates (currency TEXT PRIMARY KEY, rate REAL, updated_at TEXT)");
    $pdo->exec("INSERT INTO currency_rates VALUES ('USD', 0.017, '2020-01-01 00:00:00')");

    $offline = function ($url) { throw new Exception('no internet'); };
    $rates = CurrencyService::rates($pdo, $offline);
    check($rates['USD'] == 0.017);
});

test('Fresh rates from the API are saved', function () {
    $pdo = new PDO('sqlite::memory:');
    $pdo->exec("CREATE TABLE currency_rates (currency TEXT PRIMARY KEY, rate REAL, updated_at TEXT)");
    $pdo->exec("INSERT INTO currency_rates VALUES ('USD', 0.017, '2020-01-01 00:00:00')");

    $online = fn ($url) => [200, '[{"quote":"USD","rate":0.02}]'];
    $rates = CurrencyService::rates($pdo, $online);
    check($rates['USD'] == 0.02);
});

test('HTML is escaped to prevent XSS', function () {
    check(e('<script>alert(1)</script>') === '&lt;script&gt;alert(1)&lt;/script&gt;');
});

echo "\n$passed passed, $failed failed\n";
