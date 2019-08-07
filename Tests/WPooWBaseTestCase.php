<?php

namespace WPooWTests;

use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Interactions\WebDriverActions;
use Facebook\WebDriver\JavaScriptExecutor;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use WPSelenium\WPSTestCase;

class WPooWBaseTestCase extends WPSTestCase
{
    use WPooWTestsInputer;

    function setUp()
    {
        parent::setUp();
        $this->setUpElementInputer();
    }

    /**************************
     * NAVIGATION             *
     **************************/
    protected function locatedMenuItem($id, $title = null)
    {
        try {
            $menuItemNameLi = $this->driver->findElement(WebDriverBy::id("menu-posts-${id}"));
            $menuItemName = $menuItemNameLi->findElement(WebDriverBy::xpath("descendant::div[@class='wp-menu-name'][1]"));

            if ($title != null && $title != $this->getElementInnerText($menuItemName)) {
                return null;
            }
            return [
                "text" => $menuItemName,
                "link" => $menuItemName->findElement(WebDriverBy::xpath("ancestor::a[1]")),
                "li" => $menuItemNameLi
            ];
        } catch (NoSuchElementException $e) {
            //TODO: Log error
        }
        return null;
    }

    protected function navigateToMenuItems($postTypeID)
    {
        $menuItem = $this->locatedMenuItem($postTypeID);
        $menuItem["li"]->click();
        $this->waitForPageToLoad();
    }

    protected function goToAddPage($postTypeID)
    {
        $this->navigateToMenuItems($postTypeID);
        //TODO: Think about localization
        $addButton = $this->driver->findElement(WebDriverBy::xpath("//a[@class='page-title-action' and text()='Add New']"));
        $addButton->click();
    }

    protected function goToEditPage($postTypeID, $postId)
    {
        $this->navigateToMenuItems($postTypeID);

        $thePost = $this->driver->findElement(WebDriverBy::xpath("//tr[@id='${postId}']"));
        $editLink = $thePost->findElement(WebDriverBy::xpath("descendant::span[@class='edit']/a"));
        $this->GetWebPage($editLink->getAttribute('href'));
    }

    /**************************
     * POST RELATED           *
     * ************************/
    protected function addPost($postTypeID, $fields = [])
    {
        $this->navigateToMenuItems($postTypeID);
        $initialCount = $this->getPageCount();

        $this->goToAddPage($postTypeID);
        $this->insertValuesToPostTypeForm($postTypeID, $fields);
        $publishBtn = $this->driver->findElement(WebDriverBy::id("publish"));
        $this->driver->executeScript("arguments[0].scrollIntoView(false)", [$publishBtn]);
        $publishBtn->click();

        $this->navigateToMenuItems($postTypeID);

        if ($initialCount + 1 != $this->getPageCount()) {
            return null;
        }
        return $this->driver->findElement(WebDriverBy::xpath("//form[@id='posts-filter']/table/tbody/tr"))->getAttribute('id');
    }

    protected function editPost($postTypeID, $postId, $fields = [])
    {

        $this->goToEditPage($postTypeID, $postId);
        $this->insertValuesToPostTypeForm($postTypeID, $fields);
        $this->driver->findElement(WebDriverBy::id("publish"))->click();

        try {
            $this->driver->wait(10, 1000)->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('message'))
            );

