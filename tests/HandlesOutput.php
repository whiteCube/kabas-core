<?php 

namespace Tests;

trait HandlesOutput {

    public $result;

    public function catch(\Closure $test)
    {
        ob_start();
        $test->call($this);
        return ob_get_clean();
    }

    public function see($string)
    {
        return $this->assertContains($string, $this->result);
    }

}