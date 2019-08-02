<?php


use WPooWTests\WPooWBaseTestCase;

include_once __DIR__ . '/../../wpAPI.php';

class CombinedElementsTest extends WPooWBaseTestCase{

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
                'test_value' => 'Testing'
            ],
            [
                'id' => '_test_test_field_2',
                'label' => 'Sample Text Field 2',
                'type' => 'text',
                'test_value' => 'Testing 2'
            ]
        ]
    ];



    /**************************
    / TESTS                   *
    /**************************/


    /**
     * @WP_BeforeRun createTextElement
     */
    public function testCanInteractWithCheckboxElement(){
        $this->loginToWPAdmin();
    }




    /**************************
    / WP_BEFORE RUN FUNCTIONS *
    /**************************/

    public static function createTextElement()
    {
        self::createPostType(new wpAPI(), self::$samplePostType1);
    }

    public static function  createMultipleTextElements()
    {
        $wpOOW = new wpAPI();
        $wpOOWTestPage = $wpOOW->CreatePostType(self::$samplePostType1['id'], self::$samplePostType1['title'], true);
        $wpOOWTestPage->AddField(new Checkbox(self::$samplePostType1['fields'][0]['id'], self::$samplePostType1['fields'][0]['label']));
        $wpOOWTestPage->AddField(new Checkbox(self::$samplePostType1['fields'][1]['id'], self::$samplePostType1['fields'][1]['label']));
        $wpOOWTestPage->render();
    }
}

// Can Add Complex
// Can Edit Complex
// Can have a lot
// Permission work (maybe element for all)
