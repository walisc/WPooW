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

class MenuTest extends WPooWBaseTestCase
{
    /**************************
    / HELP DATA & FUNCTIONS   *
    /**************************/

    protected static function getSampleMenuData($id){

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
                    'display_path' =>  new  wpAPI_VIEW(wpAPI_VIEW::PATH, 'resources/templates/sample_menu.twig', ['title' => 'Sample Menu']),
                    'icon' => 'dashicons-admin-collapse',
                    'position' => 100,
                ]];
            case 4:
                return [
                    [
                        'id' => '_wpoow_test_menu_1',
                        'label' => 'WPooW Test Menu 1',
                        'capability' => 'edit_posts',
                        'display_path' =>  new  wpAPI_VIEW(wpAPI_VIEW::PATH, 'resources/templates/sample_menu.twig', ['title' => 'Sample Menu']),
                    ],
                    [
                        'id' => '_wpoow_test_menu_2',
                        'label' => 'WPooW Test Menu 2',
                        'capability' => 'edit_posts',
                        'display_path' =>  new  wpAPI_VIEW(wpAPI_VIEW::PATH, 'resources/templates/sample_menu.twig', ['title' => 'Sample Menu']),
                    ]
                ];
            case 5:
                return [
                    'id' => '_wpoow_test_menu',
                    'label' => 'WPooW Test Menu',
                    'capability' => WP_PERMISSIONS::MANAGE_OPTIONS,
                    'display_path' =>  new  wpAPI_VIEW(wpAPI_VIEW::CONTENT, "<h1>Parent Menu</h1>"),
                    'icon' => 'dashicons-admin-site',
                    'position' => 1,
                    'submenus' => [[
                        'type' => WPooWTestsConsts::MENU_TYPE_MENU,
                        'id' => '_wpoow_test_menu',
                        'label' => 'WPooW Test Menu',
                        'capability' => WP_PERMISSIONS::MANAGE_OPTIONS,
                        'display_path' =>  new  wpAPI_VIEW(wpAPI_VIEW::CONTENT, "<h1>Sub Menu</h1>")]
                    ]
                ];
            case 6:
                return [
                    'id' => '_wpoow_test_menu',
                    'label' => 'WPooW Test Menu',
                    'position' => 1,
                    'submenus' => [
                        [
                            'type' => WPooWTestsConsts::MENU_TYPE_MENU,
                            'id' => '_wpoow_test_menu',
                            'label' => 'WPooW Test Menu',
                            'capability' => WP_PERMISSIONS::MANAGE_OPTIONS,
                            'display_path' =>  new  wpAPI_VIEW(wpAPI_VIEW::CONTENT, "<h1>Sub Menu</h1>"),
                        ],
                        [
                            'type' => WPooWTestsConsts::MENU_TYPE_MENU,
                            'id' => '_wpoow_test_menu_2',
                            'label' => 'WPooW Test Menu 2',
                            'capability' => 'edit_posts',
                            'display_path' =>  new  wpAPI_VIEW(wpAPI_VIEW::PATH, 'resources/templates/sample_menu.twig', ['title' => 'Sample Menu']),
                        ]
                    ]
                ];
            case 7:
                return [
                    'id' => '_wpoow_test_menu',
                    'label' => 'WPooW Test Menu',
                    'capability' => WP_PERMISSIONS::MANAGE_OPTIONS,
                    'display_path' =>  new  wpAPI_VIEW(wpAPI_VIEW::CONTENT, "<h1>Parent Menu</h1>"),
                    'icon' => 'dashicons-admin-site',
                    'position' => 1,
                    'submenus' => [[
                            'type' => WPooWTestsConsts::MENU_TYPE_POSTTYPE,
                            'id' => '_wpoow_test_menu',
                            'title' => 'WPooW Test Menu',
                            'fields' => [
                                [
                                    'id' => '_test_text_field',
                                    'label' => 'Sample Text Field',
                                    'test_value' => 'Sample Text'
                                ]
                            ]
                        ]
                    ]
                ];
            case 8:
                return [
                    'id' => '_wpoow_test_menu',
                    'label' => 'WPooW Test Menu',
                    'position' => 1,
                    'submenus' => [
                        [
                            'type' => WPooWTestsConsts::MENU_TYPE_POSTTYPE,
                            'id' => '_wpoow_test_menu_1',
                            'title' => 'WPooW Test Menu 1',
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
                            'id' => '_wpoow_test_menu_2',
                            'title' => 'WPooW Test Menu 2',
                            'fields' => [
                                [
                                    'id' => '_test_text_field',
                                    'label' => 'Sample Text Field',
                                    'test_value' => 'Sample Text'
                                ]

                            ]
                        ]
                    ]
                ];
            case 9:
                return [
                    'id' => '_wpoow_test_menu',
                    'label' => 'WPooW Test Menu',
                    'capability' => WP_PERMISSIONS::MANAGE_OPTIONS,
                    'display_path' =>  new  wpAPI_VIEW(wpAPI_VIEW::CONTENT, "<h1>Complex Type Menu</h1>"),
                    'icon' => 'dashicons-admin-site',
                    'position' => 1,
                    'submenus' => [
                        [
                            'type' => WPooWTestsConsts::MENU_TYPE_POSTTYPE,
                            'id' => '_wpoow_test_menu_1',
                            'title' => 'WPooW Test Menu 1',
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
                            'id' => '_wpoow_test_menu',
                            'label' => 'WPooW Test Menu',
                            'capability' => WP_PERMISSIONS::MANAGE_OPTIONS,
                            'display_path' =>  new  wpAPI_VIEW(wpAPI_VIEW::CONTENT, "<h1>Sub Menu</h1>"),
                        ],
                        [
                            'type' => WPooWTestsConsts::MENU_TYPE_POSTTYPE,
                            'id' => '_wpoow_test_menu_2',
                            'title' => 'WPooW Test Menu 2',
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
                            'id' => '_wpoow_test_menu_2',
                            'label' => 'WPooW Test Menu 2',
                            'capability' => 'edit_posts',
                            'display_path' =>  new  wpAPI_VIEW(wpAPI_VIEW::PATH, 'resources/templates/sample_menu.twig', ['title' => 'Sample Menu']),
                        ]
                    ]
                ];

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
            $this->assertTrue($foundMenu['text']->getAttribute('innerText') == $menuItem['label']);
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
            $foundMenu = $this->locatedMenuItem($menuItem['id']);
            $this->assertTrue($foundMenu['title'] == $menuItem['text']);
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
            $foundMenu = $this->locatedMenuItem($menuItem['id']);
            $this->assertTrue($foundMenu['title'] == $menuItem['text']);
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
            $foundMenu = $this->locatedMenuItem($menuItem['id']);
            $this->assertTrue($foundMenu['title'] == $menuItem['text']);
        }
    }

    /**
     * @WP_BeforeRun createMenuWithSubMenu
     */
    function testCanAddSubmenu(){

        $sampleData = self::getSampleMenuData(5);
        $this->loginToWPAdmin();
        //add three with diferrent modification
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
