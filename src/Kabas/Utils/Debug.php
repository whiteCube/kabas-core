<?php

namespace Kabas\Utils;

class Debug {

    public static function backtrace()
    {
        $items = debug_backtrace();
        array_shift($items);
        echo '<table style="border:1px solid black; margin: 1em 0;"><thead><tr style="background:orange;"><th>#</th><th>File</th><th>Line</th><th>Function</th></tr></thead><tbody>';
        foreach ($items as $i => $trace) {
            echo '<tr><th>' . ($i + 1) . '</th>';
            echo '<td>' . $trace['file'] . '</td>';
            echo '<td>' . $trace['line'] . '</td>';
            echo '<td><code style="color:rgb(60,60,60)">' . $trace['class'] . $trace['type'] . '<strong>' . $trace['function'] . '</strong></code></td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    }

}