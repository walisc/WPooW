<?php

/**
 *
 * Class SubMenu
 * @package wpAPI\Core\Pages
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