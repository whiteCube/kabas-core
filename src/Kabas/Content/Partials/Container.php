<?php

namespace Kabas\Content\Partials;

use Kabas\App;
use Kabas\Utils\File;
use Kabas\Utils\Text;
use Kabas\Content\BaseContainer;
use Kabas\Exceptions\NotFoundException;

class Container extends BaseContainer
{

    /**
     * Load the specified part into memory.
     * @param  string $part
     * @return object
     */
    public function load($part)
    {
        if($item = $this->get($part)) return $item;
        $item = $this->loadItem($part);
        $this->items[$part] = $this->makeItem($item);
        return $this->items[$part];
    }

    /**
     * Returns path to partials directory
     * @return string
     */
    protected function getPath($lang = null)
    {
        return parent::getPath($lang) . DS . 'partials';
    }

    protected function makeItem($file)
    {
        return new Item($file);
    }

    //    TODO :
    //    All the following should move to BaseItem
    //    and be supported on each content type.

    protected function loadItem($id)
    {
        // check if controller exists
        $controller = $this->getController($id);
        if($controller) return $this->loadFromController($id, $controller);
        // Controller does not exist.
        // check view file
        $view = $this->getView($id);
        if($view) return $this->loadFromView($id, $view);
        // Not found.
        throw new NotFoundException($id, 'partial');
    }

    protected function loadFromController($id, $controller)
    {
        $file = new \stdClass();
        $file->id = $id;
        $file->controller = $controller;
        $ref = new \ReflectionClass($file->controller);
        if(!$ref->getStaticPropertyValue('template')) throw new NotFoundException($id,'partial');
        $file->template = $ref->getStaticPropertyValue('template');
        return $file;
    }

    protected function loadFromView($id, $view)
    {
        $file = new \stdClass();
        $file->id = $id;
        $file->controller = null;
        $file->template = $id;
        return $file;
    }

    protected function getController($id)
    {
        $controller = '\\Theme\\' . App::themes()->getCurrent('name') .'\\Partials\\' . Text::toNamespace($id);
        if(class_exists($controller)) return $controller;
    }

    protected function getView($id)
    {
        $file = THEME_PATH . DS . 'views' . DS . 'partials' . DS . $id . '.php';
        return realpath($file);
    }
}
