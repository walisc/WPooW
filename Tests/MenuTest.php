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
use WPooWTests\WPooWTestsConsts;

include_once __DIR__.'/../wpAPI.php';
include_once __DIR__.'/../Libraries/twig/twig/lib/Twig/Autoloader.php';

class MenuTest extends WPooWBaseTestCase
{
    /**************************
    / HELP DATA & FUNCTIONS   *
    /**************************/

    static function setUpBeforeClass(){
        parent::setUpBeforeClass();

        if ( ! defined( 'ABSPATH' ) ) {
            define( 'ABSPATH', dirname( __FILE__ ) . '/' );
        }

        Twig_Autoloader::register();
    }

    protected static function getSampleMenuData($id){

        $twigBasePath = dirname( __FILE__ ) . '/';

        switch ($id) {
            case 1:
                return [[
                    'id' => '_wpoow_test_menu',
                    'label' => 'WPooW Test Menu',
                ]];
            case 2:
                return [[
                    'id' => '_wpoow_test_menu',
                    'label' => 'WPooW Test Menu',
                    'capability' => WP_PERMISSIONS::MANAGE_OPTIONS,
                    'display_path' =>  new  wpAPI_VIEW(wpAPI_VIEW::CONTENT, "<h1>Test Menu</h1>"),
                    'icon' => 'dashicons-admin-site',
                    'position' => 1,
                ]];

            case 3:
                return [[
                    'id' => '_wpoow_test_menu',
                    'label' => 'WPooW Test Menu',
                    'capability' => 'edit_posts',
                    'display_path' =>  new  wpAPI_VIEW(wpAPI_VIEW::PATH, 'Resources/Templates/sample_menu.twig', ['title' => 'Sample Menu'], $twigBasePath),
                    'icon' => 'dashicons-admin-collapse',
                    'position' => 100,
                ]];
            case 4:
                return [
                    [
                        'id' => '_wpoow_test_menu_1',
                        'label' => 'WPooW Test Menu 1',
                        'capability' => 'edit_posts',
                        'display_path' =>  new  wpAPI_VIEW(wpAPI_VIEW::PATH, 'Resources/Templates/sample_menu.twig', ['title' => 'Sample Menu'], $twigBasePath),
                    ],
                    [
                        'id' => '_wpoow_test_menu_2',
                        'label' => 'WPooW Test Menu 2',
                        'capability' => 'edit_posts',
                        'display_path' =>  new  wpAPI_VIEW(wpAPI_VIEW::PATH, 'Resources/Templates/sample_menu.twig', ['title' => 'Sample Menu'], $twigBasePath),
                    ]
                ];
            case 5:
                return [[
                    'id' => '_wpoow_test_menu',
                    'label' => 'WPooW Test Menu',
                    'capability' => WP_PERMISSIONS::MANAGE_OPTIONS,
                    'display_path' =>  new  wpAPI_VIEW(wpAPI_VIEW::CONTENT, "<h1>Parent Menu</h1>"),
                    'icon' => 'dashicons-admin-site',
                    'position' => 1,
                    'submenus' => [[
                        'type' => WPooWTestsConsts::MENU_TYPE_MENU,
                        'id' => '_wpoow_test_menu_sub_1',
                        'label' => 'WPooW Test Sub Menu 1',
                        'capability' => WP_PERMISSIONS::MANAGE_OPTIONS,
                        'display_path' =>  new  wpAPI_VIEW(wpAPI_VIEW::CONTENT, "<h1>Sub Menu</h1>")]
                    ]
                ]];
            case 6:
                return [[
                    'id' => '_wpoow_test_menu',
                    'label' => 'WPooW Test Menu',
                    'position' => 1,
                    'submenus' => [
                        [
                            'type' => WPooWTestsConsts::MENU_TYPE_MENU,
                            'id' => '_wpoow_test_menu_sub_1',
                            'label' => 'WPooW Test Sub Menu 1',
                            'capability' => WP_PERMISSIONS::MANAGE_OPTIONS,
                            'display_path' =>  new  wpAPI_VIEW(wpAPI_VIEW::CONTENT, "<h1>Sub Menu</h1>"),
                        ],
                        [
                            'type' => WPooWTestsConsts::MENU_TYPE_MENU,
                            'id' => '_wpoow_test_menu_sub_2',
                            'label' => 'WPooW Test Sub Menu 2',
                            'capability' => 'edit_posts',
                            'display_path' =>  new  wpAPI_VIEW(wpAPI_VIEW::PATH, 'Resources/Templates/sample_menu.twig', ['title' => 'Sample Menu'], $twigBasePath),
                        ]
                    ]
                ]];
            case 7:
                return [[
                    'id' => '_wpoow_test_menu',
                    'label' => 'WPooW Test Menu',
                    'capability' => WP_PERMISSIONS::MANAGE_OPTIONS,
                    'display_path' =>  new  wpAPI_VIEW(wpAPI_VIEW::CONTENT, "<h1>Parent Menu</h1>"),
                    'icon' => 'dashicons-admin-site',
                    'position' => 1,
                    'submenus' => [[
                            'type' => WPooWTestsConsts::MENU_TYPE_POSTTYPE,
                            'id' => '_wpoow_test_menu_sub_1',
                            'title' => 'WPooW Test Sub Menu 1',
                            'fields' => [
                                [
                                    'id' => '_test_text_field',
                                    'label' => 'Sample Text Field',
                                    'test_value' => 'Sample Text'
                                ]
                            ]
                        ]
                    ]
                ]];
            case 8:
                return [[
                    'id' => '_wpoow_test_menu',
                    'label' => 'WPooW Test Menu',
                    'position' => 1,
                    'submenus' => [
                        [
                            'type' => WPooWTestsConsts::MENU_TYPE_POSTTYPE,
                            'id' => '_wpoow_test_menu_sub_1',
                            'title' => 'WPooW Test sub Menu 1',
                            'fields' => [
                                [
                                    'id' => '_test_text_field',
                                    'label' => 'Sample Text Field',
                                    'test_value' => 'Sample Text'
                                ]

                            ]
                        ],
                        [
                            'type' => WPooWTestsConsts::MENU_TYPE_POSTTYPE,
                            'id' => '_wpoow_test_menu_sub_2',
                            'title' => 'WPooW Test Sub Menu 2',
                            'fields' => [
                                [
                                    'id' => '_test_text_field',
                                    'label' => 'Sample Text Field',
                                    'test_value' => 'Sample Text'
                                ]

                            ]
                        ]
                    ]
                ]];
            case 9:
                return [[
                    'id' => '_wpoow_test_menu',
                    'label' => 'WPooW Test Menu',
                    'capability' => WP_PERMISSIONS::MANAGE_OPTIONS,
                    'display_path' =>  new  wpAPI_VIEW(wpAPI_VIEW::CONTENT, "<h1>Complex Type Menu</h1>"),
                    'icon' => 'dashicons-admin-site',
                    'position' => 1,
                    'submenus' => [
                        [
                            'type' => WPooWTestsConsts::MENU_TYPE_POSTTYPE,
                            'id' => '_wpoow_test_menu_sub_1',
                            'title' => 'WPooW Test Sub Menu 1',
                            'fields' => [
                                [
                                    'id' => '_test_text_field',
                                    'label' => 'Sample Text Field',
                                    'test_value' => 'Sample Text'
                                ]

                            ]
                        ],
                        [
                            'type' => WPooWTestsConsts::MENU_TYPE_MENU,
                            'id' => '_wpoow_test_menu_sub_2',
                            'label' => 'WPooW Test Sub Menu 2',
                            'capability' => WP_PERMISSIONS::MANAGE_OPTIONS,
                            'display_path' =>  new  wpAPI_VIEW(wpAPI_VIEW::CONTENT, "<h1>Sub Menu</h1>"),
                        ],
                        [
                            'type' => WPooWTestsConsts::MENU_TYPE_POSTTYPE,
                            'id' => '_wpoow_test_menu_sub_3',
                            'title' => 'WPooW Test Sub Menu 3',
                            'fields' => [
                                [
                                    'id' => '_test_text_field',
                                    'label' => 'Sample Text Field',
                                    'test_value' => 'Sample Text'
                                ]

                            ]
                        ],
                        [
                            'type' => WPooWTestsConsts::MENU_TYPE_MENU,
                            'id' => '_wpoow_test_menu_sub_4',
                            'label' => 'WPooW Test Sub Menu 4',
                            'capability' => 'edit_posts',
                            'display_path' =>  new  wpAPI_VIEW(wpAPI_VIEW::PATH, 'Resources/Templates/sample_menu.twig', ['title' => 'Sample Menu'], $twigBasePath),
                        ]
                    ]
                ]];

        }

    }

