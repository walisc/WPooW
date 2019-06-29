<?php



use WPSelenium\WPSTestCase;
include_once __DIR__.'/../wpAPI.php';

 class CustomPostTypeTest extends WPSTestCase{

    /**
     * @browserNotRequired
     */
    function testCanCreatePostType(){
    }

    /**
     * @browserNotRequired
     */
    function testCanAddField(){

    }

    /**
     * @browserNotRequired
     */
    function testCanRegisterSaveEvent(){
        //check objects
    }


    /**
     * @browserRequired
     * @WP_BeforeRun: testPostTypeCreate_WP_BeforeRun
     */
    function testPostTypeCreate()
    {  
        // check post types created
        // check names
        // cehck can navigate
    }

    /**
     * @browserRequired
     * @WP_BeforeRun testPostTypeCreate_WP_BeforeRun
     */
    function testCanAddPostType(){
        $this->driver = $this->GetSeleniumDriver();
        $this->loginToWPAdmin();
        sleep(5);
        // test can click add
        // Test can enter text(text)
        // test cansselfself save
        // test can go back
        // test items there
    }

    public static function testPostTypeCreate_WP_BeforeRun(){
        error_log("inthere");
    }

    /**
     * @browserRequired
     * @WP_BeforeRun: testCanEditPostType_WP_BeforeRun
     */
    function testCanEditPostType(){
        // click on one
        // edict text
        // save
        //check saving
        // go back
    }


    /**
     * @browserRequired
     * @WP_BeforeRun: testCanDeletePostType_WP_BeforeRun
     */
    function testCanDeletePostType(){
        // click on delete        // edict text
        // save
        //check saving
        // go back
    }


}
