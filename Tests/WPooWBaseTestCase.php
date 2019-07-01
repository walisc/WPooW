<?php

namespace WPooWTests;

use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\WebDriverBy;
use mysql_xdevapi\Exception;
use WPSelenium\WPSTestCase;

class WPooWBaseTestCase extends WPSTestCase{

    protected function LocatedMenuItem($id, $title){
        try {
            $driver = $this->GetSeleniumDriver();
            $menu_item_name = $driver->findElement(WebDriverBy::xpath("//div[@class='wp-menu-name' and text()='${title}']"));
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
            throw new Exception("Cannnot navigate"); #TODO change this to own exception
        }
        $menu_item["li"]->click();
    }
}