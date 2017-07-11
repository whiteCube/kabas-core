<?php

namespace Kabas\Utils;

use Kabas\App;
use Kabas\Exceptions\NotFoundException;

class Auth
{
    /**
     * Check if an administrator account is currently logged in
     * @return bool
     */
    static function check()
    {
        return App::content()->administrators->isAuthenticated();
    }

    /**
     * Disconnect an administrator account
     * @return void
     */
    static function logout()
    {
        return App::content()->administrators->logout();
    }

    /**
     * Attempt to login an administrator account
     * @param string $username 
     * @param string $password 
     * @return bool
     */
    static function connect($username, $password)
    {
        return App::content()->administrators->authenticate($username, $password);
    }

    /**
     * Returns how many admin accounts exist
     * @return int
     */
    static function hasAdministrators()
    {
        return App::content()->administrators->count();
    }

    /**
     * Creates an administrator account
     * @param array $data 
     * @return bool
     */
    static function create(array $data)
    {
        return App::content()->administrators->create($data);
    }
}
