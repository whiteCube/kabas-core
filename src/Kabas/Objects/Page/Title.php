<?php

namespace Kabas\Objects\Page;

class Title
{
    public $default;

    public $content;

    protected $hasFormatting = true;

    function __construct($default = null)
    {
        $this->default = trim((string) $default);
        $this->content = $this->default;
    }
    
    /**
     * Sets current title and formatting flag
     * @param  string $content
     * @param  bool $hasFormatting
     * @return void
     */
    public function set($content, $hasFormatting = true)
    {
        $this->content = trim((string) $content);
        $this->hasFormatting = $hasFormatting;
    }
    
    /**
     * Gets a full title string, with or without optional 
     * prefix & suffix, depending on the hasFormatting flag
     * @param  string $prefix
     * @param  string $suffix
     * @return string
     */
    public function get($prefix = null, $suffix = null)
    {
        if(!$this->hasFormatting) return $this->content;
        return $this->format($prefix, $suffix);
    }

    /**
     * Adds prefix and suffix to the title
     * @param  string $prefix
     * @param  string $suffix
     * @return string
     */
    protected function format($prefix, $suffix)
    {
        return $prefix . $this->content . $suffix;
    }

}
