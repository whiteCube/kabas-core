<?php

namespace Kabas\Session;

use Kabas\App;

class SessionManager
{
      protected $hasBooted = false;
      protected $container;

      public function __construct()
      {
            $this->hasBooted = session_start();
            $this->container = App::getInstance()->make('Kabas\Session\SessionContainer', [$_SESSION['kabas']]);
      }

      public function put($key, $value)
      {
            $this->container->$key = $value;
      }

      public function get($key)
      {
            return $this->container->$key;
      }

      public function flash($key, $value)
      {
            $this->container->setFlash($key, $value);
      }

      public function write()
      {
            $_SESSION['kabas'] = serialize($this->container);
      }
}
