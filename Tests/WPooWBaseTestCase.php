<?php

namespace WPooWTests;

use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\WebDriverBy;
use mysql_xdevapi\Exception;
use WPSelenium\WPSTestCase;

class WPooWBaseTestCase extends WPSTestCase{

    protected function LocatedMenuItem($id, $title){
        try {

            $menu_item_name = $this->driver->findElement(WebDriverBy::xpath("//div[@class='wp-menu-name' and text()='${title}']"));
            $menu_item_name_li = $menu_item_name->findElement(WebDriverBy::xpath("ancestor::li[1]"));
            if ($menu_item_name_li->getAttribute('id') == "menu-posts-${id}")
            {
                return [
                    "text" => $menu_item_name,
                    "link" => $menu_item_name->findElement(WebDriverBy::xpath("ancestor::a[1]")),
                    "li" => $menu_item_name_li
                ];
            }
        }catch (NoSuchElementException $e){
            return null;
        }
        return null;
    }

    protected function NavigateToMenuItems($id, $title)
    {
        $menu_item = $this->LocatedMenuItem($id, $title);
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

    protected function PublishPostType($id, $title){
        $this->NavigateToMenuItems($id, $title);


        $this->waitForPageToLoad();
        $driver = $this->GetSeleniumDriver();

        $initial_count = $this->GetPageCount();
        $page_heading = $driver->findElement(WebDriverBy::xpath("//h1[@class='wp-heading-inline' and text()='${title}']"));
        $add_button = $page_heading->findElement(WebDriverBy::xpath("following-sibling::a[@class='page-title-action']"));
        $add_button->click();
        $publish_button = $driver->findElement(WebDriverBy::id("publish"));
        $publish_button->click();
        $this->NavigateToMenuItems($id, $title);
        if ($initial_count+1 == $this->GetPageCount()){
            return true;
        }
        return false;
    }
}