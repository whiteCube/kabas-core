<?php

namespace Kabas\Content\Pages;

use \Kabas\App;
use Kabas\Utils\File;
use Kabas\Utils\Lang;
use \Kabas\Content\BaseItem;

class Item extends BaseItem
{
    public $directory = 'templates';

    public $route;

    public $meta;

    public $title;

    protected static $defaultMeta = [];

    protected function setData($data)
    {
        $this->route = isset($data->route) ? $data->route : false;
        $this->title = isset($data->title) ? $data->title : false;
        $this->meta = $this->getMeta($data);
    }

    protected function getMeta($data)
    {
        $default = $this->getDefaultMeta();
        return $this->mergeMetaWithDefault($default, $data->meta ?? []);
    }

    protected function getDefaultMeta()
    {
        // TODO : this should be the item's locale...
        $locale = Lang::getCurrent()->original;
        if(!isset(static::$defaultMeta[$locale])) {
            $defaultMetaPath = CONTENT_PATH . DS . $locale . DS . 'meta.json';
            static::$defaultMeta[$locale] = file_exists($defaultMetaPath) ? File::loadJson($defaultMetaPath) : new \stdClass;
        }
        return static::$defaultMeta[$locale];
    }

    protected function mergeMetaWithDefault($default, $meta)
    {
        $merged = (array) $default;
        foreach($meta as $key => $value) {
            $merged[$key] = $value;
        }
        return $merged;
    }

    protected function getTemplateNamespace()
    {
        return '\\Theme\\' . App::themes()->getCurrent('name') .'\\Templates\\' . parent::getTemplateNamespace();
    }

    protected function findControllerClass()
    {
        if($class = parent::findControllerClass()) return $class;
        return \Kabas\Controller\TemplateController::class;
    }
}
