<?php

namespace Kabas\Session;

use Kabas\App;

class SessionManager
{
      protected $sessionName = 'kabas';
      protected $hasBooted = false;
      protected $container;

      public function __construct()
      {
            $this->hasBooted = session_start();
            $sessionData = isset($_SESSION[$this->sessionName]) ? $_SESSION[$this->sessionName] : '';
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

      public function pull($key)
      {
            $value = $this->get($key);
            $this->forget($key);
            return $value;
      }

      public function all()
      {
            return $this->container->all();
      }

      public function flush()
      {
            $this->container->flush();
      }

      public function flash($key, $value)
      {
            $this->container->setFlash($key, $value);
      }

      public function reflash()
      {
            $this->container->reflash();
      }

      public function keep($keys)
      {
            $this->container->keep($keys);
      }

      public function forget($key)
      {
            unset($this->container->$key);
      }

      public function has($key)
      {
            return isset($this->container->$key);
      }

      public function hasFlash($key = null)
      {
            return $this->container->hasFlash($key);
      }

      public function getFlash($key)
      {
            return $this->container->getFlash($key);
      }

      public function write()
      {
            $_SESSION[$this->sessionName] = $this->container->serialize();
      }
}
