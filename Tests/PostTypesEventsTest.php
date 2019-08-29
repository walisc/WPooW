<?php

use WPooWTests\WPooWBaseTestCase;

include_once __DIR__.'/../wpAPI.php';

class PostTypesEventsTest extends WPooWBaseTestCase
{

    /**************************
    / HELP DATA & FUNCTIONS   *
    /**************************/

    static function getAfterSaveData(){
        return ['fileLocation'=> sprintf("%s/Resources/Temp/afterSaveTemp.txt", __DIR__),
                'fileContent' => 'afterSaveEvent:)'];
    }

    static function getSamplePostTypeData($id)
    {
        $baseSamplePostType = self::getBaseSamplePostTypeData();

        switch($id) {
            case 1:
                $baseSamplePostType['fields'] = [[
                    'id' => 'test_text_field_id',
                    'label' => 'Test Text Field ID'],[
                    'id' => 'test_text_field_title',
                    'label' => 'Test Text Field Title',
                    'test_value' => 'Test Value One'] ];
                break;
            case 2:
                $baseSamplePostType['fields'] = [
                    [
                    'id' => 'test_text_field_id',
                    'label' => 'Test Text Field ID'
                    ],
                    [
                    'id' => 'test_text_field_title',
                    'label' => 'Test Text Field Title'
                    ]
                ];

        }

        return $baseSamplePostType;

    }

    /**************************
    / TESTS                   *
    /**************************/

    function executeBeforeSaveFunction(){
        $this->loginToWPAdmin();
        $sampleData = self::getSamplePostTypeData(1);
        $postID = $this->addPost($sampleData['id'], $sampleData['fields']);
        $gridData = $this->getGridEntry($sampleData['id'], $postID, [$sampleData['fields'][0]]);
        $formattedTestValue = strtolower(str_replace(' ', '_', $sampleData['fields'][1]['test_value'])));
        $this->assertTrue($gridData['fieldData'][$sampleData['fields'][0]['id']] == $formattedTestValue);
    }

    function executeAfterSaveFunc(){
        $this->loginToWPAdmin();
        $sampleData = self::getSamplePostTypeData(1);
        $this->addPost($sampleData['id'], $sampleData['fields']);
        sleep(1);
        $this->assertFileExists(self::getAfterSaveData()['fileLocation']);
        $this->assertTrue(trim(file_get_contents(self::getAfterSaveData()['fileLocation']))
            == sprintf("%s:%s", self::getAfterSaveData()['fileContent'],  $sampleData['fields'][1]['test_values']));
    }

    function executeBeforeFetchFunction(){
        $this->loginToWPAdmin();
        $sampleData = self::getSamplePostTypeData(2);
        $sampleTestValues = [
            ['1' => 'Rob'],
            ['2' => 'Kim'],
            ['3' => 'Richard'],
            ['4' => 'Chris'],
            ['5' => 'Angli'],
        ];

        foreach ($sampleTestValues as $id => $value){
            $sampleData['fields'][0]['test_values'] = $id;
            $sampleData['fields'][1]['test_values'] = $value;
            $this->addPost($sampleData['id'], $sampleData['fields']);
        }

        $gridData = $this->navigateToPostTypeMenuItem($sampleData['id']);
        //find element

    }
    /**
     * @WP_BeforeRun createBeforeSaveFuncPostType
     */
    function testCanExecuteBeforeSaveFunction()
    {
        $this->executeBeforeSaveFunction();
    }

    /**
     * @WP_BeforeRun createBeforeSaveFuncPostTypeWithClass
     */
    function testCanExecuteBeforeSaveFunctionWithClass()
    {
        $this->executeBeforeSaveFunction();
    }

    /**
     * @WP_BeforeRun createAfterSaveFuncPostType
     */
    function testCanExecuteAfterSaveFunction()
    {
        $this->executeAfterSaveFunc();
    }

    /**
     * @WP_BeforeRun createAfterSaveFuncPostTypeWithClass
     */
    function testCanExecuteAfterSaveFunctionWithClass()
    {
        $this->executeAfterSaveFunc();
    }

    /**
     * @WP_BeforeRun createBeforeFetchFuncPostType
     */
    function testCanExecuteBeforeFetchFunction()
    {
        $this->executeBeforeFetchFunction();
    }

    /**
     * @WP_BeforeRun createBeforeFetchPostTypeWithClass
     */
    function testCanExecuteBeforeFetchFunctionWithClass()
    {
        $this->executeBeforeFetchFunction();
    }

    function testBeforeFetchDoesNotPersist()
    {

    }

    /**************************
    / WP_BEFORE RUN FUNCTIONS *
    /**************************/

    static function createBeforeSaveFuncPostType()
    {
        $testPostType = self::createPostType(new wpAPI(), self::getSamplePostTypeData(1), true);
        $testPostType->RegisterBeforeSaveEvent("beforeSaveFuncDataId1");
    }

    static function createBeforeSaveFuncPostTypeWithClass()
    {
        $sampleEventClass = new SampleEventsClass();

        $testPostType = self::createPostType(new wpAPI(), self::getSamplePostTypeData(1), true);
        $testPostType->RegisterBeforeSaveEvent("beforeSaveFuncDataId1", $sampleEventClass);
    }

    static function createAfterSaveFuncPostType(){
        if (file_exists(self::getAfterSaveData()['fileLocation'])){
            unlink(self::getAfterSaveData()['fileLocation']);
        }
        $testPostType = self::createPostType(new wpAPI(), self::getSamplePostTypeData(1), true);
        $testPostType->RegisterBeforeSaveEvent("afterSaveFuncDataId1");
    }

    static function createAfterSaveFuncPostTypeWithClass(){
        $sampleEventClass = new SampleEventsClass();

        if (file_exists(self::getAfterSaveData()['fileLocation'])){
            unlink(self::getAfterSaveData()['fileLocation']);
        }
        $testPostType = self::createPostType(new wpAPI(), self::getSamplePostTypeData(1), true);
        $testPostType->RegisterBeforeSaveEvent("afterSaveFuncDataId1", $sampleEventClass);
    }

    static function createBeforeFetchFuncPostType(){
        $testPostType = self::createPostType(new wpAPI(), self::getSamplePostTypeData(2), true);
        $testPostType->RegisterBeforeSaveEvent("beforeDataFetchFuncDataId2");
    }

    static function createBeforeFetchPostTypeWithClass(){
        $sampleEventClass = new SampleEventsClass();

        $testPostType = self::createPostType(new wpAPI(), self::getSamplePostTypeData(1), true);
        $testPostType->RegisterBeforeSaveEvent("beforeDataFetchFuncDataId2", $sampleEventClass);
    }


}

