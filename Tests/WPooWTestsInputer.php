<?php


namespace WPooWTests;

use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverSelect;


class WPooWTestsElements{
    CONST TEXT = 'text';
    CONST UPLOADER = 'uploader';
    CONST SELECT = 'select';
    CONST MULTISELECT = 'multiselect';
    CONST RICHTEXTAREA = 'richtextarea';
    CONST CHECKBOX = 'checkbox';
    CONST TEXTAREA = 'textarea';
}

class WPooWTestsConsts{
    CONST PAGE_TYPE_GRID = \wpAPIPermissions::ViewTable;
    CONST PAGE_TYPE_ADD = \wpAPIPermissions::AddPage;
    CONST PAGE_TYPE_EDIT = \wpAPIPermissions::EditPage;
    CONST PERMISSIONS_ALL = 'PERMISSIONS_ALL';
    CONST PERMISSIONS_READ = 'PERMISSION_READ';
    CONST PERMISSIONS_CREATE = 'PERMISSION_CREATE';
    CONST PERMISSIONS_UPDATE = 'PERMISSIONS_UPDATE';
    CONST MENU_TYPE_POSTTYPE = 'MENU_TYPE_POSTTYPE';
    CONST MENU_TYPE_MENU = 'MENU_TYPE_MENU';
}


trait WPooWTestsInputer
{
    public $elementInputer = [];

    public static $FIELD_MAP = [
        WPooWTestsElements::TEXT => TextInputer::class,
        WPooWTestsElements::UPLOADER => UploaderInputer::class,
        WPooWTestsElements::SELECT => SelectInputer::class,
        WPooWTestsElements::MULTISELECT => MultiSelectorInputer::class,
        WPooWTestsElements::CHECKBOX => CheckboxInputer::class,
        WPooWTestsElements::TEXTAREA => TextAreaInputer::class,
        WPooWTestsElements::RICHTEXTAREA => RichTextInputer::class,
    ];

    public function setUpElementInputer(){

        foreach (self::$FIELD_MAP as $fieldType => $class){
            $this->elementInputer[$fieldType] = new $class($this);
        }
    }
}

interface ElementInputer{
    function assetValueEqual($sampleField, $fieldValue);
    function inputValue($postTypeID, $field);

    //cru
    //cr
    //c
    //ru
    //u
    //cu
    function checkPermission($sampleField, $fieldValue, $pageType, $returnCanEdit=false);
    static function createElement($wpOOW, $field);

}
class WPooWInputerBase{
    protected $parent;
    protected $driver;
    protected $isEditable = true;

    use WPooWTestPermissionsHelper;

    function __construct($parent)
    {
       $this->parent = $parent;
       $this->driver = $parent->GetSeleniumDriver();
    }

    static function processExtraArgs($field){
        $extraArgs = array_key_exists('extra_args', $field) ? $field['extra_args'] : [];
        if (array_key_exists('permissions', $field))
        {
            array_unshift($extraArgs, $field['permissions']);
        }
        return $extraArgs;
    }

}

class BaseTextInputer extends WPooWInputerBase{
    function assetValueEqual($sampleField, $fieldValue)
    {
        if ($this->isEditable){
            $this->parent->assertTrue($sampleField['test_value'] == $fieldValue->GetText());
        }
        else{
            $this->parent->assertTrue(true);
        }
    }

    function getSelector($postTypeID, $field){
        $postTypeFieldID = "${postTypeID}_${field['id']}";
        $selector = $field['type'] == 'textarea' ? 'textarea' : 'input';
        return WebDriverBy::xpath("//${selector}[@id='${postTypeFieldID}']");
    }

    function inputValue($postTypeID, $field)
    {
        $input = $this->driver->findElement($this->getSelector($postTypeID, $field));
        $input->clear();
        $input->click();
        $this->driver->getKeyboard()->sendKeys($field['test_value']);
    }

    function checkPermission($postTypeID, $field, $pageType, $returnCanEdit=false)
    {
        return $this->checkPermissionsText($postTypeID, $field, $pageType, $returnCanEdit);
    }
}
class TextInputer extends BaseTextInputer implements ElementInputer {

    static function createElement($wpOOW, $field)
    {
        return new \Text($field['id'], $field['label'], ...self::processExtraArgs($field));
    }
}

class TextAreaInputer extends BaseTextInputer implements ElementInputer {

    static function createElement($wpOOW, $field)
    {
        return new \TextArea($field['id'], $field['label'], ...self::processExtraArgs($field));
    }
}


class RichTextInputer extends WPooWInputerBase implements ElementInputer{

    function assetValueEqual($sampleField, $fieldValue)
    {
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


        $this->parent->assertEqualXMLStructure($expectedDom->getElementsByTagName('body')->item(0), $actualDomBody);
    }

