<?php

namespace App\core\data;

use App\core\model\Alumno;


class AlumnoRepo implements RepoInterface
{
    // CREATE
    public static function save($alumno) {}

    // READ
    public static function findAll() {}
    public static function findById(int $id) {}

    // UPDATE
    public static function updateById($id) {}

    // DELETE
    public static function deleteById($id) {}
}
