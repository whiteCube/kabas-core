<?php

namespace Kabas\Objects\Image;

use Intervention\Image\ImageManager as Intervention;
use Kabas\App;

class Editor
{
    public $intervention;

    protected $dirname;

    protected $filename;

    protected $extension;

    protected $file;

    protected $history = [];

    function __construct($dir, $file, $extension)
    {
        $this->dirname = $dir;
        $this->filename = $file;
        $this->extension = $extension;
    }

    public function __call($name, $args)
    {
        $this->history[] = $this->getAction($name, $args, $this->getSlug($name, $args));
    }

    protected function getSlug($name, $args)
    {
        switch($name) {
            case 'blur':
            case 'brightness':
            case 'contrast':
            case 'gamma':
            case 'heighten':
            case 'opacity':
            case 'rotate':
            case 'sharpen':
            case 'widen':       return $this->appendFirstArgumentToName($name, $args[0]);
            case 'flip':        return 'flip-' . $args[0];
            case 'circle':      return 'circleX' . $args[1] . 'Y' . $args[2];
            case 'colorize':    return 'r' . $args[0] . 'g' . $args[1] . 'b' . $args[2];
            case 'crop':        return 'crop' . $args[0] . 'x' . $args[1];
            case 'ellipse':     return 'ellipseX' . $args[2] . 'Y' . $args[3];
            case 'filter':      return 'filter-' . get_class($args[0]);
            case 'fit':
            case 'resize':
            case 'resizeCanvas':return $args[0] . 'x' . $args[1];
            case 'limitColors': return $args[0] . 'colors';
            default: return null;
        }
    }

    protected function appendFirstArgumentToName($name, $argument){
        return $name . $argument;
    }

    public function hasChanges()
    {
        if (count($this->history)) return true;
        return false;
    }

    public function save()
    {
        $this->setFile();
        if(!file_exists($this->dirname . DS . $this->file)) {
            $this->executeActions();
            $this->intervention->save($this->dirname . DS . $this->file);
        }
        $this->history = [];
        return $this->file;
    }
    
    protected function setFile()
    {
        $this->file = $this->filename . $this->getHistoryString() . '.' . $this->extension;
    }

    protected function getHistoryString()
    {
        $s = '';
        foreach ($this->history as $o) {
            $s .= '-' . str_replace('.', 'dot', $o->slug);
        }
        return $s;
    }

    protected function getAction($name, $args, $slug = false)
    {
        $o = new \stdClass();
        $o->action = $name;
        $o->args = $args;
        $o->slug = $slug ? $slug : $name;
        return $o;
    }

    protected function executeActions()
    {
        $this->prepareIntervention();
        if(!isset($this->intervention->filename)) {
            // I don't think this can ever happen
            // @codeCoverageIgnoreStart
            $this->intervention->filename = $this->filename;
            $this->intervention->extension = $this->extension;
            $this->intervention->dirname = $this->dirname;
            // @codeCoverageIgnoreStop
        }
        foreach ($this->history as $o) {
            $this->intervention = call_user_func_array([$this->intervention, $o->action], $o->args);
        }
    }

    public function prepareIntervention()
    {
        if(!$this->intervention){
            $this->intervention = new Intervention(['driver' => App::config()->get('app.imageDriver')]);
            $this->intervention = $this->intervention->make($this->dirname . DS . $this->filename . '.' . $this->extension);
        }
    }
}
