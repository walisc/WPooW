<?php 

namespace WPooWTests;

use PHPUnit\Framework\TestCase;

class WPooWTests {
    public static function CreateTestElements(){
        $testFilesItre = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(sprintf("%s%s%s", __DIR__, DIRECTORY_SEPARATOR, "testCases")));
        $testFile = [];

        foreach($testFilesItre as $file){

            if(!$file->isDir()){
                if (preg_match('/Test\.php/', $file->getFileName() )){
                    include $file->getPathname();
                    $testsClass = str_replace(".php","", $file->getFileName());
                    $testFile::LoadElements();
                }
            }
        }
    }
}