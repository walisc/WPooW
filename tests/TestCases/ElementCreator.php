<?php 

namespace WPooWTests\TestCases;

use WPooW\WPooW;

class ElementCreator {
    //TODO: Specify test, instead of running them all
    public static function CreateTestElements(){
        $testFilesItre = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(sprintf("%s%s", __DIR__, DIRECTORY_SEPARATOR)));
        $testFile = [];

        $WPooWInstance = new WPooW();
        foreach($testFilesItre as $file){

            if(!$file->isDir()){
                if (preg_match('/Test\.php/', $file->getFileName() )){
                    include $file->getPathname();
                    $testsClass = str_replace(".php","", $file->getFileName());
                    $testsClass::LoadElements($WPooWInstance);
                }
            }
        }
    }
}