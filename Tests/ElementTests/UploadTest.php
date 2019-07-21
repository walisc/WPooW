<?php

use Facebook\WebDriver\WebDriverBy;
use WPooWTests\WPooWBaseTestCase;

include_once __DIR__ . '/../../wpAPI.php';

class UploadTest extends WPooWBaseTestCase
{
    //TODO: Move this to base method

    /**************************
    / HELP DATA & FUNCTIONS   *
    /**************************/
    private static $samplePostType1 = [
        'id' => '_wpoow_test_menu',
        'title' => 'WPooW Test Menu',
        'fields' => [
            [
                'id' => '_test_upload_field_1',
                'label' => 'Sample Upload Field 1'
            ],
            [
                'id' => '_test_upload_field_2',
                'label' => 'Sample Upload Field 2'
            ]
        ]
    ];

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

        $postID != null ? $this->goToEditPage($postTypeID, $postID ) :  $this->goToAddPage($postTypeID);

        $uploadButton = $this->getElementOnPostTypePage($postTypeID, $uploadField,'_upload_button');
        $uploadButton->click();

        $mediaModal = $this->driver->findElement(WebDriverBy::xpath("//div[contains(@class,'media-modal')]"));

        foreach($imageNames as $imageName){
            $this->findElementWithWait(WebDriverBy::xpath("descendant::ul[contains(@class,'attachments')]/descendant::li[@aria-label='${imageName}']"), $mediaModal)->click();
        }

        $this->findElementWithWait( WebDriverBy::xpath("//div[@class='media-toolbar']/descendant::button"), $mediaModal)->click();
        $this->driver->findElement(WebDriverBy::id("publish"))->click();

        $uploadPostBox = $this->driver->findElement(WebDriverBy::xpath(sprintf("//div[@id='%s_%s' and contains(@class,'postbox')]",  $postTypeID, $uploadField['id'] )));
        $imageURL = $uploadPostBox->findElement(WebDriverBy::xpath("descendant::img"))->getAttribute('src');
        if (!$this->checkImageUploaded($imageNames,$imageURL)){
            return false;
        }


        $this->navigateToMenuItems($postTypeID);
        $newPost = $this->driver->findElement(WebDriverBy::xpath("//form[@id='posts-filter']/table/tbody/tr"));
        $imageData = $newPost->findElement(WebDriverBy::xpath(sprintf("//td[contains(@class, '%s_%s')]", $postTypeID, $uploadField['id'])));
        $imageURL = $imageData->findElement(WebDriverBy::xpath("descendant::img"))->getAttribute('src');
        return $this->checkImageUploaded($imageNames,$imageURL);
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
        $fieldInPostTypeGrid= $this->hasFieldInPostTypeGrid(self::$samplePostType1['id'], self::$samplePostType1['fields'][0]);
        $fieldInPostTypeAddForm = $this->hasFieldInPostTypeAddForm(self::$samplePostType1['id'], self::$samplePostType1['fields'][0], '_upload_button');
        $this->assertTrue($fieldInPostTypeGrid && $fieldInPostTypeAddForm);

    }

    /**
     * @WP_BeforeRun createUploaderBasicWPBeforeRun
     */
    public function testCanInteractWithUploader(){
        $this->loginToWPAdmin();
        $this->assertTrue($this->uploadImageUsingField(self::$samplePostType1['id'], self::$samplePostType1['fields'][0],[self::$multimedia['images'][0]]));

    }


    /**
     * @WP_BeforeRun createUploaderWithOtherSettingsWPBeforeRun
     */
    public function testCanCreateUploaderWithOtherSettings(){
        $this->loginToWPAdmin();
        $this->goToAddPage(self::$samplePostType1['id']);

        $uploadButton = $this->getElementOnPostTypePage(self::$samplePostType1['id'],  self::$samplePostType1['fields'][0],'_upload_button');
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
        //$this->assertTrue($this->uploadImageUsingField(self::$samplePostType1['id'], self::$samplePostType1['fields'][0],[self::$multimedia['images'][0], self::$multimedia['images'][1] ]));
    }


    /**
     * @WP_BeforeRun createMultipleUploadersWPBeforeRun
     */
    public function testCanHaveMultipleUploadersOnOnePage(){
        $this->loginToWPAdmin();
        $postID = $this->addPost(self::$samplePostType1['id']);
        $this->assertTrue($this->uploadImageUsingField(self::$samplePostType1['id'], self::$samplePostType1['fields'][0],[self::$multimedia['images'][0]], $postID));
        $this->assertTrue($this->uploadImageUsingField(self::$samplePostType1['id'], self::$samplePostType1['fields'][1],[self::$multimedia['images'][1]], $postID));
    }


    /**************************
    / WP_BEFORE RUN FUNCTIONS *
    /**************************/

    public static function  createUploaderBasicWPBeforeRun()
    {

        self::uploadTestFile( self::$multimedia['images'][0]);
        self::uploadTestFile( self::$multimedia['images'][1]);

        $wpOOW = new wpAPI();
        $wpOOWTestPage = $wpOOW->CreatePostType(self::$samplePostType1['id'], self::$samplePostType1['title'], true);
        $wpOOWTestPage->AddField(new Uploader(self::$samplePostType1['fields'][0]['id'], self::$samplePostType1['fields'][0]['label']));
        $wpOOWTestPage->render();
    }

    public static function  createUploaderWithOtherSettingsWPBeforeRun()
    {
        self::uploadTestFile( self::$multimedia['images'][0]);
        self::uploadTestFile( self::$multimedia['images'][1]);

        $wpOOW = new wpAPI();
        $wpOOWTestPage = $wpOOW->CreatePostType(self::$samplePostType1['id'], self::$samplePostType1['title'], true);
        $wpOOWTestPage->AddField(new Uploader(self::$samplePostType1['fields'][0]['id'], self::$samplePostType1['fields'][0]['label'], [], self::$uploadBtnProperties[0]['modalTitle'] , self::$uploadBtnProperties[0]['modalSubmitBtn'], "true" ));
        $wpOOWTestPage->render();
    }

    public static function  createMultipleUploadersWPBeforeRun()
    {
        self::uploadTestFile( self::$multimedia['images'][0]);
        self::uploadTestFile( self::$multimedia['images'][1]);

        $wpOOW = new wpAPI();
        $wpOOWTestPage = $wpOOW->CreatePostType(self::$samplePostType1['id'], self::$samplePostType1['title'], true);
        $wpOOWTestPage->AddField(new Uploader(self::$samplePostType1['fields'][0]['id'], self::$samplePostType1['fields'][0]['label']));
        $wpOOWTestPage->AddField(new Uploader(self::$samplePostType1['fields'][1]['id'], self::$samplePostType1['fields'][1]['label']));
        $wpOOWTestPage->render();
    }

}
