<?php

namespace WPooW\APIs;

use WPooW\Core\PageTypes\PostType;
use WPooW\Core\PageTypes\StaticPage;

class PageTypesApi {

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

    public function CreateStaticPage($page_type, $path_content, $data=[]){
        return new StaticPage($page_type, $path_content, $data);
    }       

}