class SampleEventsClass{

    function beforeSaveFuncDataId1($data){
        $sampleData = PostTypesEventsTest::getBaseSamplePostTypeData(1);
        $postTypeID = $sampleData['id'];
        $fieldIO = $sampleData['fields'][0]['id'];
        $fieldTest = $sampleData['fields'][1]['id'];

        $data[$fieldIO] = sanitize_title($data[sprintf("%s_%s", $postTypeID, $fieldTest)]);
        return $data;
    }

    function afterSaveFuncDataId1($data){
        $sampleData = PostTypesEventsTest::getBaseSamplePostTypeData(1);

        $fileHandler = fopen(PostTypesEventsTest::getAfterSaveData()['fileLocation'], 'w+');
        fwrite($fileHandler,sprintf("%s:%s",PostTypesEventsTest::getAfterSaveData()['fileContent'], $data[$sampleData['fields'][1]['test_value']]));
        fclose($fileHandler);
    }

    function beforeDataFetchFuncDataId2($query){
        $sampleData = PostTypesEventsTest::getBaseSamplePostTypeData(1);
        $metaKey = sprintf("%s_%s", $sampleData['id'], $sampleData['fields'][1]['id']);
        $query->set('order', 'ASC');
        $query->set('orderby', 'meta_value_num');
        $query->set('meta_key', $metaKey);
    }
}

function beforeSaveFuncDataId1($data){
    $sampleData = PostTypesEventsTest::getBaseSamplePostTypeData(1);
    $postTypeID = $sampleData['id'];
    $fieldIO = $sampleData['fields'][0]['id'];
    $fieldTest = $sampleData['fields'][1]['id'];

    $data[$fieldIO] = sanitize_title($data[sprintf("%s_%s", $postTypeID, $fieldTest)]);
    return $data;
}


function afterSaveFuncDataId1($data){
    $sampleData = PostTypesEventsTest::getBaseSamplePostTypeData(1);

    $fileHandler = fopen(PostTypesEventsTest::getAfterSaveData()['fileLocation'], 'w+');
    fwrite($fileHandler,sprintf("%s:%s",PostTypesEventsTest::getAfterSaveData()['fileContent'], $data[$sampleData['fields'][1]['test_value']]));
    fclose($fileHandler);
}


function beforeDataFetchFuncDataId2($query){
    $sampleData = PostTypesEventsTest::getBaseSamplePostTypeData(1);
    $metaKey = sprintf("%s_%s", $sampleData['id'], $sampleData['fields'][1]['id']);
    $query->set('order', 'ASC');
    $query->set('orderby', 'meta_value_num');
    $query->set('meta_key', $metaKey);
}

