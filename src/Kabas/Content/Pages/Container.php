<?php

namespace Kabas\Content\Pages;

use Kabas\App;
use Kabas\Utils\Lang;
use Kabas\Utils\File;
use Kabas\Content\BaseContainer;

class Container extends BaseContainer
{
    public function get($id, $lang = null)
    {
        if(!($aggregate = parent::get($id))) return false;
        $lang = Lang::getOrDefault($lang);
        if(!isset($aggregate[$lang->original])) return false;
        return $aggregate[$lang->original];
    }

    public function getCurrent($lang = null)
    {
        return $this->get(App::router()->getCurrent()->getName(), $lang);
    }

    public function parse()
    {
        $locale = Lang::getCurrent()->original;
        foreach ($this->getItems() as $aggregate) {
            if(!isset($aggregate[$locale])) continue;
            $aggregate[$locale]->parse();
        }
    }

    protected function getPath($lang = null)
    {
        return parent::getPath($lang) . DS . 'pages';
    }

    /**
     * Loads and/or returns all items for content type
     * @return array
     */
    public function getItems()
    {
        if(is_null($this->items)) {
            $this->items = $this->loop($this->getLocalesFilesArray());
        }
        return $this->items;
    }

    /**
     * Loads all files for all available languages
     * @return array
     */
    protected function getLocalesFilesArray()
    {
        $files = [];
        foreach (Lang::getAll() as $language) {
            $files[$language->original] = File::loadJsonFromDir($this->getPath($language));
        }
        return $files;
    }

    /**
     * Recursively go through the files array to instanciate items
     * @param  array $locales
     * @return array
     */
    protected function loop($locales)
    {
        $items = [];
        foreach($locales as $locale => $files) {
            foreach ($files as $key => $data) {
                $data->id = $data->id ?? $key;
                $data->template = $data->template ?? $key;
                if(!isset($items[$data->id])) $items[$data->id] = [];
                $items[$data->id][$locale] = $this->makeItem($data);
            }
        }
        return $items;
    }

    protected function makeItem($data)
    {
        return new Item($data);
    }
}
