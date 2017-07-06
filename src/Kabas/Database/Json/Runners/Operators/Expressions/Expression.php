<?php

namespace Kabas\Database\Json\Runners\Operators\Expressions;

use Carbon\Carbon;

class Expression implements ExpressionInterface
{
    protected $cleaned;

    protected $parsed;

    const NULL = 'NULL';

    protected static $string = ['checkbox','color','email','file','radio','select','text','textarea','wysiwyg'];
    protected static $aggregate = ['flexible','gallery','group','image','partial','repeater','tel','url'];


    public function __construct($expression)
    {
        $this->cleaned = $this->cleanupValue($expression);
    }

    /**
     * returns expression transformed to given type
     * @param string $type
     * @param bool $fallbackToString
     * @return mixed
     */
    public function toType(string $type, bool $fallbackToString = false) {
        $method = $this->getTransformerMethodFromType($type);
        if(method_exists($this, $method)) {
            return call_user_func([$this, $method]);
        }
        if($fallbackToString) return $this->toString();
        return false;
    }

    /**
     * returns this expression as a string
     * @return string
     */
    public function toString() {
        if(is_null($this->cleaned)) return static::NULL;
        if(is_a($this->cleaned, Carbon::class)) return $this->cleaned->__toString();
        return (string) $this->cleaned;
    }

    /**
     * Returns this expression as a Carbon instance or false
     * @return Carbon\Carbon|bool
     */
    protected function toDate() {
        if(is_null($this->cleaned)) return false;
        if(is_a($this->cleaned, Carbon::class)) return $this->cleaned;
        return Carbon::parse($this->cleaned);
    }

    /**
     * Transforms value to usable values if needed.
     * @param mixed $value
     * @return mixed
     */
    protected function cleanupValue($value) {
        $value = $this->castNullStringToNull($value);
        if(!is_string($value)) return $value;
        return trim($value);
    }

    /**
     * Transforms "null" strings to real null values if needed.
     * @param mixed $value
     * @return mixed
     */
    protected function castNullStringToNull($value) {
        if(!is_string($value)) return $value;
        if(strtoupper($value) == static::NULL) return null;
        return $value;
    }

    /**
     * Finds the right transformation method name from Field type
     * @param string $type
     * @return string
     */
    protected function getTransformerMethodFromType($type) {
        if(in_array($type, static::$string)) return 'toString';
        // TODO : Aggregated fields are not yet supported, toAggregate
        // does not exist.
        if(in_array($type, static::$aggregate)) return 'toAggregate';
        return 'to' . ucfirst(strtolower($type));
    }
}