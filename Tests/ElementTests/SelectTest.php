<?php
/**
 * Created by PhpStorm.
 * User: chidow
 * Date: 2019/07/26
 * Time: 2:38 PM
 */

use WPooWTests\WPooWBaseTestCase;

include_once __DIR__ . '/../../wpAPI.php';

class SelectTest extends WPooWBaseTestCase
{

    /**************************
    / HELP DATA & FUNCTIONS   *
    /**************************/
    protected static $samplePostType1 = [
        'id' => '_wpoow_test_menu',
        'title' => 'WPooW Test Menu',
        'fields' => [
            [
                'id' => '_test_select_field_1',
                'label' => 'Sample Select Field 1',
                'type' => 'select'
            ],
            [
                'id' => '_test_select_field_2',
                'label' => 'Sample Select Field 2',
                'type' => 'select'
            ]
        ]
    ];


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
        self::$samplePostType1['fields'][0]['test_value'] =  ['personC' => 'Person C'];
        $postID = $this->addPost(self::$samplePostType1['id'], [self::$samplePostType1['fields'][0]]);
        $this->assertFieldDataCorrect(self::$samplePostType1['id'], $postID, [self::$samplePostType1['fields'][0]]);
    }


    /**
     * @WP_BeforeRun createMultipleSelectElements
     */
    public function testCanLoadMultiple(){
        $this->loginToWPAdmin();
        self::$samplePostType1['fields'][0]['test_value'] = ['personC' => 'Person C'];
        self::$samplePostType1['fields'][1]['test_value'] = ['categoryE' => 'Category E'];
        $postIDa = $this->addPost(self::$samplePostType1['id'], [self::$samplePostType1['fields'][0]]);
        $postIDb = $this->addPost(self::$samplePostType1['id'], [self::$samplePostType1['fields'][1]]);
        $this->assertFieldDataCorrect(self::$samplePostType1['id'], $postIDa, [self::$samplePostType1['fields'][0]]);
        $this->assertFieldDataCorrect(self::$samplePostType1['id'], $postIDb, [self::$samplePostType1['fields'][1]]);

    }

    /**
     * @WP_BeforeRun createMultipleSelectElements
     */
    public function testCanUpdateSelect(){
        $this->loginToWPAdmin();
        self::$samplePostType1['fields'][0]['test_value'] = ['personC' => 'Person C'];
        self::$samplePostType1['fields'][1]['test_value'] = ['categoryF' => 'Category F'];
        $postIDa = $this->addPost(self::$samplePostType1['id'], [self::$samplePostType1['fields'][0], self::$samplePostType1['fields'][1]]);
        $this->assertFieldDataCorrect(self::$samplePostType1['id'], $postIDa, [self::$samplePostType1['fields'][0], self::$samplePostType1['fields'][1]]);

        self::$samplePostType1['fields'][0]['test_value'] = ['personD' => 'Person D'];
        self::$samplePostType1['fields'][1]['test_value'] = ['categoryE' => 'Category E'];
        $this->editPost(self::$samplePostType1['id'], $postIDa, [self::$samplePostType1['fields'][0], self::$samplePostType1['fields'][1]]);
        $this->assertFieldDataCorrect(self::$samplePostType1['id'], $postIDa, [self::$samplePostType1['fields'][0], self::$samplePostType1['fields'][1]]);

    }

    public function assertFieldDataCorrect($postTypeID, $postID, $fields){
        $gridValues = $this->getGridEntry($postTypeID, $postID,  $fields);

        foreach ($fields as $field){
            $fieldValue = $gridValues['fieldData'][$field['id']];
            $this->assertTrue(implode(', ', array_values($field['test_value'])) == $fieldValue->GetText());
        }

    }


    /**************************
    / WP_BEFORE RUN FUNCTIONS *
    /**************************/

    public static function  createSelectElement()
    {
        $wpOOW = new wpAPI();
        $wpOOWTestPage = $wpOOW->CreatePostType(self::$samplePostType1['id'], self::$samplePostType1['title'], true);
        $wpOOWTestPage->AddField(new Select(self::$samplePostType1['fields'][0]['id'], self::$samplePostType1['fields'][0]['label'], self::$availableOptions[0]));
        $wpOOWTestPage->render();
    }

    public static function  createMultipleSelectElements()
    {
        $wpOOW = new wpAPI();
        $wpOOWTestPage = $wpOOW->CreatePostType(self::$samplePostType1['id'], self::$samplePostType1['title'], true);
        $wpOOWTestPage->AddField(new Select(self::$samplePostType1['fields'][0]['id'], self::$samplePostType1['fields'][0]['label'], self::$availableOptions[0]));
        $wpOOWTestPage->AddField(new Select(self::$samplePostType1['fields'][1]['id'], self::$samplePostType1['fields'][1]['label'], self::$availableOptions[1]));
        $wpOOWTestPage->render();
    }

}
