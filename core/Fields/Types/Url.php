<?php

namespace Kabas\Fields\Types;

use \Kabas\App;
use \Kabas\Fields\Item;
use \Kabas\Utils\Url as UrlUtil;

class Url extends Item
{
      protected $type = "url";

      protected $target;

      /**
       * Condition to check if the value is correct for this field type.
       * @param  mixed $value
       * @return bool
       */
      public function condition()
      {
            return filter_var($this->output, FILTER_VALIDATE_URL);
      }

      /**
       * Check if url is from this domain
       * @return boolean
       */
      public function isLocal()
      {
            if($this->target) return true;
            if(trim(UrlUtil::parse($this->value)->base, '/') == UrlUtil::base()) return true;
            return false;
      }

      /**
       * Get the parsed url
       * @return object
       */
      public function getParsed()
      {
            return UrlUtil::parse($this->output);
      }

      /**
       * Checks if this URL has a defined internal target
       * @return boolean
       */
      public function hasTarget()
      {
            return is_null($this->target) ? false : true;
      }

      /**
       * Get the Url's target object if defined
       * @return object
       */
      public function getTarget()
      {
            return $this->target;
      }

      /**
       * Makes an output URL from value (without trailing slashes)
       * @param  mixed $value
       * @return mixed
       */
      protected function parse($value)
      {
            if($page = $this->findTarget($value)){
                  $this->target = $page;
                  return UrlUtil::to($page->id);
            }
            return trim($value, '/');
      }

      /**
       * Returns target page if exists
       * @param  string $id
       * @return object
       */
      protected function findTarget($id)
      {
            return App::content()->pages->get($id);
      }

}
