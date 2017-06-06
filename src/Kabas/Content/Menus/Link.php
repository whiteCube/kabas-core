<?php

namespace Kabas\Content\Menus;

use \Kabas\App;
use \Kabas\Content\BaseItem;
use \Kabas\Content\Menus\Links;

class Link extends BaseItem
{
    public $items;

    public $url;

    public $active;

    public function __construct($data, $id, $structure)
    {
        $this->id = $id;
        $this->structure = $structure;
        $this->fields = $this->loadFields($data);
        $this->controller = $this->findControllerClass();
        if(isset($data->items) && is_array($data->items)){
            $this->items = new Links($data->items, $this->structure);
        }
    }

    public function parse()
    {
        parent::parse();
        if($this->items) $this->items->parse();
    }

    protected function loadFields($data = null)
    {
        $fields = null;
        $data = $this->getFieldObject($data);
        if($this->structure) $fields = $this->getItemFields($data, $this->structure);
        $this->set($data);
        return $fields;
    }

    protected function findControllerClass()
    {
        if($class = parent::findControllerClass()) return $class;
        return \Kabas\Controller\MenuItem::class;
    }
}
