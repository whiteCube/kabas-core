<?php

namespace Kabas\Utils;

class Benchmark
{
      /**
       * The currently running benchmarks
       * @var array
       */
      private static $benchmarks = [];

      /**
       * Start a benchmarking timer
       * @param  string $name
       * @return void
       */
      static function start($name)
      {
            $start = microtime(true);
            self::$benchmarks[$name] = $start;
      }

      /**
       * Stops the specified timer and displays the elapsed time.
       * @param  string $name
       */
      static function stop($name)
      {
            $end = microtime(true);
            $time = $end - self::$benchmarks[$name];
            $time = sprintf('%0.7f', $time);
            unset(self::$benchmarks[$name]);

            echo '<pre>🏁 <strong>Benchmark</strong>: "' . $name . '" took ' . $time . ' seconds. </pre>';
      }
}
