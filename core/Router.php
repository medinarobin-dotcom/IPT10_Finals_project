<?php

class Router
{
    private $routes = [];

    public function get($path, $callback, $middleware = [])
    {
        $this->routes['GET'][$path] = [
            'callback' => $callback,
            'middleware' => $middleware
        ];
    }

    public function post($path, $callback, $middleware = [])
    {
        $this->routes['POST'][$path] = [
            'callback' => $callback,
            'middleware' => $middleware
        ];
    }

    public function dispatch($request)
    {
        $method = $request->method();
        $path = $request->path();

        $route = $this->routes[$method][$path] ?? null;

        if (!$route) {
            http_response_code(404);
            echo "404 - Page Not Found";
            return;
        }

        foreach ($route['middleware'] as $middleware) {
            if (!$middleware->handle($request)) {
                return;
            }
        }

        $callback = $route['callback'];
        $response = $callback($request);

        if ($response instanceof Response) {
            $response->send();
        } elseif ($response !== null) {
            echo $response;
        }
    }
}