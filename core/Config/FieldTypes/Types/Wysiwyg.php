<?php

namespace Kabas\Config\FieldTypes;

class Wysiwyg extends Textual
{
      public $type = "wysiwyg";
      protected $raw;


      protected $headingStart;
      protected $headingLowest = false;
      protected $headingOriginal = [];
      protected $headingReplace = [];

      protected $output;

      public function __construct($fieldName = null, $data = null, $multiVal = null)
      {
            parent::__construct($fieldName, $data);
            $this->raw = $data;
            $this->data = $this->parse($data);
      }

      protected function parse($data)
      {
            return (new \ParsedownExtra())->text($data);
      }

      public function raw()
      {
            $this->modified = $this->raw;
            return $this;
      }

      public function baseHeading($level)
      {
            $this->output = $this->getText();
            $this->headingStart = $level;
            $this->modified = $this->formatHeadings($this->output);
            return $this;
      }

      protected function registerHeadings($s)
      {
            $a = [];
            preg_match_all('/<(\/)?h([1-6])([^>]*)?>/', $s, $aMatches);
            foreach ($aMatches[0] as $i => $tag) {
                  if(!in_array($tag, $this->headingOriginal)){
                        $o = new \stdClass();
                        $o->closing = $aMatches[1][$i];
                        $o->level = intval($aMatches[2][$i]);
                        $o->attr = $aMatches[3][$i];
                        $a[$tag] = $o;
                        array_push($this->headingOriginal, $tag);
                        $this->updateDelta($o->level);
                  }
            }
            return $a;
      }

      protected function formatHeadings($s)
      {
           $this->makeReplacementHeadings($this->registerHeadings($s));
           return str_replace($this->headingOriginal, $this->headingReplace, $s);
      }

      protected function makeReplacementHeadings($a)
      {
            foreach ($this->headingOriginal as $s) {
                  array_push($this->headingReplace, $this->getNewHeadingTag($a[$s]) );
            }
      }

      protected function updateDelta($i)
      {
            if($this->headingLowest === false) $this->headingLowest = $i;
            elseif($i < $this->headingLowest) $this->headingLowest = $i;
      }

      protected function getNewHeadingTag($o)
      {
            return '<' . $o->closing . 'h' . $this->getNewHeadingLevel($o->level) . $o->attr . '>';
      }

      protected function getNewHeadingLevel($i)
      {
            $i = $this->headingStart + ($i - $this->headingLowest);
            if($i > 6) return 6;
            return $i;
      }

}
