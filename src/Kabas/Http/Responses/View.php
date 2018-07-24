<?php

namespace Kabas\Http\Responses;

use Kabas\App;
use Kabas\Http\Response;
use Kabas\Utils\Assets;
use Kabas\View\View as ViewEngine;

class View extends Response implements ResponseInterface
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
        $app = App::getInstance();
        if($app->config->get('app.templating') == 'blade') {
            echo Assets::load($app->view->make($this->item->directory . '.' . $this->view, $this->getData())->render());
        } else {
            ViewEngine::make($this->view, $this->getData(), $this->item->directory);
        }
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
            $a['items'] = $this->item->getMenuItems();
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
