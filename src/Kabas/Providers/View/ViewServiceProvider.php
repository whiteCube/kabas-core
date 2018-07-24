<?php

namespace Kabas\Providers\View;

use Kabas\Providers\ServiceProvider;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;
use Kabas\Utils\File;
use \Kabas\Config\Container as Config;

class ViewServiceProvider extends ServiceProvider
{
    protected $templatesPath = [];
    protected $compiledPath;

    public function register(Config $config)
    {
        $this->readPaths($config);
        $this->app->instance(Factory::class, $this->make());
        $this->app->alias(Factory::class, 'view');
        if(!is_dir($this->compiledPath)) File::mkdir($this->compiledPath);
    }

    protected function readPaths($config)
    {
        $templatesPaths = $config->get('app.views.sources') ?? [];
        foreach($templatesPaths as $index => $templatePath) {
            $templatesPaths[$index] = THEME_PATH . DS . $templatePath;
        }
        $this->templatesPath = $templatesPaths;
        $this->compiledPath = ROOT_PATH . DS . $config->get('app.views.compiled');
    }

    protected function make()
    {
        // Dependencies
        $filesystem = new Filesystem;
        $eventDispatcher = new Dispatcher($this->app);
        // Create View Factory capable of rendering PHP and Blade templates
        $viewResolver = new EngineResolver;
        $bladeCompiler = new BladeCompiler($filesystem, $this->compiledPath);
        $viewResolver->register('blade', function () use ($bladeCompiler) {
            return new CompilerEngine($bladeCompiler); // @codeCoverageIgnore
        });
        $viewFinder = new FileViewFinder($filesystem, $this->templatesPath);
        $viewFactory = new Factory($viewResolver, $viewFinder, $eventDispatcher);
        return $viewFactory;
    }
}