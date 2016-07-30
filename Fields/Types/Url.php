<?php

namespace Kabas\Fields\Types;

use \Kabas\Fields\Item;
use \Kabas\Utils\Url as UrlUtil;

class Url extends Item
{
      public $type = "url";

      /**
       * Condition to check if the value is correct for this field type.
       * @param  mixed $value
       * @return bool
       */
      public function condition()
      {
            return filter_var($this->value, FILTER_VALIDATE_URL);
      }

      /**
       * Check if url is from this domain
       * @return boolean
       */
      public function isLocal()
      {
            if(trim(UrlUtil::parse($this->value)->base, '/') == UrlUtil::base()) return true;
            return false;
      }

      /**
       * Get the parsed url
       * @return object
       */
      public function getParsed()
      {
            return UrlUtil::parse($this->value);
      }

}
