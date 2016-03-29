<?php

namespace Kabas\Utils;

class Benchmark
{

      static $benchmarks = [];

      static function start($name)
      {
            $start = microtime(true);
            self::$benchmarks[$name] = $start;
      }

      static function stop($name)
      {
            $end = microtime(true);
            $time = $end - self::$benchmarks[$name];
            $time = sprintf('%0.7f', $time);

            echo '<pre>🏁 <strong>Benchmark</strong>: "' . $name . '" took ' . $time . ' seconds. </pre>';
      }
}
