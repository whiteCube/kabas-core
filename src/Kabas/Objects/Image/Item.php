<?php

namespace Kabas\Objects\Image;

use Kabas\Objects\Image\Editor;
use Kabas\Utils\Image;
use Kabas\Utils\Url;
use Kabas\Utils\File;

class Item
{
    public $error = false;
    public $original;
    public $path;
    public $dirname;
    public $public;
    public $filename;
    public $extension;

    protected $editor;
    protected $dataMethods = [
        'filesize',
        'getCore',
        'height',
        'iptc',
        'exif',
        'mime',
        'pickColor',
        'width'
    ];

    public function __construct($content)
    {
        if(is_array($content)) $content = (object) $content;
        if(!is_object($content)) return $this->error = true;
        $content = $this->mergeWithBase($content);
        $this->setFile($content->path);
        $this->setAlt($content->alt);
    }

    public function __toString()
    {
        return $this->src();
    }

    public function __call($name, $args)
    {
        $this->makeEditor(false);
        if(in_array($name, $this->dataMethods)) return $this->forwardToIntervention($name, $args);
        call_user_func_array([$this->editor, $name], $args);
        return $this;
    }

    protected function forwardToIntervention($name, $args)
    {
        $this->editor->prepareIntervention();
        return call_user_func_array([$this->editor->intervention, $name], $args);
    }

    public function apply()
    {
        $this->path = $this->getPublicImage();
        return $this;
    }

    public function show($echo = true)
    {
        $s = '<img src="' . $this->src() . '" alt="' . $this->alt() . '" />';
        if($echo) echo($s);
        return $s;
    }

    public function alt()
    {
        return $this->alt;
    }

    public function src()
    {
        if(!$this->path || ($this->editor && $this->editor->hasChanges())) $this->apply();
        return Url::fromPath($this->path);
    }

    protected function mergeWithBase($content)
    {
        $base = new \stdClass;
        $base->path = false;
        $base->alt = false;
        foreach($content as $key => $value) {
            $base->$key = $value;
        }
        return $base;
    }

    protected function makeEditor($prepareIntervention = true)
    {
        if($this->error) return;
        if(!$this->editor) {
            $this->editor = new Editor($this->dirname, $this->filename, $this->extension);
        }
        if($prepareIntervention) $this->editor->prepareIntervention();
    }

    protected function setFile($path)
    {
        if(!$path || !($this->original = realpath(ROOT_PATH . DS . trim($path, '\\/')))) {
            $this->error = true;
            return;
        }
        $file = pathinfo($this->original);
        $this->filename = $file['filename'] ?? null;
        $this->extension = $file['extension'] ?? null;
        $this->dirname = $file['dirname'] ?? null;
        $this->public = $this->getPublicPath($this->dirname);
    }

    protected function setAlt($string)
    {
        $this->alt = $this->getAlt($string);
    }

    protected function getAlt($string = null)
    {
        if(is_string($string)) return $string;
        return $this->filename;
    }

    /**
     * Generates a full path to the image's supposed public directory
     * @param  string $original
     * @return string
     */
    protected function getPublicPath($original)
    {
        if(!$original) return;
        $original = $this->getPublicSubDir($original);
        return rtrim(PUBLIC_UPLOADS_PATH . DS . $original, DS);
    }

    /**
     * Strips original path, only keeping useful subdirectories
     * @param  string $original
     * @return string
     */
    protected function getPublicSubDir($original)
    {
        if(strpos($original, UPLOADS_PATH) !== 0) return '';
        return trim(substr($original, strlen(UPLOADS_PATH)),'\\/');
    }

    /**
     * Creates publicly queryable image and returns its full path
     * @return string
     */
    protected function getPublicImage()
    {
        if($this->editor && $this->editor->hasChanges()) {
            return $this->editor->save($this->public);
        }
        return File::copy($this->original, $this->getPublicOriginal(), false);
    }

    /**
     * Returns full path to the original public image
     * @return string
     */
    protected function getPublicOriginal()
    {
        return $this->public . DS . $this->filename . '.' . $this->extension;
    }
}
