<?php


/**
 * Class Menu
 * Custom page which uses the wpAPI_VIEW to render a page
 * This page appears as a Submenu in the wordpress backend and added to the Menu Page
 * 
 * @package wpAPI\Core\PageType
 */
class Menu extends SubMenu
{

    protected $icon;
    protected $position;

    public function __construct($page_slug, $menu_title, $capability, $display_path_content, $icon = '', $position=null)
    {
        parent::__construct($page_slug, $menu_title, $capability, $display_path_content);
        $this->icon = $icon;
        $this->position = $position;
    }

    public function Render($parent_slug=null)
    {
        parent::Render($this->slug);
    }

    public function Generate()
    {
        add_menu_page($this->menu_title, $this->menu_title,   $this->capability, $this->slug, [$this, "GenerateView"], $this->icon, $this->position);
    }
}