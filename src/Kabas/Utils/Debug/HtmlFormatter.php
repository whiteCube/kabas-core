<?php 

namespace Kabas\Utils\Debug;

class HtmlFormatter implements FormatterInterface
{
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


    protected $stack = [];

    protected $maxStringLength = 40;

    public function format($stack)
    {
        $this->stack = $stack;
        $output = $this->getTableOpening();
        foreach ($this->stack as $trace) {
            $output .= $this->getTraceRow($trace);
        }
        $output .= $this->getTableClosing();
        return $output;
    }


    protected function getTableOpening()
    {
        $tag = '<table' . $this->getStyle('table') . '>';
        $tag .= '<thead>';
        $tag .= '<tr' . $this->getStyle('headRow') . '>';
        foreach ($this->columns as $title) {
            $tag .= '<th' . $this->getStyle('headCell') . '>' . $title . '</th>';
        }
        $tag .= '</tr>';
        $tag .= '</thead>';
        $tag .= '<tbody>';
        return $tag;
    }

    protected function getStyle($key)
    {
        if(!isset($this->styles[$key])) return '';
        return ' style="' . $this->styles[$key] . '"';
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
        foreach (array_keys($this->columns) as $key) {
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
        if($function['arguments']) {
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