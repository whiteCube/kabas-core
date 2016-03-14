<?php

namespace Whitecube\Bootstrap;

use \Kabas\Kabas;

class FileLoader
{
      // Base directory path
      public $dir;
      // Bootstrap directory
      public static $dirBootstrap = 'Bootstrap';
      // Build File
      public static $fileLoad = 'autoload-build.php';
      // Builder File
      public static $fileBuilder = 'Builder.php';

      // autoload files container
      public $files;

      function __construct( $dir )
      {
            $this->dir = $dir;
            $this->loadBuild();
      }

      //    LOAD KICK-IN

      public function autoload()
      {
            foreach ($this->files as $s) {
                  $filename = pathinfo($s, PATHINFO_FILENAME);
                  if(strpos($filename, '.class') !== false) {
                        require_once($s);
                  }
            }
      }

      //    FUNCTIONS

      protected function loadBuild()
      {
            if( Kabas::isDebug() || !file_exists( __DIR__ . DIRECTORY_SEPARATOR . self::$fileLoad ) ){
                  $this->build();
            } else {
                  $this->loadFiles();
            }
      }

      protected function build()
      {
            require_once( __DIR__ . DIRECTORY_SEPARATOR . self::$fileBuilder );
            $builder = new Builder( $this->dir );
            $this->files = $builder->files;
      }

      protected function loadFiles()
      {
            $this->files = include( __DIR__ . DIRECTORY_SEPARATOR . self::$fileLoad );
      }

}
