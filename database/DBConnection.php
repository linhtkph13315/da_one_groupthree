<?php

namespace database;

use PDO;
use PDOException;

class DBConnection
{
    public static PDO $connect;

    public static function connect() : PDO
    {
        try {
            $params = [
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ];
            self::$connect = new PDO(
                'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset='.DB_CHARSET,
                DB_USERNAME,
                DB_PASSWORD,
                $params
            );
        } catch (PDOException $exception) {
            die("PDO connected failed: ".$exception->getMessage());
        }
        return self::$connect;
    }
}