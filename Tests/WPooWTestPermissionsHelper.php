<?php


namespace WPooWTests;

use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\WebDriverBy;

trait WPooWTestPermissionsHelper{

    protected function checkFieldPermissions($gridClosureFunc, $addClosureFunc, $editClosureFunc, $field, $pageType){
        $permissions = $this->getFieldPermissions($field, $pageType);


        if (array_key_exists(WPooWTestsConsts::PERMISSIONS_ALL, $permissions)){
            return true;
        }

        if ($pageType == WPooWTestsConsts::PAGE_TYPE_GRID)
        {
            $this->parent->assertTrue($gridClosureFunc($permissions));
        }
        else if ($pageType == WPooWTestsConsts::PAGE_TYPE_ADD){
            $this->parent->assertTrue($addClosureFunc($permissions));
        }
        else if ($pageType == WPooWTestsConsts::PAGE_TYPE_EDIT){
            $this->parent->assertTrue($editClosureFunc($permissions));
        }


        return $this->isEditable;
    }


    private function getFieldPermissions($field, $pageType){

        $permissionArray = [];

        if (!array_key_exists('permissions', $field)){
            return [WPooWTestsConsts::PERMISSIONS_ALL];
        }
        if (!array_key_exists($pageType, $field['permissions'])){
            return [WPooWTestsConsts::PERMISSIONS_ALL];
        }
        else{
            $permissionString = $field['permissions'][$pageType];
            if (strpos($permissionString, 'c') !== false){
                array_push($permissionArray, WPooWTestsConsts::PERMISSIONS_CREATE);
            }
            if (strpos($permissionString, 'r') !== false){
                array_push($permissionArray, WPooWTestsConsts::PERMISSIONS_READ);
            }
            if (strpos($permissionString, 'u') !== false){
                array_push($permissionArray, WPooWTestsConsts::PERMISSIONS_UPDATE);
            }
        }
        return $permissionArray;
    }


    public function checkPermissionsText($postTypeID, $field, $pageType, $returnCanEdit=false){

        $gridPageClosureFunc = function($permissions) use ($field, $pageType) {
            return true;

        };

        $addPageClosureFunc = function($permissions) use ($postTypeID, $field, $pageType) { //On Add Page
            if (in_array(WPooWTestsConsts::PERMISSIONS_CREATE, $permissions) || in_array(WPooWTestsConsts::PERMISSIONS_UPDATE, $permissions)){
                // find element but should be editable
                $this->isEditable = true;
                try {
                    $this->driver->findElement($this->getSelector($postTypeID, $field));
                    return true;
                }catch (NoSuchElementException $e){
                    return false;
                }
            }else if (in_array(WPooWTestsConsts::PERMISSIONS_READ, $permissions)){
                //find element in read only
                $this->isEditable = false;
                try {
                    $this->driver->findElement($this->getSelector($postTypeID, $field));
                    return true;
                }catch (NoSuchElementException $e){
                    try {
                        $this->driver->findElement(WebDriverBy::xpath("//div[@id='${postTypeID}_${field['id']}' and contains(@class,'postbox')]"));
                        return true;
                    }catch(NoSuchElementException $e){
                        return false;
                    }
                }
            }
            else{
                // should not exists, as not element specified
                $this->isEditable = false;
                try {
                    $this->driver->findElement($this->getSelector($postTypeID, $field));
                    return false;
                }catch (NoSuchElementException $e){
                    return true;
                }
            }
        };

        $editPageClosureFunc = function($permissions) use ($postTypeID, $field, $pageType) {
            return true;
        };

        return $this->checkFieldPermissions($gridPageClosureFunc, $addPageClosureFunc, $editPageClosureFunc , $field, $pageType);

    }

}