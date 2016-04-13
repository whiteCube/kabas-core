<?php

namespace Kabas\Session;

class SessionContainer
{
      protected $flash;
      protected $content;

      public function __construct($sessionData)
      {
            $this->flash = new \stdClass;
            $this->content = new \stdClass;
            $this->boot($sessionData);
      }

      public function __sleep()
      {
            // TODO: cleanup before serialize
      }

      protected function boot($sessionData)
      {
            $a = unserialize($sessionData);
            // TODO: fill flash and content
            // keep a trace of the flash items so we can delete them later
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
            return $this->content->$key;
      }

      public function setFlash($key, $value)
      {
            $this->flash->$key = $value;
      }

      public function getFlash($key)
      {
            return $this->flash->$key;
      }
}
