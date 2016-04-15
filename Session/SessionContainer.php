<?php

namespace Kabas\Session;

class SessionContainer
{
      protected $flash;
      protected $content;

      public function __construct($sessionData)
      {
            $a = unserialize($sessionData);
            $this->flash = new FlashContainer(isset($a[0]) ? $a[0] : false);
            $this->content = isset($a[1]) ? $a[1] : new \stdClass;

      }

      public function serialize()
      {
            return serialize([$this->flash->get(), $this->content]);
      }

      public function reflash()
      {
            $this->flash->reflash();
      }

      public function keep($key)
      {
            $this->flash->keep($key);
      }

      public function __set($key, $value)
      {
            $this->content->$key = $value;
      }

      public function __get($key)
      {
            return isset($this->content->$key) ? $this->content->$key : false;
      }

      public function setFlash($key, $value)
      {
            $this->flash->$key = $value;
      }

      public function getFlash($key)
      {
            return $this->flash->$key;
      }

      public function hasFlash($key = null)
      {
            if($key) return property_exists($this->flash, $key);
            return !empty((array) $this->flash);
      }

}
