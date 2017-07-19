<?php

namespace Kabas\Utils;

use Kabas\App;

class Auth
{

    static function __callStatic($name, $args)
    {
        return App::content()->administrators->$name(...$args);
    }

    /**
     * Check if an administrator account is currently logged in
     * @return bool
     */
    static function check()
    {
        return App::content()->administrators->isAuthenticated();
    }

    /**
     * Returns how many admin accounts exist
     * @return int
     */
    static function hasAdministrators()
    {
        return App::content()->administrators->count();
    }
}
