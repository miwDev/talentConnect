<?php

namespace App\core\helper;

use App\core\model\Alumno;
use App\core\model\Empresa;

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
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION['token'] ?? null;
    }

    public static function readUser()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION['username'] ?? null;
    }

    public static function readRole()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION['role'] ?? null;
    }

    public static function readUserId()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION['user_id'] ?? null;
    }

    public static function sessionEnd()
    {
        unset($_SESSION);
        session_destroy();
    }
}
