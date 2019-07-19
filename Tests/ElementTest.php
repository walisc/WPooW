<?php


use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\WebDriverBy;
use WPooWTests\WPooWBaseTestCase;

include_once __DIR__.'/../wpAPI.php';

class ElementTest extends WPooWBaseTestCase
{
    //TODO: Move this to base method
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
          'testImage1.png'
      ]
    ];

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
        $this->goToAddPage(self::$samplePostType1['id']);
        $uploadButton = $this->getElementOnPostTypePage(self::$samplePostType1['id'], self::$samplePostType1['fields'][0],'_upload_button');
        $uploadButton->click();

        $mediaModal = $this->driver->findElement(WebDriverBy::xpath("//div[contains(@class,'media-modal')]"));

        $this->findElementWithWait(WebDriverBy::xpath("descendant::ul[contains(@class,'attachments')]/li"), $mediaModal)->click();
        $this->findElementWithWait( WebDriverBy::xpath("//div[@class='media-toolbar']/descendant::button"), $mediaModal)->click();

        $this->driver->findElement(WebDriverBy::id("publish"))->click();

        //TODO: Check on post page
        $this->navigateToMenuItems(self::$samplePostType1['id']);

        $newPost = $this->driver->findElement(WebDriverBy::xpath("//form[@id='posts-filter']/table/tbody/tr"));
        $imageData = $newPost->findElement(WebDriverBy::xpath(sprintf("//td[contains(@class, '%s_%s')]", self::$samplePostType1['id'], self::$samplePostType1['fields'][0]['id'])));
        $imageURL = $imageData->findElement(WebDriverBy::xpath("descendant::img"))->getAttribute('src');

        $imageNameArr = explode('.',self::$multimedia['images'][0]);
        $imageName = implode("",array_slice($imageNameArr,0, count($imageNameArr) -1));
        $this->assertTrue(strpos($imageURL, $imageName) > 0);


    }


    /**
     * @WP_BeforeRun createUploaderWithOtherSettingsWPBeforeRun
     */
    public function testCanCreateUploaderWithOtherSettings(){
        $this->loginToWPAdmin();
    }

    /**
     * @WP_BeforeRun createMultipleUploadersWPBeforeRun
     */
    public function testCanHaveMultipleUploadersOnOnePage(){
        $this->loginToWPAdmin();
    }

    public static function  createUploaderBasicWPBeforeRun()
    {

        self::uploadTestFile( self::$multimedia['images'][0]);

        $wpOOW = new wpAPI();
        $wpOOWTestPage = $wpOOW->CreatePostType(self::$samplePostType1['id'], self::$samplePostType1['title'], true);
        $wpOOWTestPage->AddField(new Uploader(self::$samplePostType1['fields'][0]['id'], self::$samplePostType1['fields'][0]['label']));
        $wpOOWTestPage->render();
    }

    public static function  createUploaderWithOtherSettingsWPBeforeRun()
    {
        $wpOOW = new wpAPI();
        $wpOOWTestPage = $wpOOW->CreatePostType(self::$samplePostType1['id'], self::$samplePostType1['title'], true);
        $wpOOWTestPage->AddField(new Uploader(self::$samplePostType1['fields'][0]['id'], self::$samplePostType1['fields'][0]['label'], [], "Picker", "Select", true ));
        $wpOOWTestPage->render();
    }

    public static function  createMultipleUploadersWPBeforeRun()
    {
        $wpOOW = new wpAPI();
        $wpOOWTestPage = $wpOOW->CreatePostType(self::$samplePostType1['id'], self::$samplePostType1['title'], true);
        $wpOOWTestPage->AddField(new Uploader(self::$samplePostType1['fields'][0]['id'], self::$samplePostType1['fields'][0]['label']));
        $wpOOWTestPage->AddField(new Uploader(self::$samplePostType1['fields'][1]['id'], self::$samplePostType1['fields'][1]['label']));
        $wpOOWTestPage->render();
    }
}
