<?php

namespace Kabas\Fields\Types;

use \Kabas\Fields\Item;

class Color extends Item
{
      public $type = "color";

      protected $mode = 'hex';

      protected $reference;

      /**
       * Condition to check if the value is correct for this field type.
       * @param  mixed $value
       * @return bool
       */
      public function condition()
      {
            if(is_object($this->reference)) return true;
            return false;
      }

      /**
       * Defines color of given value
       * @param  mixed $value
       * @return mixed
       */
      protected function parse($value)
      {
            $this->setReference($value);
            if(!$this->reference) return null;
            switch ($this->mode) {
                  case 'hex': return $this->getHex(); break;
                  case 'rgb': return $this->getRgb(); break;
            }
            return false;
      }

      /**
       * Switches to RGB output mode
       * @return $this
       */
      public function rgb()
      {
            if($this->mode !== 'rgb'){
                  $this->mode = 'rgb';
                  $this->output = $this->getRgb();
            }
            return $this;
      }

      /**
       * Switches to HEX output mode
       * @return $this
       */
      public function hex()
      {
            if($this->mode !== 'hex'){
                  $this->mode = 'hex';
                  $this->output = $this->getHex();
            }
            return $this;
      }

      /**
       * Get the RGB red value of the color
       * @return string
       */
      public function red()
      {
            if(!$this->reference) return null;
            return $this->reference->red;
      }

      /**
       * Get the RGB green value of the color
       * @return string
       */
      public function green()
      {
            if(!$this->reference) return null;
            return $this->reference->green;
      }

      /**
       * Get the RGB blue value of the color
       * @return string
       */
      public function blue()
      {
            if(!$this->reference) return null;
            return $this->reference->blue;
      }

      /**
       * Defines reference object from user value
       * @param  string $value
       * @return void
       */
      protected function setReference($value)
      {
            $this->reference = $this->getReference($value);
      }

      /**
       * Computes reference object from user value
       * @param string $value
       * @return object | boolean
       */
      protected function getReference($value)
      {
            if(is_string($value)){
                  if(strpos($value, '#') === 0) return $this->parseHexString($value);
                  if(strpos($value, 'rgb') === 0) return $this->parseRgbString($value);
            }
            return false;
      }

      /**
       * Parses a HEX string and formats it into a reference object
       * @param  string $string
       * @return object
       */
      protected function parseHexString($string)
      {
            $string = substr($string, 1);
            $o = new \stdClass();
            if(strlen($string) == 3) {
                  $o->red = hexdec(substr($string,0,1).substr($string,0,1));
                  $o->green = hexdec(substr($string,1,1).substr($string,1,1));
                  $o->blue = hexdec(substr($string,2,1).substr($string,2,1));
            }
            elseif(strlen($string) == 6){
                  $o->red = hexdec(substr($string,0,2));
                  $o->green = hexdec(substr($string,2,2));
                  $o->blue = hexdec(substr($string,4,2));
            }
            else return false;
            return $o;
      }

      /**
       * Parses a rgb string and formats it into a reference object
       * @param  string $string
       * @return object
       */
      protected function parseRgbString($string)
      {
            $string = str_replace(' ', '', $string);
            preg_match("/^rgb\((\d+),(\d+),(\d+)\)$/", $string, $a);
            if(!count($a)) return false;
            $o = new \stdClass();
            $o->red = intval($a[1]);
            $o->green = intval($a[2]);
            $o->blue = intval($a[3]);
            return $o;
      }

      /**
       * Converts reference to RGB string
       * @return string
       */
      protected function getRgb()
      {
            if(!is_object($this->reference)) return null;
            $string = 'rgb(';
            $string .= $this->red() . ',' . $this->green() . ',' . $this->blue();
            $string .= ')';
            return $string;
      }

      /**
       * Converts reference to hex string
       * @return string
       */
      protected function getHex()
      {
            if(!is_object($this->reference)) return null;
            $string = '#';
            $string .= dechex($this->red());
            $string .= dechex($this->green());
            $string .= dechex($this->blue());
            return $string;
      }

}
