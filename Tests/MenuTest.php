<?php
/**
 * Created by PhpStorm.
 * User: chidow
 * Date: 2019/08/26
 * Time: 9:41 AM
 */

use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\WebDriverBy;
use WPooWTests\WPooWBaseTestCase;

include_once __DIR__.'/../wpAPI.php';

class MenuTest extends WPooWBaseTestCase
{
    /**************************
    / HELP DATA & FUNCTIONS   *
    /**************************/

    protected static function getSamplePostTypeData($id){
        $baseSamplePostType = self::getBaseSampleData();

        switch ($id) {
            case 1:
                $baseSamplePostType['fields'] = [[
                    'id' => '_test_muiltiselect_field_1',
                    'label' => 'Sample Muilti Select Field 1',
                    'type' => WPooWTestsElements::MULTISELECT,
                    'extra_args' => [
                        'options' => self::$availableOptions[0]
                    ],
                    'test_value' =>  ['personC' => 'Person C', 'personD' => 'Person D']
                ]];
                break;
            
        }

        return $baseSamplePostType;

    }

    /**************************
    / TESTS                   *
    /**************************/

    /**
     * @WP_BeforeRun createMenuItem
     */
    function testCanAddMenu(){

        $sampleData = self::getSampleMenuData(1);
        $menuItem = $this->getMenuItem($sampleData['id']);
        $intd =0 ;

    }

    /**************************
    / WP_BEFORE RUN FUNCTIONS *
    /**************************/

    public static function createMenuItem()
    {
        $wpOOW = new wpAPI();
        $sampleData = self::getSampleMenuData(1);

        $newMenu = $wpOOW->CreateMenu($sampleData['id'], $sampleData['label']);
        $newMenu->Render();
    }


}




//can add menu (label,click, basic landing page)
//can customise menu (icon, landing page)
//can add submenu
//can add PostType submenu
//can edit submenu (icon, landing page)
