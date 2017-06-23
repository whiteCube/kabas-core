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

    protected function setData($data)
    {
        $this->route = isset($data->route) ? $data->route : false;
        $this->title = isset($data->title) ? $data->title : false;
        $this->meta = $this->getMeta($data);
    }

    protected function getMeta($data)
    {
        $default = $this->getDefaultMeta();
        $meta = $data->meta ?? [];
        return $this->mergeMetaWithDefault($default, $meta);
    }

    protected function getDefaultMeta()
    {
        $defaultMetaPath = CONTENT_PATH . DS . Lang::getCurrent()->original . DS . 'meta.json';
        return file_exists($defaultMetaPath) ? File::loadJson($defaultMetaPath) : [];
    }

    protected function mergeMetaWithDefault($default, $meta)
    {
        foreach($meta as $key => $value)
        {
            $default->$key = $value;
        }
        return (array) $default;
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
