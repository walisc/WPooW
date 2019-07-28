<?php
/**
 * Created by PhpStorm.
 * User: chidow
 * Date: 2019/07/26
 * Time: 2:38 PM
 */

use Facebook\WebDriver\WebDriverBy;
use WPooWTests\WPooWBaseTestCase;

include_once __DIR__ . '/../../wpAPI.php';

class MultiSelectTest extends SelectTest
{


    /**************************
    / TESTS                   *
    /**************************/

    /**
     * @WP_BeforeRun createDateTimeElement
     */
    public function testCanSelectMultipleOptions(){
        $this->loginToWPAdmin();
    }


}
