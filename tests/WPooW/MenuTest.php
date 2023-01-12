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
                        'id' => '_wpoow_test_ms1',
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
                            'id' => '_wpoow_test_ms1',
                            'label' => 'WPooW Test Sub Menu 1',
                            'capability' => WP_PERMISSIONS::MANAGE_OPTIONS,
                            'display_path' =>  new  wpAPI_VIEW(wpAPI_VIEW::CONTENT, "<h1>Sub Menu</h1>"),
                        ],
                        [
                            'type' => WPooWTestsConsts::MENU_TYPE_MENU,
                            'id' => '_wpoow_test_ms2',
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
                            'id' => '_wpoow_test_ms1',
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
                            'id' => '_wpoow_test_ms1',
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
                            'id' => '_wpoow_test_ms2',
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
                            'id' => '_wpoow_test_ms1',
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
                            'id' => '_wpoow_test_ms2',
                            'label' => 'WPooW Test Sub Menu 2',
                            'capability' => WP_PERMISSIONS::MANAGE_OPTIONS,
                            'display_path' =>  new  wpAPI_VIEW(wpAPI_VIEW::CONTENT, "<h1>Sub Menu</h1>"),
                        ],
                        [
                            'type' => WPooWTestsConsts::MENU_TYPE_POSTTYPE,
                            'id' => '_wpoow_test_ms3',
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
                            'id' => '_wpoow_test_ms4',
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

    /**
     * @param $menuItemContent
     */
    public function assertMenuContentEqual($menuItemContent)
    {
        $pageContent = $this->driver->findElement(WebDriverBy::id('wpbody-content'))->getAttribute('innerHTML');
        $pageContentFormatted = preg_replace("/\r\n|\r|\n/", '', $pageContent);

        if (array_key_exists('display_path', $menuItemContent)) {
            ob_start();
            $menuItemContent['display_path']->Render();
            $menuPageContent = ob_get_contents();
            ob_end_clean();
            $menuPageContentFormatted = preg_replace("/\r\n|\r|\n/", '', $menuPageContent);

            $this->assertContains($menuPageContentFormatted, $pageContentFormatted);

        } else {
            $pageContent = $this->driver->findElement(WebDriverBy::id('wpbody-content'))->getAttribute('innerHTML');
            $pageContentFormatted = preg_replace("/\r\n|\r|\n/", '', $pageContent);

            $contentValue = array_key_exists('type', $menuItemContent) ? ($menuItemContent['type'] == WPooWTestsConsts::MENU_TYPE_MENU ? $menuItemContent['label'] : $menuItemContent['title']) : 'label';

            $this->assertContains($contentValue, $pageContentFormatted);

        }
    }

    function assertMenuItemEqual($menuItem, $menuItemContent){

        $menuItem['li']->click();
        $this->waitForPageToLoad();

        //because it become stale
        $menuItem = $this->locatedMenuItem($menuItemContent['id'], WPooWTestsConsts::MENU_TYPE_MENU);
        $this->assertTrue($menuItem['text']->getAttribute('innerText') == $menuItemContent['label']);

        $this->assertMenuContentEqual($menuItemContent);

        if (array_key_exists('icon', $menuItemContent)){
            $this->assertNotEmpty($menuItem['li']->findElement(WebDriverBy::xpath("descendant::div[contains(@class, 'wp-menu-image') and contains(@class, '${menuItemContent['icon']}')]")));
        }
        else{
            $this->assertNotEmpty($menuItem['li']->findElement(WebDriverBy::xpath("descendant::div[contains(@class, 'wp-menu-image') and contains(@class, 'dashicons-admin-generic')]")));
        }

        if (array_key_exists('submenus', $menuItemContent)){
            foreach ($menuItemContent['submenus'] as $subMenu){
                $menuItem = $this->locatedMenuItem($menuItemContent['id'], WPooWTestsConsts::MENU_TYPE_MENU);
                $subMenuObj = $menuItem['li']->findElement(WebDriverBy::xpath("descendant::a[contains(@href, '${subMenu['id']}' )]")); //nt the best option
                $this->assertTrue($subMenuObj->getAttribute('innerText') == ($subMenu['type'] == WPooWTestsConsts::MENU_TYPE_MENU ? $subMenu['label'] : $subMenu['title']));
                $this->driver->Get($subMenuObj->getAttribute('href'));
                $this->assertMenuContentEqual($subMenu);

            }
        }


    }

    function runMenuTestCase($sampleDataId){
        $sampleData = self::getSampleMenuData($sampleDataId);
        $this->loginToWPAdmin();

        foreach ($sampleData as $menuItem)
        {
            $foundMenu = $this->locatedMenuItem($menuItem['id'], WPooWTestsConsts::MENU_TYPE_MENU);
            $this->assertMenuItemEqual($foundMenu, $menuItem);
        }
    }

    /**************************
    / TESTS                   *
    /**************************/

    /**
     * @wpBeforeRun createMenuItem
     */
    function testCanAddMenu(){
        $this->runMenuTestCase(1);
    }

    /**
     * @wpBeforeRun createCustomisedMenuItemOne
     */
    function testCanCustomiseMenuOne(){
        $this->runMenuTestCase(2);
    }

    /**
     * @wpBeforeRun createCustomisedMenuItemTwo
     */
    function testCanCustomiseMenuTwo(){
        $this->runMenuTestCase(3);
    }

    /**
     * @wpBeforeRun createMultipleMenuItems
     */
    function testCanHaveMultipleMenus(){
        $this->runMenuTestCase(4);
    }

    /**
     * @wpBeforeRun createMenuWithSubMenu
     */
    function testCanAddSubmenu(){
        $this->runMenuTestCase(5);
    }

    /**
     * @wpBeforeRun createMenuWithSubMenus
     */
    function testCanAddSubmenus(){
        $this->runMenuTestCase(6);
    }

    /**
     * @wpBeforeRun createMenuWithPostType
     */
    function testCanAddPostTypeAsSubMenus(){
        $this->runMenuTestCase(7);
    }

    /**
     * @wpBeforeRun createMenuWithMultiplePostType
     */
    function testCanAddMultiplePostTypeAsSubMenus(){
        $this->runMenuTestCase(8);
    }

    /**
     * @wpBeforeRun createComplexMenu
     */
    function testComplexMenuStructure(){
        $this->runMenuTestCase(9);
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


