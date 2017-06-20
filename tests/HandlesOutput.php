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

    public function visit($route)
    {
        $_SERVER['REQUEST_URI'] = $route;
        $this->result = $this->catch(function(){
            $this->app->handle();
        });
        return $this;
    }

    public function see($string)
    {
        return $this->assertContains($string, $this->result);
    }

}