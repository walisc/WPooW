<?php

use Facebook\WebDriver\WebDriverBy;
use WPooWTests\WPooWBaseTestCase;

include_once __DIR__ . '/../../wpAPI.php';

class DateTimeTest extends WPooWBaseTestCase
{

    /**************************
    / HELP DATA & FUNCTIONS   *
    /**************************/
    private static $samplePostType1 = [
        'id' => '_wpoow_test_menu',
        'title' => 'WPooW Test Menu',
        'fields' => [
            [
                'id' => '_test_datetime_field_1',
                'label' => 'Sample DateTime Field 1',
                'test_value' => '010119891058PM'
            ],
            [
                'id' => '_test_datetime_field_2',
                'label' => 'Sample Upload Field 2'
            ]
        ]
    ];



    /**************************
    / TESTS                   *
    /**************************/

    /**
     * @WP_BeforeRun createDateTimeElement
     */
    public function testCanInteractWithDateTimeElement(){
        $this->loginToWPAdmin();
        $newPost = $this->addPost(self::$samplePostType1['id'], [self::$samplePostType1['fields'][0]]);
    }

    /**
     * @WP_BeforeRun createDateTimeElement
     */
    public function testCanClearDataTimeElement(){
        $this->loginToWPAdmin();

    }


    /**
     * @WP_BeforeRun createDateTimeElement
     */
    public function testCanHaveMultipleDateTimeElements()
    {
        $this->loginToWPAdmin();

    }

    /**
     * @WP_BeforeRun createDateTimeElement
     */
    public function testTimeChangeWithLocale(){
    }


    /**************************
    / WP_BEFORE RUN FUNCTIONS *
    /**************************/

    public static function  createDateTimeElement()
    {

        $wpOOW = new wpAPI();
        $wpOOWTestPage = $wpOOW->CreatePostType(self::$samplePostType1['id'], self::$samplePostType1['title'], true);
        $wpOOWTestPage->AddField(new wpAPIDateTime(self::$samplePostType1['fields'][0]['id'], self::$samplePostType1['fields'][0]['label']));
        $wpOOWTestPage->AddField(new wpAPIDateTime(self::$samplePostType1['fields'][1]['id'], self::$samplePostType1['fields'][1]['label']));
        $wpOOWTestPage->render();
    }

}




//Allow changing format
//Allow selectable between datetime and date
//disallow the need for micro seconds