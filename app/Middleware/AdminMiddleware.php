<?php

class AdminMiddleware
{
    public function handle($request)
    {
        $user = $_SESSION['user'] ?? null;

        if (!$user || $user['role'] !== 'admin') {
            http_response_code(403);
            echo "403 - Admins only";
            return false;
        }

        return true;
    }
}
