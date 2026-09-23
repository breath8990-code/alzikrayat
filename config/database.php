<?php
class Database
{
    private static ?PDO $connection = null;

    public static function connect(): PDO
    {
        if (self::$connection === null) {
            $host = getenv('ALZIKRAYAT_DB_HOST') ?: '127.0.0.1';
            $port = getenv('ALZIKRAYAT_DB_PORT') ?: '3306';
            $name = getenv('ALZIKRAYAT_DB_NAME') ?: 'alzikrayat';
            $user = getenv('ALZIKRAYAT_DB_USER') ?: 'root';
            $pass = getenv('ALZIKRAYAT_DB_PASS') ?: '1234';
            $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";
            self::$connection = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        }
        return self::$connection;
    }
}
