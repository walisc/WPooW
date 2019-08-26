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
        $baseSamplePostType = self::getBaseSamplePostTypeData();

        switch ($id) {
            case 1:
                return $baseSamplePostType;
            case 2:
                $baseSamplePostType['capability'] = WP_PERMISSIONS::MANAGE_OPTIONS;
                $baseSamplePostType['display_path'] = new  wpAPI_VIEW(wpAPI_VIEW::CONTENT, "<h1>Test Menu</h1>");
                $baseSamplePostType['icon'] = "dashicons-admin-site";
                $baseSamplePostType['position'] = 1;
                return $baseSamplePostType;
            case 3:
                $baseSamplePostType['capability'] = 'edit_posts';
                $baseSamplePostType['display_path'] = new  wpAPI_VIEW(wpAPI_VIEW::PATH, 'resources/templates/sample_menu.twig', ['title' => 'Sample Menu']);
                $baseSamplePostType['icon'] = "dashicons-admin-collapse";
                $baseSamplePostType['position'] = 100;
                return $baseSamplePostType;
            
        }

    }


    /**************************
    / TESTS                   *
    /**************************/

    /**
     * @WP_BeforeRun createMenuItem
     */
    function testCanAddMenu(){


        $sampleData = self::getSampleMenuData(1);
        $menuItem = $this->locatedMenuItem($sampleData['id']);
        $this->assertTrue($menuItem['title'] == $sampleData['text']);
    }

    /**
     * @WP_BeforeRun createCustomisedMenuItem
     */
    function testCanCustomiseMenu(){
        $sampleData = self::getSampleMenuData(1);
        $menuItem = $this->locatedMenuItem($sampleData['id']);
    }

    function testCanHaveMultipleMenus(){

    }

    function testCanAddSubmenus(){

        //add three with diferrent modification
    }

    function testCanAddPostTypeAsSubMenus(){

    }

    function testCanAddMultiplePostTypeAsSubMenus(){

    }

    function testComplexMenuStructure(){

    }

    /**************************
    / WP_BEFORE RUN FUNCTIONS *
    /**************************/

    public static function createMenuItem()
    {
        $wpOOW = new wpAPI();
        $sampleData = self::getSampleMenuData(1);

        $newMenu = $wpOOW->CreateMenu(...array_values($sampleData));
        $newMenu->Render();
    }

    public static function createCustomisedMenuItem()
    {
        $wpOOW = new wpAPI();
        $sampleData = self::getSampleMenuData(2);

        $newMenu = $wpOOW->CreateMenu(...array_values($sampleData));
        $newMenu->Render();
    }


}




//can add menu (label,click, basic landing page)
//can customise menu (icon, landing page)
//can have muiltple menues
//can add submenu
//can add PostType submenu
//can edit submenu (icon, landing page)

//test can set Permissions

//WP_VIEW

//TODO: tickt on permissions