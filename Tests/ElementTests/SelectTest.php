<?php
/**
 * Created by PhpStorm.
 * User: chidow
 * Date: 2019/07/26
 * Time: 2:38 PM
 */

use WPooWTests\WPooWBaseTestCase;
use WPooWTests\WPooWTestsElements;

include_once __DIR__ . '/../../wpAPI.php';

class SelectTest extends WPooWBaseTestCase
{

    /**************************
    / HELP DATA & FUNCTIONS   *
    /**************************/
    protected static function getSamplePostTypeData($id){
        $baseSamplePostType = self::getBaseSamplePostTypeData();

        switch ($id) {
            case 1:
                $baseSamplePostType['fields'] = [[
                    'id' => '_test_select_field_1',
                    'label' => 'Sample Select Field 1',
                    'type' => WPooWTestsElements::SELECT,
                    'extra_args' => [
                        'options' => self::$availableOptions[0]
                    ],
                    'test_value' =>  ['personC' => 'Person C']
                ]];
                break;
            case 2:
                $baseSamplePostType['fields'] = [[
                    'id' => '_test_select_field_1',
                    'label' => 'Sample Select Field 1',
                    'type' => WPooWTestsElements::SELECT,
                    'extra_args' => [
                        'options' => self::$availableOptions[0]
                    ],
                    'test_value' =>  ['personC' => 'Person C']
                ],
                    [
                        'id' => '_test_select_field_2',
                        'label' => 'Sample Select Field 2',
                        'type' =>  WPooWTestsElements::SELECT,
                        'extra_args' => [
                            'options' => self::$availableOptions[1]
                        ],
                        'test_value' =>  ['categoryE' => 'Category E']
                    ]];
                break;
        }

        return $baseSamplePostType;

    }


    protected static $availableOptions = [
        ['personA' => 'Person A',
            'personB' => 'Person B',
            'personC' => 'Person C',
            'personD' => 'Person D',
        ],
        ['categoryA' => 'Category A',
            'categoryB' => 'Category B',
            'categoryC' => 'Category C',
            'categoryD' => 'Category D',
            'categoryE' => 'Category E',
            'categoryF' => 'Category F',
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
        $sampleData = static::getSamplePostTypeData(1);
        $postID = $this->addPost($sampleData['id'], [$sampleData['fields'][0]]);
        $this->assertGridDataCorrect($sampleData['id'], $postID, [$sampleData['fields'][0]]);
    }


    /**
     * @WP_BeforeRun createMultipleSelectElements
     */
    public function testCanLoadMultiple(){
        $this->loginToWPAdmin();
        $sampleData = static::getSamplePostTypeData(2);
        $postIDa = $this->addPost($sampleData['id'], [$sampleData['fields'][0]]);
        $postIDb = $this->addPost($sampleData['id'], [$sampleData['fields'][1]]);
        $this->assertGridDataCorrect($sampleData['id'], $postIDa, [$sampleData['fields'][0]]);
        $this->assertGridDataCorrect($sampleData['id'], $postIDb, [$sampleData['fields'][1]]);

    }

    /**
     * @WP_BeforeRun createMultipleSelectElements
     */
    public function testCanUpdateSelect(){
        $this->loginToWPAdmin();
        $sampleData = static::getSamplePostTypeData(2);
        $postIDa = $this->addPost($sampleData['id'], [$sampleData['fields'][0], $sampleData['fields'][1]]);
        $this->assertGridDataCorrect($sampleData['id'], $postIDa, [$sampleData['fields'][0], $sampleData['fields'][1]]);

        $sampleData['fields'][0]['test_value'] = ['personD' => 'Person D'];
        $sampleData['fields'][1]['test_value'] = ['categoryE' => 'Category E'];
        $this->editPost($sampleData['id'], $postIDa, [$sampleData['fields'][0], $sampleData['fields'][1]]);
        $this->assertGridDataCorrect($sampleData['id'], $postIDa, [$sampleData['fields'][0], $sampleData['fields'][1]]);

    }



    /**************************
    / WP_BEFORE RUN FUNCTIONS *
    /**************************/

    public static function  createSelectElement()
    {
        self::createPostType(new wpAPI(), static::getSamplePostTypeData(1));
    }

    public static function  createMultipleSelectElements()
    {
        self::createPostType(new wpAPI(), static::getSamplePostTypeData(2));
    }

}
