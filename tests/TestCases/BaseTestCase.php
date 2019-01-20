<?php

namespace WPooWTests\TestCases;

use \PHPUnit\Framework\TestCase;
use \Facebook\WebDriver\Remote\RemoteWebDriver;
use \Facebook\WebDriver\Remote\DesiredCapabilities;

abstract class BaseTestCase extends TestCase{

    protected $hosturl = "http://localhost:4444/wd/hub";

    protected function setUp()
    {
        RemoteWebDriver::create($this->hosturl, DesiredCapabilities::chrome());
    }
    abstract function LoadElements($WPooW);
}