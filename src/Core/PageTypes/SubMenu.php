<?php


/**
 * Class SubMenu
 * Custom page which uses the wpAPI_VIEW to render a page
 * This page appears as a Submenu in the wordpress backend and added to the Menu Page
 *
 * @package wpAPI\Core\PageType
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
        $this->display_path_content = $display_path_content == null ? new wpAPI_VIEW(wpAPI_VIEW::CONTENT, $menu_title) : $display_path_content;

    }

    /**
     * Add a child to the menu items represented by this page
     * @param $child
     */
    public function AddChild($child)
    {
        array_push($this->_children, $child);
    }


    /**
     * Called by the BasePage for the appropriate RenderHook
     * Calls the add_submenu_page method of wordpress, which then calls the GenerateView of the page
     * @throws Exception
     */
    public function Generate()
    {
        if ($this->parent_slug == '')
        {
            throw new Exception(sprintf("You need to specify the parent for the submenu - %s", $this->slug));
        }

        add_submenu_page($this->parent_slug, $this->menu_title, $this->menu_title, $this->capability , $this->slug, [$this, "GenerateView"]);

    }

    /**
     * Generates the view of the Page
     * @throws Exception
     */
    public function GenerateView()
    {
        if ($this->display_path_content == null)
        {
            throw new Exception(sprintf("No view specified for the page - %s", $this->slug));
        }
        $this->display_path_content->Render();
    }

    /**
     * Render the page and it children
     * @param null $parent_slug
     */
    public function Render($parent_slug = null)
    {
        parent::Render($parent_slug);


        foreach ($this->_children as $child)
        {
            $child->Render($parent_slug);
        }

    }

    /**
     * @return string
     */
    function RenderHook()
    {
        return "admin_menu";
    }
}
