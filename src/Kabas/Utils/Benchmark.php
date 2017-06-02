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
       * Displays the elapsed time for a timer.
       * @param  string $name
       */
      static function inspect($name)
      {
            if(!array_key_exists($name, self::$benchmarks)) throw new \Exception('No benchmarks named ' . $name . ' currently running');
            $end = microtime(true);
            $time = $end - self::$benchmarks[$name];
            $time = sprintf('%0.7f', $time);
            return '<pre>🏁 <strong>Benchmark</strong>: "' . $name . '" has been running for ' . $time . ' seconds. </pre>';
      }

      /**
       * Stops the specified timer and displays the elapsed time.
       * @param  string $name
       */
      static function stop($name)
      {
            $feedback = self::inspect($name);
            unset(self::$benchmarks[$name]);
            return $feedback;
      }
}
