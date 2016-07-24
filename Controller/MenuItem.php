<?php

namespace Kabas\Controller;

use \Kabas\App;
use \Kabas\Utils\Url;

class MenuItem
{
      public $url;

      public $label;

      public $items;

      public $page;

      protected $attributes;

      public function __construct($item)
      {
            $this->attributes = $item;
            $this->url = $this->getTargetUrl();
            $this->label = $this->getLabel();
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
}
