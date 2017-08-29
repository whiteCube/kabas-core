<?php

namespace Kabas\Objects\Uploads;

use Kabas\Utils\Url;
use Kabas\Utils\File as FileUtil;

//  TODO : this and Kabas\Objects\Image\Item have a lot of shared stuff. 
//  Image should probably inherit from this class.

class File
{
    public $error = false;
    public $original;
    public $path;
    public $dirname;
    public $public;
    public $filename;
    public $extension;
    public $size;

    public function __construct(string $path)
    {
        $this->setFile($path);
    }

    public function __toString()
    {
        return $this->src();
    }

    public function apply()
    {
        $this->path = $this->getPublicImage();
        return $this;
    }

    public function src()
    {
        if(!$this->path) $this->apply();
        return Url::fromPath($this->path);
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
        $this->size = filesize($this->original);
        $this->public = $this->getPublicPath($this->dirname);
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
        return FileUtil::copy($this->original, $this->getPublicOriginal(), false);
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
