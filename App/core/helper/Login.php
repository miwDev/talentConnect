<?php

namespace App\core\helper;

use App\core\helper\Session;

class Login
{

    public static function login($user)
    {
        Session::setSession($user);
    }

    public static function logout()
    {
        Session::start();
        if (self::loggedIn()) {
            Session::sessionEnd();
        }
    }

    public static function loggedIn()
    {
        Session::start();

        $loggedIn = false;

        if(isset($_SESSION['user_id'])){
            $loggedIn = true;
        }
        return $loggedIn;
    }
}
