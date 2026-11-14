<?php

class Database
{
    private static $connection = null;

    public static function connect($config)
    {
        if (self::$connection === null) {
            self::$connection = new PDO(
                "mysql:host={$config['host']};dbname={$config['name']}",
                $config['username'],
                $config['password']
            );

            self::$connection->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
        }

        return self::$connection;
    }
}