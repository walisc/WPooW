<?php
/**
 * Created by PhpStorm.
 * User: chidow
 * Date: 2019/07/29
 * Time: 3:08 PM
 */

use WPooWTests\WPooWBaseTestCase;
use WPooWTests\WPooWTestsElements;

include_once __DIR__ . '/../../wpAPI.php';

class TextTest extends WPooWBaseTestCase{

    /**************************
    / HELP DATA & FUNCTIONS   *
    /**************************/
    protected static function getSamplePostTypeData($id){
        $baseSamplePostType = self::getBaseSamplePostTypeData();

        switch ($id) {
            case 1:
                $baseSamplePostType['fields'] = [[
                    'id' => '_test_text_field_1',
                    'label' => 'Sample Text Field 1',
                    'type' => WPooWTestsElements::TEXT,
                    'test_value' => 'Sample Text One'
                ]];
                break;
            case 2:
                $baseSamplePostType['fields'] = [[
                    'id' => '_test_text_field_1',
                    'label' => 'Sample Text Field 1',
                    'type' => WPooWTestsElements::TEXT,
                    'test_value' => 'Sample Text One'
                    ],
                    [
                        'id' => '_test_text_field_2',
                        'label' => 'Sample Text Field 2',
                        'type' => WPooWTestsElements::TEXT,
                        'test_value' => 'Sample Text Two'
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
    public function testCanInteractWithTextElement(){
        $this->loginToWPAdmin();
        $sampleData =  static::getSamplePostTypeData(1);
        $postID = $this->addPost($sampleData['id'], [$sampleData['fields'][0]]);
        $this->assertGridDataCorrect($sampleData['id'], $postID, [$sampleData['fields'][0]]);

    }

    /**
     * @WP_BeforeRun createTextElement
     */
    public function testCanUpdateTextBox(){
        $this->loginToWPAdmin();
        $sampleData =  static::getSamplePostTypeData(1);
        $postID = $this->addPost($sampleData['id'], [$sampleData['fields'][0]]);
        $this->assertGridDataCorrect($sampleData['id'], $postID, [$sampleData['fields'][0]]);

        $sampleData['fields'][0]['test_value'] = 'New Test Sample Text';
        $this->editPost($sampleData['id'], $postID, [$sampleData['fields'][0]]);
        $this->assertGridDataCorrect($sampleData['id'], $postID, [$sampleData['fields'][0]]);

    }

    /**
     * @WP_BeforeRun createMultipleTextElements
     */
    public function testCanHaveMultipleTextElements(){
        $this->loginToWPAdmin();
        $sampleData =  static::getSamplePostTypeData(2);
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
