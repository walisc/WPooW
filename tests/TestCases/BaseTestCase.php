<?php

namespace WPooWTests\TestCases;

use \PHPUnit\Framework\TestCase;
use \Facebook\WebDriver\Remote\RemoteWebDriver;
use \Facebook\WebDriver\Remote\DesiredCapabilities;

global $binPath;
global $hosturl;
$binPath = realpath(sprintf("%s%s%s%s%s%s",__DIR__, DIRECTORY_SEPARATOR,"..", DIRECTORY_SEPARATOR, "bin", DIRECTORY_SEPARATOR));
$hosturl = "http://localhost:4444/wd/hub";

abstract class BaseTestCase extends TestCase{

    function __construct(){
        global $binPath, $hosturl;
        putenv('webdriver.chrome.driver='.$binPath. DIRECTORY_SEPARATOR.'chromedriver');
        putenv('PATH=' . getenv('PATH') . PATH_SEPARATOR . $binPath);
        RemoteWebDriver::create($hosturl, DesiredCapabilities::chrome());
    }

    protected function setUp()
    {
    }
    
    abstract function LoadElements($WPooW);
}

