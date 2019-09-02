<?php

namespace WPooWTests;

use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Interactions\WebDriverActions;
use Facebook\WebDriver\JavaScriptExecutor;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use PHPUnit\Runner\Exception;
use WPSelenium\WPSTestCase;

class WPooWBaseTestCase extends WPSTestCase
{
    protected $addedPostsToDelete = [];

    use WPooWTestsInputer;

    function setUp()
    {
        parent::setUp();
        $this->setUpElementInputer();
    }

    function tearDown()
    {
        parent::tearDown();
        try{
            foreach ($this->addedPostsToDelete as $index => $postToDelete){
                $this->deletePost(...$postToDelete);
                unset($this->addedPostsToDelete[$index]);
            }
        }catch (NoSuchElementException $e){
            //log
        }
    }

    public function loginToWPAdmin(){
        //TODO: Check if wpsite first
        $this->driver->Get(sprintf('%s/wp-admin', $this->GetTestSite()));
        $this->waitForPageToLoad();
        if(strpos($this->driver->getCurrentURL(), 'wp-login')) {

            $usernameField = $this->driver->findElement(WebDriverby::id('user_login'));
            $passwordField = $this->driver->findElement(WebDriverby::id('user_pass'));
            $loginButton = $this->driver->findElement(WebDriverby::id('wp-submit'));

            $username= getenv('WPSELENIUM_WP_TEST_USERNAME');
            $password = getenv('WPSELENIUM_WP_TEST_PASSWORD');
            sleep(1);
            $usernameField->click();
            $this->driver->getKeyboard()->sendKeys($username);
            $passwordField->click();
            $this->driver->getKeyboard()->sendKeys($password);
            $loginButton->click();

            $this->waitForPageToLoad();
        }
    }

    /**************************
     * NAVIGATION             *
     **************************/
    protected function locatedMenuItem($id, $menuType, $title = null) //TODO: Maybe remove 'menuIDTag' and add the need the specify type
    {
        $menuIDTag = $menuType == WPooWTestsConsts::MENU_TYPE_POSTTYPE  ? 'menu-posts-' : 'toplevel_page_';

        try {
            $menuItemNameLi = $this->driver->findElement(WebDriverBy::id("${menuIDTag}${id}"));
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

    protected function navigateToPostTypeMenuItem($postTypeID)
    {
        $menuItem = $this->locatedMenuItem($postTypeID, WPooWTestsConsts::MENU_TYPE_POSTTYPE);
        $menuItem["li"]->click();
        $this->waitForPageToLoad();
    }

    protected function navigateToMenuItem($postTypeID)
    {
        $menuItem = $this->locatedMenuItem($postTypeID, WPooWTestsConsts::MENU_TYPE_MENU);
        $menuItem["li"]->click();
        $this->waitForPageToLoad();
    }

    protected function goToAddPage($postTypeID)
    {
        $this->navigateToPostTypeMenuItem($postTypeID);
        //TODO: Think about localization
        $addButton = $this->driver->findElement(WebDriverBy::xpath("//a[@class='page-title-action' and text()='Add New']"));
        $addButton->click();
    }

    protected function goToEditPage($postTypeID, $postId)
    {
        $this->navigateToPostTypeMenuItem($postTypeID);

        $thePost = $this->driver->findElement(WebDriverBy::xpath("//tr[@id='${postId}']"));
        $editLink = $thePost->findElement(WebDriverBy::xpath("descendant::span[@class='edit']/a"));
        $this->GetWebPage($editLink->getAttribute('href'));
    }

    /**************************
     * POST RELATED           *
     * ************************/
    protected function addPost($postTypeID, $fields = [])
    {
        $this->navigateToPostTypeMenuItem($postTypeID);
        $initialCount = $this->getPageCount();

        $this->goToAddPage($postTypeID);
        $this->insertValuesToPostTypeForm($postTypeID, $fields, WPooWTestsConsts::PAGE_TYPE_ADD);
        $publishBtn = $this->driver->findElement(WebDriverBy::id("publish"));
        $this->driver->executeScript("arguments[0].scrollIntoView(false)", [$publishBtn]);
        $publishBtn->click();
        $this->waitForPageToLoad();
        $postId =  sprintf('post-%s',$this->driver->findElement(WebDriverBy::id('post_ID'))->getAttribute('value'));

        $this->navigateToPostTypeMenuItem($postTypeID);

        if ($initialCount + 1 != $this->getPageCount()) {
            return null;
        }

        array_push($this->addedPostsToDelete, [$postTypeID, $postId]);
        return $postId;
    }

    protected function editPost($postTypeID, $postId, $fields = [])
    {

        $this->goToEditPage($postTypeID, $postId);
        $this->insertValuesToPostTypeForm($postTypeID, $fields, WPooWTestsConsts::PAGE_TYPE_EDIT);
        $this->driver->findElement(WebDriverBy::id("publish"))->click();

        try {
            $this->driver->wait(10, 1000)->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('message'))
            );

            //TODO: Localization, and case insentivity
            if (strpos($this->getElementInnerText($this->driver->findElement(WebDriverBy::xpath("//div[@id='message']/p"))), 'Post updated') !== false) {
                $this->navigateToPostTypeMenuItem($postTypeID);
                return $postId;
            }
        } catch (NoSuchElementException $e) {
            //TODO: Log error
        }
        return $postId;
    }

