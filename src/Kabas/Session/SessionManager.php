<?php

namespace Kabas\Session;

use Kabas\App;
use Kabas\Session\SessionContainer;

class SessionManager
{
      protected $sessionName = 'kabas';
      protected $hasBooted = false;
      protected $container;

      public function __construct()
      {
            $this->hasBooted = session_start();
            $this->container = new SessionContainer($_SESSION[$this->sessionName] ?? null);
      }

      /**
       * Writes data to the session.
       * @param  string $key
       * @param  mixed $value
       * @return void
       */
      public function put($key, $value)
      {
            $this->container->$key = $value;
      }

      /**
       * Get data from the session.
       * @param  string $key
       * @return mixed
       */
      public function get($key)
      {
            return $this->container->$key;
      }

      /**
       * Get data from the session then forget it.
       * @param  string $key
       * @return mixed
       */
      public function pull($key)
      {
            $value = $this->get($key);
            $this->forget($key);
            return $value;
      }

      /**
       * Get all data from the session.
       * @return object
       */
      public function all()
      {
            return $this->container->all();
      }

      /**
       * Forget all the data in the session.
       * @return void
       */
      public function flush()
      {
            $this->container->flush();
      }

      /**
       * Stores a value in the session that will be forgotten after the next request.
       * @param  string $key
       * @param  mixed $value
       * @return void
       */
      public function flash($key, $value)
      {
            $this->container->setFlash($key, $value);
      }

      /**
       * Keeps the flash data for one more request.
       * @return void
       */
      public function reflash()
      {
            $this->container->reflash();
      }

      /**
       * Keeps specific flash data for one more request.
       * @param  string|array $keys
       * @return void
       */
      public function keep($keys)
      {
            $this->container->keep($keys);
      }

      /**
       * Delete data from the session.
       * @param  string $key
       * @return void
       */
      public function forget($key)
      {
            unset($this->container->$key);
      }

      /**
       * Check if session contains data.
       * @param  string  $key
       * @return boolean
       */
      public function has($key)
      {
            return isset($this->container->$key);
      }

      /**
       * Check if session contains flash data.
       * @param  string  $key
       * @return boolean
       */
      public function hasFlash($key = null)
      {
            return $this->container->hasFlash($key);
      }

      /**
       * Get flash data from the session.
       * @param  string $key
       * @return mixed
       */
      public function getFlash($key)
      {
            return $this->container->getFlash($key);
      }

      /**
       * Serialize the session data and write it to $_SESSION
       * @return void
       */
      public function write()
      {
            $_SESSION[$this->sessionName] = $this->container->serialize();
      }
}
