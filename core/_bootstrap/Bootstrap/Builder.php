<?php

namespace Whitecube\Bootstrap;

class Builder
{

      public $files = [];

      protected $exclude = [ 'autoload.php' ];
      protected $globalExclude = [ '.', '..', '.DS_Store', 'Thumbs.db' ];

      function __construct( $dir )
      {
            $this->buildExclude( $dir );
            $this->scan( $dir );
            $this->write( __DIR__ . DIRECTORY_SEPARATOR . FileLoader::$fileLoad );
      }

      protected function buildExclude( $dir )
      {
            array_push($this->exclude, FileLoader::$dirBootstrap);
            foreach ($this->exclude as $i => $s) {
                  $this->exclude[$i] = $dir . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $s);
            }
      }

      protected function scan( $dir )
      {
            foreach ( $this->sort( scandir( $dir ), $dir ) as $a) {
                  if( $a['type'] == 'file' ) array_push($this->files, $a['path']);
                  else{ $this->scan( $a['path'] ); }
            }
      }

      protected function sort( $a, $dir )
      {
            $aFilesUnderscored = []; $aFilesRegular = []; $aDir = [];
            foreach ($a as $s) {
                  if(!in_array($s, $this->globalExclude)){
                        $path = $dir . DIRECTORY_SEPARATOR . $s;
                        if(!in_array($path, $this->exclude)) {
                              if( is_file($path) ) {
                                    if(substr($s, 0, 1) == '_') array_push($aFilesUnderscored, ['type' => 'file', 'path' => $path]);
                                    else array_push($aFilesRegular, ['type' => 'file', 'path' => $path]);
                              }
                              else{
                                    array_push($aDir, ['type' => 'dir', 'path' => $path]);
                              }
                        }
                  }
            }
            return $this->merge([$aFilesUnderscored, $aFilesRegular, $aDir]);
      }

      protected function merge($a)
      {
            $arr = [];
            foreach ($a as $aElem) {
                  sort($aElem);
                  foreach ($aElem as $aVal) {
                        array_push($arr, $aVal);
                  }
            }
            return $arr;
      }

      protected function write( $file )
      {
            $file = fopen( $file, "w" ) or die('Whitecube\Theme autoloader error: Unable to open/create file "' . $file . '" !');
            fwrite( $file, $this->getFileContent() );
            fclose( $file );
      }

      protected function getFileContent()
      {
            $s = '<?php' . PHP_EOL . PHP_EOL;
            $s .= $this->buildSign();
            $s .= PHP_EOL;
            $s .= 'return ';
            $s .= $this->buildFilesArray() . ';';
            return $s;
      }

      protected function buildSign()
      {
            $s = '/* **************************************' . PHP_EOL;
            $s .= ' * AUTOLOAD BUILD' . PHP_EOL;
            $s .= ' * --------------' . PHP_EOL;
            $s .= ' * generated ' . date('d/m/Y') . ' @ ' . date('H:i:m:s') . PHP_EOL;
            $s .= ' * In order to regenerate this file, just' . PHP_EOL;
            $s .= ' * delete it, the autoloader will' . PHP_EOL;
            $s .= ' * automatically re-build it.' . PHP_EOL;
            $s .= ' * - WhiteCube.' . PHP_EOL;
            $s .= ' * ************************************** */' . PHP_EOL;
            return $s;
      }

      protected function buildFilesArray()
      {
            $s = '[' . PHP_EOL;
            foreach ($this->files as $file) {
                  $s .= '      ';
                  $s .= '\'' . $file . '\',';
                  $s .= PHP_EOL;
            }
            $s .= ']';
            return $s;
      }

}
