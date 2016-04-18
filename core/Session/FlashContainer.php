<?php

namespace Kabas\Session;

class FlashContainer
{
      protected $old;
      protected $new;

      public function __construct($old)
      {
            $this->old = $old ? $old : new \stdClass;
            $this->new = new \stdClass;
      }

      public function __get($key)
      {
            if(isset($this->old->$key)) return $this->old->$key;
            else if(isset($this->new->$key)) return $this->new->$key;
            return false;
      }

      public function __set($key, $value)
      {
            $this->new->$key = $value;
      }

      public function get()
      {
            return $this->new;
      }

      public function reflash()
      {
            $this->new = (object) array_merge((array)$this->new, (array) $this->old);
      }

      public function keep($keys)
      {
            if(!is_array($keys)) $keys = [$keys];

            foreach($keys as $key){
                  if(isset($this->old->$key)) $this->new->$key = $this->old->$key;
            }
      }

      public function has($key)
      {
            if(property_exists($this->old, $key)) return property_exists($this->old, $key);
            else if(property_exists($this->new, $key)) return property_exists($this->new, $key);
            else return false;
      }

      public function isEmpty()
      {
            return empty((array) $this->old) && empty((array) $this->new);
      }
}
