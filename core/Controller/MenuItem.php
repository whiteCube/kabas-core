<?php

namespace Kabas\Controller;

use \Kabas\App;
use \Kabas\Utils\Url;
use \Kabas\Utils\Page;

class MenuItem
{
      public $url;

      public $label;

      public $items;

      public $page;

      public $active;

      protected $attributes;

      public function __construct($item)
      {
            $this->attributes = $item;
            $this->url = $this->getTargetUrl();
            $this->label = $this->getLabel();
            $this->active = $this->getLocalActive();
            $this->cleanAttributes();
      }

      public function __get($name)
      {
            return $this->attributes->$name;
      }

      /**
       * Check if menu item has a submenu
       * @return boolean
       */
      public function hasSub()
      {
            if(is_array($this->items)) return true;
            if(isset($this->attributes->items)){
                  $a = $this->attributes->items;
                  if(is_array($a) && count($a)) return true;
            }
            return false;
      }

      /**
       * get item's subitems
       * @return array
       */
      public function getSub()
      {
            if(is_array($this->items)) return $this->items;
            if($this->hasSub()) return $this->attributes->items;
            return [];
      }

      /**
       * Checks if target is a page
       * @return array
       */
      public function isPage()
      {
            if($this->page) return true;
            return false;
      }

      /**
       * Checks if this page or sub-pages is/are active
       * @param  boolean $checkSub (optionnal)
       * @return array
       */
      public function isActive($checkSub = true)
      {
            if($this->active) return true;
            if($checkSub && $this->items){
                  foreach ($this->items as $sub) {
                        if($sub->isActive()) return true;
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
            if(!isset($this->attributes->target)) return '#';
            if($page = $this->getTargetPage()) {
                  $this->page = $page;
                  return Url::to($page->id);
            }
            return $this->attributes->target;
      }

      /**
       * Returns target page if exists
       * @return object
       */
      protected function getTargetPage()
      {
            return App::content()->pages->get($this->attributes->target);
      }

      /**
       * Returns the menu item's label
       * @return string
       */
      protected function getLabel()
      {
            if(isset($this->attributes->label) && $this->attributes->label){
                  return $this->attributes->label;
            }
            if($this->isPage()) return $this->page->title;
            return '';
      }

      /**
       * removes target & label information from attributes
       * @return void
       */
      protected function cleanAttributes()
      {
            if(isset($this->attributes->target)) unset($this->attributes->target);
            if(isset($this->attributes->label)) unset($this->attributes->label);
      }

      /**
       * removes items array from attributes
       * @return void
       */
      protected function cleanSub()
      {
            unset($this->attributes->items);
      }

      /**
       * Checks if this page is the active one
       * @return boolean
       */
      protected function getLocalActive()
      {
            if($this->page) return $this->isPageActive();
            return $this->isUrlActive();
      }

      /**
       * Checks if page is active
       * @return boolean
       */
      protected function isPageActive()
      {
            if($this->page->id == Page::id()) return true;
            return false;
      }

      /**
       * Checks if URL is active
       * @return boolean
       */
      protected function isUrlActive()
      {
            $route = Url::route($this->url);
            if($route) return App::router()->getCurrent()->matches($route);
            return false;
      }
}