            //TODO: Localization, and case insentivity
            if (strpos($this->getElementInnerText($this->driver->findElement(WebDriverBy::xpath("//div[@id='message']/p"))), 'Post updated') !== false) {
                $this->navigateToMenuItems($postTypeID);
                return $postId;
            }
        } catch (NoSuchElementException $e) {
            //TODO: Log error
        }
        return $postId;
    }

    protected function deletePost($postTypeID, $postId, $fields = [])
    {
        $this->navigateToMenuItems($postTypeID);
        $initialCount = $this->getPageCount();

        $thePost = $this->driver->findElement(WebDriverBy::xpath("//tr[@id='${postId}']"));
        $deleteLink = $thePost->findElement(WebDriverBy::xpath("descendant::span[@class='trash']/a"));
        $this->GetWebPage($deleteLink->getAttribute('href'));

        if ($initialCount - 1 != $this->getPageCount()) {
            return false;
        }
        return true;
    }

    public function getElementOnPostTypePage($postTypeID, $field, $fieldIDTag = '')
    {
        $postTypeFieldID = "${postTypeID}_${field['id']}";
        $postbox = $this->driver->findElement(WebDriverBy::xpath("//div[@id='${postTypeFieldID}']"));

        if (strpos($postbox->getAttribute('class'), 'postbox') === false) {
            return null;
        }

        return $postbox->findElement(WebDriverBy::xpath("descendant::input[@id='${postTypeFieldID}${fieldIDTag}']"));

    }

    protected function hasFieldInPostTypeAddForm($postTypeID, $field, $fieldIDTag)
    {
        try {
            $this->goToAddPage($postTypeID);
            return $this->getElementOnPostTypePage($postTypeID, $field, $fieldIDTag) != null;
        } catch (NoSuchElementException $e) {
            //TODO: Log
        }
        return false;
    }

    /**************************
     * GRID RELATED           *
     **************************/

    public function hasFieldInPostTypeGrid($postTypeID, $field)
    {
        try {
            $this->navigateToMenuItems($postTypeID);
            $fieldCol = $this->driver->findElement(WebDriverBy::xpath("//form[@id='posts-filter']/table/thead/tr/th[@id='${postTypeID}_${field['id']}']"));
            if ($this->getElementInnerText($fieldCol) == $field['label']) {
                return true;
            }
        } catch (NoSuchElementException $e) {
            //TODO: Log
        }
        return false;
    }

    public function getGridEntry($postTypeID, $postID, $fields=null){

        $this->navigateToMenuItems($postTypeID);
        $gridData = ['gridEntry' => $this->driver->findElement(WebDriverBy::id($postID)),
                     'fieldData' => []];

        if ($fields == null){
            return $gridData['gridEntry'];
        }

        foreach ($fields as $field){
            $postTypeFieldID = "${postTypeID}_${field['id']}";
            $gridData['fieldData'][$field['id']] = $gridData['gridEntry']->findElement(WebDriverBy::xpath("td[contains(@class, '${postTypeFieldID}')]"));
        }

        return $gridData;
    }

    public function assertGridDataCorrect($postTypeID, $postID, $fields){
        $gridValues = $this->getGridEntry($postTypeID, $postID,  $fields);

        foreach ($fields as $field){
            $fieldValue = $gridValues['fieldData'][$field['id']];
            if (array_key_exists('test_value', $field)) {
                $fieldType = array_key_exists('type', $field) ? $field['type'] : 'text';

                if (array_key_exists($fieldType, self::$FIELD_MAP)){
                    $this->elementInputer[$fieldType]->assetValueEqual($field, $fieldValue);
                }
                else{
                    $this->elementInputer[WPooWTestsElements::TEXT]->assetValueEqual($field, $fieldValue);
                }
            }
        }

    }

    /**************************
     * HELPER                 *
     **************************/

    public static function uploadTestFile($imageName)
    {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . WPINC . '/post.php');
        require_once(ABSPATH . WPINC . '/pluggable.php');

        global $wpdb;

        $imagePath = __DIR__ . "/images/${imageName}";

        $uploadDir = WP_CONTENT_DIR . '/uploads';
        $newFilePath = "${uploadDir}/${imageName}";

        if (!file_exists($newFilePath)) {
            copy($imagePath, $newFilePath);

        }

        $attachment = array(
            'guid' => $newFilePath,
            'post_mime_type' => wp_get_image_mime($imagePath),
            'post_title' => basename($imageName),
            'post_content' => '',
            'post_status' => 'inherit'
        );


        if ($wpdb->get_var($wpdb->prepare(sprintf("SELECT ID FROM $wpdb->posts WHERE post_title='%s' AND post_type='attachment'", $attachment['post_title']), [])) == null) {

            $id = wp_insert_attachment($attachment, basename($imageName), 0, true);
            if (!is_wp_error($id)) {
                wp_update_attachment_metadata($id, wp_generate_attachment_metadata($id, $newFilePath));
            }
        }
    }

    public static function createPostType($wpOOW, $postTypeObj){
        $wpOOWTestPage = $wpOOW->CreatePostType($postTypeObj['id'], $postTypeObj['title'], true);

        foreach ($postTypeObj['fields'] as $field){
            $wpOOWTestPage->AddField(self::$FIELD_MAP[$field['type']]::createElement($wpOOW, $field));
        }

        $wpOOWTestPage->render();

    }

    public static function getBaseSamplePostTypeData(){
        return [
            'id' => '_wpoow_test_menu',
            'title' => 'WPooW Test Menu'
        ];
    }

    private function insertValuesToPostTypeForm($postTypeID, $fields)
    {
        foreach ($fields as $field) {
            if (array_key_exists('test_value', $field)) {
                $postbox = $this->findElementWithWait(WebDriverBy::xpath("//div[@id='${postTypeID}_${field['id']}' and contains(@class,'postbox')]"));
                $this->driver->executeScript("arguments[0].scrollIntoView(false)", [$postbox]);

                $fieldType = array_key_exists('type', $field) ? $field['type'] : 'text';
                if (array_key_exists($fieldType, self::$FIELD_MAP)){
                    $this->elementInputer[$fieldType]->inputValue($postTypeID, $field);
                }
                else{
                    $this->elementInputer[WPooWTestsElements::TEXT]->inputValue($postTypeID, $field);
                }

            }
        }
    }


    private function getPageCount()
    {
        try {
            $numberOfItems = $this->driver->findElement(WebDriverBy::xpath("//form[@id='posts-filter']/descendant::span[@class='displaying-num']"));
            return (int)(str_replace('items', '', $numberOfItems->GetText()));
        } catch (NoSuchElementException $e) {
            //TODO: Log error
        }
        return -1;
    }

    private function getElementInnerText($element)
    {
        return $element->getAttribute('innerText');
    }


}
