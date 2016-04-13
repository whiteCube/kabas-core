<?php

namespace Kabas\Config\FieldTypes;

class Url extends Item
{
      public $type = "url";

      public function __construct($fieldName = null, $data = null)
      {
            $this->fieldName = $fieldName;
            $this->data = $data;

            if(isset($data)) {
                  $this->href = $data->href;
                  $this->label = $data->label;
                  $this->title = $data->title;
            }

            if(isset($this->fieldName) && isset($this->href)) {
                  try { $this->check($fieldName, $this->href); }
                  catch (\Kabas\Exceptions\TypeException $e) {
                        echo $e->getMessage();
                  }
            }
      }

      public function __toString()
      {
            return $this->href;
      }

      /**
       * Condition to check if the value is correct for this field type.
       * @param  mixed $value
       * @return bool
       */
      public function condition()
      {
            return filter_var($this->data, FILTER_VALIDATE_URL);
      }

      /**
       * Check if url is from this domain
       * @return boolean
       */
      public function isLocal()
      {
            $serverHost = str_replace('www.', '', $_SERVER['HTTP_HOST']);
            $linkHost = str_replace('www.', '', $this->parse()->host);
            return $serverHost === $linkHost;
      }

      /**
       * Get the parsed url
       * @return object
       */
      public function parse()
      {
            return (object) parse_url($this->href);
      }

}
