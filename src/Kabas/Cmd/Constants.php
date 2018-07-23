<?php

namespace Kabas\Cmd;

class Constants
{
    public function __construct($projectDir)
    {
        if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
        if(!defined('ROOT_PATH')) define('ROOT_PATH', realpath($projectDir));
        if(!defined('TEMPLATES_PATH')) define('TEMPLATES_PATH', __DIR__ . DS . 'Templates' . DS);
        if(!defined('CONFIG_PATH')) define('CONFIG_PATH', ROOT_PATH . DS . 'config');
        if(!defined('THEMES_PATH')) define('THEMES_PATH', ROOT_PATH . DS . 'themes');
    }
}