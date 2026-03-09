<?php

declare(strict_types=1);

class Database
{
    private const string DSN = 'mysql:host=127.0.0.1;port=3307;dbname=categories;charset=utf8mb4';
    private const string USER = 'root';
    private const string PASSWORD = 'root';
    private static ?PDO $instance = null;

    private function __construct()
    {
    }

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            self::$instance = new PDO(self::DSN, self::USER, self::PASSWORD, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        }

        return self::$instance;
    }

    private function __clone()
    {
    }
}
