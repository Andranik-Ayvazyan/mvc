<?php
class Autoload
{
   var $dirName;

    public function __construct ()
    {
        $this->dirName = dirname(__FILE__);

    }

    public function  load ()
    {
        $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->dirName), RecursiveIteratorIterator::SELF_FIRST);
        foreach($objects as $name => $object){
            $file = explode("\\",$name);
            $fileName = end($file);
            $fileName = trim($fileName,'.php')."<br>";
            if(class_exists('$fileName')) {

            }

        }

    }
}