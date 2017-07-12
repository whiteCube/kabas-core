<?php

namespace Kabas\Fields\Types;

use \Kabas\Fields\Textual;

class Wysiwyg extends Textual
{
      protected $type = "wysiwyg";

      protected $headingStart;

      protected $headingLowest = false;

      protected $headingOriginal = [];

      protected $headingReplace = [];

      protected function parse($value)
      {
            $md = new \ParsedownExtra();
            return $md->text($value);
      }

      public function headingLevel($level)
      {
            $this->headingStart = intval($level);
            $this->output = $this->formatHeadings($this->output);
            return $this;
      }

      protected function formatHeadings($s)
      {
            $this->computeDelta($s);
            
            $result = preg_replace_callback('/<(\/)?[hH]([1-6])([^>]*)?>/', function($matches) {
                  return $this->getNewHeadingTag($matches);
            }, $s);

            return $result;
      }

      protected function computeDelta($s)
      {
            preg_match_all('/\<[hH]([1-6])(?:.*?)\>/', $s, $matches);
            foreach($matches[1] as $level) {
                  $this->updateDelta($level);
            }
      }

      protected function updateDelta($i)
      {
            if($this->headingLowest === false || $i < $this->headingLowest) $this->headingLowest = $i;
      }

      protected function getNewHeadingTag($heading)
      {
            return '<' . $heading[1] . 'h' . $this->getNewHeadingLevel($heading[2]) . $heading[3] . '>';
      }

      protected function getNewHeadingLevel($i)
      {
            $i = $this->headingStart + ($i - $this->headingLowest);
            if($i > 6) return 6;
            return $i;
      }


}
