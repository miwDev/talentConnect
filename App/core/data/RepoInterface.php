<?php

namespace App\core\data;

interface RepoInterface
{
    // CREATE
    public static function save($data);

    // READ
    public static function findAll();
    public static function findById(int $id);

    // UPDATE
    public static function updateById($object);

    // DELETE
    public static function deleteById($id);
}
