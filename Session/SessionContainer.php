<?php

namespace Kabas\Session;

class SessionContainer
{
      protected $flash;
      protected $content;
      protected $flashToDelete;

      public function __construct($sessionData)
      {
            $this->flash = new \stdClass;
            $this->content = new \stdClass;
            $this->boot($sessionData);
      }

      public function serialize()
      {
            var_dump($this->flash);
            return serialize([$this->flash, $this->content]);
      }

      protected function boot($sessionData)
      {
            $a = unserialize($sessionData);
            if(isset($a[0])) $this->flash = $a[0];
            if(isset($a[1])) $this->content = $a[1];
            var_dump($this->flash);
            $this->content = $a[1];
            $this->flashToDelete = $a[0];
      }

      public function flash()
      {
            return $this->flash;
      }

      public function content()
      {
            return $this->content;
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
            return !empty($this->flash);
      }

      public function getFlashToDelete()
      {
            return $this->flashToDelete;
      }

      public function deleteAllFlash()
      {
            var_dump('before', $this->flashToDelete);
            foreach($this->flashToDelete as $key => $value){
                  if($this->hasFlash($key)) unset($this->flash->$key);
            }
            var_dump('after', $this->flash);
      }
}
