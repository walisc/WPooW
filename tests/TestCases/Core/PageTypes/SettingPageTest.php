<?php
declare(strict_types=1);

use WPooWTests\TestCases\BaseTestCase;
use WPooW\Utilities\CONSTS;

final class SettingPageTest extends BaseTestCase{

    function LoadElements($WPooW)
    {
        $wc_cm_setting_new = $WPooW->PageTypesApi->CreateSettingsPage("_wc_setting3",  "Settings344", CONSTS::WP_AUTH_MANAGE_OPTIONS, "Test Setting", "test setting for wpoow settings");
        $newSection = $wc_cm_setting_new->AddSection("_secone", "Section One");
        $newSection->AddField( new Text("_name", "Name"));
        $newSection->AddField( new TextArea("_description", "Description"));
        $newSection->AddField( new Uploader("_logo", "Logo"));
        $newSection->AddField( new Text("_url", "Url"));
        $wc_cm_setting_new->Render();
    }


    protected function setUp()
    {
        $this->seleniumDriver->get("http://www.google.com");
    }
    


    function testCanCreateSettingPage(): void{

    }

    function testCanAddSectionPage(){

    }

    function testCanAddFieldsToSectionDirectly(){

    }

    function testCanAddFieldsToSectionIndividually(){

    }

    function testCanAddFieldsWithOutSection(){

    }

    function testCanSaveSettings(){

    }

    function testBeforeSaveFunction(){

    }

    function testUpdateSettingPageStyle(){

    }

    function testCustomSettingTemplate(){
        
    }



}