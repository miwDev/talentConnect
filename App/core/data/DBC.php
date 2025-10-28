<?php

namespace App\core\data;

class DBC
{
    private static $conn = null;

    public static function getConnection()
    {
        if (self::$conn === null) {
            self::$conn = new PDO("mysql:host=db;dbname=user;charset=utf8", "root", "root");
        }

        return self::$conn;
    }
}
