<?php

namespace Kabas\Http\Responses;

use Kabas\View\View as ViewEngine;
use Kabas\Http\Response;

class View extends Response
{
    public function __construct($view, $item)
    {
        $this->view = $view;
        $this->item = $item;
    }

    /**
     * Executes the response. Called automatically.
     * @return void
     */
    public function run()
    {
        $this->setHeaders();
        ViewEngine::make($this->view, $this->getData(), $this->item->directory);
    }

    /**
     * Makes a variables array from item
     * @return array
     */
    protected function getData()
    {
        $a = [];
        $this->addVarsObject($a, $this->item->fields);
        $this->addVarsObject($a, $this->item->data);
        if(is_a($this->item, \Kabas\Content\Menus\Item::class)) {
            $a['items'] = $this->item->items;
        }
        return $a;
    }

    protected function addVarsObject(&$array, $object)
    {
        if(is_object($object)){
            foreach($object as $key => $value) {
                if(!is_numeric($key) && !isset($array[$key])){
                    $array[$key] = $value;
                }
            }
        }
    }
}
