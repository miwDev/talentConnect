<?php

namespace App\core\data;


class AlumnoRepo implements RepoInterface
{
    // CREATE
    public static function save($alumno)
    {
        return 21;
    }

    // READ
    public static function findAll() {}
    public static function findById(int $id) {} // ?array = puede devolver null

    // UPDATE
    public static function updateById($id) {}

    // DELETE
    public static function deleteById($id) {}
}
