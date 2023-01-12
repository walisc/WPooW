<?php


use WPooWTests\WPooWBaseTestCase;
use WPooWTests\WPooWTestsElements;

include_once __DIR__ . '/../../wpAPI.php';

class CombinedElementsTest extends WPooWBaseTestCase{

    /**************************
    / HELP DATA & FUNCTIONS   *
    /**************************/
    protected static function getSamplePostTypeData($id){
        $baseSamplePostType = self::getBaseSamplePostTypeData();

        $fields = [[
            'id' => '_test_text_field_1',
            'label' => 'Sample Text Field 1',
            'type' => WPooWTestsElements::TEXT,
            'test_value' => 'Sample Text One'
        ],
            [
                'id' => '_test_textarea_field_1',
                'label' => 'Sample Text Area Field 1',
                'type' => WPooWTestsElements::TEXTAREA,
                'test_value' => 'Sample Text Area One'
            ],
            [
                'id' => '_test_richtextarea_field_1',
                'label' => 'Sample Rich Text Area Field 1',
                'type' => WPooWTestsElements::RICHTEXTAREA,
                'test_value' => preg_replace("/\r\n|\r|\n/", '', '<strong>Sample Text One</strong>
                                <ul>
                                    <li>This is sample text</li>
                                    <li>This is sample text 2</li>
                                    <li>This is sample text 3</li>
                                </ul>
                                <a href="https://www.centridsol.com">www.centridsol.com</a>')
            ],
            [
                'id' => '_test_muiltiselect_field_1',
                'label' => 'Sample Muilti Select Field 1',
                'type' => WPooWTestsElements::MULTISELECT,
                'extra_args' => [
                    'options' => ['categoryA' => 'Category A',
                        'categoryB' => 'Category B',
                        'categoryC' => 'Category C',
                        'categoryD' => 'Category D',
                        'categoryE' => 'Category E',
                        'categoryF' => 'Category F',
                    ]
                ],
                'test_value' =>  ['categoryB' => 'Category B', 'categoryE' => 'Category E']
            ],
            [
                'id' => '_test_upload_field_1',
                'label' => 'Sample Upload Field 1',
                'type' => WPooWTestsElements::UPLOADER,
                'test_value' => ['testImage1.jpg']
            ],
            [
                'id' => '_test_select_field_1',
                'label' => 'Sample Select Field 1',
                'type' => WPooWTestsElements::SELECT,
                'extra_args' => [
                    'options' => ['personA' => 'Person A',
                        'personB' => 'Person B',
                        'personC' => 'Person C',
                        'personD' => 'Person D',
                    ]
                ],
                'test_value' =>  ['personC' => 'Person C']
            ]



        ];

        switch ($id) {
            case 1:
                $baseSamplePostType['fields'] = $fields;
                break;
            case 2:
                //create 50 input types
                $baseSamplePostType['fields'] = [];
                for ($i =0; $i < 5; $i++){
                    foreach ($fields as $field){
                        $field['id'] .= "_${i}";
                        array_push($baseSamplePostType['fields'], $field);
                    }
                }
                break;

        }

        return $baseSamplePostType;

    }

    /**************************
    / TESTS                   *
    /**************************/


    /**
     * @wpBeforeRun createComplexPostType
     */
    public function testCanInteractWithComplexPostType(){
        $this->loginToWPAdmin();
        $sampleData = self::getSamplePostTypeData(1);
        $postID = $this->addPost($sampleData['id'], $sampleData['fields']);
        $this->assertGridDataCorrect($sampleData['id'], $postID, $sampleData['fields']);
    }


    /**
    * @wpBeforeRun createManyInputTypes
    */
    public function testCanInputManyTypes(){
        $this->loginToWPAdmin();
        $sampleData = self::getSamplePostTypeData(2);
        $postID = $this->addPost($sampleData['id'], $sampleData['fields']);
        $this->assertGridDataCorrect($sampleData['id'], $postID, $sampleData['fields']);

    }


    /**************************
    / WP_BEFORE RUN FUNCTIONS *
    /**************************/

    public static function createComplexPostType()
    {
        self::uploadTestFile( 'testImage1.jpg');
        self::createPostType(new wpAPI(), static::getSamplePostTypeData(1));
    }

    public static function createManyInputTypes()
    {
        self::uploadTestFile( 'testImage1.jpg');
        self::createPostType(new wpAPI(), static::getSamplePostTypeData(2));
    }


}

// Can Add Complex (d)
// Can Edit Complex
// Can have a lot (d)
// Permission work (maybe element for all)
