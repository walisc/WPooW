<?php


namespace WPooWTests;

use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Interactions\WebDriverActions;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverKeys;
use Facebook\WebDriver\WebDriverSelect;

trait WPooWTestsInputer
{

    public function inputText($postTypeID, $field)
    {
        $postTypeFieldID = "${postTypeID}_${field['id']}";
        $selector = $field['type'] == 'textarea' ? 'textarea' : 'input';
        $input = $this->driver->findElement(WebDriverBy::xpath("//${selector}[@id='${postTypeFieldID}']"));
        $input->clear();
        $input->click();
        $this->driver->getKeyboard()->sendKeys($field['test_value']);
    }

    public function inputRichTextArea($postTypeID, $field){

        $postTypeFieldID = "${postTypeID}_${field['id']}";
        $this->driver->switchTo()->frame("tinymce_${postTypeFieldID}_ifr");

        $richTextFrame = $this->findElementWithWait(WebDriverBy::xpath("//body[@id='tinymce']"));
        $this->driver->executeScript("arguments[0].innerHTML = '${field['test_value']}'", [$richTextFrame]);

        $this->driver->switchTo()->defaultContent();
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

    public function inputCheckbox($postTypeID, $field){
        $postTypeFieldID = "${postTypeID}_${field['id']}";
        $input = $this->driver->findElement(WebDriverBy::xpath("//input[@id='${postTypeFieldID}' and @type='checkbox']"));

        if (($field['test_value'] && !$input->isSelected()) || (!$field['test_value'] && $input->isSelected())){
            $input->click();
        }
    }

    public function assertSelectValueEqual($sampleField, $fieldValue){
        $this->assertTrue(implode(', ', array_values($sampleField['test_value'])) == $fieldValue->GetText());
    }

    public function assertTextValueEqual($sampleField, $fieldValue){
        $this->assertTrue($sampleField['test_value'] == $fieldValue->GetText());
    }

    public function assertRichTextAreaValueEqual($sampleField, $fieldValue){
        $expectedDom = new \DomDocument();
        $expectedDom->loadHTML($sampleField['test_value']);
        $expectedDom->preserveWhiteSpace = false;

        $actualDom = new \DomDocument();
        $actualDom->loadHTML($fieldValue->getAttribute('innerHTML'));
        $actualDom->preserveWhiteSpace = false;

        $actualDomBody = $actualDom->getElementsByTagName('body')->item(0);


        try{
            $fieldValue->findElement(WebDriverBy::xpath("descendant::div[@class = 'row-actions']"));
            $actualDomBody->removeChild($actualDomBody->lastChild);
            $actualDomBody->removeChild($actualDomBody->lastChild);
        }
        catch(NoSuchElementException $e){}


        $this->assertEqualXMLStructure($expectedDom->getElementsByTagName('body')->item(0), $actualDomBody);
    }

    public function assertCheckboxValueEqual($sampleField, $fieldValue){
        $this->assertTrue($sampleField['test_value'] == $fieldValue->findElement(WebDriverBy::xpath("input[@type = 'checkbox']"))->isSelected());
    }

}