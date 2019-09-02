<?php

use WPooWTests\WPooWBaseTestCase;
use Facebook\WebDriver\WebDriverBy;

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
        $baseSamplePostType['id'] =  $baseSamplePostType['id']. '_or';

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
        $formattedTestValue = strtolower(str_replace(' ', '-', $sampleData['fields'][1]['test_value']));
        $this->assertTrue($gridData['fieldData'][$sampleData['fields'][0]['id']]->getText() == $formattedTestValue);
    }

    function executeAfterSaveFunc(){
        $this->loginToWPAdmin();
        $sampleData = self::getSamplePostTypeData(1);
        $this->addPost($sampleData['id'], $sampleData['fields']);
        sleep(1);
        $this->assertFileExists(self::getAfterSaveData()['fileLocation']);
        $this->assertTrue(trim(file_get_contents(self::getAfterSaveData()['fileLocation']))
            == sprintf("%s:%s", self::getAfterSaveData()['fileContent'],  $sampleData['fields'][1]['test_value']));

        if (file_exists(self::getAfterSaveData()['fileLocation'])){
            unlink(self::getAfterSaveData()['fileLocation']);
        }
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
            $sampleData['fields'][0]['test_value'] = $id;
            $sampleData['fields'][1]['test_value'] = $value;
            $this->addPost($sampleData['id'], $sampleData['fields']);
        }


        $gridEntries = $this->getGridEntries($sampleData['id']);

        $currentValue = '';
        $isOrdered = true;

        foreach ($gridEntries as $entry){
            $entryValue = $entry['fieldData'][$sampleData['fields'][1]['id']]->getText();
            if (strcmp($currentValue,  $entryValue) > 0 ){
                $isOrdered = false;
                break;
            }
            else{
                $currentValue = $entryValue;
            }
        }

        $this->assertTrue($isOrdered);

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
        //find element make sure there in order
    }

    /**
     * @WP_BeforeRun createBeforeFetchPostTypeWithClass
     */
    function testCanExecuteBeforeFetchFunctionWithClass()
    {
        $this->executeBeforeFetchFunction();
        //find element make sure there in order
    }

    /**
     * @WP_BeforeRun createBeforeFetchFuncPostType
     */
    function testBeforeFetchDoesNotPersist()
    {
        $this->executeBeforeFetchFunction();
        // ntot find element make sure there in order
    }

    /**************************
    / WP_BEFORE RUN FUNCTIONS *
    /**************************/

    static function createBeforeSaveFuncPostType()
    {
        $testPostType = self::createPostType(new wpAPI(), self::getSamplePostTypeData(1), true);
        $testPostType->RegisterBeforeSaveEvent("beforeSaveFuncDataId1");
        $testPostType->Render();
    }

    static function createBeforeSaveFuncPostTypeWithClass()
    {
        $sampleEventClass = new SampleEventsClass();

        $testPostType = self::createPostType(new wpAPI(), self::getSamplePostTypeData(1), true);
        $testPostType->RegisterBeforeSaveEvent("beforeSaveFuncDataId1", $sampleEventClass);
        $testPostType->Render();
    }

    static function createAfterSaveFuncPostType(){
        $testPostType = self::createPostType(new wpAPI(), self::getSamplePostTypeData(1), true);
        $testPostType->RegisterBeforeSaveEvent("afterSaveFuncDataId1");
        $testPostType->Render();
    }

    static function createAfterSaveFuncPostTypeWithClass(){
        $sampleEventClass = new SampleEventsClass();

        $testPostType = self::createPostType(new wpAPI(), self::getSamplePostTypeData(1), true);
        $testPostType->RegisterBeforeSaveEvent("afterSaveFuncDataId1", $sampleEventClass);
        $testPostType->Render();
    }

    static function createBeforeFetchFuncPostType(){
        $testPostType = self::createPostType(new wpAPI(), self::getSamplePostTypeData(2), true);
        $testPostType->RegisterBeforeDataFetch("beforeDataFetchFuncDataId2");
        $testPostType->Render();
    }

    static function createBeforeFetchPostTypeWithClass(){
        $sampleEventClass = new SampleEventsClass();

        $testPostType = self::createPostType(new wpAPI(), self::getSamplePostTypeData(1), true);
        $testPostType->RegisterBeforeDataFetch("beforeDataFetchFuncDataId2", $sampleEventClass);
        $testPostType->Render();
    }


}

class SampleEventsClass{

    function beforeSaveFuncDataId1($data){
        //calling global version of the function
        return beforeSaveFuncDataId1($data);
    }

    function afterSaveFuncDataId1($data){
        afterSaveFuncDataId1($data);
    }

    function beforeDataFetchFuncDataId2($query){
        beforeDataFetchFuncDataId2($query);
    }
}

function beforeSaveFuncDataId1($data){
    $sampleData = PostTypesEventsTest::getSamplePostTypeData(1);
    $postTypeID = $sampleData['id'];
    $fieldID = $sampleData['fields'][0]['id'];
    $fieldTest = $sampleData['fields'][1]['id'];

    $data[$fieldID] = sanitize_title($data[sprintf("%s_%s", $postTypeID, $fieldTest)]);
    return $data;
}


function afterSaveFuncDataId1($data){
    $sampleData = PostTypesEventsTest::getSamplePostTypeData(1);
    $fieldId = sprintf("%s_%s",$sampleData['id'], $sampleData['fields'][1]['id'] );

    $fileHandler = fopen(PostTypesEventsTest::getAfterSaveData()['fileLocation'], 'w+');

    fwrite($fileHandler,sprintf("%s:%s",PostTypesEventsTest::getAfterSaveData()['fileContent'], $data[$fieldId]));
    fclose($fileHandler);
    sleep(1);
}


function beforeDataFetchFuncDataId2($query){
    $sampleData = PostTypesEventsTest::getSamplePostTypeData(1);
    $metaKey = sprintf("%s_%s_value_key", $sampleData['id'], $sampleData['fields'][1]['id']);
    $query->set('order', 'ASC');
    $query->set('orderby', 'meta_value');
    $query->set('meta_key', $metaKey);
}

