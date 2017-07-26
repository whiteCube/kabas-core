<?php 

namespace Kabas\Utils;

use Kabas\App;
use Kabas\Exceptions\NotFoundException;

trait GetsContent
{
    /**
     * Get and display the item with the corresponding ID onto the page.
     * @param  string $identifier
     * @param  array $params (optionnal)
     * @return void
     */
    static function get($identifier, $params = [])
    {
        $item = App::content()->{self::$type}->get($identifier);
        if(!$item) throw new NotFoundException($identifier, self::$type);
        $item->set($params);
        $item->make();
    }

    static function __callStatic($method, $params)
    {
        if(!empty($params)) $params = $params[0];
        self::get($method, $params);
    }
}