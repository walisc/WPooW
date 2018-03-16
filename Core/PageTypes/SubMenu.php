<?php

/**
 *
 * Class SubMenu
 * @package wpAPI\Core\Pages
 */
class SubMenu extends wpAPIBasePage
{
    protected $menu_title = '';
    protected $capability = '';
    protected $display_path_content = null;
    protected $_children = [];

    function __construct($page_slug, $menu_title, $capability, $display_path_content)
    {
        parent::__construct($page_slug, $menu_title);
        $this->menu_title = $menu_title;
        $this->capability = $capability;
        $this->display_path_content = $display_path_content;

    }

    public function AddChild($child)
    {
        array_push($this->_children, $child);
    }
    

    public function Generate()
    {
        if ($this->parent_slug == '')
        {
            throw new Exception(sprintf("You need to specify the parent for the submenu - %s", $this->slug));
        }

        add_submenu_page($this->parent_slug, $this->menu_title, $this->menu_title, $this->capability , $this->slug, [$this, "GenerateView"]);

    }

    public function GenerateView()
    {
        if ($this->display_path_content == null)
        {
            throw new Exception(sprintf("No view specified for the page - %s", $this->slug));
        }
        $this->display_path_content->Render();
    }

    public function Render($parent_slug = null)
    {
        parent::Render($parent_slug);


        foreach ($this->_children as $child)
        {
            $child->Render($parent_slug);
        }

    }

    function RenderHook()
    {
        return "admin_menu";
    }
}
