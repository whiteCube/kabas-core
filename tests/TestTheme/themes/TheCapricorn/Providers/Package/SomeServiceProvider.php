<?php

namespace Theme\TheCapricorn\Providers\Package;

use Kabas\Providers\ServiceProvider;

class SomeServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->singleton(SomeService::class);
        $this->app->alias(SomeService::class, 'someservice');
    }

    public function boot() 
    {
        $this->loadRoutesFrom(__dir__ . '/routes.php');
        $this->publishConfig(__dir__ . '/config.php', 'mypackage');
        $this->publishViews(__dir__ . '/views/view.php', 'mypackage');
        $this->publishViews(__dir__ . '/views', 'mypackage');
    }

}