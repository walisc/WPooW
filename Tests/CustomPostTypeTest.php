<?php



use WPooWTests\WPooWBaseTestCase;

include __DIR__.'/../wpAPI.php';

 class CustomPostTypeTest extends WPooWBaseTestCase{

     private static $sample_post_type1 = [
         'id' => '_wpoow_test_menu',
         'title' => 'WPooW Test Menu',
         'fields' => [
             [
                 'id' => '_test_text_field',
                 'label' => 'Sample Text Field'
             ]
         ]
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
         if ($menuitem){
             $this->assertTrue($this->NavigateToMenuItems(self::$sample_post_type1['id']));
         }

     }

     public static function CanCreatePostType_WP_BeforeRun(){
         $wpOOW = new wpAPI();
         $wpOOWTestPage = $wpOOW->CreatePostType(self::$sample_post_type1['id'], self::$sample_post_type1['title'], true);
         $wpOOWTestPage->render();
     }

     /**
      * @browserRequired$checkIfFieldIsThere
      * @WP_BeforeRun CanPublishPostType_WP_BeforeRun
      */
     function testCanPublishPostType(){
         $this->loginToWPAdmin();
         $this->assertTrue($this->PublishPostType(self::$sample_post_type1['id']));
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
        $this->NavigateToMenuItems(self::$sample_post_type1['id']);
        $checkIfFieldIsThere = $this->HasFieldInPostTypeGrid(self::$sample_post_type1['id'], self::$sample_post_type1['fields'][0]);
        $this->assertTrue($checkIfFieldIsThere);

    }


    public static function CanAddField_WP_BeforeRun(){
         $wpOOW = new wpAPI();
         $wpOOWTestPage = $wpOOW->CreatePostType(self::$sample_post_type1['id'], self::$sample_post_type1['title'], true);
         $wpOOWTestPage->AddField(new Text( self::$sample_post_type1['fields'][0]['id'] ,  self::$sample_post_type1['fields'][0]['label']));
         $wpOOWTestPage->render();
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
