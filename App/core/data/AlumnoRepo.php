<?php

class AlumnoRepo implements RepoInterface
{
    // CREATE
    public static function save($data)
    {
        return 1;
    }

    // READ
    public static function findAll() {}
    public static function findById(int $id) {} // ?array = puede devolver null

    // UPDATE
    public static function updateById($id) {}

    // DELETE
    public static function deleteById($id) {}
}
