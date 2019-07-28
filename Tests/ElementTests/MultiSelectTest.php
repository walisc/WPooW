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
include_once  __DIR__. '/SelectTest.php';

class MultiSelectTest extends SelectTest
{

    /**************************
    / HELP DATA & FUNCTIONS   *
    /**************************/
    protected static $samplePostType1 = [
        'id' => '_wpoow_test_menu',
        'title' => 'WPooW Test Menu',
        'fields' => [
            [
                'id' => '_test_muiltiselect_field_1',
                'label' => 'Sample Muilti Select Field 1',
                'type' => 'multiselect'
            ],
            [
                'id' => '_test_muiltiselect_field_2',
                'label' => 'Sample Muilti Select Field 2',
                'type' => 'multiselect'
            ]
        ]
    ];


    /**************************
    / TESTS                   *
    /**************************/

    /**
     * @WP_BeforeRun createMultipleSelectElements
     */
    public function testCanSelectMultipleOptions(){
        $this->loginToWPAdmin();
        self::$samplePostType1['fields'][0]['test_value'] =  ['personA' => 'Person A', 'personC' => 'Person C'];
        $postID = $this->addPost(self::$samplePostType1['id'], [self::$samplePostType1['fields'][0]]);
        $this->assertFieldDataCorrect(self::$samplePostType1['id'], $postID, [self::$samplePostType1['fields'][0]]);
    }

    /**************************
    / WP_BEFORE RUN FUNCTIONS *
    /**************************/

    public static function  createSelectElement()
    {
        $wpOOW = new wpAPI();
        $wpOOWTestPage = $wpOOW->CreatePostType(self::$samplePostType1['id'], self::$samplePostType1['title'], true);
        $wpOOWTestPage->AddField(new MultiSelect(self::$samplePostType1['fields'][0]['id'], self::$samplePostType1['fields'][0]['label'], self::$availableOptions[0]));
        $wpOOWTestPage->render();
    }

    public static function  createMultipleSelectElements()
    {
        $wpOOW = new wpAPI();
        $wpOOWTestPage = $wpOOW->CreatePostType(self::$samplePostType1['id'], self::$samplePostType1['title'], true);
        $wpOOWTestPage->AddField(new MultiSelect(self::$samplePostType1['fields'][0]['id'], self::$samplePostType1['fields'][0]['label'], self::$availableOptions[0]));
        $wpOOWTestPage->AddField(new MultiSelect(self::$samplePostType1['fields'][1]['id'], self::$samplePostType1['fields'][1]['label'], self::$availableOptions[1]));
        $wpOOWTestPage->render();
    }


}
