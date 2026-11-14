<?php

class Request
{
    public function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function path()
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    public function input($key)
    {
        return $_POST[$key] ?? $_GET[$key] ?? null;
    }
}