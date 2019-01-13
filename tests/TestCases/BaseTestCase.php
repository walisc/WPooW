<?php

namespace WPooWTests\TestCases;

use \PHPUnit\Framework\TestCase;

abstract class BaseTestCase extends TestCase{

    abstract function LoadElements($WPooW);
}