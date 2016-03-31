<?php

namespace Kabas\Utils;

use Kabas\App;

class Model
{
      static function get($modelType)
      {
            $type = Text::toNamespace($modelType);
            $class = 'Theme\\' . App::config()->settings->site->theme . '\Models\\' . $type;
            $model = App::getInstance()->make($class);
            return $model;
      }
}
