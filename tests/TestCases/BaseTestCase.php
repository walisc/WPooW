<?php

namespace WPooWTests\TestCases;

use \PHPUnit\Framework\TestCase;
use \Facebook\WebDriver\Remote\RemoteWebDriver;
use \Facebook\WebDriver\Remote\DesiredCapabilities;

$binPath = realpath(sprintf("%s%s%s%s%s%s",__DIR__, DIRECTORY_SEPARATOR,"..", DIRECTORY_SEPARATOR, "bin", DIRECTORY_SEPARATOR));
$hosturl = "http://localhost:4444/wd/hub";

global $seleniumDriver;
$seleniumDriver = RemoteWebDriver::create($hosturl, DesiredCapabilities::chrome());

abstract class BaseTestCase extends TestCase{

    abstract function LoadElements($WPooW);

    protected $seleniumDriver;

    function __construct(){
        global $seleniumDriver;
        $this->seleniumDriver = $seleniumDriver; 
    }
}

