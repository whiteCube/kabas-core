<?php

namespace Kabas\Cmd\Commands;

use Illuminate\Container\Container;
use Kabas\App;
use Kabas\Utils\File;

class Publish
{
    public function run()
    {
        $app = App::getInstance();
        foreach($app->getProviders() as $provider) {
            $this->publishConfigs($provider->getConfigs());
        }
    }

    protected function publishConfigs($configs)
    {
        foreach($configs as $name => $path) {
            $this->publishConfig($name, $path);
        }
    }

    protected function publishConfig($name, $path)
    {
        File::copy($path, CONFIG_PATH . DS . $name . '.php', false);
    }
}