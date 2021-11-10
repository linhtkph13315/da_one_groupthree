<?php

namespace storage;

class Session
{
    public function __construct()
    {
        session_start();
    }

    public static function get($key)
    {
        return $_SESSION[$key] ?? '';
    }

    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function remove($key)
    {
        unset($_SESSION[$key]);
    }

    public static function message($value)
    {
        $_SESSION['flash_message'] = $value;
    }

    public static function has($key) : bool
    {
        if (self::get($key)) {
            return true;
        }
        return false;
    }
}