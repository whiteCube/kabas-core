<?php

namespace Kabas\Fields\Types;

use Kabas\Fields\Textual;

class Wysiwyg extends Textual
{
      protected $type = "wysiwyg";

      protected $headingStart;

      protected $headingLowest = false;

      protected $headingOriginal = [];

      protected $headingReplace = [];

      protected function parse($value)
      {
            $parser = new \ParsedownExtra();
            return $parser->text($value);
      }

      public function headingLevel($level)
      {
            $this->headingStart = intval($level);
            $this->output = $this->formatHeadings($this->output);
            return $this;
      }

      protected function formatHeadings($text)
      {
            $this->computeDelta($text);
            
            $result = preg_replace_callback('/<(\/)?[hH]([1-6])([^>]*)?>/', function($matches) {
                  return $this->getNewHeadingTag($matches);
            }, $text);

            return $result;
      }

      protected function computeDelta($text)
      {
            preg_match_all('/\<[hH]([1-6])(?:.*?)\>/', $text, $matches);
            foreach($matches[1] as $level) {
                  $this->updateDelta($level);
            }
      }

      protected function updateDelta($level)
      {
            if($this->headingLowest === false || $level < $this->headingLowest) $this->headingLowest = $level;
      }

      protected function getNewHeadingTag($heading)
      {
            return '<' . $heading[1] . 'h' . $this->getNewHeadingLevel($heading[2]) . $heading[3] . '>';
      }

      protected function getNewHeadingLevel($level)
      {
            $level = $this->headingStart + ($level - $this->headingLowest);
            if($level > 6) return 6;
            return $level;
      }


}
