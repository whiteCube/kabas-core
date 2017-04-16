<?php

namespace Kabas\Config;

use Kabas\App;
use Kabas\Utils\Text;
use Kabas\Model\Container as ModelContainer;

class Container
{
      public function __construct(Settings $settings, ModelContainer $models)
      {
            $this->settings = $settings;
            $this->models = $models;
            $this->initDriver();
      }

      public function __call($name, $arguments)
      {
            if(!method_exists($this->settings, $name)) {
                  $error = 'Error: Method "' . $name . '" does not exist on config tree.';
                  throw new \Exception($error);
            }
            return call_user_func_array([$this->settings, $name], $arguments);
      }

      /**
       * Initialise the data driver based on
       * what's specified in the app config
       * @return void
       */
      protected function initDriver()
      {
            $driverName = 'Kabas\\Drivers\\';
            $driverName .= Text::toNamespace($this->settings->get('app.driver'));
            $driver = App::getInstance()->make($driverName);
            App::setDriver($driver);
      }
}
