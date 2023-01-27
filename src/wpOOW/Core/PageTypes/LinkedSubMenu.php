<?php

namespace wpOOW\Core\PageTypes;


/**
 * Class Menu
 * Custom page which uses the wpAPI_VIEW to render a page
 * This page appears as a Submenu in the wordpress backend and added to the Menu Page
 * 
 * @package wpAPI\Core\PageType
 */
class LinkedSubMenu extends SubMenu
{
    protected $linked_child_type;
    protected $linked_child_id;

    public function __construct($link_slug, $linked_child_type, $linked_child_id, $submenu_title, $capability)
    {
        parent::__construct($link_slug, $submenu_title, $capability, null);
        $this->linked_child_type = $linked_child_type;
        $this->linked_child_id = $linked_child_id;
    }

    public function Render($parent_slug=null)
    {
        parent::Render($parent_slug);
    }

    public function Generate()
    {
        if ($this->parent_slug == '')
        {
            throw new Exception(sprintf("You need to specify the parent for the submenu - %s", $this->slug));
        }
        add_submenu_page($this->parent_slug, $this->menu_title, $this->menu_title,  $this->capability, $this->GetToLink());
    }

    public function GetToLink(){
        $baseUrl = "";

        if ($this->linked_child_type == LinkedSubMenuType::POST_TYPE){
            $baseUrl = "edit.php?post_type";
        }
        else if($this->linked_child_type == LinkedSubMenuType::PAGE){
            $baseUrl = "admin.php?page";
        }
        else{
            throw new \Exception(sprintf("This Linked sub menu type '%s' is unknown", $this->linked_child_type));
        }
        return sprintf("%s=%s", $baseUrl, $this->linked_child_id);
    }
}