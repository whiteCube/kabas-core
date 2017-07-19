<?php

namespace Kabas\Utils\Debug;

class Backtrace {

    protected $output;

    public function __construct($stack = [])
    {
        $this->pickFormatter();
        $this->stack = $this->prepareStack($stack);
    }

    public function __toString()
    {
        return $this->getOutput();
    }

    public function get()
    {
        return $this->stack;
    }

    public function getOutput()
    {
        if(is_null($this->output)) $this->output = $this->generateOutput();
        return $this->output;
    }

    protected function pickFormatter()
    {
        if(PHP_SAPI == 'cli') return $this->formatter = new CliFormatter;
        return $this->formatter = new HtmlFormatter;
    }

    protected function prepareStack($stack)
    {
        $items = [];
        $index = count($stack);
        foreach ($stack as $line) {
            $items[] = $this->getTrace(($index--), $line);
        }
        return $items;
    }

    protected function getTrace($index, $line)
    {
        $trace = [];
        $trace['index'] = $index;
        $trace['file'] = $line['file'] ?? false;
        $trace['line'] = $line['line'] ?? false;
        $trace['function'] = $this->getFunction($line);
        return $trace;
    }

    protected function getFunction($line)
    {
        if(!isset($line['function'])) return false;
        $function = [];
        $function['prefix'] = isset($line['class']) ? $line['class'] . $line['type'] : false;
        $function['name'] = $line['function'];
        $function['arguments'] = $this->getFunctionArguments($line);
        return $function;
    }

    protected function getFunctionArguments($line)
    {
        if(!count($line['args'])) return false;
        $arguments = [];
        foreach ($line['args'] as $argument) {
            $arguments[] = $this->getArgument($argument);
        }
        return $arguments;
    }

    protected function getArgument($value)
    {
        $argument = [];
        switch (gettype($value)) {
            case 'boolean':
                $argument['formatter'] = 'formatBooleanArgument';
                $argument['value'] = $value ? 'true' : 'false';
                break;
            case 'integer':
                $argument['formatter'] = 'formatIntegerArgument';
                $argument['value'] =  $value;
                break;
            case 'double':
                $argument['formatter'] = 'formatFloatArgument';
                $argument['value'] =  $value;
                break;
            case 'array':
                $argument['formatter'] = 'formatArrayArgument';
                $argument['value'] =  count($value);
                break;
            case 'object':
                $argument['formatter'] = 'formatObjectArgument';
                $argument['value'] =  get_class($value);
                break;
            case 'NULL':
                $argument['formatter'] = 'formatNullArgument';
                $argument['value'] =  'null';
                break;
            default:   
                $argument['formatter'] = 'formatStringArgument';
                $argument['value'] =  strval($value);
                break;
        }
        return $argument;
    }

    protected function generateOutput()
    {
        return $this->formatter->format($this->stack);
    }
}