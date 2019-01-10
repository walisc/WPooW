<?php

namespace WPooW\APIs;

use WPooW\Core\PageTypes\PostType;
use WPooW\Core\PageTypes\StaticPage;
use WPooW\Core\PageTypes\SettingsPage;

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

    public function CreateStaticPage($page_slug, $page_title, $capabilities, $page_template, $icon = '', $position=null){
        return new StaticPage($page_slug, $page_title, $capabilities, $page_template, $icon, $position);
    }       


    public function CreateSettingsPage($page_slug, $page_title, $capabilities,  $heading="", $description="", $page_template=null, $icon = '', $position=null){
        return new SettingsPage($page_slug, $page_title, $capabilities, $heading, $description, $page_template, $icon, $position);
    }

}