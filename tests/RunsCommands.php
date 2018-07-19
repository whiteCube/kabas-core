<?php

namespace Tests;

use Kabas\Cmd\Commander;

trait RunsCommands
{
    public function prepareCommands()
    {
        if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
        if(!defined('THEMES_DIR')) define('THEMES_DIR', __DIR__ . DS . '..' . DS . THEME . DS . 'themes');
    }

    public function cmd(...$args)
    {
        return new Commander(THEMES_DIR . DS . '..' . DS, $args);
    }
}