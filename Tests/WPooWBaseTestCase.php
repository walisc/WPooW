<?php

namespace WPooWTests;

use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\WebDriverBy;
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
        if (!strpos($this->driver->getCurrentURL(), "edit.php?post_type=${id}")){
            return false;
        }
        return true;
    }



    protected function PublishPostType($id){
        $this->NavigateToMenuItems($id);
        $initial_count = $this->GetPageCount();

        $this->GoToAddPage($id);

        $publish_button = $this->driver->findElement(WebDriverBy::id("publish"));
        $publish_button->click();

        $this->NavigateToMenuItems($id);

        if ($initial_count+1 == $this->GetPageCount()){
            return true;
        }
        return false;
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

    protected function HasFieldInPostTypeAddForm(){

    }

    protected function HasFieldInPostTypeEditForm(){

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