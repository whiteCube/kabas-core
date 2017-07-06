<?php

namespace Kabas\Utils;

use Kabas\App;
use Kabas\Exceptions\NotFoundException;

class Auth
{
    static function check()
    {
        return App::content()->administrators->isAuthenticated();
    }

    static function logout()
    {
        return App::content()->administrators->logout();
    }

    static function connect($username, $password)
    {
        return App::content()->administrators->authenticate($username, $password);
    }

    static function hasAdministrators()
    {
        return App::content()->administrators->count();
    }
}
