<?php

namespace WPooW\APIs;

use WPooW\Core\PageTypes\PostType;
use WPooW\Core\PageTypes\SubMenu;
use WPooW\Core\PageTypes\Menu;

class PageTypesApi {

    
    public function CreateStaticPage(){

    }

    /**
     *
     * Create a new post-type page with a sub menu link that can be added to the wpAPI wrapper Menu
     *
     * @param $page_slug
     * @param $title
     * @param bool $persist
     * @return PostType
     */
    public function CreatePostType($page_slug, $title, $persist=false, $options=[])
    {
        return new PostType($page_slug, $title , $persist, $options);

    }
    

}