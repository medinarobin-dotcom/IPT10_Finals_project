<?php

class HomeController
{
    public function index($request)
    {
        ob_start();

        require __DIR__ . '/../Views/home.php';

        $content = ob_get_clean();

        return new Response($content);
    }

    public function about($request)
    {
        return new Response("<h1>About CorePHP</h1>");
    }
}