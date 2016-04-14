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
            $sessionData = isset($_SESSION['kabas']) ? $_SESSION['kabas'] : '';
            var_dump($sessionData);
            $this->container = App::getInstance()->make('Kabas\Session\SessionContainer', [$sessionData]);
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

      public function forget($key)
      {
            unset($this->container->$key);
      }

      public function finish()
      {
            $this->clearFlash();
            $this->write();
      }

      public function clearFlash()
      {
            if($this->container->hasFlash()) $this->container->deleteAllFlash();
      }

      public function write()
      {
            $_SESSION['kabas'] = $this->container->serialize();
            var_dump($_SESSION['kabas']);
      }
}
