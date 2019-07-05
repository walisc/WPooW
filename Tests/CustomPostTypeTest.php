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
                 'label' => 'Sample Text Field',
                 'test_value' => 'Sample Text'
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
         $this->assertTrue($this->PublishPostType(self::$sample_post_type1['id']) != null);
     }

     public static function CanPublishPostType_WP_BeforeRun(){
         $wpOOW = new wpAPI();
         $wpOOWTestPage = $wpOOW->CreatePostType(self::$sample_post_type1['id'], self::$sample_post_type1['title'], true);
         $wpOOWTestPage->render();
     }


     /**
      * @browserRequired
      * @WP_BeforeRun AddField_WP_BeforeRun
      */
    function testCanAddField(){
        $this->loginToWPAdmin();
        $this->NavigateToMenuItems(self::$sample_post_type1['id']);
        $FieldInPostTypeGrid= $this->HasFieldInPostTypeGrid(self::$sample_post_type1['id'], self::$sample_post_type1['fields'][0]);
        $FieldInPostTypeAddForm = $this->HasFieldInPostTypeAddForm(self::$sample_post_type1['id'], self::$sample_post_type1['fields'][0]);
        //$checkIfFieldIsThere = $this->HasFieldInPostTypeGrid(self::$sample_post_type1['id'], self::$sample_post_type1['fields'][0]);
        $this->assertTrue($FieldInPostTypeGrid && $FieldInPostTypeAddForm);

    }


    public static function AddField_WP_BeforeRun(){
         $wpOOW = new wpAPI();
         $wpOOWTestPage = $wpOOW->CreatePostType(self::$sample_post_type1['id'], self::$sample_post_type1['title'], true);
         $wpOOWTestPage->AddField(new Text( self::$sample_post_type1['fields'][0]['id'] ,  self::$sample_post_type1['fields'][0]['label']));
         $wpOOWTestPage->render();
    }

    /**
     * @browserRequired
     * @WP_BeforeRun AddField_WP_BeforeRun
     */
    function testCanEditPostType(){
        $this->loginToWPAdmin();
        $postID = $this->PublishPostType(self::$sample_post_type1['id'], self::$sample_post_type1['fields']);
        $this->EditPost(self::$sample_post_type1['id'], $postID, self::$sample_post_type1['fields']);
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
