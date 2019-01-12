<?php 

namespace WPooWTests\TestCases;

class ElementCreator {
    public static function CreateTestElements(){
        $testFilesItre = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(sprintf("%s%s", __DIR__, DIRECTORY_SEPARATOR)));
        $testFile = [];

        foreach($testFilesItre as $file){

            if(!$file->isDir()){
                if (preg_match('/Test\.php/', $file->getFileName() )){
                    //include $file->getPathname();
                    //$testsClass = str_replace(".php","", $file->getFileName());
                   // $testFile::LoadElements();
                }
            }
        }
    }
}