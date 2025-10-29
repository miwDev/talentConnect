<?php

namespace App\core\data;

use App\core\model\Ciclo;

use PDO;
use PDOException;
use Exception;

class CicloRepo implements RepoInterface
{
    // CREATE
    public static function save($ciclo) {}

    // READ
    public static function findAll() {}
    public static function findById(int $id) {}

    // UPDATE
    public static function updateById($id) {}

    // DELETE
    public static function deleteById($id) {}
}
