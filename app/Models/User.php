<?php

class User
{
    public static function findByEmail($email)
    {
        $stmt = db()->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function create($name, $email, $password)
    {
        $stmt = db()->prepare('INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)');
        $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT), 'customer']);

        return db()->lastInsertId();
    }
}
