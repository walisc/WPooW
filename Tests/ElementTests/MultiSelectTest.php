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
     * @WP_BeforeRun createSelectElement
     */
    public function testCanSelectOption(){
        $this->loginToWPAdmin();
        self::$samplePostType1['fields'][0]['test_value'] =  ['personC' => 'Person C', 'personD' => 'Person D'];
        $postID = $this->addPost(self::$samplePostType1['id'], [self::$samplePostType1['fields'][0]]);
        $this->assertGridDataCorrect(self::$samplePostType1['id'], $postID, [self::$samplePostType1['fields'][0]]);
    }


    /**
     * @WP_BeforeRun createMultipleSelectElements
     */
    public function testCanLoadMultiple(){
        $this->loginToWPAdmin();
        self::$samplePostType1['fields'][0]['test_value'] = ['personC' => 'Person C'];
        self::$samplePostType1['fields'][1]['test_value'] = ['categoryA' => 'Category A', 'categoryE' => 'Category E' ];
        $postIDa = $this->addPost(self::$samplePostType1['id'], [self::$samplePostType1['fields'][0]]);
        $postIDb = $this->addPost(self::$samplePostType1['id'], [self::$samplePostType1['fields'][1]]);
        $this->assertGridDataCorrect(self::$samplePostType1['id'], $postIDa, [self::$samplePostType1['fields'][0]]);
        $this->assertGridDataCorrect(self::$samplePostType1['id'], $postIDb, [self::$samplePostType1['fields'][1]]);

    }

    /**
     * @WP_BeforeRun createMultipleSelectElements
     */
    public function testCanSelectMultipleOptions(){
        $this->loginToWPAdmin();
        self::$samplePostType1['fields'][0]['test_value'] =  ['personA' => 'Person A', 'personC' => 'Person C'];
        self::$samplePostType1['fields'][1]['test_value'] = ['categoryB' => 'Category B', 'categoryE' => 'Category E','categoryF' => 'Category F'];

        $postIDa = $this->addPost(self::$samplePostType1['id'], [self::$samplePostType1['fields'][0]]);
        $postIDb = $this->addPost(self::$samplePostType1['id'], [self::$samplePostType1['fields'][1]]);

        $this->assertGridDataCorrect(self::$samplePostType1['id'], $postIDa, [self::$samplePostType1['fields'][0]]);
        $this->assertGridDataCorrect(self::$samplePostType1['id'], $postIDb, [self::$samplePostType1['fields'][1]]);
    }

    /**
     * @WP_BeforeRun createMultipleSelectElements
     */
    public function testCanUpdateSelect(){
        $this->loginToWPAdmin();
        self::$samplePostType1['fields'][0]['test_value'] = ['personA' => 'Person A', 'personC' => 'Person C'];
        self::$samplePostType1['fields'][1]['test_value'] = ['categoryB' => 'Category B', 'categoryE' => 'Category E', 'categoryF' => 'Category F'];
        $postIDa = $this->addPost(self::$samplePostType1['id'], [self::$samplePostType1['fields'][0], self::$samplePostType1['fields'][1]]);
        $this->assertGridDataCorrect(self::$samplePostType1['id'], $postIDa, [self::$samplePostType1['fields'][0], self::$samplePostType1['fields'][1]]);

        self::$samplePostType1['fields'][0]['test_value'] = ['personB' => 'Person B', 'personD' => 'Person D'];
        self::$samplePostType1['fields'][1]['test_value'] = ['categoryA' => 'Category A', 'categoryC' => 'Category C','categoryD' => 'Category D'];
        $this->editPost(self::$samplePostType1['id'], $postIDa, [self::$samplePostType1['fields'][0], self::$samplePostType1['fields'][1]]);
        $this->assertGridDataCorrect(self::$samplePostType1['id'], $postIDa, [self::$samplePostType1['fields'][0], self::$samplePostType1['fields'][1]]);

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
