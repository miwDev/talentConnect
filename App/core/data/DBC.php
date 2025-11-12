<?php

namespace App\core\data;

use PDO;

class DBC
{
    private static $conn = null;

    public static function getConnection()
    {
        if (self::$conn === null) {
            self::$conn = new PDO("mysql:host=db;dbname=talent_connect_db;charset=utf8", "root", "root");
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$conn;
    }
}