    protected function deletePost($postTypeID, $postId)
    {
        $this->navigateToPostTypeMenuItem($postTypeID);
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
            $this->navigateToPostTypeMenuItem($postTypeID);
            $fieldCol = $this->driver->findElement(WebDriverBy::xpath("//form[@id='posts-filter']/table/thead/tr/th[@id='${postTypeID}_${field['id']}']"));
            if ($this->getElementInnerText($fieldCol) == $field['label']) {
                return true;
            }
        } catch (NoSuchElementException $e) {
            //TODO: Log
        }
        return false;
    }

    public function getGridEntries($postTypeID, $fields=null, $limit=22){

        $entriesCount = 0;
        $gridEntries= [];
        $this->navigateToPostTypeMenuItem($postTypeID);

        while(true) {
            $entries = $this->driver->findElements(WebDriverBy::xpath("//form[@id = 'posts-filter']/table/tbody/tr"));

            foreach ($entries as $entry) {
                if (++$entriesCount > $limit){
                    break;
                }

                $entryDetail = ['gridEntry' => $entry,
                                'fieldData' => []];

                if ($fields != null) {
                    foreach ($fields as $field) {
                        $postTypeFieldID = "${postTypeID}_${field['id']}";
                        $entryDetail['fieldData'][$field['id']] = $entry->findElement(WebDriverBy::xpath("td[contains(@class, '${postTypeFieldID}')]"));
                    }
                } else {
                    foreach ($entry->findElements(WebDriverBy::xpath("td")) as $entryField) {
                        $fieldID = str_replace($postTypeID, '', explode(' ', $entryField->getAttribute('class'))[0]);
                        $entryDetail['fieldData'][$fieldID] = $entryField;
                    }

                }

                array_push($gridEntries, $entryDetail);


            }

            try {
                $nextPageElement = $this->driver->findElement(WebDriverBy::xpath("//a[@class = 'next-page']"));
                if ($entriesCount <= $limit){
                    $nextPageElement->click();
                }else{
                    break;
                }
            } catch (NoSuchElementException $e) {
                break;
            }

        }

        return $gridEntries;
    }

    public function getGridEntry($postTypeID, $postID, $fields=null){

        $this->navigateToPostTypeMenuItem($postTypeID);
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

        $imagePath = __DIR__ . "/Resources/Images/${imageName}";

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

    public static function createPostType($wpOOW, $postTypeObj, $returnPostType=false){
        $wpOOWTestPostType = $wpOOW->CreatePostType($postTypeObj['id'], $postTypeObj['title'], true);

        foreach ($postTypeObj['fields'] as $field){
            $fieldType = array_key_exists('type', $field) ? $field['type'] : 'text';
            $wpOOWTestPostType->AddField(self::$FIELD_MAP[$fieldType]::createElement($wpOOW, $field));
        }

        return $returnPostType ? $wpOOWTestPostType :  $wpOOWTestPostType->render();
    }

    public static function createMenus($wpOOW, $menuItemsObj){

        foreach ($menuItemsObj as $menuItem){
            $subMenus = [];

            if (array_key_exists('submenus', $menuItem)){
                $subMenus = $menuItem['submenus'];
                unset($menuItem['submenus']);

            }

            $menu = $wpOOW->createMenu(...array_values($menuItem));

            foreach($subMenus as $subMenu){
                if ($subMenu['type'] == WPooWTestsConsts::MENU_TYPE_POSTTYPE) {
                    unset($subMenu["type"]);
                    $menu->AddChild(self::createPostType($wpOOW, $subMenu, true));
                }
                else if ($subMenu['type'] == WPooWTestsConsts::MENU_TYPE_MENU){
                    unset($subMenu["type"]);
                    $menu->AddChild($wpOOW->CreateSubMenu(...array_values($subMenu)));
                }
            }
            $menu->Render();
        }
    }

    public static function getBaseSamplePostTypeData(){
        return [
            'id' => '_wpoow_test_menu',
            'title' => 'WPooW Test Menu'
        ];
    }

    public function checkPermissions($postTypeID, $fields, $pageType, $postID=null){

        if ($pageType == WPooWTestsConsts::PAGE_TYPE_GRID){
            $this->navigateToPostTypeMenuItem($postTypeID);
        }
        else if ($pageType == WPooWTestsConsts::PAGE_TYPE_ADD){
            $this->goToAddPage($postTypeID);
        }
        else if ($pageType == WPooWTestsConsts::PAGE_TYPE_EDIT){
            $this->goToEditPage($postTypeID, $postID);
        }

        foreach ($fields as $field){
            if (array_key_exists('permissions', $field)){
                $fieldType =  array_key_exists('type', $field) ? $field['type'] : 'text';

                if (array_key_exists($fieldType, self::$FIELD_MAP)){
                    $this->elementInputer[$fieldType]->checkPermission($postTypeID, $field, $pageType);
                }

            }
        }
    }

    private function insertValuesToPostTypeForm($postTypeID, $fields, $pageType)
    {
        foreach ($fields as $field) {
            if (array_key_exists('test_value', $field)) {
                $fieldType = array_key_exists('type', $field) ? $field['type'] : 'text';
                $elementInputer =  array_key_exists($fieldType, self::$FIELD_MAP) ? $this->elementInputer[$fieldType] : $this->elementInputer[WPooWTestsElements::TEXT];

                if (array_key_exists('permission', $field)  && !$elementInputer->checkPermission($postTypeID, $field, $pageType, true)) {
                    return;
                }

                $postbox = $this->findElementWithWait(WebDriverBy::xpath("//div[@id='${postTypeID}_${field['id']}' and contains(@class,'postbox')]"));
                $this->driver->executeScript("arguments[0].scrollIntoView(false)", [$postbox]);
                $elementInputer->inputValue($postTypeID, $field);


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
