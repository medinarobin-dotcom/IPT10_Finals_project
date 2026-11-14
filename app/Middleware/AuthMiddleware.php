<?php

class AuthMiddleware
{
    public function handle($request)
    {
        if (!isset($_SESSION['user'])) {
            http_response_code(403);
            echo "403 - Access Denied";
            return false;
        }

        return true;
    }
}