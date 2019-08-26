<?php

use Facebook\WebDriver\WebDriverBy;
use WPooWTests\WPooWBaseTestCase;
use WPooWTests\WPooWTestsElements;

include_once __DIR__ . '/../../wpAPI.php';

class UploadTest extends WPooWBaseTestCase
{
    //TODO: Move this to base method

    /**************************
    / HELP DATA & FUNCTIONS   *
    /**************************/

    protected static function getSamplePostTypeData($id){
        $baseSamplePostType = self::getBaseSamplePostTypeData();

        switch ($id) {
            case 1:
                $baseSamplePostType['fields'] = [[
                    'id' => '_test_upload_field_1',
                    'label' => 'Sample Upload Field 1',
                    'type' => WPooWTestsElements::UPLOADER,
                    'test_value' => ['testImage1.jpg']
                ]];
                break;
            case 2:
                $baseSamplePostType['fields'] = [[
                    'id' => '_test_upload_field_1',
                    'label' => 'Sample Upload Field 1',
                    'type' => WPooWTestsElements::UPLOADER,
                    'test_value' => ['testImage1.jpg']
                ],
                    [
                        'id' => '_test_upload_field_2',
                        'label' => 'Sample Upload Field 2',
                        'type' => WPooWTestsElements::UPLOADER,
                        'test_value' => ['testImage2.jpg']
                    ]];
                break;
        }

        return $baseSamplePostType;

    }

    private static $multimedia = [
      'images' => [
          'testImage1.jpg',
          'testImage2.jpg'
      ]
    ];

    private static $uploadBtnProperties =[
        [
            'modalTitle' => 'Picker',
            'modalSubmitBtn' => 'Select'
        ]
    ];

    private function uploadImageUsingField($postTypeID, $uploadField, $imageNames, $postID=null){

        if ($postID != null){
            $this->editPost($postTypeID, $postID, [$uploadField] );
        }
        else {
            $postID = $this->addPost($postTypeID, [$uploadField]);
        }


        $newPost = $this->driver->findElement(WebDriverBy::xpath("//form[@id='posts-filter']/table/tbody/tr"));
        $imageData = $newPost->findElement(WebDriverBy::xpath(sprintf("//td[contains(@class, '%s_%s')]", $postTypeID, $uploadField['id'])));
        $imageURL = $imageData->findElement(WebDriverBy::xpath("descendant::img"))->getAttribute('src');

        if (!$this->checkImageUploaded($uploadField['test_value'],$imageURL)){
            return false;
        }

        $this->goToEditPage($postTypeID, $postID);
        $uploadPostBox = $this->driver->findElement(WebDriverBy::xpath(sprintf("//div[@id='%s_%s' and contains(@class,'postbox')]",  $postTypeID, $uploadField['id'] )));
        $imageURL = $uploadPostBox->findElement(WebDriverBy::xpath("descendant::img"))->getAttribute('src');
        return $this->checkImageUploaded($uploadField['test_value'], $imageURL);

    }

    private function checkImageUploaded($imageNames, $imageURL){
        foreach($imageNames as $imageName) {

            $imageNameArr = explode('.',$imageName);
            $imageName = implode("",array_slice($imageNameArr,0, count($imageNameArr) -1));

            if (strpos($imageURL, $imageName) === false) {
                return false;
            }
        }
        return true;
    }


    /**************************
    / TESTS                   *
    /**************************/

    /**
     * @WP_BeforeRun createUploaderBasicWPBeforeRun
     */
    public function testCanCreateUploader(){
        $this->loginToWPAdmin();
        $sampleData = static::getSamplePostTypeData(1);
        $fieldInPostTypeGrid= $this->hasFieldInPostTypeGrid($sampleData['id'], $sampleData['fields'][0]);
        $fieldInPostTypeAddForm = $this->hasFieldInPostTypeAddForm($sampleData['id'], $sampleData['fields'][0], '_upload_button');
        $this->assertTrue($fieldInPostTypeGrid && $fieldInPostTypeAddForm);

    }

