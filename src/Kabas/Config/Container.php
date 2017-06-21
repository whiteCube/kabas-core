<?php

namespace Kabas\Config;

use Kabas\App;
use Kabas\Utils\Text;
use Illuminate\Database\Capsule\Manager as Capsule;

class Container
{
    public function __construct(Settings $settings)
    {
        $this->settings = $settings;
        $this->languages = new LanguageRepository($this->settings->pluck('lang.available'), $this->settings->get('lang.default'));
        $this->setDatabase($this->settings->pluck('database'));
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
     * Defines the default eloquent connection
     * @param array $defaultConnection
     * @return void
     */
    protected function setDatabase(array $defaultConnection)
    {
        $capsule = new Capsule;
        $capsule->addConnection($defaultConnection);
        $capsule->bootEloquent();
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
