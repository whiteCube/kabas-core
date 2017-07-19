<?php

namespace Kabas\Database\Json\Runners\Operators\Expressions;

use Carbon\Carbon;
use Kabas\Database\Json\Runners\Exceptions\ExpressionTypeException;
use Kabas\Database\Json\Runners\Exceptions\ExpressionConversionException;

class Expression implements ExpressionInterface
{
    protected $cleaned;

    protected $parsed = [];

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
            return $this->__call($method);
        }
        if($fallbackToString) return $this->toString(true);
        return false;
    }

    /**
     * Transforms expression to string through uncached toString method
     * @return string
     */
    public function __toString() {
        return $this->toString(true);
    }

    /**
     * Puts conversion method result in cache
     * @return string
     */
    public function __call($method, $arguments = []) {
        if(!isset($this->parsed[$method])){
            $this->parsed[$method] = call_user_func([$this, $method]);
        }
        return $this->parsed[$method];
    }

    /**
     * returns this expression as a string (which can or
     * cannot fail, depending on findSolution argument).
     * @param bool  $findSolution
     * @return string
     * @throws Kabas\Database\Json\Runners\Exceptions\ExpressionConversionException
     */
    public function toString($findSolution = false) {
        if(is_null($this->cleaned)) return static::NULL;
        if(is_a($this->cleaned, Carbon::class)) return $this->cleaned->__toString();
        try {
            $string = (string) $this->cleaned;
        } catch (\Exception $e) {
            if(!$findSolution) throw new ExpressionConversionException($this, 'string', null, $e);
            if(is_object($this->cleaned)) return get_class($this->cleaned);
            return gettype($this->cleaned);
        }
        return $string;
    }

    /**
     * Returns this expression as a Carbon instance or false
     * @return Carbon\Carbon|bool
     * @throws Kabas\Database\Json\Runners\Exceptions\ExpressionConversionException
     */
    public function toDate() {
        if(is_null($this->cleaned)) return false;
        if(is_a($this->cleaned, Carbon::class)) return $this->cleaned;
        try {
            $date = new Carbon($this->cleaned);
        } catch (\Exception $e) {
            throw new ExpressionConversionException($this, 'date', null, $e);
        }
        return $date;
    }

    /**
     * Returns this expression as a number
     * @return float
     * @throws Kabas\Database\Json\Runners\Exceptions\ExpressionConversionException
     */
    public function toNumber() {
        if(is_numeric($this->cleaned)) return floatval($this->cleaned);
        throw new ExpressionConversionException($this, 'number');
    }

    /**
     * Transforms value to usable values if needed.
     * @param mixed $value
     * @return mixed
     */
    protected function cleanupValue($value) {
        if(!$this->checkValue($value)) {
            throw new ExpressionTypeException($value);
        }
        $value = $this->castNullStringToNull($value);
        if(!is_string($value)) return $value;
        return trim($value);
    }

    /**
     * Checks if given value is usable inside an expression
     * @param mixed $value
     * @return bool
     */
    protected function checkValue($value) {
        if(is_array($value)) return false;
        if(is_object($value) && !method_exists($value, '__toString')) return false;
        return true;
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
        // Other types will be called as a to[Type] method.
        return 'to' . ucfirst(strtolower($type));
    }
}