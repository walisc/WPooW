<?php

namespace WPooW\Core\PageTypes;
use WPooW\Core\BasePage;
use WPooW\Core\Menus\SubMenu;
use WPooW\Core\Menus\BaseMenu;

class StaticPage extends BasePage{
   
    protected $page_template;
    protected $capabilities;
    protected $position;

    function __construct($page_slug, $page_title, $capabilities, $page_template, $icon = '', $position=null)
    {
        parent::__construct($page_slug, $page_title);
        $this->page_template = $page_template;
        $this->capabilities = $capabilities;
        $this->icon = $icon;
        $this->position = $position;
    }

    function Render($parent_slug=null)
    {
        if ($parent_slug != null){
            (new SubMenu($this->slug, $this->label, $this->capabilities, $this->page_template))->Render($parent_slug);
        }
        else{
            (new BaseMenu($this->slug, $this->label, $this->capabilities, $this->page_template, $this->icon, $this->position))->Render();
        }     
    }

    function Generate(){}
    function RenderHook(){}

}