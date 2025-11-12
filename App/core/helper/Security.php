<?php

namespace App\core\helper;

use App\core\helper\Login;
use App\core\helper\Session;

class Security
{

    public static function generateToken()
    {
        // $timestamp = microtime(true);

        // $randomNumber = round(random_int(100000, 999999));

        // return $timestamp * $randomNumber; // generated Token

        return bin2hex(random_bytes(32));
    }

    public static function passEncrypter($pass){
        return true;
    }
    public static function passVerify($pass){
        return true;
    }

    
}


