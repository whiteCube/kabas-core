<?php

namespace Kabas\Config\FieldTypes;

class Select extends Selectable
{
      public $type = "select";

      public function __construct($fieldName = null, $data = null, $allowsMultipleValues = null)
      {
            $this->allowsMultipleValues = isset($allowsMultipleValues) ? $allowsMultipleValues : true;
            parent::__construct($fieldName, $data);
      }
}
