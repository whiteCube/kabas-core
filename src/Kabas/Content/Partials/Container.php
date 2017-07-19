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

    protected function loadItem($identifier)
    {
        // check if controller exists
        $controller = $this->getController($identifier);
        if($controller) return $this->loadFromController($identifier, $controller);
        // Controller does not exist.
        // check view file
        $view = $this->getView($identifier);
        if($view) return $this->loadFromView($identifier);
        // Not found.
        throw new NotFoundException($identifier, 'partial');
    }

    protected function loadFromController($identifier, $controller)
    {
        $file = new \stdClass();
        $file->id = $identifier;
        $file->controller = $controller;
        $ref = new \ReflectionClass($file->controller);
        if(!$ref->getStaticPropertyValue('template')) throw new NotFoundException($identifier,'partial');
        $file->template = $ref->getStaticPropertyValue('template');
        return $file;
    }

    protected function loadFromView($identifier)
    {
        $file = new \stdClass();
        $file->id = $identifier;
        $file->controller = null;
        $file->template = $identifier;
        return $file;
    }

    protected function getController($identifier)
    {
        $controller = '\\Theme\\' . App::themes()->getCurrent('name') .'\\Partials\\' . Text::toNamespace($identifier);
        if(class_exists($controller)) return $controller;
    }

    protected function getView($identifier)
    {
        $file = THEME_PATH . DS . 'views' . DS . 'partials' . DS . $identifier . '.php';
        return realpath($file);
    }
}
