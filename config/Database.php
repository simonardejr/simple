<?php
class Database
{
    const HOST = '127.0.0.1';
    const DATABASE = 'simpleTest';
    const USER = 'root';
    const PASSWORD = '5agF56X3';

    public static $connection;

    public static function getConnection()
    {
        // $dsn = 'mysql://host='.self::HOST.';dbname='.self::DATABASE.';charset=utf8';
        $dsn = 'mysql:host='.self::HOST.';dbname='.self::DATABASE.';charset=utf8';
        // new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
        try {
            self::$connection = new \PDO($dsn, self::USER, self::PASSWORD);
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return self::$connection;
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }
}