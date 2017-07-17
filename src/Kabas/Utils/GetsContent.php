<?php 

namespace Kabas\Utils;

use Kabas\App;

trait GetsContent
{
    /**
     * Get and display the item with the corresponding ID onto the page.
     * @param  string $item
     * @param  array $params (optionnal)
     * @return void
     */
    static function get($item, $params = [])
    {
        $item = App::content()->{self::$type}->get($item);
        if($item){
            $item->set($params);
            $item->make();
        }
    }

    static function __callStatic($method, $params)
    {
        if(!empty($params)) $params = $params[0];
        self::get($method, $params);
    }
}