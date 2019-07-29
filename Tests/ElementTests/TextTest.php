<?php
/**
 * Created by PhpStorm.
 * User: chidow
 * Date: 2019/07/29
 * Time: 3:08 PM
 */

use WPooWTests\WPooWBaseTestCase;

include_once __DIR__ . '/../../wpAPI.php';

class TextTest extends WPooWBaseTestCase{

    /**************************
    / HELP DATA & FUNCTIONS   *
    /**************************/
    protected static $samplePostType1 = [
        'id' => '_wpoow_test_menu',
        'title' => 'WPooW Test Menu',
        'fields' => [
            [
                'id' => '_test_text_field_1',
                'label' => 'Sample Text Field 1',
                'type' => 'text',
                'test_value' => 'Sample Text One'
            ],
            [
                'id' => '_test_text_field_2',
                'label' => 'Sample Text Field 2',
                'type' => 'text',
                'test_value' => 'Sample Text Two'
            ]
        ]
    ];



    /**************************
    / TESTS                   *
    /**************************/


    /**
     * @WP_BeforeRun createTextElement
     */
    public function testCanInteractWithTextElement(){
        $this->loginToWPAdmin();
        $postID = $this->addPost(self::$samplePostType1['id'], [self::$samplePostType1['fields'][0]]);
        $this->assertGridDataCorrect(self::$samplePostType1['id'], $postID, [self::$samplePostType1['fields'][0]]);

    }

    /**
     * @WP_BeforeRun createTextElement
     */
    public function testCanUpdateTextBox(){
        $this->loginToWPAdmin();
        $postID = $this->addPost(self::$samplePostType1['id'], [self::$samplePostType1['fields'][0]]);
        $this->assertGridDataCorrect(self::$samplePostType1['id'], $postID, [self::$samplePostType1['fields'][0]]);

        self::$samplePostType1['fields'][0]['test_value'] = 'New Test Sample Text';
        $this->editPost(self::$samplePostType1['id'], $postID, [self::$samplePostType1['fields'][0]]);
        $this->assertGridDataCorrect(self::$samplePostType1['id'], $postID, [self::$samplePostType1['fields'][0]]);

    }

    /**
     * @WP_BeforeRun createMultipleTextElements
     */
    public function testCanHaveTextElements(){
        $this->loginToWPAdmin();
        $postID = $this->addPost(self::$samplePostType1['id'], [self::$samplePostType1['fields'][0], self::$samplePostType1['fields'][1]]);
        $this->assertGridDataCorrect(self::$samplePostType1['id'], $postID, [self::$samplePostType1['fields'][0], self::$samplePostType1['fields'][1]]);
    }




    /**************************
    / WP_BEFORE RUN FUNCTIONS *
    /**************************/

    public static function createTextElement()
    {
        $wpOOW = new wpAPI();
        $wpOOWTestPage = $wpOOW->CreatePostType(self::$samplePostType1['id'], self::$samplePostType1['title'], true);
        $wpOOWTestPage->AddField(new Text(self::$samplePostType1['fields'][0]['id'], self::$samplePostType1['fields'][0]['label']));
        $wpOOWTestPage->render();
    }

    public static function  createMultipleTextElements()
    {
        $wpOOW = new wpAPI();
        $wpOOWTestPage = $wpOOW->CreatePostType(self::$samplePostType1['id'], self::$samplePostType1['title'], true);
        $wpOOWTestPage->AddField(new Text(self::$samplePostType1['fields'][0]['id'], self::$samplePostType1['fields'][0]['label']));
        $wpOOWTestPage->AddField(new Text(self::$samplePostType1['fields'][1]['id'], self::$samplePostType1['fields'][1]['label']));
        $wpOOWTestPage->render();
    }
}
