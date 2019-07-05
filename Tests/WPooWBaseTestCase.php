<?php

namespace WPooWTests;

use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use mysql_xdevapi\Exception;
use WPSelenium\WPSTestCase;

class WPooWBaseTestCase extends WPSTestCase{

    protected function LocatedMenuItem($id, $title=null){
        try {
            $menu_item_name_li = $this->driver->findElement(WebDriverBy::id("menu-posts-${id}"));
            $menu_item_name = $menu_item_name_li->findElement(WebDriverBy::xpath("descendant::div[@class='wp-menu-name'][1]"));

            if ($title != null && $title != $menu_item_name->getAttribute("innerText")){
                return null;
            }
            return [
                "text" => $menu_item_name,
                "link" => $menu_item_name->findElement(WebDriverBy::xpath("ancestor::a[1]")),
                "li" => $menu_item_name_li
            ];

        }catch (NoSuchElementException $e){
            //TODO: Log error
        }
        return null;
    }

    protected function NavigateToMenuItems($id)
    {
        $menu_item = $this->LocatedMenuItem($id);
        if ($menu_item == null)
        {
            throw new Exception("Cannot navigate"); #TODO change this to own exception
        }
        $menu_item["li"]->click();
        $this->waitForPageToLoad();
        if (strpos($this->driver->getCurrentURL(), "edit.php?post_type=${id}") === false){
            return false;
        }
        return true;
    }

    private function InsertValuesToPostTypeForm($postTypeID, $fields){
        foreach ($fields as $field){
            if (array_key_exists('test_value', $field)){
                $postTypeFieldID = "${postTypeID}_${field['id']}";
                $input = $this->driver->findElement(WebDriverBy::xpath("//input[@id='${postTypeFieldID}']"));

                //TODO: Create field type processor
                $input->click();
                $this->driver->getKeyboard()->sendKeys($field['test_value']);
            }
        }
    }

    protected function PublishPostType($id, $fields=[]){
        $this->NavigateToMenuItems($id);
        $initial_count = $this->GetPageCount();

        $this->GoToAddPage($id);

        $this->InsertValuesToPostTypeForm($id, $fields);

        $this->driver->findElement(WebDriverBy::id("publish"))->click();

        $this->NavigateToMenuItems($id);

        if ($initial_count+1 != $this->GetPageCount()){
            return null;
        }
        return $this->driver->findElement(WebDriverBy::xpath("//form[@id='posts-filter']/table/tbody/tr"))->getAttribute('id');
    }

    protected function EditPost($postTypeID, $postId, $fields=[]){
        $this->NavigateToMenuItems($postTypeID);

        $thePost = $this->driver->findElement(WebDriverBy::xpath("//tr[@id='${postId}']"));
        $editLink = $thePost->findElement(WebDriverBy::xpath("descendant::span[@class='edit']/a"));


        $this->GetWebPage($editLink->getAttribute('href'));

        $this->InsertValuesToPostTypeForm($postTypeID, $fields);

        $this->driver->findElement(WebDriverBy::id("publish"))->click();

        try {
            $this->driver->wait(10, 1000)->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('message'))
            );

            return strpos( $this->driver->findElement(WebDriverBy::xpath("//div[@id='message']/p"))->getAttribute("innerText"), 'Post updated') !== false; //TODO: Localization, and case insentivity
        }catch (NoSuchElementException $e){
            //TODO: Log
        }
        return false;

    }

    protected function DeletePost($postTypeID, $postId, $fields=[]){
        $this->NavigateToMenuItems($postTypeID);
        $initial_count = $this->GetPageCount();

        $thePost = $this->driver->findElement(WebDriverBy::xpath("//tr[@id='${postId}']"));
        $deleteLink = $thePost->findElement(WebDriverBy::xpath("descendant::span[@class='trash']/a"));
        $this->GetWebPage($deleteLink->getAttribute('href'));


        if ($initial_count-1 != $this->GetPageCount()){
            return false;
        }
        return true;

    }

    protected function HasFieldInPostTypeGrid($postTypeID, $field){
        try {
            $this->GoToViewGridPage($postTypeID);
            $fieldCol = $this->driver->findElement(WebDriverBy::xpath("//form[@id='posts-filter']/table/thead/tr/th[@id='${postTypeID}_${field['id']}']"));
            if ($fieldCol->getAttribute("innerText") == $field['label']) {
                return true;
            }
        }catch(NoSuchElementException $e)
        {
            //TODO: Log
        }
        return false;
    }


    protected function HasFieldInPostTypeAddForm($postTypeID,$field){
        try {
            $this->GoToAddPage($postTypeID);
            return $this->CheckElementExistsOnPostTypePage($postTypeID,$field);
        }catch(NoSuchElementException $e)
        {
            //TODO: Log
        }
        return false;
    }

    protected function HasFieldInPostTypeEditForm(){

    }

    private function  CheckElementExistsOnPostTypePage($postTypeID,$field){
        $postTypeFieldID = "${postTypeID}_${field['id']}";
        $postbox = $this->driver->findElement(WebDriverBy::xpath("//div[@id='${postTypeFieldID}']"));
        if (strpos($postbox->getAttribute('class'), 'postbox') === false){
            return false;
        }

        $input = $postbox->findElement(WebDriverBy::xpath("descendant::input[@id='${postTypeFieldID}']"));

        if (array_key_exists('type', $field) && $input->getAttribute('type') != $field['type']){
            return false;
        }
        return true;
    }


    private function GoToAddPage($id){
        $this->NavigateToMenuItems($id);
        $add_button = $this->driver->findElement(WebDriverBy::xpath("//a[@class='page-title-action' and text()='Add New']")); //TODO: Think about localization
        $add_button->click();
    }

    private function GoToEditPage($id){
        $this->NavigateToMenuItems($id);
    }

    private function GoToViewGridPage($id){
        $this->NavigateToMenuItems($id);

    }
    private function GetPageCount(){
        try{
            $number_of_items = $this->driver->findElement(WebDriverBy::xpath("//form[@id='posts-filter']/descendant::span[@class='displaying-num']"));
            return (int)(str_replace('items', '', $number_of_items->GetText()));
        }
        catch (NoSuchElementException $e){
            //TODO: Log error
        }
        return -1;

    }
}