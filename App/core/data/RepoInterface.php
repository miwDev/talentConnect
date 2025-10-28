<?php

interface RepoInterface
{
    // CREATE
    public static function save($data);

    // READ
    public static function findAll();
    public static function findById(int $id);

    // UPDATE
    public static function updateById($id);

    // DELETE
    public static function deleteById($id);
}
