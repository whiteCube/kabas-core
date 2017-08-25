<?php

namespace Kabas\Objects\Image;

use Intervention\Image\ImageManager as Intervention;
use Kabas\App;

class Editor
{
    public $intervention;

    protected $directory;
    protected $filename;
    protected $extension;
    protected $history = [];

    function __construct($directory, $filename, $extension)
    {
        $this->directory = $directory;
        $this->filename = $filename;
        $this->extension = $extension;
    }

    public function __call($name, $args)
    {
        $this->history[] = $this->getAction($name, $args, $this->getActionString($name, $args));
    }

    public function hasChanges()
    {
        if (count($this->history)) return true;
        return false;
    }

    public function save($directory = null)
    {
        if(!$directory) return;
        mkdir($directory, 0755, true);
        $file = $directory . DS . $this->getFullFilename();
        if(!file_exists($file)) {
            $this->executeActions();
            $this->intervention->save($file);
        }
        $this->history = [];
        return $file;
    }

    protected function getActionString($name, $args)
    {
        return $name . $this->getArgsString($args);
    }

    protected function getArgsString($args, $separator = '_')
    {
        return implode($separator, array_map(function($arg){
            if(is_callable($arg)) return 'closure';
            if(is_array($arg)) return $this->getArgsString($arg, '-');
            if(is_object($arg)) return serialize($arg);
            return (string) $arg;
        }, $args));
    }
    
    protected function getFullFilename()
    {
        return $this->filename . '-' . $this->getHistoryString() . '.' . $this->extension;
    }

    protected function getHistoryString()
    {
        return md5(implode('#', array_map(function($action) {
            return $action->slug;
        }, $this->history)));
    }

    protected function getAction($name, $args, $slug = null)
    {
        $edit = new \stdClass();
        $edit->action = $name;
        $edit->args = $args;
        $edit->slug = $slug ?? $name;
        return $edit;
    }

    protected function executeActions()
    {
        $this->prepareIntervention();
        foreach ($this->history as $o) {
            $this->intervention = call_user_func_array([$this->intervention, $o->action], $o->args);
        }
    }

    public function prepareIntervention()
    {
        if($this->intervention) return;
        $this->intervention = new Intervention(['driver' => App::config()->get('app.imageDriver')]);
        $this->intervention = $this->intervention->make($this->directory . DS . $this->filename . '.' . $this->extension);
    }
}
