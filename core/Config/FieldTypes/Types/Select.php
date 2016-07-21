<?php

namespace Kabas\Config\FieldTypes;

class Select extends Selectable
{
      public $type = "select";

      public $multi = true;

      public function __construct($fieldName = null, $data = null, $multiValues = null)
      {
            $this->multi = $multiValues ? true : false;
            parent::__construct($fieldName, $data);
      }
}
