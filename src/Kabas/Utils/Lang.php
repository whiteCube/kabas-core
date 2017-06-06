<?php

namespace Kabas\Utils;

use \Kabas\App;
use \Kabas\Utils\Url;

class Lang
{

    /**
    * Forwards static calls on the LanguageRepository
    * @return mixed
    */
    static function __callStatic($method, $args = [])
    {
        return call_user_func_array([App::config()->languages, $method], $args);
    }

    /**
    * Returns array to create a menu
    * @return object
    */
    static function getMenu()
    {
        //    TODO : Should return an instance of a menu object
        $languages = App::config()->languages->getAll();
        foreach ($languages as $language) {
            $language->url = Url::lang($language->slug);
        }
        return $languages;
    }
}
