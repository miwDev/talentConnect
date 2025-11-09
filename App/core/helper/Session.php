<?php

namespace App\core\helper;

class Session
{

    public static function setSession($user)
    {
        session_start();
        $_SESSION['user_id'] = $user->id;
        $_SESSION['username'] = $user->username;
    }

    public static function sessionEnd()
    {
        unset($_SESSION);
        session_destroy();
    }
}
