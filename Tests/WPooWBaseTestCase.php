<?php

namespace WPooWTests;

use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use WPSelenium\WPSTestCase;

class WPooWBaseTestCase extends WPSTestCase
{
    protected function locatedMenuItem($id, $title=null)
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

    protected function navigateToMenuItems($id)
    {
        $menuItem = $this->locatedMenuItem($id);
        $menuItem["li"]->click();
        $this->waitForPageToLoad();
    }

    protected function addPost($postTypeID, $fields=[])
    {
        $this->navigateToMenuItems($postTypeID);
        $initialCount = $this->getPageCount();

        $this->goToAddPage($postTypeID);
        $this->insertValuesToPostTypeForm($postTypeID, $fields);
        $this->driver->findElement(WebDriverBy::id("publish"))->click();

        $this->navigateToMenuItems($postTypeID);

        if ($initialCount+1 != $this->getPageCount()) {
            return null;
        }
        return $this->driver->findElement(WebDriverBy::xpath("//form[@id='posts-filter']/table/tbody/tr"))->getAttribute('id');
    }

    protected function editPost($postTypeID, $postId, $fields=[])
    {

        $this->goToEditPage($postTypeID, $postId);
        $this->insertValuesToPostTypeForm($postTypeID, $fields);
        $this->driver->findElement(WebDriverBy::id("publish"))->click();

        try {
            $this->driver->wait(10, 1000)->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('message'))
            );

            //TODO: Localization, and case insentivity
            return strpos($this->getElementInnerText($this->driver->findElement(WebDriverBy::xpath("//div[@id='message']/p"))), 'Post updated') !== false;
        } catch (NoSuchElementException $e) {
            //TODO: Log error
        }
        return false;
    }

    protected function deletePost($postTypeID, $postId, $fields=[])
    {
        $this->navigateToMenuItems($postTypeID);
        $initialCount = $this->getPageCount();

        $thePost = $this->driver->findElement(WebDriverBy::xpath("//tr[@id='${postId}']"));
        $deleteLink = $thePost->findElement(WebDriverBy::xpath("descendant::span[@class='trash']/a"));
        $this->GetWebPage($deleteLink->getAttribute('href'));

        if ($initialCount-1 != $this->getPageCount()) {
            return false;
        }
        return true;
    }

    protected function hasFieldInPostTypeGrid($postTypeID, $field)
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

    protected function getElementOnPostTypePage($postTypeID, $field, $fieldIDTag='')
    {
        $postTypeFieldID = "${postTypeID}_${field['id']}";
        $postbox = $this->driver->findElement(WebDriverBy::xpath("//div[@id='${postTypeFieldID}']"));

        if (strpos($postbox->getAttribute('class'), 'postbox') === false) {
            return null;
        }

        $input = $postbox->findElement(WebDriverBy::xpath("descendant::input[@id='${postTypeFieldID}${fieldIDTag}']"));
        if (array_key_exists('type', $field) && $input->getAttribute('type') != $field['type']) {
            return null;
        }
        return $input;
    }

    public static function uploadTestFile($imageName){
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . WPINC . '/post.php' );
        require_once(ABSPATH . WPINC . '/pluggable.php' );

        global $wpdb;

        $imagePath = __DIR__. "/images/${imageName}";

        $uploadDir  = WP_CONTENT_DIR . '/uploads';
        $newFilePath = "${uploadDir}/${imageName}";

        if (!file_exists($newFilePath)){
            copy($imagePath, $newFilePath);

        }

        $attachment = array(
            'guid' => $newFilePath,
            'post_mime_type' =>  wp_get_image_mime( $imagePath ),
            'post_title' => preg_replace('/\.[^.]+$/', '', basename( $imageName ) ),
            'post_content' => '',
            'post_status' => 'inherit'
        );


        if ($wpdb->get_var( $wpdb->prepare( sprintf("SELECT ID FROM $wpdb->posts WHERE post_title='%s' AND post_type='attachment'", $attachment['post_title']), [] ) ) == null){

            $id = wp_insert_attachment( $attachment, basename( $imageName ), 0 ,true );
            if ( !is_wp_error($id) ) {
                wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $newFilePath ) );
            }
        }
    }

    private function insertValuesToPostTypeForm($postTypeID, $fields)
    {
        foreach ($fields as $field) {
            if (array_key_exists('test_value', $field)) {
                $postTypeFieldID = "${postTypeID}_${field['id']}";
                $input = $this->driver->findElement(WebDriverBy::xpath("//input[@id='${postTypeFieldID}']"));

                //TODO: Create field type processor
                $input->click();
                $this->driver->getKeyboard()->sendKeys($field['test_value']);
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
