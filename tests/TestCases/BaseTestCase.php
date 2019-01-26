<?php

namespace WPooWTests\TestCases;

use \PHPUnit\Framework\TestCase;
use \Facebook\WebDriver\Remote\RemoteWebDriver;
use \Facebook\WebDriver\Remote\DesiredCapabilities;


abstract class BaseTestCase extends TestCase{

    abstract function LoadElements($WPooW);

    private static $seleniumDriver;

    protected function GetSeleniumDriver(){
        if (self::$seleniumDriver == null){
            $hosturl = "http://localhost:4444/wd/hub";
            //TODO: Report bug. Unable to catch exception when this fails
            self::$seleniumDriver = RemoteWebDriver::create($hosturl, DesiredCapabilities::chrome());
        }
        return self::$seleniumDriver;
    }
}

