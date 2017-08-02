<?php namespace RescueCore\Services;

class Session
{
    static public function add($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    static public function get($key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    static public function all()
    {
        return $_SESSION;
    }

    static public function remove($key)
    {
        unset($_SESSION[$key]);
    }
}
