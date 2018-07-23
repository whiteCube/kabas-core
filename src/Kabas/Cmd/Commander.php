<?php

namespace Kabas\Cmd;

use Kabas\Config\Container as Config;
use Kabas\Utils\File;
use Kabas\Utils\Text;
use Kabas\Exceptions\TypeException;
use Kabas\Exceptions\InvalidDriverException;
use Kabas\Exceptions\ArgumentMissingException;
use Kabas\Exceptions\CommandNotFoundException;

class Commander
{
    protected $command;
    protected $arguments;
    protected $config;
    protected $theme;
    protected $lang;

    public function __construct($args, Config $config)
    {
        $this->extractConfig($config);
        $this->setConstants();
        $this->command = $this->findCommand($args);
        $this->arguments = $args;
        $this->setThemeConstants();
        try {
            $this->executeCommand();
        } catch(\Exception $e) {
            $this->showErrorMessage($e);
        }
    }

    protected function findCommand(&$args)
    {
        $command = array_shift($args);
        return $this->getNamespaceAndMethod($command);
    }

    protected function getNamespaceAndMethod($command)
    {
        if($this->commandEmpty($command)) $command = 'help';

        $parts = explode(':', $command);
        return (object) [
            'class' => 'Kabas\\Cmd\\Commands\\' . Text::toNamespace($parts[0]),
            'method' => $parts[1] ?? 'run'
        ];
    }

    protected function showErrorMessage($exception)
    {
        echo PHP_EOL;
        echo '•• Error ••' . PHP_EOL;
        echo $exception->getMessage();
        echo PHP_EOL;
    }

    /**
     * Set constants to use throughout commands.
     * @return void
     */
    protected function setConstants()
    {
        if(!defined('THEME')) define('THEME', $this->theme);
        if(!defined('THEME_PATH')) define('THEME_PATH', THEMES_PATH . DS . THEME);
    }

    /**
     * Set constants for theme paths
     * @return void
     */
    protected function setThemeConstants()
    {
        if(!defined('THEME_STRUCTURES')) define('THEME_STRUCTURES', THEME_PATH . DS . 'structures');
        if(!defined('THEME_VIEWS')) define('THEME_VIEWS', THEME_PATH . DS . 'views');
        if(!defined('THEME_CONTROLLERS')) define('THEME_CONTROLLERS', THEME_PATH . DS . 'controllers');
        if(!defined('THEME_MODELS')) define('THEME_MODELS', THEME_PATH . DS . 'models');
    }

    /**
     * Point the command to the right method.
     * @return void
     */
    protected function executeCommand()
    {
        if($this->commandNotFound()) throw new CommandNotFoundException();

        return (new $this->command->class)->{$this->command->method}(...$this->arguments);
    }

    protected function commandEmpty($command)
    {
        return $command == '' || $command == [];
    }

    protected function commandNotFound()
    {
        return !method_exists($this->command->class, $this->command->method);
    }

    protected function extractConfig($config)
    {
        $this->theme = $config->get('site.theme');
    }


}
