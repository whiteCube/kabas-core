<?php 

namespace Kabas\Cmd\Commands;

class Help 
{
    public function run()
    {
        require TEMPLATES_PATH . 'Help.php';
    }
}