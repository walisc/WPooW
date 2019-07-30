<?php
/**
 * Created by PhpStorm.
 * User: chidow
 * Date: 2019/07/29
 * Time: 3:08 PM
 */

use WPooWTests\WPooWBaseTestCase;

include_once __DIR__ . '/../../wpAPI.php';
include_once  __DIR__. '/TextTest.php';

class RichTextAreaTest extends TextTest
{

    /**************************
     * / HELP DATA & FUNCTIONS   *
     * /**************************/
    protected static $samplePostType1 = [
        'id' => '_wpoow_test_menu',
        'title' => 'WPooW Test Menu',
        'fields' => [
            [
                'id' => '_test_richtextarea_field_1',
                'label' => 'Sample Rich Text Area Field 1',
                'type' => 'richtextarea'
            ],
            [
                'id' => '_test_richtextarea_field_2',
                'label' => 'Sample Rich Text AreaField 2',
                'type' => 'richtextarea'
            ]
        ]
    ];

    public function setUp()
    {
        parent::setUp();

        self::$samplePostType1['fields'][0]['test_value'] =  preg_replace("/\r\n|\r|\n/", '', '<strong>Sample Text One</strong>
                                <ul>
                                    <li>This is sample text</li>
                                    <li>This is sample text 2</li>
                                    <li>This is sample text 3</li>
                                </ul>
                                <a href="https://www.centridsol.com">www.centridsol.com</a>');

        self::$samplePostType1['fields'][1]['test_value'] =  preg_replace("/\r\n|\r|\n/", '', '<h1><strong>Sample Text Two</strong></h1>
                                    <ol>
                                        <li>
                                    <blockquote>This is sample text</blockquote>
                                    </li>
                                        <li>
                                    <blockquote>This is sample text 2</blockquote>
                                    </li>
                                        <li>
                                    <blockquote>This is sample text 3</blockquote>
                                    </li>
                                    </ol>');


        parent::$samplePostType1 = self::$samplePostType1;
    }

    /**************************
     * / TESTS                   *
     * /**************************/


    // Using parents tests

    /**************************
     * / WP_BEFORE RUN FUNCTIONS *
     * /**************************/

    public static function createTextElement()
    {
        $wpOOW = new wpAPI();
        $wpOOWTestPage = $wpOOW->CreatePostType(self::$samplePostType1['id'], self::$samplePostType1['title'], true);
        $wpOOWTestPage->AddField(new RichTextArea(self::$samplePostType1['fields'][0]['id'], self::$samplePostType1['fields'][0]['label']));
        $wpOOWTestPage->render();
    }

    public static function createMultipleTextElements()
    {
        $wpOOW = new wpAPI();
        $wpOOWTestPage = $wpOOW->CreatePostType(self::$samplePostType1['id'], self::$samplePostType1['title'], true);
        $wpOOWTestPage->AddField(new RichTextArea(self::$samplePostType1['fields'][0]['id'], self::$samplePostType1['fields'][0]['label']));
        $wpOOWTestPage->AddField(new RichTextArea(self::$samplePostType1['fields'][1]['id'], self::$samplePostType1['fields'][1]['label']));
        $wpOOWTestPage->render();
    }

}
