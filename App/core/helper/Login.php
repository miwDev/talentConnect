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
        if (self::loggedIn()) {
            Session::sessionEnd();
        }
    }

    public static function loggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    // get Session Details

    public static function getUserId(): ?int
    {
        return $_SESSION['user_id'] ?? null;
    }

    public static function getUsername(): ?string
    {
        return $_SESSION['username'] ?? null;
    }
}
