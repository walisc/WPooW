<?php



use WPooWTests\WPooWBaseTestCase;

include __DIR__.'/../wpAPI.php';

 class CustomPostTypeTest extends WPooWBaseTestCase{

     private static $sample_post_type1 = [
         'id' => '_wpoow_test_menu',
         'title' => 'WPooW Test Menu'
     ];

     /**
      * @browserRequired
      * @WP_BeforeRun IntializeWPoowWithAnyErrors_WP_BeforeRun
      */
     function testIntializeWPoowWithAnyErrors(){
         $this->loginToWPAdmin();
         $this->assertFalse(strpos($this->driver->getPageSource(), 'Stack trace'));
     }

     public static function IntializeWPoowWithAnyErrors_WP_BeforeRun(){
         $wpOOW = new wpAPI();

     }

     /**
      * @browserRequired
      * @WP_BeforeRun CanCreatePostType_WP_BeforeRun
      */
     function testCanCreatePostType()
     {
         $this->loginToWPAdmin();
         $menuitem = $this->LocatedMenuItem(self::$sample_post_type1['id'], self::$sample_post_type1['title']);
         $this->assertTrue($menuitem != null);
         $this->assertTrue($this->NavigateToMenuItems(self::$sample_post_type1['id'], self::$sample_post_type1['title']));
     }

     public static function CanCreatePostType_WP_BeforeRun(){
         $wpOOW = new wpAPI();
         $wpOOWTestPage = $wpOOW->CreatePostType(self::$sample_post_type1['id'], self::$sample_post_type1['title'], true);
         $wpOOWTestPage->render();
     }

     /**
      * @browserRequired
      * @WP_BeforeRun CanPublishPostType_WP_BeforeRun
      */
     function testCanPublishPostType(){
         $this->loginToWPAdmin();
         $this->PublishPostType(self::$sample_post_type1['id'], self::$sample_post_type1['title']);
     }

     public static function CanPublishPostType_WP_BeforeRun(){
         $wpOOW = new wpAPI();
         $wpOOWTestPage = $wpOOW->CreatePostType(self::$sample_post_type1['id'], self::$sample_post_type1['title'], true);
         $wpOOWTestPage->render();
     }


     /**
      * @browserRequired
      * @WP_BeforeRun CanAddField_WP_BeforeRun
      */
    function testCanAddField(){
        $this->loginToWPAdmin();
        $this->NavigateToMenuItems(self::$sample_post_type1['id'], self::$sample_post_type1['title']);
    }

    public static function CanAddField_WP_BeforeRun(){
         $wpOOW = new wpAPI();
         $wpOOWTestPage = $wpOOW->CreatePostType(self::$sample_post_type1['id'], self::$sample_post_type1['title'], true);
         $wpOOWTestPage->AddField(new Text("_test_text_field" , "Sample Text Field"));
         $wpOOWTestPage->render();
    }

    /**
     * @browserNotRequired
     */
    function testCanRegisterSaveEvent(){
        //check objects
    }




    /**
     * @browserRequired
     * @WP_BeforeRun testCanEditPostType_WP_BeforeRun
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
