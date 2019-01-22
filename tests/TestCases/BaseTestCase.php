<?php

namespace WPooWTests\TestCases;

use \PHPUnit\Framework\TestCase;
use \Facebook\WebDriver\Remote\RemoteWebDriver;
use \Facebook\WebDriver\Remote\DesiredCapabilities;

global $binPath;
$binPath = realpath(sprintf("%s%s%s%s%s%s",__DIR__, DIRECTORY_SEPARATOR,"..", DIRECTORY_SEPARATOR, "bin", DIRECTORY_SEPARATOR));

abstract class BaseTestCase extends TestCase{

    protected $hosturl = "http://localhost:4444/wd/hub";

    protected function setUp()
    {
        global $binPath;
        putenv('webdriver.chrome.driver='.$binPath. DIRECTORY_SEPARATOR.'chromedriver');
        putenv('PATH=' . getenv('PATH') . PATH_SEPARATOR . $binPath);
        RemoteWebDriver::create($this->hosturl, DesiredCapabilities::chrome());
    }
    abstract function LoadElements($WPooW);
}

