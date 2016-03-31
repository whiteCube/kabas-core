<?php

namespace Kabas\Model;

use Kabas\App;
use Kabas\Utils\Text;

class Model
{
      protected $driver;

      public function __construct()
      {
            $this->checkDriver();
            $this->makeDriver();
      }

      public function getAll()
      {
            return ['one', 'two'];
      }

      private function makeDriver()
      {
            $class = Text::toNamespace($this->driver);
            $this->model = App::getInstance()->make('Kabas\Drivers\\' . $class);
            var_dump($this->model);
      }

      private function checkDriver()
      {
            if(!isset($this->driver)) $this->setDefaultDriver();
      }

      private function setDefaultDriver()
      {
            $this->driver = 'json';
      }
}
