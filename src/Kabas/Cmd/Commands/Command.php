<?php 

namespace Kabas\Cmd\Commands;

class Command
{
    /**
     * Gets (or creates) the path to a directory
     * @param string $path 
     * @return string
     */
    protected function dir($path){
        if(!realpath($path)) mkdir($path, 0755, true);
        return realpath($path);
    }
}