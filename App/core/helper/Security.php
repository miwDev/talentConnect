<?php

namespace App\core\helper;

class Security
{

    public static function generateToken()
    {
        $timestamp = microtime(true);

        $randomNumber = random_int(100000000, 999999999);

        return $timestamp * $randomNumber; // generated Token
    }
}
