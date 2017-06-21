<?php 

namespace Tests;

trait HandlesOutput {

    public $result;

    public function catch(\Closure $test, $preferClosureOutput = false)
    {
        ob_start();
        $result = $test->call($this);
        $output = ob_get_clean();
        return $preferClosureOutput ? $result : $output;
    }

    public function see($string)
    {
        return $this->assertContains($string, $this->result);
    }

}