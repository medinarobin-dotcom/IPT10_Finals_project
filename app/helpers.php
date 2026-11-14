<?php

// Small helper functions used by the store (controllers and views).

// Shared PDO connection from the framework's Database class.
function db()
{
    return Database::connect($GLOBALS['config']['database']);
}

// Render a view inside the main layout and return a framework Response.
function view($name, $data = [])
{
    extract($data);

    ob_start();
    require __DIR__ . '/Views/' . $name . '.php';
    $content = ob_get_clean();

    ob_start();
    require __DIR__ . '/Views/layout.php';

    return new Response(ob_get_clean());
}

// Redirect using the framework Response.
function redirect($path)
{
    (new Response())->redirect($path);
}

// Escape text before printing it in HTML (prevents XSS).
function e($text)
{
    return htmlspecialchars((string) $text, ENT_QUOTES, 'UTF-8');
}

// Show a one-time message on the next page.
function flash($message, $type = 'success')
{
    $_SESSION['flash'] = ['message' => $message, 'type' => $type];
}

function current_user()
{
    return $_SESSION['user'] ?? null;
}

// Format a peso price in the shopper's chosen currency (uses the API rates).
function price($pesos)
{
    $currency = $_SESSION['currency'] ?? 'PHP';

    return CurrencyService::format($pesos, $currency);
}

function peso($amount)
{
    return '₱' . number_format($amount, 2);
}

// CSRF protection: every POST form includes this hidden token.
function csrf_field()
{
    if (empty($_SESSION['token'])) {
        $_SESSION['token'] = bin2hex(random_bytes(16));
    }

    return '<input type="hidden" name="_token" value="' . $_SESSION['token'] . '">';
}

function check_csrf($request)
{
    $token = $request->input('_token');

    if (!$token || !hash_equals($_SESSION['token'] ?? '', $token)) {
        http_response_code(419);
        exit('Page expired. Go back, refresh and try again.');
    }
}
