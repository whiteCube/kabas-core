<?php

namespace Kabas\FieldTypes;

class Email extends Item
{
      public $type = "email";

      /**
       * Condition to check if the value is correct for this field type.
       * @param  mixed $value
       * @return bool
       */
      public function condition()
      {
            return (gettype($this->data) === 'string' && filter_var($this->data, FILTER_VALIDATE_EMAIL));
      }

      /**
       * Parse the e-mail address to get the local part and the domain.
       * @return object
       */
      public function parse()
      {
            $parts = explode('@', $this->data);
            $o = new \stdClass();
            $o->local = $parts[0];
            $o->domain = $parts[1];
            // TODO: add extension
            // TODO: add subdomain
            return $o;
      }

      /**
       * Get the local part of the e-mail.
       * @return sting
       */
      public function local()
      {
            return $this->parse()->local;
      }

      /**
       * Get the domain of the e-mail.
       * @return string
       */
      public function domain()
      {
            return $this->parse()->domain;
      }
}
