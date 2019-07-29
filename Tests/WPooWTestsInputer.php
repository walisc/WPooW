<?php


namespace WPooWTests;

use Facebook\WebDriver\Interactions\WebDriverActions;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverKeys;
use Facebook\WebDriver\WebDriverSelect;

trait WPooWTestsInputer
{

    public function inputText($postTypeID, $field)
    {
        $postTypeFieldID = "${postTypeID}_${field['id']}";
        $input = $this->driver->findElement(WebDriverBy::xpath("//input[@id='${postTypeFieldID}']"));
        $input->clear();
        $input->click();
        $this->driver->getKeyboard()->sendKeys($field['test_value']);
        return true;
    }

    public function inputUploader($postTypeID, $field)
    {
        $uploadButton = $this->getElementOnPostTypePage($postTypeID, $field, '_upload_button');
        $uploadButton->click();

        $mediaModal = $this->driver->findElement(WebDriverBy::xpath("//div[contains(@class,'media-modal')]"));

        foreach ($field['test_value'] as $imageName) {
            $this->findElementWithWait(WebDriverBy::xpath("descendant::ul[contains(@class,'attachments')]/descendant::li[@aria-label='${imageName}']"), $mediaModal)->click();
        }

        $this->findElementWithWait(WebDriverBy::xpath("//div[@class='media-toolbar']/descendant::button"), $mediaModal)->click();
    }

    public function inputSelect($postTypeID, $field){
        $postTypeFieldID = "${postTypeID}_${field['id']}";
        $selectInput = $this->driver->findElement(WebDriverBy::xpath("//select[@id='${postTypeFieldID}']"));
        $selectInput->click();
        $testValue = array_keys($field['test_value'])[0];
        $selectInput->findElement(WebDriverBy::xpath("option[text() = '${testValue}']"))->click();
    }


    public function inputMultiSelect($postTypeID, $field){
        $postTypeFieldID = "${postTypeID}_${field['id']}";
        $selectInput = new WebDriverSelect($this->driver->findElement(WebDriverBy::xpath("//select[@id='${postTypeFieldID}']")));
        $selectInput->deselectAll();

        foreach ($field['test_value'] as $key => $value){
            $selectInput->selectByValue($key);
        }

    }

    public function assertSelectValueEqual($sampleField, $fieldValue){
        $this->assertTrue(implode(', ', array_values($sampleField['test_value'])) == $fieldValue->GetText());
    }

    public function assertTextValueEqual($sampleField, $fieldValue){
        $this->assertTrue($sampleField['test_value'] == $fieldValue->GetText());
    }

}