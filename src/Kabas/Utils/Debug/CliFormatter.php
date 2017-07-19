<?php 

namespace Kabas\Utils\Debug;

class CliFormatter
{

    protected $stack = [];

    public function format($stack)
    {
        $this->stack = $stack;
        $output = '' . PHP_EOL;
        foreach($this->stack as $trace) {
            $output .= $this->generateCliRow($trace);
        }
        return $output;
    }

    protected function generateCliRow($trace)
    {
        $spaces = str_repeat(' ', 5 - strlen($trace['index']));
        $header = $spaces . $trace['index'] . ' | ' . ($trace['file'] ? $trace['file'] : 'unknown file') . PHP_EOL;
        $detail = '        ' . $trace['function']['prefix'] . $trace['function']['name'] ;
        if($trace['function']['arguments']) {
            $detail .= $this->generateArguments($trace['function']['arguments']);
        }
        $detail .= PHP_EOL . PHP_EOL;
        return $header . $detail;
    }

    protected function generateArguments($args)
    {
        $string = '(';
        foreach($args as $index => $arg) {
            if($arg['formatter'] == 'formatStringArgument') $string .= '\''; 
            $string .= $arg['value'];
            if($arg['formatter'] == 'formatStringArgument') $string .= '\''; 
            if($index < count($args) - 1) $string .= ', ';
        }
        $string .= ')';
        return $string;
    }

}