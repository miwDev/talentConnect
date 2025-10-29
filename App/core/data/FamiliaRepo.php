<?php

namespace App\core\data;

use PDO;
use PDOException;
use Exception;

use App\core\model\Familia;

class FamiliaRepo implements RepoInterface
{
    // CREATE
    public static function save($familia) {}

    // READ
    public static function findAll() {}
    public static function findById(int $id) {}

    // UPDATE
    public static function updateById($id) {}

    // DELETE
    public static function deleteById($id) {}
}
