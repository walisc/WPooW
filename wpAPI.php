<?php

/**
 * Created by PhpStorm.
 * User: chidow
 * Date: 2016/05/30
 * Time: 9:31 PM
 */
//TODO: Use autoloading
include_once 'Core/wpAPIBasePage.php';
include_once 'Core/wpAPIObjects.php';
include_once 'Core/Elements/BaseElement.php';

include_once 'Core/PageTypes/PostType.php';
include_once 'Core/PageTypes/SubMenu.php';
include_once 'Core/PageTypes/Menu.php';

include_once 'Libraries/Mustache/Autoloader.php';
include_once 'Core/Elements/Autoloader.php';

class wpAPI
{
    function __construct()
    {
        //TODO: Add helper
        Mustache_Autoloader::register();
        Elements_Autoloader::register();
    }

    public function CreateMenu($page_slug, $menu_title, $capability, $display_path, $icon='', $position=null)
    {
        //TODO: Move the adding of object to the constructors
        return new Menu($page_slug, $menu_title ,$capability,$display_path, $icon,$position);

    }

    public function CreateSubMenu($page_slug, $menu_title, $capability, $display_path)
    {
        return new SubMenu($page_slug, $menu_title ,$capability,$display_path);

    }

    public function CreatePostType($page_slug, $title)
    {
        return new PostType($page_slug, $title );

    }


}

class wpAPI_VIEW
{
    CONST PATH = 1;
    CONST CONTENT = 2;

    private $type;
    private $path_content;
    private $data = [];

    function __construct($type, $path_content, $data)
    {
        $this->type = $type;
        $this->path_content = $path_content;
        $this->data = array_merge($this->data, $data);

    }
    
    function Render()
    {
        
        if ($this->type == self::PATH)
        {
            //TODO: Make this global
            $m = new Mustache_Engine;
            $loader = new Mustache_Loader_FilesystemLoader(__DIR__.'/../../');

            echo $m->render($loader->load($this->path_content), $this->data);
        }
        else if ($this->type == self::CONTENT)
        {
            $m = new Mustache_Engine;
            echo $m->render($this->path_content, $this->data);
        }



    }
    
    function SetData($data, $append=true)
    {
        if ($append) {
            $this->data = array_merge($this->data, $data);
        }
        else
        {
            $this->data = $data;
        }

    }
}

class wpAPI_PERMISSIONS
{
   CONST MANAGE_OPTIONS = "manage_options";
}


