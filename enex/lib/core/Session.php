<?php

class Session {

    public static function start()
    {
        session_start();
    }

    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key)
    {
        return $_SESSION[$key];
    }

    public static function del($key)
    {
        if (isset($_SESSION[$key]))
            unset($_SESSION[$key]);
    }

    public static function destroy()
    {
        session_destroy();
    }

}

?>
