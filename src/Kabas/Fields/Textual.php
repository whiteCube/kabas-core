<?php

namespace Kabas\Fields;

use Kabas\Utils\Text;

class Textual extends Item
{
    public function condition()
    {
        return is_string($this->value);
    }

    /**
     * Turn the string to uppercase characters.
     * @return $this
     */
    public function uppercase()
    {
        $this->output = Text::uppercase($this->output);
        return $this;
    }

    /**
     * Turn the string to lowercase characters.
     * @return $this
     */
    public function lowercase()
    {
        $this->output = Text::lowercase($this->output);
        return $this;
    }

    /**
     * Escapes html entities
     * @return $this
     */
    public function escape()
    {
        $this->output = Text::escape($this->output);
        return $this;
    }

    /**
     * Checks if output contains given substring
     * @return boolean
     */
    public function contains($string, $caseSensitive = true)
    {
        return Text::contains($string, $this->output, $caseSensitive);
    }

    /**
     * Cuts a string after a specified amount of characters.
     * Appends triple dots.
     * @param  int $length
     * @param  string $append
     * @return $this
     */
    public function cut($length = 100, $append = '&nbsp;&hellip;')
    {
        $this->output = Text::cut($this->output, $length, $append);
        return $this;
    }

    /**
     * Cuts a string after a specified amount of characters,
     * without cutting inside a word. Appends triple dots.
     * @param  int $length
     * @param  string $append
     * @return $this
     */
    public function shorten($length = 100, $append = '&nbsp;&hellip;')
    {
        $this->output = Text::shorten($this->output, $length, $append);
        return $this;
    }

    /**
     * Checks if string is longer than given length
     * @param int $length 
     * @return bool
     */
    public function exceeds($length)
    {
        return Text::exceeds($this->output, $length);
    }
}
