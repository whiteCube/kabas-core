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
        $app->registerProviders($app->config->get('app.providers'));
        foreach($app->getProviders() as $provider) {
            $this->publishConfigs($provider->getConfigs());
            $this->publishViews($provider->getViews());
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

    protected function publishViews($views)
    {
        foreach($views as $name => $path) {
            $this->publishView($name, $path);
        }
    }

    protected function publishView($name, $path)
    {
        if(is_array($path)) {
            foreach($path as $item) {
                $this->publishView($name, $item);
            }
            return;
        }

        $basename = pathinfo($path, PATHINFO_BASENAME);

        File::copy($path, THEME_VIEWS . DS . 'vendor' . DS . $name . DS . $basename);
    }
}