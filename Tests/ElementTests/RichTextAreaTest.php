<?php
/**
 * Created by PhpStorm.
 * User: chidow
 * Date: 2019/07/29
 * Time: 3:08 PM
 */

use WPooWTests\WPooWBaseTestCase;

include_once __DIR__ . '/../../wpAPI.php';

class RichTextAreaTest extends WPooWBaseTestCase{

    /**************************
    / HELP DATA & FUNCTIONS   *
    /**************************/
    protected static $samplePostType1 = [
        'id' => '_wpoow_test_menu',
        'title' => 'WPooW Test Menu',
        'fields' => [
            [
                'id' => '_test_text_field_1',
                'label' => 'Sample Rich Text Area Field 1',
                'type' => 'richtextarea',
                'test_value' => 'Sample Text One'
            ],
            [
                'id' => '_test_text_field_2',
                'label' => 'Sample Rich Text AreaField 2',
                'type' => 'richtextarea',
                'test_value' => 'Sample Text Two'
            ]
        ]
    ];



    /**************************
    / TESTS                   *
    /**************************/


    /**
     * @WP_BeforeRun createMultipleRichTextAreaElements
     */
    public function testCanHaveTextElements(){
        $this->loginToWPAdmin();
        $postID = $this->addPost(self::$samplePostType1['id'], [self::$samplePostType1['fields'][0]]);
    }



    /**************************
    / WP_BEFORE RUN FUNCTIONS *
    /**************************/


    public static function  createMultipleRichTextAreaElements()
    {
        $wpOOW = new wpAPI();
        $wpOOWTestPage = $wpOOW->CreatePostType(self::$samplePostType1['id'], self::$samplePostType1['title'], true);
        $wpOOWTestPage->AddField(new RichTextArea(self::$samplePostType1['fields'][0]['id'], self::$samplePostType1['fields'][0]['label']));
        $wpOOWTestPage->AddField(new RichTextArea(self::$samplePostType1['fields'][1]['id'], self::$samplePostType1['fields'][1]['label']));
        $wpOOWTestPage->render();
    }
}
