<?php

namespace Kabas\Controller;

use \Kabas\App;
use \Kabas\Utils\Url;
use \Kabas\Utils\Page;

class MenuItem
{
      protected $item;

      public function __construct($item)
      {
            $this->item = $item;
            $this->item->url = $this->getTargetUrl();
            $this->item->active = $this->getLocalActive();
      }

      public function __get($name)
      {
            return $this->item->fields->$name;
      }

      /**
       * Check if menu item has a submenu
       * @return boolean
       */
      public function hasSub()
      {
            if(is_null($this->item->items)) return false;
            if(count($this->item->items)) return true;
            return false;
      }

      /**
       * get item's subitems
       * @return array
       */
      public function getSub()
      {
            if($this->hasSub()) return $this->item->items;
            return [];
      }

      /**
       * Checks if target is a page
       * @return array
       */
      public function isPage()
      {
            return $this->item->url->hasTarget();
      }

      /**
       * Checks if this page or sub-pages is/are active
       * @param  boolean $checkSub (optionnal)
       * @return array
       */
      public function isActive($checkSub = true)
      {
            if($this->item->active) return true;
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
            foreach ($this->item->fields as $field) {
                  if($field->getType() == 'url') return $field;
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
            if($this->item->url->getTarget()->id == Page::id()) return true;
            return false;
      }

      /**
       * Checks if URL is active
       * @return boolean
       */
      protected function isUrlActive()
      {
            $route = Url::route($this->item->url);
            if($route) return App::router()->getCurrent()->matches($route);
            return false;
      }
}
