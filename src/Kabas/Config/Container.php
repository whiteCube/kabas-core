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
        $this->languages = new LanguageRepository($this->settings->pluck('lang.available'), $this->settings->get('lang.default'));
        $this->setDriver();
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
     * Defines the main content data driver
     * @return void
     */
    protected function setDriver()
    {
        $driver = 'Kabas\\Drivers\\';
        $driver .= Text::toNamespace($this->settings->get('app.driver'));
        App::setDriver($driver);
    }
}
