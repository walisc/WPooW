<?php


use WPooWTests\WPooWBaseTestCase;
use WPooWTests\WPooWTestsElements;

include_once __DIR__ . '/../../wpAPI.php';

class CheckboxTests extends WPooWBaseTestCase{

    /**************************
    / HELP DATA & FUNCTIONS   *
    /**************************/

    protected static function getSamplePostTypeData($id){
        $baseSamplePostType = self::getBaseSamplePostTypeData();

        switch ($id) {
            case 1:
                $baseSamplePostType['fields'] = [[
                    'id' => '_test_checkbox_field_1',
                    'label' => 'Sample Checkbox Field 1',
                    'type' => WPooWTestsElements::CHECKBOX,
                    'test_value' => true
                ]];
                break;
            case 2:
                $baseSamplePostType['fields'] = [[
                    'id' => '_test_checkbox_field_1',
                    'label' => 'Sample Checkbox Field 1',
                    'type' => WPooWTestsElements::CHECKBOX,
                    'test_value' => true
                ],
                    [
                        'id' => '_test_checkbox_field_2',
                        'label' => 'Sample Checkbox Field 2',
                        'type' => 'checkbox',
                        'test_value' => false
                    ]];
                break;
        }

        return $baseSamplePostType;

    }

    /**************************
    / TESTS                   *
    /**************************/


    /**
     * @WP_BeforeRun createTextElement
     */
    public function testCanInteractWithCheckboxElement(){
        $this->loginToWPAdmin();
        $sampleData = self::getSamplePostTypeData(1);
        $postID = $this->addPost($sampleData['id'], [$sampleData['fields'][0]]);
        $this->assertGridDataCorrect($sampleData['id'], $postID, [$sampleData['fields'][0]]);

    }

    /**
     * @WP_BeforeRun createTextElement
     */
    public function testCanUpdateCheckboxElement(){
        $this->loginToWPAdmin();
        $sampleData = self::getSamplePostTypeData(1);
        $postID = $this->addPost($sampleData['id'], [$sampleData['fields'][0]]);
        $this->assertGridDataCorrect($sampleData['id'], $postID, [$sampleData['fields'][0]]);

        $sampleData['fields'][0]['test_value'] = false;
        $this->editPost($sampleData['id'], $postID, [$sampleData['fields'][0]]);
        $this->assertGridDataCorrect($sampleData['id'], $postID, [$sampleData['fields'][0]]);

    }

    /**
     * @WP_BeforeRun createMultipleTextElements
     */
    public function testCanHaveMultipleTextElements(){
        $this->loginToWPAdmin();
        $sampleData = self::getSamplePostTypeData(2);
        $sampleData['fields'][1]['test_value'] = true;
        $postID = $this->addPost($sampleData['id'], [$sampleData['fields'][0], $sampleData['fields'][1]]);
        $this->assertGridDataCorrect($sampleData['id'], $postID, [$sampleData['fields'][0], $sampleData['fields'][1]]);
    }




    /**************************
    / WP_BEFORE RUN FUNCTIONS *
    /**************************/

    public static function createTextElement()
    {
        self::createPostType(new wpAPI(), static::getSamplePostTypeData(1));
    }

    public static function  createMultipleTextElements()
    {
        self::createPostType(new wpAPI(), static::getSamplePostTypeData(2));
    }
}

