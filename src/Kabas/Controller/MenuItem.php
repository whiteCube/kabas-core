<?php

namespace Kabas\Controller;

use Kabas\App;
use Kabas\Utils\Url;
use Kabas\Utils\Page;
use Kabas\Utils\Lang;

class MenuItem
{
    protected $item;

    public function __construct($item)
    {
        $this->item = $item;
        $this->item->url = $this->getTargetUrl();
        $this->item->active = $this->getLocalActive();
        if($this->hasSub()) $this->getSub()->make();
    }

    public function __get($key)
    {
        return $this->item->$key;
    }

    public function __set($key, $value)
    {
        $this->item->$key = $value;
    }

    /**
     * Check if menu item has a submenu
     * @return boolean
     */
    public function hasSub()
    {
        if(is_null($this->items) || !count($this->items)) return false;
        return true;
    }

    /**
     * get item's subitems
     * @return array
     */
    public function getSub()
    {
        if($this->hasSub()) return $this->items;
        return [];
    }

    /**
     * Checks if target is a page
     * @return array
     */
    public function isPage()
    {
        if(is_string($this->url)) return false;
        return $this->url->hasTarget();
    }

    /**
     * Checks if this page or sub-pages is/are active
     * @param  boolean $checkSub (optionnal)
     * @return array
     */
    public function isActive($checkSub = true)
    {
        if($this->active) return true;
        if($checkSub && $this->hasSub()){
            foreach ($this->getSub() as $item) {
                if($item->isActive()) return true;
            }
        }
        return false;
    }

    /**
     * Returns defined target's real URL
     * @return string
     */
    protected function getTargetUrl()
    {
        foreach ($this->fields as $field) {
            if($field->getType() == 'Url') return $field;
        }
        return '#';
    }

    /**
     * Checks if this page is the active one
     * @return boolean
     */
    protected function getLocalActive()
    {
        if($this->isPage()) return $this->isPageActive();
        return $this->isUrlActive();
    }

    /**
     * Checks if page is active
     * @return boolean
     */
    protected function isPageActive()
    {
        if($this->url->getTarget()->id != Page::id()) return false;
        return true;
    }

    /**
     * Checks if URL is active
     * @return boolean
     */
    protected function isUrlActive()
    {
        if(is_string($this->url)) return false;
        $url = trim(explode('?', explode('#', $this->url->getValue())[0])[0], '/');
        return (App::request()->getQuery()->getURL() === $url);
    }
}
