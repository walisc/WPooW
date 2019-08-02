<?php


use WPooWTests\WPooWBaseTestCase;

include_once __DIR__ . '/../../wpAPI.php';

class CheckboxTests extends WPooWBaseTestCase{

    /**************************
    / HELP DATA & FUNCTIONS   *
    /**************************/
    protected static $samplePostType1 = [
        'id' => '_wpoow_test_menu',
        'title' => 'WPooW Test Menu',
        'fields' => [
            [
                'id' => '_test_checkbox_field_1',
                'label' => 'Sample Checkbox Field 1',
                'type' => 'checkbox',
                'test_value' => true
            ],
            [
                'id' => '_test_checkbox_field_2',
                'label' => 'Sample Checkbox Field 2',
                'type' => 'checkbox',
                'test_value' => false
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
        $postID = $this->addPost(self::$samplePostType1['id'], [self::$samplePostType1['fields'][0]]);
        $this->assertGridDataCorrect(self::$samplePostType1['id'], $postID, [self::$samplePostType1['fields'][0]]);

    }

    /**
     * @WP_BeforeRun createTextElement
     */
    public function testCanUpdateCheckboxElement(){
        $this->loginToWPAdmin();
        $postID = $this->addPost(self::$samplePostType1['id'], [self::$samplePostType1['fields'][0]]);
        $this->assertGridDataCorrect(self::$samplePostType1['id'], $postID, [self::$samplePostType1['fields'][0]]);

        self::$samplePostType1['fields'][0]['test_value'] = false;
        $this->editPost(self::$samplePostType1['id'], $postID, [self::$samplePostType1['fields'][0]]);
        $this->assertGridDataCorrect(self::$samplePostType1['id'], $postID, [self::$samplePostType1['fields'][0]]);

    }

    /**
     * @WP_BeforeRun createMultipleTextElements
     */
    public function testCanHaveMultipleTextElements(){
        $this->loginToWPAdmin();
        self::$samplePostType1['fields'][1]['test_value'] = true;
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
        $wpOOWTestPage->AddField(new Checkbox(self::$samplePostType1['fields'][0]['id'], self::$samplePostType1['fields'][0]['label']));
        $wpOOWTestPage->render();
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

