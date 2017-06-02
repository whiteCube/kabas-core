<?php

namespace Kabas\Content\Pages;

use Kabas\App;
use Kabas\Utils\Lang;
use Kabas\Utils\File;
use Kabas\Content\BaseContainer;

class Container extends BaseContainer
{

    /**
    * identifier of the language (linked to $this->$path)
    * @var string
    */
    protected $language;

    public function __construct()
    {
        foreach (Lang::getAll() as $language) {
            $this->setLanguage($language);
            $this->loop(File::loadJsonFromDir($this->path));
        }
        $this->setLanguage(Lang::getCurrent());
    }

    public function get($id, $lang = null)
    {
        if(!($aggregate = parent::get($id))) return false;
        $lang = Lang::getOrDefault($lang);
        if(!isset($aggregate[$lang->original])) return false;
        return $aggregate[$lang->original];
    }

    public function getCurrent($lang = null)
    {
        return $this->get(App::router()->getCurrent()->page, $lang);
    }

    public function parse()
    {
        foreach ($this->items as $aggregate) {
            if(!isset($aggregate[$this->language])) continue;
            $aggregate[$this->language]->parse();
        }
    }

    protected function getPath($lang = null)
    {
        return parent::getPath($lang) . DS . 'pages';
    }

    protected function makeItem($file)
    {
        $aggregate = $this->getAggregate($file);
        $aggregate[$this->language] = new Item($file);
        return $aggregate;
    }

    protected function getAggregate($file)
    {
        if(!isset($this->items[$file->id])) return [];
        return $this->items[$file->id];
    }

    protected function setLanguage($lang)
    {
        $this->language = $lang->original;
        $this->path = $this->getPath($lang);
    }
}