    /**************************
    / HELPERS                 *
    /**************************/

    function assertMenuItemEqual($menuItem, $menuItemContent){

        $menuItem['li']->click();
        $this->waitForPageToLoad();

        //because it become stale
        $menuItem = $this->locatedMenuItem($menuItemContent['id'], WPooWTestsConsts::MENU_TYPE_MENU);
        $this->assertTrue($menuItem['text']->getAttribute('innerText') == $menuItemContent['label']);

        $pageContent = $this->driver->findElement(WebDriverBy::id('wpbody-content'))->getAttribute('innerHTML');
        $pageContentFormatted = preg_replace("/\r\n|\r|\n/", '', $pageContent);

        if (array_key_exists('display_path', $menuItemContent)){
            ob_start();
            $menuItemContent['display_path']->Render();
            $menuPageContent = ob_get_contents();
            ob_end_clean();
            $menuPageContentFormatted = preg_replace("/\r\n|\r|\n/", '', $menuPageContent);

            $this->assertContains($menuPageContentFormatted, $pageContentFormatted);

        }
        else{
            $pageContent = $this->driver->findElement(WebDriverBy::id('wpbody-content'))->getAttribute('innerHTML');
            $pageContentFormatted = preg_replace("/\r\n|\r|\n/", '', $pageContent);
            $this->assertContains($menuItemContent['label'], $pageContentFormatted);

        }

        if (array_key_exists('icon', $menuItemContent)){
            $this->assertNotEmpty($menuItem['li']->findElement(WebDriverBy::xpath("descendant::div[contains(@class, 'wp-menu-image') and contains(@class, '${menuItemContent['icon']}')]")));
        }
        else{
            $this->assertNotEmpty($menuItem['li']->findElement(WebDriverBy::xpath("descendant::div[contains(@class, 'wp-menu-image') and contains(@class, 'dashicons-admin-generic')]")));
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
        $this->loginToWPAdmin();

        foreach ($sampleData as $menuItem)
        {
            $foundMenu = $this->locatedMenuItem($menuItem['id'], WPooWTestsConsts::MENU_TYPE_MENU);
            $this->assertMenuItemEqual($foundMenu, $menuItem);
        }

    }

    /**
     * @WP_BeforeRun createCustomisedMenuItemOne
     */
    function testCanCustomiseMenuOne(){
        $sampleData = self::getSampleMenuData(2);
        $this->loginToWPAdmin();

        foreach ($sampleData as $menuItem)
        {
            $foundMenu = $this->locatedMenuItem($menuItem['id'], WPooWTestsConsts::MENU_TYPE_MENU);
            $this->assertMenuItemEqual($foundMenu, $menuItem);
        }
    }

    /**
     * @WP_BeforeRun createCustomisedMenuItemTwo
     */
    function testCanCustomiseMenuTwo(){
        $sampleData = self::getSampleMenuData(3);
        $this->loginToWPAdmin();

        foreach ($sampleData as $menuItem)
        {
            $foundMenu = $this->locatedMenuItem($menuItem['id'], WPooWTestsConsts::MENU_TYPE_MENU);
            $this->assertMenuItemEqual($foundMenu, $menuItem);
        }
    }

    /**
     * @WP_BeforeRun createMultipleMenuItems
     */
    function testCanHaveMultipleMenus(){
        $sampleData = self::getSampleMenuData(4);
        $this->loginToWPAdmin();

        foreach ($sampleData as $menuItem)
        {
            $foundMenu = $this->locatedMenuItem($menuItem['id'], WPooWTestsConsts::MENU_TYPE_MENU);
            $this->assertMenuItemEqual($foundMenu, $menuItem);
        }
    }

    /**
     * @WP_BeforeRun createMenuWithSubMenu
     */
    function testCanAddSubmenu(){

        $sampleData = self::getSampleMenuData(5);
        $this->loginToWPAdmin();

        foreach ($sampleData as $menuItem)
        {
            $foundMenu = $this->locatedMenuItem($menuItem['id'], WPooWTestsConsts::MENU_TYPE_MENU);
            $this->assertMenuItemEqual($foundMenu, $menuItem);
        }
    }

    /**
     * @WP_BeforeRun createMenuWithSubMenus
     */
    function testCanAddSubmenus(){

        $sampleData = self::getSampleMenuData(6);
        $this->loginToWPAdmin();
        //add three with diferrent modification
    }

    /**
     * @WP_BeforeRun createMenuWithPostType
     */
    function testCanAddPostTypeAsSubMenus(){
        $sampleData = self::getSampleMenuData(7);
        $this->loginToWPAdmin();
    }

    /**
     * @WP_BeforeRun createMenuWithMultiplePostType
     */
    function testCanAddMultiplePostTypeAsSubMenus(){
        $sampleData = self::getSampleMenuData(8);
        $this->loginToWPAdmin();

    }

    /**
     * @WP_BeforeRun createComplexMenu
     */
    function testComplexMenuStructure(){
        $sampleData = self::getSampleMenuData(9);
        $this->loginToWPAdmin();
    }

    /**************************
    / WP_BEFORE RUN FUNCTIONS *
    /**************************/

    public static function createMenuItem()
    {
        self::createMenus(new wpAPI(), self::getSampleMenuData(1));
    }

    public static function createCustomisedMenuItemOne()
    {
        self::createMenus(new wpAPI(), self::getSampleMenuData(2));
    }

    public static function createCustomisedMenuItemTwo()
    {
        self::createMenus(new wpAPI(), self::getSampleMenuData(3));
    }

    public static function createMultipleMenuItems()
    {
        self::createMenus(new wpAPI(), self::getSampleMenuData(4));
    }

    public static function createMenuWithSubMenu(){
        self::createMenus(new wpAPI(), self::getSampleMenuData(5));
    }

    public static function createMenuWithSubMenus(){
        self::createMenus(new wpAPI(), self::getSampleMenuData(6));
    }


    public static function createMenuWithPostType(){
        self::createMenus(new wpAPI(), self::getSampleMenuData(7));
    }

    public static function createMenuWithMultiplePostType(){
        self::createMenus(new wpAPI(), self::getSampleMenuData(8));
    }

    public static function createComplexMenu(){
        self::createMenus(new wpAPI(), self::getSampleMenuData(9));
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
