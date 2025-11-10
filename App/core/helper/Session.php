<?php

namespace App\core\helper;

use App\core\model\Alumno;

class Session
{

    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function setSession($user)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['user_id'] = $user['user']->id;
        $_SESSION['username'] = $user['user']->username;
        $_SESSION['token'] = $user['token'];

        if ($user['user'] instanceof Alumno) {
            $_SESSION['role'] = "ROLE_ALUMNO";
        } elseif ($user['user'] instanceof Empresa) {
            $_SESSION['role'] = "ROLE_EMPRESA";
        } else {
            $_SESSION['role'] = "ROLE_ADMIN";
        }
    }

    public static function readToken()
    {
        return $_SESSION['token'] ?? null;
    }

    public static function readUser()
    {
        return $_SESSION['username'] ?? null;
    }

    public static function readRole()
    {
        return $_SESSION['role'] ?? null;
    }

    public static function sessionEnd()
    {
        unset($_SESSION);
        session_destroy();
    }
}
