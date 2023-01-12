<?php
/**
 * Created by PhpStorm.
 * User: chidow
 * Date: 2019/07/26
 * Time: 2:38 PM
 */

use WPooWTests\WPooWTestsElements;

include_once __DIR__ . '/../../wpAPI.php';
include_once  __DIR__. '/SelectTest.php';

class MultiSelectTest extends SelectTest
{

    /**************************
    / HELP DATA & FUNCTIONS   *
    /**************************/
    protected static function getSamplePostTypeData($id){
        $baseSamplePostType = self::getBaseSamplePostTypeData();

        switch ($id) {
            case 1:
                $baseSamplePostType['fields'] = [[
                    'id' => '_test_muiltiselect_field_1',
                    'label' => 'Sample Muilti Select Field 1',
                    'type' => WPooWTestsElements::MULTISELECT,
                    'extra_args' => [
                        'options' => self::$availableOptions[0]
                    ],
                    'test_value' =>  ['personC' => 'Person C', 'personD' => 'Person D']
                ]];
                break;
            case 2:
                $baseSamplePostType['fields'] = [[
                    'id' => '_test_muiltiselect_field_1',
                    'label' => 'Sample Muilti Select Field 1',
                    'type' => WPooWTestsElements::MULTISELECT,
                    'extra_args' => [
                        'options' => self::$availableOptions[0]
                    ],
                    'test_value' =>  ['personC' => 'Person C', 'personD' => 'Person D']
                ],
                [
                    'id' => '_test_muiltiselect_field_2',
                    'label' => 'Sample Muilti Select Field 2',
                    'type' => WPooWTestsElements::MULTISELECT,
                    'extra_args' => [
                        'options' => self::$availableOptions[1]
                    ],
                    'test_value' => ['categoryA' => 'Category A', 'categoryE' => 'Category E' ]
                ]];
                break;
        }

        return $baseSamplePostType;

    }

    /**************************
    / TESTS                   *
    /**************************/

    /**
     * @wpBeforeRun createSelectElement
     */
    public function testCanSelectOption(){
        $this->loginToWPAdmin();
        $sampleData = self::getSamplePostTypeData(1);
        $postID = $this->addPost($sampleData['id'], [$sampleData['fields'][0]]);
        $this->assertGridDataCorrect($sampleData['id'], $postID, [$sampleData['fields'][0]]);
    }


    /**
     * @wpBeforeRun createMultipleSelectElements
     */
    public function testCanLoadMultiple(){
        $this->loginToWPAdmin();
        $sampleData = self::getSamplePostTypeData(2);
        $sampleData['fields'][0]['test_value'] = ['personC' => 'Person C'];
        $postIDa = $this->addPost($sampleData['id'], [$sampleData['fields'][0]]);
        $postIDb = $this->addPost($sampleData['id'], [$sampleData['fields'][1]]);
        $this->assertGridDataCorrect($sampleData['id'], $postIDa, [$sampleData['fields'][0]]);
        $this->assertGridDataCorrect($sampleData['id'], $postIDb, [$sampleData['fields'][1]]);

    }

    /**
     * @wpBeforeRun createMultipleSelectElements
     */
    public function testCanSelectMultipleOptions(){
        $this->loginToWPAdmin();
        $sampleData = self::getSamplePostTypeData(2);
        $postIDa = $this->addPost($sampleData['id'], [$sampleData['fields'][0]]);
        $postIDb = $this->addPost($sampleData['id'], [$sampleData['fields'][1]]);
        $this->assertGridDataCorrect($sampleData['id'], $postIDa, [$sampleData['fields'][0]]);
        $this->assertGridDataCorrect($sampleData['id'], $postIDb, [$sampleData['fields'][1]]);
    }

    /**
     * @wpBeforeRun createMultipleSelectElements
     */
    public function testCanUpdateSelect(){
        $this->loginToWPAdmin();
        $sampleData = self::getSamplePostTypeData(2);
        $postIDa = $this->addPost($sampleData['id'], [$sampleData['fields'][0], $sampleData['fields'][1]]);
        $this->assertGridDataCorrect($sampleData['id'], $postIDa, [$sampleData['fields'][0], $sampleData['fields'][1]]);

        $sampleData['fields'][0]['test_value'] = ['personB' => 'Person B', 'personD' => 'Person D'];
        $sampleData['fields'][1]['test_value'] = ['categoryA' => 'Category A', 'categoryC' => 'Category C','categoryD' => 'Category D'];
        $this->editPost($sampleData['id'], $postIDa, [$sampleData['fields'][0], $sampleData['fields'][1]]);
        $this->assertGridDataCorrect($sampleData['id'], $postIDa, [$sampleData['fields'][0], $sampleData['fields'][1]]);

    }

    /**************************
    / WP_BEFORE RUN FUNCTIONS *
    /**************************/

    // Using parent class


}
