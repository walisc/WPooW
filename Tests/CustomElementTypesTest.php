<?php

use WPooWTests\WPooWBaseTestCase;
use WPooWTests\CustomTestElements\TestGreetingElementBasic;
use Facebook\WebDriver\WebDriverBy;

include_once __DIR__.'/../wpAPI.php';

class CustomElementTypesTest extends WPooWBaseTestCase{

    CONST ASSERT_CONTENT_EQUAL_EDIT = 1;
    CONST ASSERT_CONTENT_EQUAL_VIEW = 2;

    static function setUpBeforeClass(){
        parent::setUpBeforeClass();

        if ( ! defined( 'ABSPATH' ) ) {
            define( 'ABSPATH', dirname( __FILE__ ) . '/' );
        }

        Twig_Autoloader::register();
    }

    protected static function getSamplePostTypeData($id)
    {
        $baseSamplePostType = self::getBaseSamplePostTypeData();

        switch ($id) {
            case 1:
                $baseSamplePostType['fields'] = [[
                    'id' => '_test_greeting_element',
                    'label' => 'Test Greeting Element',
                ]];
                break;
        }

        return$baseSamplePostType;

    }
    /**************************
    / HELPERS                 *
    /**************************/

    function assertCustomElementContentEqual($element,  $renderContent, $editOrView=self::ASSERT_CONTENT_EQUAL_EDIT, $postId=null){

        ob_start();
        $editOrView == self::ASSERT_CONTENT_EQUAL_EDIT ? $element->EditView($postId) : $element->ReadView($postId);
        $elementTemplateContent = ob_get_contents();
        ob_end_clean();

        $elementTemplateContentFormatted = preg_replace("/\r\n|\r|\n/", '', $elementTemplateContent);
        $renderContentFormatted = preg_replace("/\r\n|\r|\n/", '', $renderContent);

        $this->assertContains($elementTemplateContentFormatted, $renderContentFormatted);
    }
    /**************************
    / TESTS                   *
    /**************************/

    /**
     * @WP_BeforeRun createBasicCustomElement
     */
    function testCanInteractWithElement(){
        $sampleData = self::getSamplePostTypeData(1);

        $this->loginToWPAdmin();
        $this->goToAddPage($sampleData['id']);

        $customPostBox = $this->getElementOnPostTypePage($sampleData['id'], $sampleData['fields'][0], '', true);
        $renderContent = $customPostBox->findElement(WebDriverBy::xpath("div[@class = 'inside']"))->getAttribute('innerHTML');

        $this->assertCustomElementContentEqual(new TestGreetingElementBasic($sampleData['fields'][0]['id'], $sampleData['fields'][0]['label']), $renderContent);

    }

    function testCanUseTwigTemplating(){

    }

    function testCanLoadJavaScript(){

    }

    function testCanLoadCss(){

    }

    /**************************
    / WP_BEFORE RUN FUNCTIONS *
    /**************************/

    public static function createBasicCustomElement()
    {
        $sampleData = self::getSamplePostTypeData(1);
        $wpOOW = new wpAPI();

        $wpOOWTestPostType = $wpOOW->CreatePostType($sampleData['id'], $sampleData['title'], true);
        $wpOOWTestPostType->AddField(new TestGreetingElementBasic($sampleData['fields'][0]['id'], $sampleData['fields'][0]['label']));
        $wpOOWTestPostType->Render();
    }

}



//testCanInteractWithElement
// - read content
// - edit content
// - content saved

//testCan use Twig Template

// - read content
// - edit content
// - content saved

//test can load JavaSrcript
// -base
// -instance


//test can load css