    /**
     * @WP_BeforeRun createUploaderBasicWPBeforeRun
     */
    public function testCanInteractWithUploader(){
        $this->loginToWPAdmin();
        $sampleData = static::getSamplePostTypeData(1);
        $this->assertTrue($this->uploadImageUsingField($sampleData['id'], $sampleData['fields'][0],[self::$multimedia['images'][0]]));

    }


    /**
     * @WP_BeforeRun createUploaderWithOtherSettingsWPBeforeRun
     */
    public function testCanCreateUploaderWithOtherSettings(){
        $this->loginToWPAdmin();
        $sampleData = static::getSamplePostTypeData(1);
        $this->goToAddPage($sampleData['id']);

        $uploadButton = $this->getElementOnPostTypePage($sampleData['id'],  $sampleData['fields'][0],'_upload_button');
        $uploadButton->click();

        $mediaModal = $this->driver->findElement(WebDriverBy::xpath("//div[contains(@class,'media-modal')]"));
        $mediaModelTitle = $mediaModal->findElement(WebDriverBy::xpath("descendant::div[@class='media-frame-title']/h1"))->getAttribute('innerText');
        $mediaModelSubmitBtnText = $this->findElementWithWait( WebDriverBy::xpath("//div[@class='media-toolbar']/descendant::button"), $mediaModal)->getAttribute('innerText');
        $this->assertTrue($mediaModelTitle == self::$uploadBtnProperties[0]['modalTitle'] && $mediaModelSubmitBtnText == self::$uploadBtnProperties[0]['modalSubmitBtn']);
    }

    /**
     * @WP_BeforeRun createUploaderWithOtherSettingsWPBeforeRun
     * @doesNotPerformAssertions
     */
    public function testCanUploaderTwoAtOnce(){
        //TODO: Implement in version v2.0.0
        //$this->loginToWPAdmin();
        //$this->assertTrue($this->uploadImageUsingField($sampleData['id'], $sampleData['fields'][0],[self::$multimedia['images'][0], self::$multimedia['images'][1] ]));
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testCanClearUploadField(){
        //TODO: Implement in version v2.0.0
    }


    /**
     * @WP_BeforeRun createMultipleUploadersWPBeforeRun
     */
    public function testCanHaveMultipleUploadersOnOnePage(){
        $this->loginToWPAdmin();
        $sampleData = static::getSamplePostTypeData(2);
        $postID = $this->addPost($sampleData['id']);
        $this->assertTrue($this->uploadImageUsingField($sampleData['id'], $sampleData['fields'][0],[self::$multimedia['images'][0]], $postID));
        $this->assertTrue($this->uploadImageUsingField($sampleData['id'], $sampleData['fields'][1],[self::$multimedia['images'][1]], $postID));
    }


    /**************************
    / WP_BEFORE RUN FUNCTIONS *
    /**************************/

    public static function  createUploaderBasicWPBeforeRun()
    {

        self::uploadTestFile( self::$multimedia['images'][0]);
        self::uploadTestFile( self::$multimedia['images'][1]);

        self::createPostType(new wpAPI(), static::getSamplePostTypeData(1));
    }

    public static function  createUploaderWithOtherSettingsWPBeforeRun()
    {
        self::uploadTestFile( self::$multimedia['images'][0]);
        self::uploadTestFile( self::$multimedia['images'][1]);

        $sampleData = static::getSamplePostTypeData(1);
        $sampleData['fields'][0]['extra_args'] = [
            'permissions' => [],
            'uploaderTitle' => self::$uploadBtnProperties[0]['modalTitle'],
            'btnText' =>  self::$uploadBtnProperties[0]['modalSubmitBtn'],
            'enableMultiple' => true
        ];
        self::createPostType(new wpAPI(), $sampleData);
    }

    public static function  createMultipleUploadersWPBeforeRun()
    {
        self::uploadTestFile( self::$multimedia['images'][0]);
        self::uploadTestFile( self::$multimedia['images'][1]);

        self::createPostType(new wpAPI(), static::getSamplePostTypeData(2));
    }

}