    function inputValue($postTypeID, $field)
    {
        $postTypeFieldID = "${postTypeID}_${field['id']}";
        $this->driver->switchTo()->frame("tinymce_${postTypeFieldID}_ifr");

        $richTextFrame = $this->parent->findElementWithWait(WebDriverBy::xpath("//body[@id='tinymce']"));
        $this->driver->executeScript("arguments[0].innerHTML = '${field['test_value']}'", [$richTextFrame]);

        $this->driver->switchTo()->defaultContent();
    }

    function checkPermission($postTypeID, $field, $pageType, $returnCanEdit=false)
    {
        return true;
    }

    static function createElement($wpOOW, $field)
    {
        return new \RichTextArea($field['id'], $field['label']);
    }
}

class UploaderInputer extends WPooWInputerBase implements ElementInputer{

    function assetValueEqual($sampleField, $fieldValue)
    {
        // TODO: Implement assetValueEqual() method.
    }

    function inputValue($postTypeID, $field)
    {
        $uploadButton = $this->parent->getElementOnPostTypePage($postTypeID, $field, '_upload_button');
        $uploadButton->click();

        $mediaModal = null;

        foreach($this->driver->findElements(WebDriverBy::xpath("//div[contains(@class,'media-modal')]")) as $modal){
            if ($modal->isDisplayed()){
                $mediaModal = $modal;
                break;
            }
        };


        foreach ($field['test_value'] as $imageName) {
            $this->parent->findElementWithWait(WebDriverBy::xpath("descendant::ul[contains(@class,'attachments')]/descendant::li[@aria-label='${imageName}']"), $mediaModal)->click();
        }

        $this->parent->findElementWithWait(WebDriverBy::xpath("descendant::button"), $mediaModal)->click();
    }

    function checkPermission($postTypeID, $field, $pageType, $returnCanEdit=false)
    {
        return true;
    }

    static function createElement($wpOOW, $field)
    {
        $extraArgs = array_key_exists('extra_args', $field) ? $field['extra_args'] : [];
        return new \Uploader($field['id'], $field['label'], ...array_values($extraArgs));
    }
}

class SelectInputer extends WPooWInputerBase implements ElementInputer{

    function assetValueEqual($sampleField, $fieldValue)
    {
        $this->parent->assertTrue(implode(', ', array_values($sampleField['test_value'])) == $fieldValue->GetText());
    }

    function inputValue($postTypeID, $field)
    {
        $postTypeFieldID = "${postTypeID}_${field['id']}";
        $selectInput = new WebDriverSelect($this->driver->findElement(WebDriverBy::xpath("//select[@id='${postTypeFieldID}']")));

        $testValue = array_keys($field['test_value'])[0];

        foreach ($selectInput->getOptions() as $option) {
            if ($option->getText() == $testValue) {
                if (!$option->isSelected()) {
                    $option->click();
                }
            }
        }

    }

    function checkPermission($postTypeID, $field, $pageType, $returnCanEdit=false)
    {
        return true;
    }

    static function createElement($wpOOW, $field)
    {
        $extraArgs = array_key_exists('extra_args', $field) ? $field['extra_args'] : [];
        return new \Select($field['id'], $field['label'], ...array_values($extraArgs));
    }
}

class MultiSelectorInputer extends WPooWInputerBase implements ElementInputer{

    function assetValueEqual($sampleField, $fieldValue)
    {
        $this->parent->assertTrue(implode(', ', array_values($sampleField['test_value'])) == $fieldValue->GetText());
    }

    function inputValue($postTypeID, $field)
    {
        $postTypeFieldID = "${postTypeID}_${field['id']}";
        $selectInput = new WebDriverSelect($this->driver->findElement(WebDriverBy::xpath("//select[@id='${postTypeFieldID}']")));
        $selectInput->deselectAll();

        foreach ($field['test_value'] as $key => $value){
            $selectInput->selectByValue($key);
        }
    }

    function checkPermission($postTypeID, $field, $pageType, $returnCanEdit=false)
    {
        return true;
    }

    static function createElement($wpOOW, $field)
    {
        $extraArgs = array_key_exists('extra_args', $field) ? $field['extra_args'] : [];
        return new \MultiSelect($field['id'], $field['label'], ...array_values($extraArgs));
    }
}

class CheckboxInputer extends WPooWInputerBase implements ElementInputer{

    function assetValueEqual($sampleField, $fieldValue)
    {
        $this->parent->assertTrue($sampleField['test_value'] == $fieldValue->findElement(WebDriverBy::xpath("input[@type = 'checkbox']"))->isSelected());
    }

    function inputValue($postTypeID, $field)
    {
        $postTypeFieldID = "${postTypeID}_${field['id']}";
        $input = $this->driver->findElement(WebDriverBy::xpath("//input[@id='${postTypeFieldID}' and @type='checkbox']"));

        if (($field['test_value'] && !$input->isSelected()) || (!$field['test_value'] && $input->isSelected())) {
            $input->click();
        }
    }

    function checkPermission($postTypeID, $field, $pageType, $returnCanEdit=false)
    {
        return true;
    }

    static function createElement($wpOOW, $field)
    {
        return new \Checkbox($field['id'], $field['label']);
    }
}