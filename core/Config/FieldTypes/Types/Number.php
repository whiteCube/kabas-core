<?php

namespace Kabas\Config\FieldTypes;

class Number extends Item
{
      public $type = "number";

      /**
       * Condition to check if the value is correct for this field type.
       * @param  mixed $value
       * @return bool
       */
      public function condition()
      {
            return gettype($this->data) === 'integer';
      }

      public function __toString()
      {
            return (string) $this->modified;
      }

      public function __get($key)
      {
            return $this->convert($key);
      }

      public function __call($method, $args)
      {
            return $this->convert($method);
      }

      protected function convert($key)
      {
            switch (strtolower($key)) {
                  case 'i':
                  case 'int':
                  case 'toint':
                  case 'integer':
                  case 'parseint':
                  case 'intval':
                        return (int) $this->getNumber();
                        break;
                  case 'f':
                  case 'float':
                  case 'tofloat':
                  case 'parsefloat':
                  case 'floatval':
                        return (float) $this->getNumber();
                        break;
            }
            return false;
      }

      public function add($number)
      {
            $number = $this->checkNumberType($number);
            $this->modified = $this->getNumber() + $number;
            return $this;
      }

      public function subtract($number)
      {
            $number = $this->checkNumberType($number);
            $this->modified = $this->getNumber() - $number;
            return $this;
      }

      public function divide($number)
      {
            $number = $this->checkNumberType($number);
            $this->modified = $this->getNumber() / $number;
            return $this;
      }

      public function multiply($number)
      {
            $number = $this->checkNumberType($number);
            $this->modified = $this->getNumber() * $number;
            return $this;
      }

      protected function checkNumberType($number)
      {
            if(is_object($number) && isset($number->data)) return $number->data;
      }

      protected function getNumber()
      {
            return isset($this->modified) ? $this->modified : $this->data;
      }

}
