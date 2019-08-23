<?php


namespace WPooWTests;

use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\WebDriverBy;

trait WPooWTestPermissionsHelper{

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

    protected function getSelectorState($selector, $shouldFind){
        try {
            $this->driver->findElement($selector);
            return $shouldFind ? true : false;
        }catch (NoSuchElementException $e) {
            return $shouldFind ? false : true;
        }
    }





    public function checkPermissionsText($postTypeID, $field, $pageType, $returnCanEdit=false){

        $addEditPageClosureFunc = function($permissions, $permissionToCheck) use ($postTypeID, $field, $pageType) {
            // On Add Page
            // only (c)reate, (r)ead and blank apply, hence (u)pdate should not work

            if (in_array($permissionToCheck, $permissions)){
                // find element but should be editable
                $this->isEditable = true;
                return $this->getSelectorState($this->getSelector($postTypeID, $field), true);
            }else if (in_array(WPooWTestsConsts::PERMISSIONS_READ, $permissions)){
                //find element in read only
                $this->isEditable = false;
                return $this->getSelectorState($this->getSelector($postTypeID, $field), false) &&
                    $this->getSelectorState(WebDriverBy::xpath("//div[@id='${postTypeID}_${field['id']}' and contains(@class,'postbox')]"), true);
            }
            else{
                // should not exists, as not element specified
                $this->isEditable = false;
                return $this->getSelectorState($this->getSelector($postTypeID, $field), false);
            }
        };

        $gridPageClosureFunc = function($permissions) use ($postTypeID, $field, $pageType) {
            // On Grid Page
            // only (r)ead and blank apply

            if (in_array(WPooWTestsConsts::PERMISSIONS_READ, $permissions)){
                //find element in read only
                $this->isEditable = false;
                return $this->getSelectorState(WebDriverBy::xpath("//form[@id='posts-filter']/table/thead/tr/th[@id='${postTypeID}_${field['id']}']"), true);
            }
            else{
                // should not exists, as not element specified
                $this->isEditable = false;
                return $this->getSelectorState($this->getSelector($postTypeID, $field), false);
            }

        };

        $addPageClosureFunc = function($permissions) use ($postTypeID, $field, $pageType, $addEditPageClosureFunc) {
            // On Add Page
            // only (c)reate, (r)ead and blank apply, hence (u)pdate should not work
            return $addEditPageClosureFunc($permissions, WPooWTestsConsts::PERMISSIONS_CREATE);

        };

        $editPageClosureFunc = function($permissions) use ($postTypeID, $field, $pageType, $addEditPageClosureFunc) {
            // On Edit Page
            // only (u)date, (r)ead and blank apply, hence (c)reate should not work
            return $addEditPageClosureFunc($permissions, WPooWTestsConsts::PERMISSIONS_UPDATE);
        };

        return $this->checkFieldPermissions($gridPageClosureFunc, $addPageClosureFunc, $editPageClosureFunc , $field, $pageType);

    }

}