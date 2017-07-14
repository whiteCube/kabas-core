<?php

namespace Kabas\Utils\Debug;

class Backtrace {

    protected $styles = [
        'table' => 'margin:1em 0;border-collapse:collapse;font-family:sans-serif;font-size:0.8em',
        'headRow' => 'background:#ffe094',
        'headCell' => 'font-weight:bold;padding:0.5em 1em;border:1px solid #ffb294',
        'index' => 'font-weight:bold;padding:0.5em 1em;border:1px solid lightgrey',
        'cell' => 'padding:0.5em 1em;border:1px solid lightgrey',
        'placeholder' => 'color:rgb(120,120,120);font-style:italic',
        'line' => 'font-weight:bold',
        'function' => 'color:rgb(60,60,60);font-family:monospace',
        'boolean' => 'color:orange',
        'integer' => 'color:blue',
        'float' => 'color:orange',
        'array' => 'color:grey',
        'object' => 'color:brown',
        'null' => 'color:lightgrey',
        'string' => 'color:green'
    ];

    protected $columns = [
        'index' => '#',
        'file' => 'File',
        'line' => 'Line',
        'function' => 'Called function'
    ];

    protected $placeholders = [
        'file' => 'unknown file',
        'line' => '--',
        'function' => 'undefined function called'
    ];

    protected $maxStringLength = 40;

    protected $hasArguments;

    protected $stack = [];

    protected $output;

    public function __construct($stack = [], $hasArguments = true)
    {
        $this->hasArguments = $hasArguments;
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

    public function cli()
    {
        $output = '' . PHP_EOL;
        foreach($this->stack as $trace) {
            $output .= $this->generateCliRow($trace);
        }
        echo $output;
    }

    protected function generateCliRow($trace)
    {
        // TODO: this is very dirty but I needed it quickly
        // We can refactor this to have multiple formatters 
        // that follow an interfacewhen we have more time
        $spaces = str_repeat(' ', 5 - strlen($trace['index']));
        $header = $spaces . $trace['index'] . ' | ' . ($trace['file'] ? $trace['file'] : 'unknown file') . PHP_EOL;
        $detail = '        ' . $trace['function']['prefix'] . $trace['function']['name'] ;
        if($this->hasArguments && $trace['function']['arguments']) {
            $detail .= '(';
            foreach($trace['function']['arguments'] as $index => $arg) {
                if($arg['formatter'] == 'formatStringArgument') $detail .= '\''; 
                $detail .= $arg['value'];
                if($arg['formatter'] == 'formatStringArgument') $detail .= '\''; 
                if($index < count($trace['function']['arguments']) - 1) $detail .= ', ';
            }
            $detail .= ')';
        }
        $detail .= PHP_EOL . PHP_EOL;
        return $header . $detail;
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
        $output = $this->getTableOpening();
        foreach ($this->stack as $trace) {
            $output .= $this->getTraceRow($trace);
        }
        $output .= $this->getTableClosing();
        return $output;
    }

    protected function getStyle($key)
    {
        if(!isset($this->styles[$key])) return '';
        return ' style="' . $this->styles[$key] . '"';
    }

    protected function getTableOpening()
    {
        $tag = '<table' . $this->getStyle('table') . '>';
        $tag .= '<thead>';
        $tag .= '<tr' . $this->getStyle('headRow') . '>';
        foreach ($this->columns as $key => $title) {
            $tag .= '<th' . $this->getStyle('headCell') . '>' . $title . '</th>';
        }
        $tag .= '</tr>';
        $tag .= '</thead>';
        $tag .= '<tbody>';
        return $tag;
    }

    protected function getTableClosing()
    {
        $tag = '</tbody>';
        $tag .= '</table>';
        return $tag;
    }

    protected function getTraceRow($trace)
    {
        $tag = '<tr>';
        foreach ($this->columns as $key => $title) {
            $tag .= call_user_func_array([$this, 'get' . ucfirst($key) . 'Column'], [$trace[$key]]);
        }
        $tag .= '</tr>';
        return $tag;
    }

    public function getIndexColumn($index) {
        return '<th' . $this->getStyle('index') . '>' . $index . '</th>';
    }

    public function getFileColumn($file) {
        if($file === false) return $this->getCell($this->getPlaceholder('file'));
        return $this->getCell('<span' . $this->getStyle('file') . '>' . $file . '</span>');
    }

    public function getLineColumn($line) {
        if($line === false) return $this->getCell($this->getPlaceholder('line'));
        return $this->getCell('<strong' . $this->getStyle('line') . '>' . $line . '</strong>');
    }

    public function getFunctionColumn($function) {
        if(!is_array($function)) return $this->getCell($this->getPlaceholder('function'));
        $tag = '<code' . $this->getStyle('function') . '>';
        $tag .= isset($function['prefix']) ? $function['prefix'] : '';
        $tag .= '<strong' . $this->getStyle('functionName') . '>' . $function['name'] . '</strong>';
        $tag .= '<span' . $this->getStyle('functionArgs') . '>(';
        if($this->hasArguments && $function['arguments']) {
            $tag .= implode(', ', array_map(function($argument) { 
                return call_user_func_array([$this, $argument['formatter']], [$argument['value']]);
            }, $function['arguments']));
        }
        $tag .= ')</span>';
        $tag .= '</code>';
        return $this->getCell($tag);
    }

    protected function getCell($content) {
        return '<td' . $this->getStyle('cell') . '>' . $content . '</td>';
    }

    protected function getPlaceholder($type) {
        return '<em' . $this->getStyle('placeholder') . '>' . ($this->placeholders[$type] ?? '') . '</em>';
    }

    public function formatBooleanArgument($value)
    {
        return '<span' . $this->getStyle('boolean') . '>' . $value . '</span>';
    }

    public function formatIntegerArgument($value)
    {
        return '<span' . $this->getStyle('integer') . '>' . $value . '</span>';
    }

    public function formatFloatArgument($value)
    {
        return '<span' . $this->getStyle('float') . '>' . $value . '</span>';
    }

    public function formatArrayArgument($value)
    {
        return '<span' . $this->getStyle('array') . '>array(' . $value . ')</span>';
    }

    public function formatObjectArgument($value)
    {
        return '<span' . $this->getStyle('object') . '>' . $value . '</span>';
    }

    public function formatNullArgument($value)
    {
        return '<span' . $this->getStyle('null') . '>' . strtoupper($value) . '</span>';
    }

    public function formatStringArgument($value)
    {
        $string = (strlen($value) <= $this->maxStringLength) ? $value : substr($value, 0, $this->maxStringLength) . '&hellip;';
        return '"<span' . $this->getStyle('string') . ' title="' . $value . '">' . $string . '</span>"';
    }
}