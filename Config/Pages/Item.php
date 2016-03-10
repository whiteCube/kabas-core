<?php

namespace Kabas\Config\Pages;

class Item
{
      public function __construct($jsonData)
      {
            $this->route = $jsonData->route;
            $this->id = $jsonData->id;
            $this->template = $jsonData->template;
      }
}
